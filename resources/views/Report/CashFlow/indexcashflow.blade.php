@extends('layout.main')

@section('title', 'Report | CashFlow')

@section('main')

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">CashFlow</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Report</li>
            <li class="breadcrumb-item active" aria-current="page">CashFlow</li>
        </ol>
    </div>
    <div class="modal fade" id="modalFilterTanggal" tabindex="-1" role="dialog"
        aria-labelledby="modalFilterTanggalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilterTanggalTitle">Filter Tanggal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mt-3">
                                <label for="Tanggal" class="form-label fw-bold">Pilih Tanggal:</label>
                                <div class="d-flex align-items-center">
                                    <input type="date" id="startDate" class="form-control"
                                        placeholder="Pilih tanggal mulai" style="width: 200px;">
                                    <span class="mx-2">sampai</span>
                                    <input type="date" id="endDate" class="form-control"
                                        placeholder="Pilih tanggal akhir" style="width: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveFilterTanggal" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        <a class="btn btn-success mr-1" style="color:white;" id="Print"><span class="pr-2"><i
                                    class="fas fa-solid fa-print mr-1"></i></span>Print</a>
                    </div>
                    <div class="d-flex mb-2 mr-3 mb-4">
                        <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                        <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                            onclick="window.location.reload()">
                            Reset
                        </button>
                    </div>
                    <div id="containercashflow" class="table-responsive px-3">
                        <table class="table" width="100%">
                            <tr style="font-size: 18px;">
                                <td>
                                    <h4 class="page-title"> OPENING CASH BALANCE </h4>
                                </td>
                                <td class="text-right">(400,000.00)
                                </td>
                            </tr>
                        </table>
                        <table width="100%" class="table mt-5">
                            <tbody>
                                <tr style="font-size: 15px;">
                                    <td>
                                        <h4 class="page-title"> OPERATIONS </h4>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>ASET</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>ASET LANCAR</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>ASET TETAP</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>KAS</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>KAS IDR</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>LIABILITAS</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>EQUITAS</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>KAS KECIL</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>KAS SGD</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BANK</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PAJAK DIBAYAR DIMUKA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PPN MASUKAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>LIABILITAS JANGKA PENDEK</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG PAJAK</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PPN KELUARAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG PPH 23</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PPH 23 DIBAYAR DI MUKA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BANK BCA IDR</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BANK BCA SGD</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BANK MANDIRI IDR</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BANK BNI SGD</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BANK NIAGA IDR</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BANK NIAGA SGD</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BANK BCA CHANDRA SUSANTO</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PIUTANG </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PIUTANG USAHA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PIUTANG LAIN-LAIN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PERLENGKAPAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PERLENGKAPAN KANTOR </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PPH 21 DIBAYAR DI MUKA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PPH 22 DIBAYAR DI MUKA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PPH 24 DIBAYAR DI MUKA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PPH 25 DIBAYAR DI MUKA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PPH LEBIH BAYAR </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PPH 15 DIBAYAR DI MUKA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BIAYA DIBAYAR DI MUKA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UANG MUKA PEMBELIAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>SEWA DIBAYAR DI MUKA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>ASURANSI DIBAYAR DI MUKA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UANG MUKA PEMBELIAN KENDARAAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG USAHA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG USAHA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>HUTANG PAK CHANDRA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>HUTANG KENDARAAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>HUTANG - PT ISB</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>HUTANG LAINNYA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>HUTANG - PT MMCT</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>HUTANG BANGUNAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG PPH 21</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG PPH 22 </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG PPH 25</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG PPH 4(2) </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PPH KURANG BAYAR </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG PPH 15</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BIAYA YANG MASIH HARUS DIBAYAR </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG GAJI DAN UPAH </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG BPJS KETENAGAKERJAAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG BPJS KESEHATAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG LISTRIK DAN AIR </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG TELEPON DAN INTERNET </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG ASURANSI </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG SEWA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BIAYA YANG MASIH HARUS DIBAYAR LAINNYA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG LAIN-LAIN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG BANK JANGKA PENDEK </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG DEVIDEN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PENDAPATAN DITERIMA DI MUKA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG PEMBELIAN BELUM DITAGIH </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG PADA PT. BMS</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>LIABILITAS JANGKA PANJANG </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG BANK JANGKA PANJANG </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>UTANG JANGKA PANJANG LAINNYA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PENDAPATAN USAHA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PENJUALAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>DISKON PENJUALAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>RETUR PENJUALAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BIAYA ANGKUT PENJUALAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>HARGA POKOK PENJUALAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>HARGA POKOK PENJUALAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>HARGA POKOK PENJUALAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>HARGA POKOK BARANG DAGANG </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PEMBELIAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>DISKON PEMBELIAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>RETUR PEMBELIAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BIAYA ANGKUT PEMBELIAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN USAHA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PENJUALAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN IKLAN DAN PROMOSI </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN KOMISI PENJUALAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN TRANSPORTASI MARKETING</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN ENTERTAINMENT</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN LEGALITAS DOKUMEN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN GST PENGIRIMAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN OPERASIONAL EKSPEDISI</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PENGIRIMAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN ADMINISTRASI DAN UMUM</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN GAJI DAN UPAH</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PPH 21</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN BPJS KETENAGAKERJAAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN BPJS KESEHATAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN TUNJANGAN HARI RAYA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN BONUS DAN INSENTIF</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN TUNJANGAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN LEMBUR</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PERIJINAN DAN LISENSI</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN SEWA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN LISTRIK DAN AIR</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN TELEPON DAN INTERNET</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN KEBERSIHAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN KEAMANAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PERLENGKAPAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN ATK DAN FOTOKOPI</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PENGIRIMAN DOKUMEN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PERBAIKAN DAN PEMELIHARAAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN TRANSPORTASI</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PERJALANAN DINAS</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN REKRUITMEN DAN PELATIHAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN ASURANSI</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN SUMBANGAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN JASA PROFESIONAL</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN RUMAH TANGGA KANTOR</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PERCETAKKAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN KENDARAAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN BBM DAN PARKIR</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN KEPERLUAN KANTOR</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PENYUSUTAN DAN AMORTISASI</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PENYUSUTAN GEDUNG</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PENYUSUTAN KENDARAAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PENYUSUTAN MESIN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PENYUSUTAN PERALATAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PENYUSUTAN INVENTARIS KANTOR</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PENYUSUTAN SARANA DAN PRASARANA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN AMORTISASI GOODWILL</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN AMORTISASI FRANCHISE</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN AMORTISASI LISENSI</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN AMORTISASI HAK CIPTA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PENDAPATAN DI LUAR USAHA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PENDAPATAN BUNGA BANK DAN JASA GIRO</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>LABA ATAS SELISIH KURS </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>LABA PELEPASAN ASET </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PENDAPATAN DI LUAR USAHA LAINNYA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN DI LUAR USAHA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN BUNGA PINJAMAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN ADMINISTRASI DAN PAJAK GIRO </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PROVISI </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>RUGI ATAS SELISIH KURS </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>RUGI PELEPASAN ASET </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN SANKSI PAJAK </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN PAJAK PENGHASILAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BEBAN DI LUAR USAHA LAINNYA </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>BIAYA</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 18px;">
                                    <td><b>OPERATIONS SUBTOTAL</b></td>
                                    <td class="text-right"><b>0.00</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table mt-5" width="100%">
                            <tbody>
                                <tr style="font-size: 15px;">
                                    <td>
                                        <h4 class="page-title"> INVESTING ACTIVITIES </h4>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>KENDARAAN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PERALATAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>INVENTARIS KANTOR </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>AKUMULASI PENYUSUTAN KENDARAAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>AKUMULASI PENYUSUTAN PERALATAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>AKUMULASI PENYUSUTAN PERALATAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>AKUMULASI PENYUSUTAN INVENTARIS KANTOR </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 18px;">
                                    <td><b>INVESTING SUBTOTAL</b></td>
                                    <td class="text-right"><b>0.00</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table mt-5" width="100%">
                            <tbody>
                                <tr style="font-size: 15px;">
                                    <td>
                                        <h4 class="page-title"> FINANCING ACTIVITIES </h4>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>MODAL DISETOR </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>TAMBAHAN MODAL DISETOR </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>SALDO LABA DITAHAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>SALDO LABA TAHUN BERJALAN </td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td>PREV ATAU DEVIDEN</td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 18px;">
                                    <td><b>FINANCING SUBTOTAL</b></td>
                                    <td class="text-right"><b>0.00</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table mt-5" width="100%">
                            <tbody>
                                <tr style="font-size: 18px;">
                                    <td>
                                        <h4 class="page-title">ENDING CASH BALANCE </h4>
                                    </td>
                                    <td class="text-right">(400,000.00)
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    flatpickr("#startDate", {
        dateFormat: "d M Y",
        onChange: function (selectedDates, dateStr, instance) {

            $("#endDate").flatpickr({
                dateFormat: "d M Y",
                minDate: dateStr
            });
        }
    });

    flatpickr("#endDate", {
        dateFormat: "d MM Y",
        onChange: function (selectedDates, dateStr, instance) {
            var startDate = new Date($('#startDate').val());
            var endDate = new Date(dateStr);
            if (endDate < startDate) {
                showwMassage(error, "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                $('#endDate').val('');
            }
        }
    });

    $(document).on('click', '#filterTanggal', function (e) {
        $('#modalFilterTanggal').modal('show');
    });


</script>
@endsection