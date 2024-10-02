@extends('layout.main')

@section('title', 'Report | ProfitLoss')

@section('main')
<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">ProfitLoss</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Report</li>
            <li class="breadcrumb-item active" aria-current="page">ProfitLoss</li>
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
                    <div class="d-flex mb-2 mr-3">
                        <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                        <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                            onclick="window.location.reload()">
                            Reset
                        </button>
                    </div>
                    <div id="containerProfitLoss" class="table-responsive px-3">
                        <div class="card-header">
                            <h2 class="card-title">Operating Revenues</h2>
                        </div>
                        <div class="card-body">
                            <table class="table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 80%;">Account</th>
                                        <th style="width: 20%;" class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-left">PENJUALAN </td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">DISKON PENJUALAN </td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">RETUR PENJUALAN </td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">BIAYA ANGKUT PENJUALAN </td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left"><b> TOTAL OPEARTING REVENUE </b></td>
                                        <td class="text-right"><b>0.00</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-header">
                            <h2 class="card-title">Operating Expenses</h2>
                        </div>
                        <div class="card-body">
                            <table class="table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 80%;">Account</th>
                                        <th style="width: 20%;" class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-left">HARGA POKOK PENJUALAN </td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">PEMBELIAN</td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">DISKON PEMBELIAN </td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">RETUR PEMBELIAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BIAYA ANGKUT PEMBELIAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN IKLAN DAN PROMOSI</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN KOMISI PENJUALAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN TRANSPORTASI MARKETING</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN ENTERTAINMENT</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN LEGALITAS DOKUMEN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN GST PENGIRIMAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN OPERASIONAL EKSPEDISI</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PENGIRIMAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN GAJI DAN UPAH</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PPH 21</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN BPJS KETENAGAKERJAAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN BPJS KESEHATAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN TUNJANGAN HARI RAYA</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN BONUS DAN INSENTIF</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN TUNJANGAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN LEMBUR</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PERIJINAN DAN LISENSI</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN SEWA</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN LISTRIK DAN AIR</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN TELEPON DAN INTERNET</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN KEBERSIHAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN KEAMANAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PERLENGKAPAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN ATK DAN FOTOKOPI</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PENGIRIMAN DOKUMEN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PERBAIKAN DAN PEMELIHARAAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN TRANSPORTASI</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PERJALANAN DINAS</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN REKRUITMEN DAN PELATIHAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN ASURANSI</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN SUMBANGAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN JASA PROFESIONAL</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN RUMAH TANGGA KANTOR</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PERCETAKKAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN KENDARAAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN BBM DAN PARKIR</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN KEPERLUAN KANTOR</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PENYUSUTAN GEDUNG</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PENYUSUTAN MESIN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PENYUSUTAN PERALATAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PENYUSUTAN INVENTARIS KANTOR</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PENYUSUTAN SARANA DAN PRASARANA</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN AMORTISASI GOODWILL</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN AMORTISASI FRANCHISE</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN AMORTISASI LISENSI</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN AMORTISASI HAK CIPTA</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"><b> TOTAL OPEARTING EXPENSE</b></td>
                                        <td class="text-right"><b>0.00</b></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"><b> TOTAL OPERATING PROFIT</b></td>
                                        <td class="text-right"><b>0.00</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-header">
                            <h2 class="card-title">NON-BUSINESS REVENUE</h2>
                        </div>
                        <div class="card-body">
                            <table class="table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 80%;">Account</th>
                                        <th style="width: 20%;" class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-left">PENDAPATAN BUNGA BANK DAN JASA GIRO </td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">LABA ATAS SELISIH KURS</td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">LABA PELEPASAN ASET</td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">PENDAPATAN DI LUAR USAHA LAINNYA</td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left"><b>TOTAL NON BUSINESS REVENUE</b></td>
                                        <td class="text-right"><b>0.00</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-header">
                            <h2 class="card-title">NON BUSINESS EXPENSES</h2>
                        </div>
                        <div class="card-body">
                            <table class="table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="width: 80%;">Account</th>
                                        <th style="width: 20%;" class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-left">BEBAN BUNGA PINJAMAN</td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN ADMINISTRASI DAN PAJAK GIRO</td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PROVISI</td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">RUGI ATAS SELISIH KURS</td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">RUGI PELEPASAN ASET</td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN SANKSI PAJAK</td>
                                        <td class="text-right">0.00</td>

                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN PAJAK PENGHASILAN</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">BEBAN DI LUAR USAHA LAINNYA</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"><b>TOTAL NON BUSINESS EXPENSE</b></td>
                                        <td class="text-right"><b>0.00</b></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"><b>TOTAL NON OPERATING PROFIT</b></td>
                                        <td class="text-right"><b>0.00</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body">
                            <table class="table" width="100%">
                                <thead>
                                    <tr>
                                        <td class="text-left"><b>NET PROFIT BEFORE TAX</b></td>
                                        <td class="text-right"><b>0.00</b></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
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