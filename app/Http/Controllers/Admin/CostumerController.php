<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

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
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
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
                'tbl_users.email',
            )
            ->leftJoin('tbl_alamat', 'tbl_alamat.pembeli_id', '=', 'tbl_pembeli.id')
            ->leftJoin('tbl_category', 'tbl_pembeli.category_id', '=', 'tbl_category.id')
            ->leftJoin('tbl_users', 'tbl_users.id', '=', 'tbl_pembeli.user_id')
            ->where('tbl_pembeli.company_id', $companyId)
            ->where(function ($q) use ($txSearch) {
                $q->where(DB::raw('UPPER(tbl_pembeli.nama_pembeli)'), 'LIKE', strtoupper($txSearch))
                    ->orWhere(DB::raw('UPPER(tbl_pembeli.marking)'), 'LIKE', strtoupper($txSearch))
                    ->orWhere(DB::raw('UPPER(tbl_alamat.alamat)'), 'LIKE', strtoupper($txSearch));
            });

        if ($status === '1') {
            $query->where('tbl_pembeli.status', 1);
        } elseif ($status === '0') {
            $query->where('tbl_pembeli.status', 0);
        }

        $data = $query->groupBy(
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
            'tbl_users.email',
        )
            ->orderBy('tbl_pembeli.status', 'DESC')
            ->orderBy('tbl_pembeli.transaksi_terakhir', 'DESC')
            ->get();

        $output = '<table class="table align-items-center table-flush table-hover" id="tableCostumer">
                        <thead class="thead-light">
                        <tr>
                            <th>Marking</th>
                            <th>Nama</th>
                            <th>Pengiriman</th>
                            <th>Alamat</th>
                            <th>Transaksi Terakhir</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                <tbody>';
        foreach ($data as $item) {

            $statusCell = ($item->status == 1)
                ? '<td><span class="badge badge-success">Active</span></td>'
                : '<td><span class="badge badge-danger">Non Active</span></td>';

            $alamatCell = ($item->alamat_count > 1)
                ? '<td><button type="button" class="btn btn-primary btn-sm show-address-modal" data-id="' . $item->id . '" data-alamat="' . htmlentities($item->alamat) . '">Lihat Alamat (' . $item->alamat_count . ')</button></td>'
                : '<td class="">' . ($item->alamat ?? '-') . '</td>';

            $output .=
                '
                <tr>
                    <td class="">' . ($item->marking ?? '-') . '</td>
                    <td class="">' . ($item->nama_pembeli ?? '-') . '</td>
                     <td class="">' . ($item->metode_pengiriman ?? '-') . '</td>
                    ' . $alamatCell . '
                    <td class="">' . ($item->tanggal_bayar ?? '-') . '</td>
                    ' . $statusCell . '
                    <td>
                        <a  class="btn btnPointCostumer btn-sm btn-primary text-white" data-id="' . $item->id . '"  data-poin="' . $item->sisa_poin . '" data-notelp="' . $item->no_wa . '"><i class="fas fa-eye"></i></a>
                        <a  class="btn btnUpdateCustomer btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-nama="' . $item->nama_pembeli . '" data-email="' . $item->email . '" data-alamat="' . $item->alamat . '" data-notelp="' . $item->no_wa . '"  data-metode_pengiriman="' . $item->metode_pengiriman . '"  data-category="' . $item->category_id . '"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
        return $output;
    }


    public function addCostumer(Request $request)
    {

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


}
