@extends('layout.main')

@section('title', 'Report | Balance')

@section('main')

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Balance</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Report</li>
            <li class="breadcrumb-item active" aria-current="page">Balance</li>
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
                    <div id="containerbalance" class="table-responsive px-3">
                        <table id="tableBalanceAsset" width="100%" class="treetable">
                            <tbody>
                                <tr style="font-size: 15px;" data-tt-id="1" data-tt-parent-id="0"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 0px;"></span>1.0.00 ASET</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="7" data-tt-parent-id="1"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 19px;"></span>1.1.00 ASET LANCAR</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="9" data-tt-parent-id="7"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>1.1.01 KAS</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="10" data-tt-parent-id="9"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.01.01 KAS IDR</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="13" data-tt-parent-id="9"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.01.02 KAS KECIL</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="14" data-tt-parent-id="9"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.01.03 KAS SGD</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="15" data-tt-parent-id="7"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>1.1.02 BANK</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="23" data-tt-parent-id="15"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.02.01 BANK BCA IDR</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="24" data-tt-parent-id="15"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.02.02 BANK BCA SGD</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="25" data-tt-parent-id="15"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.02.03 BANK MANDIRI IDR</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="26" data-tt-parent-id="15"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.02.04 BANK BNI SGD</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="27" data-tt-parent-id="15"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.02.05 BANK NIAGA IDR</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="28" data-tt-parent-id="15"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.02.06 BANK NIAGA SGD</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="29" data-tt-parent-id="15"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.02.07 BANK BCA CHANDRA SUSANTO
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="30" data-tt-parent-id="7"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>1.1.03 PIUTANG </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="31" data-tt-parent-id="30"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.03.01 PIUTANG USAHA</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="32" data-tt-parent-id="30"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.03.02 PIUTANG LAIN-LAIN </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="33" data-tt-parent-id="7"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>1.1.05 PERLENGKAPAN </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="34" data-tt-parent-id="33"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.05.01 PERLENGKAPAN KANTOR </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="16" data-tt-parent-id="7"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>1.1.06 PAJAK DIBAYAR DIMUKA</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="17" data-tt-parent-id="16"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.06.01 PPN MASUKAN</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="35" data-tt-parent-id="16"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.06.02 PPH 21 DIBAYAR DI MUKA
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="36" data-tt-parent-id="16"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.06.03 PPH 22 DIBAYAR DI MUKA
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="22" data-tt-parent-id="16"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.06.04 PPH 23 DIBAYAR DI MUKA
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="37" data-tt-parent-id="16"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.06.05 PPH 24 DIBAYAR DI MUKA
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="38" data-tt-parent-id="16"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.06.06 PPH 25 DIBAYAR DI MUKA
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="39" data-tt-parent-id="16"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.06.07 PPH LEBIH BAYAR </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="40" data-tt-parent-id="16"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.06.08 PPH 15 DIBAYAR DI MUKA</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="41" data-tt-parent-id="7"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>1.1.07 BIAYA DIBAYAR DI MUKA</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="42" data-tt-parent-id="41"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.07.01 UANG MUKA PEMBELIAN </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="43" data-tt-parent-id="41"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.07.02 SEWA DIBAYAR DI MUKA</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="44" data-tt-parent-id="41"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.07.03 ASURANSI DIBAYAR DI MUKA
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="45" data-tt-parent-id="41"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.1.07.04 UANG MUKA PEMBELIAN
                                        KENDARAAN
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="8" data-tt-parent-id="1"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 19px;"></span>1.2.00 ASET TETAP</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="46" data-tt-parent-id="8"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>1.2.01 ASET TETAP BERWUJUD </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="47" data-tt-parent-id="46"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.2.01.03 KENDARAAN</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="48" data-tt-parent-id="46"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.2.01.05 PERALATAN </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="49" data-tt-parent-id="46"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.2.01.06 INVENTARIS KANTOR </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="50" data-tt-parent-id="46"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.2.01.09 AKUMULASI PENYUSUTAN
                                        KENDARAAN
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="51" data-tt-parent-id="46"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.2.01.11 AKUMULASI PENYUSUTAN
                                        PERALATAN
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="52" data-tt-parent-id="46"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.2.01.11 AKUMULASI PENYUSUTAN
                                        PERALATAN
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="53" data-tt-parent-id="46"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>1.2.01.12 AKUMULASI PENYUSUTAN
                                        INVENTARIS
                                        KANTOR </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="card-title text-right pt-4"><b>
                                            <p style="font-size: 18px;">TOTAL 0.00</p>
                                        </b></td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="tableBalanceLiabilitas" width="100%" class="treetable">
                            <tbody>
                                <tr style="font-size: 15px;" data-tt-id="11" data-tt-parent-id="0"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 0px;"></span>2.0.00 LIABILITAS</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="18" data-tt-parent-id="11"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 19px;"></span>2.1.00 LIABILITAS JANGKA PENDEK</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="54" data-tt-parent-id="18"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>2.1.01 UTANG USAHA </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="55" data-tt-parent-id="54"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.01.01 UTANG USAHA </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="56" data-tt-parent-id="54"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.01.02 HUTANG PAK CHANDRA</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="57" data-tt-parent-id="55"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 76px;"></span>2.1.01.03 HUTANG KENDARAAN</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="58" data-tt-parent-id="54"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.01.04 HUTANG - PT ISB</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="59" data-tt-parent-id="54"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.01.05 HUTANG LAINNYA</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="60" data-tt-parent-id="54"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.01.06 HUTANG - PT MMCT</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="61" data-tt-parent-id="54"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.01.07 HUTANG BANGUNAN</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="19" data-tt-parent-id="18"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>2.1.02 UTANG PAJAK</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="20" data-tt-parent-id="19"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.02.01 PPN KELUARAN</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="62" data-tt-parent-id="19"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.02.02 UTANG PPH 21</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="63" data-tt-parent-id="19"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.02.03 UTANG PPH 22 </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="21" data-tt-parent-id="19"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.02.04 UTANG PPH 23</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="64" data-tt-parent-id="19"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.02.05 UTANG PPH 25</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="65" data-tt-parent-id="19"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.02.06 UTANG PPH 4(2) </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="66" data-tt-parent-id="19"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.02.07 PPH KURANG BAYAR </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="67" data-tt-parent-id="19"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.02.08 UTANG PPH 15</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="68" data-tt-parent-id="18"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>2.1.03 BIAYA YANG MASIH HARUS DIBAYAR
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="69" data-tt-parent-id="68"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.03.01 UTANG GAJI DAN UPAH </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="70" data-tt-parent-id="68"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.03.02 UTANG BPJS KETENAGAKERJAAN
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="71" data-tt-parent-id="68"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.03.03 UTANG BPJS KESEHATAN </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="72" data-tt-parent-id="68"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.03.04 UTANG LISTRIK DAN AIR </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="73" data-tt-parent-id="68"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.03.05 UTANG TELEPON DAN INTERNET
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="74" data-tt-parent-id="68"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.03.06 UTANG ASURANSI </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="75" data-tt-parent-id="68"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.03.07 UTANG SEWA </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="76" data-tt-parent-id="68"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.03.08 BIAYA YANG MASIH HARUS
                                        DIBAYAR
                                        LAINNYA </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="77" data-tt-parent-id="18"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>2.1.04 UTANG LAIN-LAIN </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="78" data-tt-parent-id="77"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.04.01 UTANG BANK JANGKA PENDEK
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="79" data-tt-parent-id="77"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.04.02 UTANG DEVIDEN </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="80" data-tt-parent-id="77"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.04.03 PENDAPATAN DITERIMA DI MUKA
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="81" data-tt-parent-id="77"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.04.04 UTANG PEMBELIAN BELUM
                                        DITAGIH
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="82" data-tt-parent-id="77"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 57px;"></span>2.1.04.05 UTANG PADA PT. BMS</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="83" data-tt-parent-id="11"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 19px;"></span>2.2.00 LIABILITAS JANGKA PANJANG
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="84" data-tt-parent-id="83"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>2.2.01 UTANG BANK JANGKA PANJANG
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="85" data-tt-parent-id="83"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>2.2.02 UTANG JANGKA PANJANG LAINNYA
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="card-title text-right pt-4"><b>
                                            <p style="font-size: 18px;">TOTAL 0.00</p>
                                        </b></td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="tableBalanceEquity" width="100%" class="treetable">
                            <tbody>
                                <tr style="font-size: 15px;" data-tt-id="12" data-tt-parent-id="0"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 0px;"></span>3.0.00 EQUITAS</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="86" data-tt-parent-id="12"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 19px;"></span>3.1.00 MODAL </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="87" data-tt-parent-id="86"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>3.1.01 MODAL DISETOR </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="88" data-tt-parent-id="86"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>3.1.02 TAMBAHAN MODAL DISETOR </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="89" data-tt-parent-id="12"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 19px;"></span>3.2.00 SALDO LABA</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="90" data-tt-parent-id="89"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>3.2.01 SALDO LABA DITAHAN </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="91" data-tt-parent-id="89"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>3.2.02 SALDO LABA TAHUN BERJALAN
                                    </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="92" data-tt-parent-id="12"
                                    class="branch expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 19px;"></span>3.3.00 DEVIDEN </td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr style="font-size: 15px;" data-tt-id="93" data-tt-parent-id="92"
                                    class="leaf expanded">
                                    <td style="padding-top:10px;"><span class="spasi"
                                            style="padding-left: 38px;"></span>3.3.01 PREV ATAU DEVIDEN</td>
                                    <td class="card-title text-right">0.00</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="card-title text-right pt-4"><b>
                                            <p style="font-size: 18px;">TOTAL 0.00</p>
                                        </b></td>
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