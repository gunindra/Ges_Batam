@extends('layout.main')

@section('title', 'Report | Ledger')

@section('main')


<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ledger</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Report</li>
            <li class="breadcrumb-item active" aria-current="page">Ledger</li>
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
                    <div class="d-flex mb-4 mr-3">
                        <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                        <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                            onclick="window.location.reload()">
                            Reset
                        </button>
                    </div>
                    <div id="containerledger" class="table-responsive px-3">
                        <table width="100%" class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th width="30%">Date</th>
                                    <th width="30%">Description</th>
                                    <th width="20%" class="text-right">Total Debit</th>
                                    <th width="20%" class="text-right">Total Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.0.00 ASET</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.00 ASET LANCAR</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.01 KAS</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.01.01 KAS IDR</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.01.02 KAS KECIL</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.01.03 KAS SGD</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.02 BANK</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.02.01 BANK BCA IDR</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.02.02 BANK BCA SGD</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.02.03 BANK MANDIRI IDR</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.02.04 BANK BNI SGD</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.02.05 BANK NIAGA IDR</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.02.06 BANK NIAGA SGD</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.02.07 BANK BCA CHANDRA SUSANTO</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.03 PIUTANG</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.03.01 PIUTANG USAHA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.03.02 PIUTANG LAIN-LAIN</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.05 PERLENGKAPAN</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.05.01 PERLENGKAPAN KANTOR</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.06 PAJAK DIBAYAR DIMUKA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.06.01 PPN MASUKAN</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.06.02 PPH 21 DIBAYAR DIMUKA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.06.03 PPH 22 DIBAYAR DIMUKA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.06.04 PPH 23 DIBAYAR DIMUKA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.06.03 PPH 22 DIBAYAR DI MUKA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.06.04 PPH 23 DIBAYAR DI MUKA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.06.05 PPH 24 DIBAYAR DI MUKA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.06.06 PPH 25 DIBAYAR DI MUKA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.06.07 PPH LEBIH BAYAR</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.06.08 PPH 15 DIBAYAR DI MUKA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.07 BIAYA DIBAYAR DI MUKA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.07.01 UANG MUKA PEMBELIAN</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.07.02 SEWA DIBAYAR DI MUKA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.07.03 ASURANSI DIBAYAR DI MUKA</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.1.07.04 UANG MUKA PEMBELIAN KENDARAAN</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.2.00 ASET TETAP</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.2.01 ASET TETAP BERWUJUD</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.2.01.03 KENDARAAN</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.2.01.05 PERALATAN</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.2.01.06 INVENTARIS KANTOR</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.2.01.09 AKUMULASI PENYUSUTAN KENDARAAN</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.2.01.11 AKUMULASI PENYUSUTAN PERALATAN</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>1.2.01.12 AKUMULASI PENYUSUTAN INVENTARIS KANTOR</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.0.00 LIABILITAS</td>
                                    <td>Begining Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class="text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>2.1.00 LIABILITAS JANGKA PENDEK</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.01 UTANG USAHA </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.01.01 UTANG USAHA </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.01.02 HUTANG PAK CHANDRA</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.01.03 HUTANG KENDARAAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.01.04 HUTANG - PT ISB</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.01.05 HUTANG LAINNYA</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.01.06 HUTANG - PT MMCT</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.01.07 HUTANG BANGUNAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.02 UTANG PAJAK</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.02.01 PPN KELUARAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.02.02 UTANG PPH 21</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.02.03 UTANG PPH 22 </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.02.04 UTANG PPH 23</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.02.05 UTANG PPH 25</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.02.06 UTANG PPH 4(2) </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.02.07 PPH KURANG BAYAR </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.02.08 UTANG PPH 15</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.03 BIAYA YANG MASIH HARUS DIBAYAR </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.03.01 UTANG GAJI DAN UPAH </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.03.02 UTANG BPJS KETENAGAKERJAAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.03.03 UTANG BPJS KESEHATAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.03.04 UTANG LISTRIK DAN AIR </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.03.05 UTANG TELEPON DAN INTERNET </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.03.06 UTANG ASURANSI </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.03.07 UTANG SEWA </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.03.08 BIAYA YANG MASIH HARUS DIBAYAR LAINNYA </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.04 UTANG LAIN-LAIN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.04.01 UTANG BANK JANGKA PENDEK </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.04.02 UTANG DEVIDEN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.04.03 PENDAPATAN DITERIMA DI MUKA </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.04.04 UTANG PEMBELIAN BELUM DITAGIH </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.1.04.05 UTANG PADA PT. BMS</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.2.00 LIABILITAS JANGKA PANJANG </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.2.01 UTANG BANK JANGKA PANJANG </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>2.2.02 UTANG JANGKA PANJANG LAINNYA </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>3.0.00 EQUITAS</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>3.1.00 MODAL </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>3.1.01 MODAL DISETOR </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>3.1.02 TAMBAHAN MODAL DISETOR </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>3.2.00 SALDO LABA</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>3.2.01 SALDO LABA DITAHAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>3.2.02 SALDO LABA TAHUN BERJALAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>3.3.00 DEVIDEN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>3.3.01 PREV ATAU DEVIDEN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>4.0.00 PENDAPATAN USAHA</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>4.1.00 PENJUALAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>4.2.00 DISKON PENJUALAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>4.3.00 RETUR PENJUALAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>4.4.00 BIAYA ANGKUT PENJUALAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>5.0.00 HARGA POKOK PENJUALAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>5.1.00 HARGA POKOK PENJUALAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>5.1.01 HARGA POKOK PENJUALAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>5.2.00 HARGA POKOK BARANG DAGANG </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>5.2.01 PEMBELIAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>5.2.02 DISKON PEMBELIAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>5.2.03 RETUR PEMBELIAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>5.2.04 BIAYA ANGKUT PEMBELIAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.0.00 BEBAN USAHA</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.1.00 BEBAN PENJUALAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.1.01 BEBAN IKLAN DAN PROMOSI </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.1.02 BEBAN KOMISI PENJUALAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.1.03 BEBAN TRANSPORTASI MARKETING</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.1.04 BEBAN ENTERTAINMENT</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.1.05 BEBAN LEGALITAS DOKUMEN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.1.06 BEBAN GST PENGIRIMAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.1.07 BEBAN OPERASIONAL EKSPEDISI</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.1.08 BEBAN PENGIRIMAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.00 BEBAN ADMINISTRASI DAN UMUM</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.01 BEBAN GAJI DAN UPAH</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.02 BEBAN PPH 21</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.03 BEBAN BPJS KETENAGAKERJAAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.04 BEBAN BPJS KESEHATAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.05 BEBAN TUNJANGAN HARI RAYA</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.06 BEBAN BONUS DAN INSENTIF</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.07 BEBAN TUNJANGAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.08 BEBAN LEMBUR</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.09 BEBAN PERIJINAN DAN LISENSI</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.10 BEBAN SEWA</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.11 BEBAN LISTRIK DAN AIR</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.12 BEBAN TELEPON DAN INTERNET</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.13 BEBAN KEBERSIHAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.14 BEBAN KEAMANAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.15 BEBAN PERLENGKAPAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.16 BEBAN ATK DAN FOTOKOPI</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.17 BEBAN PENGIRIMAN DOKUMEN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.18 BEBAN PERBAIKAN DAN PEMELIHARAAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.19 BEBAN TRANSPORTASI</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.20 BEBAN PERJALANAN DINAS</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.21 BEBAN REKRUITMEN DAN PELATIHAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.22 BEBAN ASURANSI</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.23 BEBAN SUMBANGAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.24 BEBAN JASA PROFESIONAL</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.25 BEBAN RUMAH TANGGA KANTOR</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.26 BEBAN PERCETAKKAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.27 BEBAN KENDARAAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.28 BEBAN BBM DAN PARKIR</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.2.29 BEBAN KEPERLUAN KANTOR</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.3.00 BEBAN PENYUSUTAN DAN AMORTISASI</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.3.01 BEBAN PENYUSUTAN GEDUNG</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.3.02 BEBAN PENYUSUTAN KENDARAAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.3.03 BEBAN PENYUSUTAN MESIN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.3.04 BEBAN PENYUSUTAN PERALATAN</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.3.05 BEBAN PENYUSUTAN INVENTARIS KANTOR</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.3.06 BEBAN PENYUSUTAN SARANA DAN PRASARANA</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.3.07 BEBAN AMORTISASI GOODWILL</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.3.08 BEBAN AMORTISASI FRANCHISE</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.3.09 BEBAN AMORTISASI LISENSI</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>6.3.10 BEBAN AMORTISASI HAK CIPTA</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>7.0.00 PENDAPATAN DI LUAR USAHA </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>7.1.00 PENDAPATAN BUNGA BANK DAN JASA GIRO</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>7.2.00 LABA ATAS SELISIH KURS </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>7.3.00 LABA PELEPASAN ASET </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>7.4.00 PENDAPATAN DI LUAR USAHA LAINNYA </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>8.0.00 BEBAN DI LUAR USAHA </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>8.1.00 BEBAN BUNGA PINJAMAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>8.2.00 BEBAN ADMINISTRASI DAN PAJAK GIRO </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>8.3.00 BEBAN PROVISI </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>8.4.00 RUGI ATAS SELISIH KURS </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>8.5.00 RUGI PELEPASAN ASET </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>8.6.00 BEBAN SANKSI PAJAK </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>8.7.00 BEBAN PAJAK PENGHASILAN </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>8.8.00 BEBAN DI LUAR USAHA LAINNYA </td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>9.0.00 BIAYA</td>
                                    <td>Begining Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Ending Balance</td>
                                    <td class=" text-right">0.00</td>
                                    <td></td>
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