@extends('layout.main')

@section('title', 'Buat Invoice')

@section('main')

    <style>
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #cccccc;
        }

        .divider::before {
            margin-right: .25em;
        }

        .divider::after {
            margin-left: .25em;
        }

        .divider span {
            padding: 0 10px;
            font-weight: bold;
            color: #555555;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #d1d3e2;
            border-radius: 0.25rem;
            padding: 6px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 25px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
    </style>


    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Buat Invoice</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('invoice') }}">Invoice</a></li>
                <li class="breadcrumb-item active" aria-current="page">Buat Invoice</li>
            </ol>
        </div>

        <a class="btn btn-primary mb-3" href="{{ route('invoice') }}">
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
                                    <input type="text" class="form-control col-8" id="noResi" value="">
                                    <div id="noResiError" class="text-danger mt-1">Silahkan scan terlebih dahulu</div>
                                </div>
                                <div class="mt-3">
                                    <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                                    <input type="date" class="form-control col-8" id="tanggal" value="">
                                    <div id="tanggalError" class="text-danger mt-1">Tanggal tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="customer" class="form-label fw-bold col-12">Customer</label>
                                    <select class="select2-single form-control" id="select2Single" style="width: 65%">
                                        <option value="" selected disabled>Pilih Customer</option>
                                        <option value="Tandrio">Tandrio - 082199328292</option>
                                        <option value="Ricard">Ricard - 082199328292</option>
                                        <option value="Naruto">Naruto - 082199328292</option>
                                        <option value="Sasuke">Sasuke - 082199328292</option>
                                    </select>
                                    <div id="customerError" class="text-danger mt-1">Silahkan Pilih Customer</div>
                                </div>
                            </div>
                        </div>
                        <div class="divider mt-4">
                            <span>Detail Barang</span>
                        </div>
                        <div class="d-flex flex-row">
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="namaBarang" class="form-label fw-bold">Nama Barang</label>
                                    <input type="text" class="form-control col-8" id="namaBarang" value="">
                                    <div id="namaBarangError" class="text-danger mt-1">Nama Barang tidak boleh kosong</div>
                                </div>
                                <div class="mt-3">
                                    <label for="hargaBarang" class="form-label fw-bold">Harga Barang</label>
                                    <input type="number" class="form-control col-8" id="hargaBarang" value="">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="beratBarang" class="form-label fw-bold">Berat (Kg)</label>
                                    <input type="" class="form-control col-8" id="beratBarang" value="">
                                </div>
                                <div class="mt-4">
                                    <label for="volumeBarang" class="form-label fw-bold">Volume</label>
                                    <div class="d-flex">
                                        <input type="number" class="form-control col-2" id="panjang" placeholder="P"
                                            value="">
                                        <p class="m-2">X</p>
                                        <input type="number" class="form-control col-2" id="lebar" placeholder="L"
                                            value="">
                                        <p class="m-2">X</p>
                                        <input type="number" class="form-control col-2" id="tinggi" placeholder="T"
                                            value="">
                                        <h5 class="mt-2 mx-3">cm</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="divider mt-4">
                            <span>Pengiriman</span>
                        </div>
                        <div class="d-flex flex-row">
                            <div class="col-2">
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
                            <div class="col-6">
                                <div class="mt-3" id="driverSection" style="display: none;">
                                    <label for="driver" class="form-label fw-bold">Driver</label>
                                    <select class="form-control col-8" id="driver">
                                        <option value="" selected disabled>Pilih Driver</option>
                                        <option value="Driver1">Driver1</option>
                                        <option value="Driver2">Driver2</option>
                                    </select>
                                    <div id="driverError" class="text-danger mt-1">Silahkan pilih driver</div>
                                </div>
                                <div class="mt-3" id="alamatSection" style="display: none;">
                                    <label for="alamat" class="form-label fw-bold">Alamat Tujuan</label>
                                    <input type="text" class="form-control col-8" id="alamat" value="">
                                    <div id="alamatError" class="text-danger mt-1">Alamat tidak boleh kosong</div>
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
                                        <option value="cash">Cash</option>
                                        <option value="transfer">Transfer</option>
                                        <option value="poin">Poin</option>
                                    </select>
                                    <div id="metodePembayaranError" class="text-danger mt-1">Silahkan pilih metode
                                        pembayaran</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3" id="rekeningSection" style="display: none;">
                                    <label for="rekening" class="form-label fw-bold">Pilih Rekening</label>
                                    <select class="form-control col-8" id="rekening">
                                        <option value="" selected disabled>Pilih Rekening</option>
                                        <option value="Rekening1">Rekening1</option>
                                        <option value="Rekening2">Rekening2</option>
                                    </select>
                                    <div id="rekeningError" class="text-danger mt-1">Silahkan pilih rekening</div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary float-right mt-3">Buat Invoice</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---Container Fluid-->

@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $('.select2-single').select2({
                width: 'resolve'
            });
            var today = new Date().toISOString().split('T')[0];
            $('#tanggal').val(today);
            $('#tanggal').attr('min', today);

            $('#hargaBarang').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('#beratBarang').on('input', function() {
                this.value = this.value.replace(/[^0-9,\.]/g, '');
                this.value = this.value.replace('.', ',');
                if (this.value) {
                    $('#panjang, #lebar, #tinggi').val('');
                }
            });

            $('#panjang, #lebar, #tinggi').on('input', function() {
                if ($(this).val()) {
                    $('#beratBarang').val('');
                }
            });

            function updateSections() {
                if ($('#delivery').is(':checked')) {
                    $('#driverSection').show();
                    $('#alamatSection').show();
                } else {
                    $('#driverSection').hide();
                    $('#alamatSection').hide();
                }
            }

            $('#pickup').change(function() {
                if ($(this).is(':checked')) {
                    $('#delivery').prop('checked', false);
                    updateSections();
                }
            });

            $('#delivery').change(function() {
                if ($(this).is(':checked')) {
                    $('#pickup').prop('checked', false);
                    updateSections();
                }
            });

            updateSections();

            $('#metodePembayaran').change(function() {
                if ($(this).val() === 'transfer') {
                    $('#rekeningSection').show();
                } else {
                    $('#rekeningSection').hide();
                }
            });



        });
    </script>
@endsection
