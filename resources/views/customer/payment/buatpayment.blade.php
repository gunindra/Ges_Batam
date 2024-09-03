@extends('layout.main')

@section('title', 'Buat Payment')

@section('main')

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Buat Payment</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Costumer</li>
                <li class="breadcrumb-item"><a href="{{ route('payment') }}">Payment</a></li>
                <li class="breadcrumb-item active" aria-current="page">Buat Payment</li>
            </ol>
        </div>

        <a class="btn btn-primary mb-3" href="{{ route('payment') }}">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="noResi" class="form-label fw-bold">No Resi</label>
                                    <input type="text" class="form-control col-8" id="noResi" value=""
                                        placeholder="Scan Resi">
                                    <div id="noResiError" class="text-danger mt-1 d-none">Silahkan Scan No Resi terlebih
                                        dahulu</div>
                                </div>
                                <div class="mt-3">
                                    <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                                    <input type="text" class="form-control col-8" id="tanggal" value=""
                                        placeholder="Pilih tanggal">
                                    <div id="tanggalError" class="text-danger mt-1 d-none">Tanggal tidak boleh kosong</div>
                                </div>
                                <div class="mt-3">
                                    <label for="customer" class="form-label fw-bold col-12">Customer</label>
                                    <select class="form-control select2singgle" id="selectCostumer" style="width: 67%">
                                        <option value="" selected disabled>Pilih Customer</option>

                                    </select>
                                    <div id="customerError" class="text-danger mt-1 d-none">Silahkan Pilih Customer</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="currencyInvoice" class="form-label fw-bold">Currency</label>
                                    <select class="form-control col-8" name="" id="currencyInvoice">
                                        <option value="" selected disabled>Pilih Currency</option>

                                    </select>
                                    <div id="currencyInvoiceError" class="text-danger mt-1 d-none">Silahkan Pilih Currency
                                        terlebih dahulu</div>
                                </div>
                                <div class="mt-3" id="rateCurrencySection" style="display: none;">
                                    <label for="rateCurrency" class="form-label fw-bold">Rate Currency</label>
                                    <input type="text" class="form-control col-8" id="rateCurrency" value=""
                                        placeholder="Masukkan rate">
                                    <div id="rateCurrencyError" class="text-danger mt-1 d-none">Rate tidak boleh kosong
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="divider mt-4">
                            <span>Detail Barang</span>
                        </div>
                        <div class="d-flex flex-row">
                            <label class="switch">
                                <input type="checkbox" id="toggleSwitch">
                                <span class="slider"></span>
                            </label>
                            <label class="fw-bold pl-2" id="idlabel">Berat</label>
                        </div>
                        <div class="d-flex flex-row">
                            <div class="col-6" id="weightDiv">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mt-3">
                                            <label for="beratBarang" class="form-label fw-bold">Berat (Kg)</label>
                                            <input type="text" class="form-control" id="beratBarang" value="">
                                            <div id="beratError" class="text-danger mt-1 d-none">Masukkan Berat</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mt-3">
                                            <label for="rateSelect" class="form-label fw-bold">Rate</label>
                                            <select class="form-control" id="rateBerat">
                                                <option value="" selected disabled>Pilih Rate</option>

                                            </select>
                                            <div id="rateBeratError" class="text-danger mt-1 d-none">Silahkan Pilih Rate
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-none" id="volumeDiv">
                                <div class="d-flex flex-row mt-3">
                                    <!-- Volume Section -->
                                    <div class="flex-grow-1 me-3">
                                        <label for="volume" class="form-label fw-bold">Volume</label>
                                        <div class="d-flex align-items-center">
                                            <input type="text" class="form-control col-2 me-1" id="panjang"
                                                placeholder="P" value="">
                                            <span class="mx-1">X</span>
                                            <input type="text" class="form-control col-2 mx-1" id="lebar"
                                                placeholder="L" value="">
                                            <span class="mx-1">X</span>
                                            <input type="text" class="form-control col-2 ms-1" id="tinggi"
                                                placeholder="T" value="">
                                            <span class="ml-2 ">cm</span>
                                        </div>
                                    </div>
                                    <!-- Pembagi Section -->
                                    <div class="flex-grow-1">
                                        <label for="pembagiVolume" class="form-label fw-bold">Pembagi</label>
                                        <select class="form-control" id="pembagiVolume">
                                            <option value="" selected disabled>Pilih Pembagi</option>

                                        </select>
                                        <div id="pembagiErrorVolume" class="text-danger mt-1 d-none">Silahkan Pilih
                                            Pembagi</div>
                                    </div>
                                    <!-- Rate Section -->
                                    <div class="flex-grow-1 ml-3">
                                        <label for="rate" class="form-label fw-bold">Rate</label>
                                        <select class="form-control" id="rateVolume">
                                            <option value="" selected disabled>Pilih Rate</option>

                                        </select>
                                        <div id="raterErrorVolume" class="text-danger mt-1 d-none">Silahkan Pilih Rate
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="divider mt-4">
                            <span>Pengiriman</span>
                        </div>
                        <div class="d-flex flex-row">
                            <!-- Left Column: Delivery Method -->
                            <div class="col-4">
                                <div class="mt-3">
                                    <label for="metodePengiriman" class="form-label fw-bold">Metode Pengiriman</label>
                                    <div>
                                        <input type="checkbox" id="pickup" checked>
                                        <label for="pickup" class="form-label fw-bold">Pick Up</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="delivery">
                                        <label for="delivery" class="form-label fw-bold">Delivery</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Middle Column: Driver and Address -->
                            <div class="col-4">
                                <div class="mt-3" id="driverSection" style="display: none;">
                                    <label for="driver" class="form-label fw-bold col-12">Driver</label>
                                    <select class="form-control select2singgle col-8" id="driver" style="width: 100%">
                                        <option value="" selected disabled>Pilih Driver</option>

                                    </select>
                                    <div id="driverError" class="text-danger mt-1 d-none">Silahkan pilih driver</div>
                                </div>
                                <div class="mt-3" id="alamatSection" style="display: none;">
                                    <label for="alamat" class="form-label fw-bold">Alamat Tujuan</label>
                                    {{-- <input type="text" class="form-control" id="alamat" style="width: 100%"
                                        value="" placeholder="Masukkan Alamat Tujuan"> --}}
                                    <textarea type="text" class="form-control" id="alamat" style="width: 100%" value=""
                                        placeholder="Masukkan Alamat Tujuan" cols="30" rows="10"></textarea>
                                    <div id="alamatError" class="text-danger mt-1 d-none">Alamat tidak boleh kosong</div>
                                    <label for="provinsi" class="form-label mt-1 fw-bold">Provinsi</label>
                                    <select class="form-control select2singgle col-8" id="provinsi" style="width: 100%">
                                        <option value="" selected disabled>Pilih Provinsi</option>
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                    <div id="provinsiError" class="text-danger mt-1 d-none">Provinsi tidak boleh kosong
                                    </div>
                                </div>


                            </div>

                            <!-- Right Column: Location Details -->
                            <div class="col-4">
                                <div class="mt-3" id="lokasiSection" style="display: none;">
                                    <label for="kota" class="form-label fw-bold mt-2">Kota / Kabupaten</label>
                                    <select class="form-control select2singgle col-8" id="kabupatenKota"
                                        style="width: 100%">
                                        <option value="" selected disabled>Pilih Kabupaten/Kota</option>
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                    <div id="kotaError" class="text-danger mt-1 d-none">Kota/Kab tidak boleh kosong</div>

                                    <label for="kecamatan" class="form-label fw-bold mt-2">Kecamatan</label>
                                    <select class="form-control select2singgle col-8" id="kecamatan" style="width: 100%">
                                        <option value="" selected disabled>Pilih Kecamatan</option>
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                    <div id="kecamatanError" class="text-danger mt-1 d-none">Kecamatan tidak boleh kosong
                                    </div>

                                    <label for="kelurahan" class="form-label fw-bold">Kelurahan</label>
                                    <select class="form-control select2singgle col-8" id="kelurahan" style="width: 100%">
                                        <option value="" selected disabled>Pilih Kelurahan</option>
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                    <div id="kelurahanError" class="text-danger mt-1 d-none">Kelurahan tidak boleh kosong
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="divider mt-4">
                            <span>Metode Pembayaran</span>
                        </div>
                        <div class="d-flex flex-row">
                            <div class="col-4">
                                <div class="mt-3">
                                    <label for="metodePembayaran" class="form-label fw-bold">Metode Pembayaran</label>
                                    <select class="form-control" id="metodePembayaran">
                                        <option value="" selected disabled>Pilih Pembayaran</option>

                                    </select>
                                    <div id="metodePembayaranError" class="text-danger mt-1 d-none">Silahkan pilih metode
                                        pembayaran</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3" id="rekeningSection" style="display: none;">
                                    <label for="rekening" class="form-label fw-bold col-12">Pilih Rekening</label>
                                    <select class="form-control select2singgle col-8" id="rekening" style="width: 67%">
                                        <option value="" selected disabled>Pilih Rekening</option>

                                    </select>
                                    <div id="rekeningError" class="text-danger mt-1 d-none">Silahkan pilih rekening</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-4 d-none" id="rowDimensi">
                                <div class="col-2 offset-8 me-1">
                                    <p class="mb-0">Dimensi</p>
                                    <div class="box bg-light text-dark p-3 mt-2"
                                        style="border: 1px solid; border-radius: 8px; font-size: 1.5rem;">
                                        <span id="dimensiValue" style="font-weight: bold; color: #555;">0</span><img
                                            class="pb-1" style="width: 35px; height: 35px;" src="/img/m3_icon.png"
                                            alt="m3">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="col-4 float-right">
                                <p class="mb-0">Total Harga</p>
                                <div class="box bg-light text-dark p-3 mt-2"
                                    style="border: 1px solid; border-radius: 8px; font-size: 1.5rem;">
                                    <span id="total-harga" style="font-weight: bold; color: #555;">Rp. 0</span>
                                </div>
                                <input type="hidden" name="" id="totalHargaValue">
                                <button id="buatInvoice" class="btn btn-primary p-3 float-right mt-3"
                                    style="width: 100%;">Buat
                                    Invoice</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---Container Fluid-->

@endsection
