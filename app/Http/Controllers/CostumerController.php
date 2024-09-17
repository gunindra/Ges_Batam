<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $statusCondition = '';
            if ($status === '1') {
                $statusCondition = "AND tp.status = 1";
            } elseif ($status === '0') {
                $statusCondition = "AND tp.status = 0";
            }

        $q = "SELECT tp.id,
                tp.marking,
                tp.nama_pembeli,
                GROUP_CONCAT(ta.alamat SEPARATOR ', ') AS alamat,
                COUNT(ta.alamat) AS alamat_count,
                tp.no_wa,
                tp.sisa_poin,
                tp.metode_pengiriman,
                DATE_FORMAT(tp.transaksi_terakhir, '%d %M %Y') AS tanggal_bayar,
                tp.status,
                tp.category_id,
                tc.category_name
                FROM tbl_pembeli tp
                LEFT JOIN tbl_alamat ta ON ta.pembeli_id = tp.id
                LEFT JOIN tbl_category tc ON tp.category_id = tc.id
                 WHERE (
                UPPER(tp.nama_pembeli) LIKE UPPER('$txSearch')
                OR UPPER(tp.marking) LIKE UPPER('$txSearch')
                )
                $statusCondition

                GROUP BY tp.id, tp.marking, tp.nama_pembeli, tp.no_wa, tp.sisa_poin, tp.metode_pengiriman, tp.transaksi_terakhir, tp.status, tp.category_id, tc.category_name
                ORDER BY tp.status DESC, tp.transaksi_terakhir DESC;

                        ";

        $data = DB::select($q);


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
        $markingCostumer = $request->input('markingCostmer');
        $namacostumer = $request->input('namaCustomer');
        $notlponcostumer = $request->input('noTelpon');
        $categorycostumer = $request->input('categoryCustomer');
        $metodePengiriman = $request->input('metodePengiriman');
        $alamatcostumer = $request->input('alamatCustomer', []);

        try {
            DB::beginTransaction();

            $pembeliId = DB::table('tbl_pembeli')->insertGetId([
                'marking' => $markingCostumer,
                'nama_pembeli' => $namacostumer,
                'no_wa' => $notlponcostumer,
                'category_id' => $categorycostumer,
                'metode_pengiriman' => $metodePengiriman,
                'status' => 1,
                'created_at' => now(),
            ]);

            foreach ($alamatcostumer as $alamat) {
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
