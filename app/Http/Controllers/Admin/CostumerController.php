<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Imports\CustomersImport;
use App\Jobs\ImportCustomerData;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class CostumerController extends Controller
{
    public function index()
    {
        $listCategory = DB::select("SELECT id, category_name FROM tbl_category");

        return view('masterdata.costumer.indexmastercostumer', [
            'listCategory' => $listCategory
        ]);

    }

    public function getlistCostumer(Request $request)
{
    $companyId = session('active_company_id');
    $txSearch = $request->txSearch;
    $status = $request->status;

    $query = DB::table('tbl_pembeli')
        ->select(
            'tbl_pembeli.id',
            'tbl_pembeli.marking',
            'tbl_pembeli.nama_pembeli',
            DB::raw('GROUP_CONCAT(tbl_alamat.alamat SEPARATOR "; ") AS alamat'),
            DB::raw('COUNT(tbl_alamat.alamat) AS alamat_count'),
            'tbl_pembeli.no_wa',
            'tbl_pembeli.sisa_poin',
            'tbl_pembeli.metode_pengiriman',
            DB::raw("DATE_FORMAT(tbl_pembeli.transaksi_terakhir, '%d %M %Y') AS tanggal_bayar"),
            'tbl_pembeli.status',
            'tbl_pembeli.category_id',
            'tbl_category.category_name',
            'tbl_users.email'
        )
        ->leftJoin('tbl_alamat', 'tbl_alamat.pembeli_id', '=', 'tbl_pembeli.id')
        ->leftJoin('tbl_category', 'tbl_pembeli.category_id', '=', 'tbl_category.id')
        ->leftJoin('tbl_users', 'tbl_users.id', '=', 'tbl_pembeli.user_id')
        ->where('tbl_pembeli.company_id', $companyId)
        ->when($txSearch, function ($q) use ($txSearch) {
            $q->where(function ($query) use ($txSearch) {
                $query->where(DB::raw('UPPER(tbl_pembeli.nama_pembeli)'), 'LIKE', '%' . strtoupper($txSearch) . '%')
                    ->orWhere(DB::raw('UPPER(tbl_pembeli.marking)'), 'LIKE', '%' . strtoupper($txSearch) . '%')
                    ->orWhere(DB::raw('UPPER(tbl_alamat.alamat)'), 'LIKE', '%' . strtoupper($txSearch) . '%');
            });
        })
        ->when($status, function ($q) use ($status) {
            if ($status === 'Active') {
                $q->where('tbl_pembeli.status', 1);
            } elseif ($status === 'Non Active') {
                $q->where('tbl_pembeli.status', 0);
            }
        })
        ->groupBy(
            'tbl_pembeli.id',
            'tbl_pembeli.marking',
            'tbl_pembeli.nama_pembeli',
            'tbl_pembeli.no_wa',
            'tbl_pembeli.sisa_poin',
            'tbl_pembeli.metode_pengiriman',
            'tbl_pembeli.transaksi_terakhir',
            'tbl_pembeli.status',
            'tbl_pembeli.category_id',
            'tbl_category.category_name',
            'tbl_users.email'
        )
        ->orderBy('tbl_pembeli.status', 'DESC')
        ->orderBy('tbl_pembeli.transaksi_terakhir', 'DESC');

    return DataTables::of($query)
        ->addColumn('alamat_cell', function ($item) {
            if ($item->alamat_count > 1) {
                return '<button type="button" class="btn btn-primary btn-sm show-address-modal" data-id="' . $item->id . '" data-alamat="' . htmlentities($item->alamat) . '">Lihat Alamat (' . $item->alamat_count . ')</button>';
            }
            return $item->alamat ?? '-';
        })
        ->addColumn('status_cell', function ($item) {
            return ($item->status == 1)
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Non Active</span>';
        })
        ->addColumn('action', function ($item) {
            return '
                <a class="btn btnPointCostumer btn-sm btn-primary text-white" data-id="' . $item->id . '" data-poin="' . $item->sisa_poin . '" data-notelp="' . $item->no_wa . '"><i class="fas fa-eye"></i></a>
                <a class="btn btnUpdateCustomer btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-nama="' . $item->nama_pembeli . '" data-email="' . $item->email . '" data-alamat="' . $item->alamat . '" data-notelp="' . $item->no_wa . '" data-metode_pengiriman="' . $item->metode_pengiriman . '" data-category="' . $item->category_id . '"><i class="fas fa-edit"></i></a>
            ';
        })
        ->rawColumns(['alamat_cell', 'status_cell', 'action'])
        ->make(true);
}


    public function addCostumer(Request $request)
    {

        $companyId = session('active_company_id');
        $request->validate([
            'markingCostmer' => 'required|string|max:255|unique:tbl_pembeli,marking',
            'namaCustomer' => 'required|string|max:255',
            'noTelpon' => 'required|string|max:15',
            'categoryCustomer' => 'required|exists:tbl_category,id',
            'metodePengiriman' => 'required|string|in:Delivery,Pickup',
            'alamatCustomer' => 'nullable|array',
            'alamatCustomer.*' => 'nullable|string|max:255',
            'email' => 'required|email|unique:tbl_users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'markingCostmer.unique' => 'Marking sudah terdaftar. Gunakan marking yang berbeda.',
            'email.unique' => 'Email sudah terdaftar. Gunakan email yang berbeda.',
        ]);
        // dd($companyId);
        $alamatCustomer = $request->input('alamatCustomer', []);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->namaCustomer,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'customer',
            ]);

            $pembeliId = DB::table('tbl_pembeli')->insertGetId([
                'user_id' => $user->id,
                'marking' => $request->input('markingCostmer'),
                'nama_pembeli' => $request->input('namaCustomer'),
                'no_wa' => $request->input('noTelpon'),
                'category_id' => $request->input('categoryCustomer'),
                'metode_pengiriman' => $request->input('metodePengiriman'),
                'status' => 1,
                'company_id'=> $companyId,
                'created_at' => now(),
            ]);

            if (!empty($alamatCustomer)) {
                foreach ($alamatCustomer as $alamat) {
                    if (!is_null($alamat) && trim($alamat) !== '') {
                        DB::table('tbl_alamat')->insert([
                            'pembeli_id' => $pembeliId,
                            'alamat' => $alamat,
                            'created_at' => now(),
                        ]);
                    }
                }
            }


            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data Pelanggan Berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal Menambahkan Data Pelanggan: ' . $e->getMessage()], 500);
        }
    }

    public function updateCostumer(Request $request)
    {
        $request->validate([
            'namaCustomer' => 'required|string|max:255',
            'noTelpon' => 'required|string|max:15',
            'categoryCustomer' => 'required|exists:tbl_category,id',
            'metodePengiriman' => 'required|string|in:Delivery,Pickup',
            'alamatCustomer' => 'nullable|array',
            'alamatCustomer.*' => 'nullable|string|max:255',
        ]);

        $id = $request->input('id');
        $namacostumer = $request->input('namaCustomer');
        $notlponcostumer = $request->input('noTelpon');
        $categoryCustomer = $request->input('categoryCustomer');
        $alamatCustomer = $request->input('alamatCustomer', []);
        $metodePengiriman = $request->input('metodePengiriman');

        try {
            DB::beginTransaction();

            DB::table('tbl_pembeli')
                ->where('id', $id)
                ->update([
                    'nama_pembeli' => $namacostumer,
                    'no_wa' => $notlponcostumer,
                    'category_id' => $categoryCustomer,
                    'metode_pengiriman' => $metodePengiriman,
                    'updated_at' => now(),
                ]);

            DB::table('tbl_alamat')
                ->where('pembeli_id', $id)
                ->delete();

                if (!empty($alamatCustomer)) {
                    foreach ($alamatCustomer as $alamat) {
                        if (!is_null($alamat) && trim($alamat) !== '') {
                            DB::table('tbl_alamat')->insert([
                                'pembeli_id' => $id,
                                'alamat' => $alamat,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data Pelanggan berhasil diupdate'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data Pelanggan: ' . $e->getMessage()], 500);
        }
    }




    public function generateMarking(Request $request)
    {
        $q = "SELECT marking FROM tbl_pembeli ORDER BY created_at DESC limit 1;";
        $data = DB::select($q);
        if (!empty($data)) {
            $lastMarking = $data[0]->marking;
            $newMarking = str_pad((int) $lastMarking + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newMarking = '0001';
        }

        return response()->json(['new_marking' => $newMarking]);
    }

    public function customerByName(Request $request)
    {
        $query = Customer::query();

        // If a customer name filter is provided, apply it
        if ($request->has('search') && $request->search['value'] != '') {
            $searchTerm = $request->search['value'];
            $query->where('nama_pembeli', 'like', "%$searchTerm%")
                  ->orWhere('no_wa', 'like', "%$searchTerm%")
                  ->orWhere('marking', 'like', "%$searchTerm%");
        }

        // Get the total count of records
        $totalRecords = $query->count();

        // Apply pagination
        $customers = $query->offset($request->start)  // Offset for pagination (start)
                        ->limit($request->length)  // Limit for pagination (length)
                        ->get();

        // Return response in DataTables format
        return response()->json([
            'draw' => $request->draw,  // DataTables draw count (to sync requests)
            'recordsTotal' => $totalRecords,  // Total records (for pagination)
            'recordsFiltered' => $totalRecords,  // Total filtered records (for search results)
            'data' => $customers  // Customer data to populate the table
        ]);
    }

    // public function import(Request $request)
    // {
    //     $companyId = session('active_company_id');
    //     $invalidData = [];
    //     $validData = [];

    //     // Validasi manual untuk setiap baris data
    //     foreach ($request->data as $index => $row) {
    //         $validator = Validator::make($row, [
    //             'marking_costumer' => 'required|string|max:255',
    //             'email' => 'required|email',
    //             'nama_customer' => 'required|string|max:255',
    //             'no_telpon' => 'required|string|max:20',
    //             'alamat_customer' => 'required|string',
    //             'password' => 'required|string|min:6',
    //             'category_customer' => 'required|exists:tbl_category,id',
    //             'metode_pengiriman' => 'required|in:Delivery,Pickup',
    //         ]);

    //         $errors = [];

    //         // Cek duplikat email
    //         if (DB::table('tbl_users')->where('email', $row['email'])->exists()) {
    //             $errors[] = "Email duplicate.";
    //         }

    //         // Cek duplikat marking_costumer
    //         if (DB::table('tbl_pembeli')->where('marking', $row['marking_costumer'])->exists()) {
    //             $errors[] = "Marking duplicate.";
    //         }

    //         // Jika validasi gagal atau ada duplikat, tambahkan ke invalidData
    //         if ($validator->fails() || !empty($errors)) {
    //             $row['keterangan'] = implode(" ", array_merge($validator->errors()->all(), $errors));
    //             $invalidData[] = $row;
    //         } else {
    //             $validData[] = $row;
    //         }
    //     }

    //     // Jika tidak ada data valid, langsung return
    //     if (empty($validData)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Tidak ada data valid yang dapat diimpor.',
    //             'invalid_data' => $invalidData,
    //         ], 400);
    //     }

    //     // Proses data yang valid dengan chunk processing
    //     try {
    //         $chunks = array_chunk($validData, 500); // Bagi data menjadi batch

    //         foreach ($chunks as $chunk) {
    //             foreach ($chunk as $row) {
    //                 try {
    //                     DB::beginTransaction(); // Mulai transaksi database

    //                     // Insert user and get the user_id
    //                     $user = User::create([
    //                         'name' => $row['nama_customer'],
    //                         'email' => $row['email'],
    //                         'password' => Hash::make($row['password']),
    //                         'role' => 'customer',
    //                         'created_at' => now(),
    //                         'updated_at' => now(),
    //                     ]);

    //                     // Insert pembeli dengan user_id yang baru dibuat
    //                     $pembeliId = DB::table('tbl_pembeli')->insertGetId([
    //                         'user_id' => $user->id,
    //                         'marking' => $row['marking_costumer'],
    //                         'nama_pembeli' => $row['nama_customer'],
    //                         'no_wa' => $row['no_telpon'],
    //                         'category_id' => $row['category_customer'],
    //                         'metode_pengiriman' => $row['metode_pengiriman'],
    //                         'status' => 1,
    //                         'company_id' => $companyId,
    //                         'created_at' => now(),
    //                         'updated_at' => now(),
    //                     ]);

    //                     // Proses alamat (jika ada)
    //                     $alamatCustomer = is_array($row['alamat_customer']) ? $row['alamat_customer'] : [$row['alamat_customer']];
    //                     foreach ($alamatCustomer as $alamat) {
    //                         if (!is_null($alamat) && trim($alamat) !== '') {
    //                             DB::table('tbl_alamat')->insert([
    //                                 'pembeli_id' => $pembeliId,
    //                                 'alamat' => trim($alamat),
    //                                 'created_at' => now(),
    //                                 'updated_at' => now(),
    //                             ]);
    //                         }
    //                     }

    //                     DB::commit();
    //                 } catch (QueryException $e) {
    //                     DB::rollBack();

    //                     $errorMessage = $e->errorInfo[2];

    //                     if (str_contains($errorMessage, 'tbl_users_email_unique')) {
    //                         $row['keterangan'] = "Email duplicate.";
    //                     } elseif (str_contains($errorMessage, 'tbl_pembeli_marking_unique')) {
    //                         $row['keterangan'] = "Marking duplicate.";
    //                     } else {
    //                         $row['keterangan'] = "Terjadi kesalahan saat menyimpan data.";
    //                     }

    //                     $invalidData[] = $row;
    //                 } catch (Exception $e) {
    //                     DB::rollBack();
    //                     $row['keterangan'] = "Terjadi kesalahan saat menyimpan data.";
    //                     $invalidData[] = $row;
    //                 }
    //             }
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Data berhasil diimpor.',
    //             'invalid_data' => $invalidData, // Data yang gagal tetap dikembalikan
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal mengimpor data: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function import(Request $request)
    {
        $companyId = session('active_company_id');
        $invalidData = [];
        $validData = [];

        // Validate each row
        foreach ($request->data as $row) {
            $validator = Validator::make($row, [
                'marking_costumer' => 'required|string|max:255',
                'email' => 'required|email',
                'nama_customer' => 'required|string|max:255',
                'no_telpon' => 'required|string|max:20',
                'alamat_customer' => 'required|string',
                'password' => 'required|string|min:6',
                'category_customer' => 'required|exists:tbl_category,id',
                'metode_pengiriman' => 'required|in:Delivery,Pickup',
            ]);

            $errors = [];

            if ($validator->fails()) {
                $errors = array_merge($validator->errors()->all(), $errors);
            }

            // Check for duplicate email or marking
            if (DB::table('tbl_users')->where('email', $row['email'])->exists()) {
                $errors[] = "Email duplicate.";
            }

            if (DB::table('tbl_pembeli')->where('marking', $row['marking_costumer'])->exists()) {
                $errors[] = "Marking duplicate.";
            }

            if (!empty($errors)) {
                $row['keterangan'] = implode(" ", $errors);
                $invalidData[] = $row;
            } else {
                $validData[] = $row;
            }
        }

        if (empty($validData)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data valid yang dapat diimpor.',
                'invalid_data' => $invalidData,
            ], 400);
        }

        // Bagi data valid menjadi bagian kecil (misalnya 500 data per job)
        $chunkSize = 200;
        $chunks = array_chunk($validData, $chunkSize);

        // Generate job ID untuk keseluruhan proses
        $jobId = Str::uuid()->toString();

        // Set initial progress
        Cache::put('job_progress_' . $jobId, 0, now()->addMinutes(5));
        Cache::put('job_total_' . $jobId, count($chunks), now()->addMinutes(5));

        // Dispatch job untuk setiap chunk data
        foreach ($chunks as $index => $chunk) {
            ImportCustomerData::dispatch($chunk, $companyId, $jobId, $index + 1, count($chunks));
        }

        return response()->json([
            'success' => true,
            'message' => 'Data sedang diproses. Anda akan diberitahu setelah selesai.',
            'invalid_data' => $invalidData,
            'job_id' => $jobId,
        ]);
    }

    public function getJobStatus($jobId)
    {
        $progress = Cache::get('job_progress_' . $jobId, 0);
        return response()->json(['progress' => $progress]);
    }
}
