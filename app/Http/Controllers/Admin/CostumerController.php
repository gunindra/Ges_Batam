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
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;

        $query = DB::table('tbl_pembeli')
                ->select(
                'tbl_pembeli.id',
                'tbl_pembeli.marking',
                'tbl_pembeli.nama_pembeli',
                DB::raw('GROUP_CONCAT(tbl_alamat.alamat SEPARATOR ", ") AS alamat'),
                DB::raw('COUNT(tbl_alamat.alamat) AS alamat_count'),
                'tbl_pembeli.no_wa',
                'tbl_pembeli.sisa_poin',
                'tbl_pembeli.metode_pengiriman',
                DB::raw("DATE_FORMAT(tbl_pembeli.transaksi_terakhir, '%d %M %Y') AS tanggal_bayar"),
                'tbl_pembeli.status',
                'tbl_pembeli.category_id',
                'tbl_category.category_name'
            )
            ->leftJoin('tbl_alamat', 'tbl_alamat.pembeli_id', '=', 'tbl_pembeli.id')
            ->leftJoin('tbl_category', 'tbl_pembeli.category_id', '=', 'tbl_category.id')
            ->where(function($q) use ($txSearch) {
                $q->where(DB::raw('UPPER(tbl_pembeli.nama_pembeli)'), 'LIKE', strtoupper($txSearch))
                  ->orWhere(DB::raw('UPPER(tbl_pembeli.marking)'), 'LIKE', strtoupper($txSearch));
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
                'tbl_category.category_name'
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
                        <a  class="btn btnUpdateCustomer btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-nama="' . $item->nama_pembeli . '" data-alamat="' . $item->alamat . '" data-notelp="' . $item->no_wa . '"  data-metode_pengiriman="' . $item->metode_pengiriman . '"  data-category="' . $item->category_id . '"><i class="fas fa-edit"></i></a>
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
            'markingCostmer' => 'required|string|max:255',
            'namaCustomer' => 'required|string|max:255',
            'noTelpon' => 'required|string|max:15',
            'categoryCustomer' => 'required|exists:tbl_category,id',
            'metodePengiriman' => 'required|string|in:Delivery,Pickup',
            'alamatCustomer' => 'nullable|array',
            'alamatCustomer.*' => 'nullable|string|max:255',
            'email' => 'required|email|unique:tbl_users,email',
            'password' => 'required|min:6|confirmed',
        ]);

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

            foreach ($request->input('alamatCustomer', []) as $alamat) {
                DB::table('tbl_alamat')->insert([
                    'pembeli_id' => $pembeliId,
                    'alamat' => $alamat,
                    'created_at' => now(),
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data Pelanggan berhasil ditambahkan'], 200);
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
        $alamatcostumer = $request->input('alamatCustomer');
        $metodePengiriman = $request->input('metodePengiriman');
        try {
            DB::beginTransaction();

            // Update data customer di tbl_pembeli
            DB::table('tbl_pembeli')
                ->where('id', $id)
                ->update([
                    'nama_pembeli' => $namacostumer,
                    'no_wa' => $notlponcostumer,
                    'category_id' => $categoryCustomer,
                    'metode_pengiriman' => $metodePengiriman,
                    'updated_at' => now(),
                ]);

            // Hapus semua alamat lama dari tbl_alamat untuk customer ini
            DB::table('tbl_alamat')
                ->where('pembeli_id', $id)
                ->delete();

            // Insert alamat baru ke tbl_alamat hanya jika metode pengiriman adalah Delivery dan alamat tidak kosong
            if ($metodePengiriman === 'Delivery' && !empty($alamatcostumer) || $metodePengiriman === 'Pickup' && !empty($alamatcostumer)) {
                foreach ($alamatcostumer as $alamat) {
                    // Cek jika alamat tidak null atau kosong
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

}
