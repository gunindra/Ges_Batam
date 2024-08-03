<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {


        return view('invoice.indexinvoice');
    }

    public function addinvoice()
    {
        $listPembeli = DB::select("SELECT id, nama_pembeli, no_wa FROM tbl_pembeli");

        $listSopir = DB::select("SELECT id, nama_supir, no_wa FROM tbl_supir");

        $listRekening = DB::select("SELECT id, pemilik, nomer_rekening, nama_bank FROM tbl_rekening");

        $listTipePembayaran = DB::select("SELECT id, tipe_pembayaran FROM tbl_tipe_pembayaran");

        $listRateVolume = DB::select("SELECT id, rate_volume FROM tbl_rate_volume");

        return view('invoice.buatinvoice', [
            'listPembeli' => $listPembeli,
            'listSupir' => $listSopir,
            'listRekening' => $listRekening,
            'listTipePembayaran' => $listTipePembayaran,
            'listRateVolume' => $listRateVolume
        ]);
    }


    public function getlistInvoice(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT a.id,
                        a.no_resi,
                        DATE_FORMAT(a.tanggal_pembayaran, '%d %M %Y') AS tanggal_bayar,
                        b.nama_pembeli AS pembeli,
                        a.berat,
                        a.panjang,
                        a.lebar,
                        a.tinggi,
                        a.pengiriman,
                        a.harga,
                        d.status_name
                FROM tbl_pembayaran a
                JOIN tbl_pembeli b ON a.pembeli_id = b.id
                JOIN tbl_status d ON a.status_id = d.id
        ";

        $data = DB::select($q);

        $output = '<table class="table align-items-center table-flush table-hover" id="tableInvoice">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No Resi</th>
                                        <th>Tanggal</th>
                                        <th>Costumer</th>
                                        <th>Pengiriman</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {


            $output .=
                '
                <tr>
                    <td class="">' . ($item->no_resi ?? '-') .'</td>
                    <td class="">' . ($item->tanggal_bayar ?? '-') .'</td>
                    <td class="">' . ($item->pembeli ?? '-') .'</td>
                    <td class="">' . ($item->pengiriman ?? '-') .'</td>
                  <td class="">' . (isset($item->harga) ? 'Rp ' . number_format($item->harga, 0, ',', '.') : '-') . '</td>
                    <td><span class="badge badge-warning">' . ($item->status_name ?? '-') .'</span></td>
                   <td>
                        <a  class="btn btnUpdateBooking btn-sm btn-secondary text-white" data-id="' .$item->id .'"><i class="fas fa-print"></i></a>
                        <a  class="btn btnDestroyBooking btn-sm btn-danger text-white" data-id="' .$item->id .'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }


    public function tambainvoice(Request $request)
    {
        $noResi = $request->input('noResi');
        $tanggal = $request->input('tanggal');
        $customer = $request->input('customer');
        $beratBarang = $request->input('beratBarang');
        $panjang = $request->input('panjang');
        $lebar = $request->input('lebar');
        $tinggi = $request->input('tinggi');
        $rate = $request->input('namaBarang');
        $pembagi = $request->input('hargaBarang');
        $metodePengiriman = $request->input('metodePengiriman');
        $driver = $request->input('driver');
        $alamatTujuan = $request->input('alamat');
        $metodePembayaran = $request->input('metodePembayaran');
        $rekening = $request->input('rekening');
        $totalharga = $request->input('totalharga');


        $date = DateTime::createFromFormat('j F Y', $tanggal);

        // Format the date to Y-m-d for MySQL
        $formattedDate = $date ? $date->format('Y-m-d') : null;


        try {
            DB::table('tbl_pembayaran')->insert([
                'no_resi' => $noResi,
                'tanggal_pembayaran' => $formattedDate,
                'pembeli_id' => $customer,
                'berat' => $beratBarang,
                'panjang' => $panjang,
                'lebar' => $lebar,
                'tinggi' => $tinggi,
                'pengiriman' => $metodePengiriman,
                'harga' => $totalharga,
                'pembayaran_id' => $metodePembayaran,
                'rekening_id' => $rekening,
                'status_id' => 3,
                'created_at' => now(),
            ]);

            // Mengembalikan respons JSON jika berhasil
            return response()->json(['status' => 'success', 'message' => 'Pelanggan berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan pelanggan: ' . $e->getMessage()], 500);
        }
    }
}
