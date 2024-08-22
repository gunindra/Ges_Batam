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
            height: 40px;
            border: 1px solid #d1d3e2;
            border-radius: 0.25rem;
            padding: 6px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 27px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }

        /* Styling for the switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            /* Reduced width */
            height: 20px;
            /* Reduced height */
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 20px;
            /* Match height */
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            /* Reduced height */
            width: 16px;
            /* Reduced width */
            left: 2px;
            /* Adjusted positioning */
            bottom: 2px;
            /* Adjusted positioning */
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
            /* Adjusted translation */
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
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="customer" class="form-label fw-bold col-12">Customer</label>
                                    <select class="form-control select2singgle" id="selectCostumer" style="width: 65%">
                                        <option value="" selected disabled>Pilih Customer</option>
                                        @foreach ($listPembeli as $pembeli)
                                            <option value="{{ $pembeli->id }}">
                                                {{ $pembeli->marking }} - {{ $pembeli->nama_pembeli }}</option>
                                        @endforeach
                                    </select>
                                    <div id="customerError" class="text-danger mt-1 d-none">Silahkan Pilih Customer</div>
                                </div>
                                <div class="mt-3">
                                    <label for="currencyInvoice" class="form-label fw-bold">Currency</label>
                                    <select class="form-control col-8" name="" id="currencyInvoice">
                                        <option value="" selected disabled>Pilih Currency</option>
                                        @foreach ($listCurrency as $currency)
                                            <option value="{{ $currency->id }}">
                                                {{ $currency->nama_matauang }} ({{ $currency->singkatan_matauang }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="currencyInvoiceError" class="text-danger mt-1 d-none">Silahkan Pilih Currency
                                        terlebih
                                        dahulu
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
                                <div class="mt-3">
                                    <label for="beratBarang" class="form-label fw-bold">Berat (Kg)</label>
                                    <input type="text" class="form-control col-8" id="beratBarang" value="">
                                    <div id="beratError" class="text-danger mt-1 d-none">Masukkan Berat</div>
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
                                            @foreach ($lisPembagi as $pembagi)
                                                <option value="{{ $pembagi->nilai_pembagi }}">
                                                    {{ number_format($pembagi->nilai_pembagi, 0, ',', '.') }}</option>
                                            @endforeach
                                        </select>
                                        <div id="pembagiErrorVolume" class="text-danger mt-1 d-none">Silahkan Pilih
                                            Pembagi</div>
                                    </div>
                                    <!-- Rate Section -->
                                    <div class="flex-grow-1 ml-3">
                                        <label for="rate" class="form-label fw-bold">Rate</label>
                                        <select class="form-control" id="rateVolume">
                                            <option value="" selected disabled>Pilih Rate</option>
                                            @foreach ($listRateVolume as $ratevolume)
                                                <option value="{{ $ratevolume->nilai_rate }}">
                                                    {{ number_format($ratevolume->nilai_rate, 0, ',', '.') }}</option>
                                            @endforeach
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
                                        @foreach ($listSupir as $supir)
                                            <option value="{{ $supir->id }}">
                                                {{ $supir->nama_supir }} - {{ $supir->no_wa }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="driverError" class="text-danger mt-1 d-none">Silahkan pilih driver</div>
                                </div>
                                <div class="mt-3" id="alamatSection" style="display: none;">
                                    <label for="alamat" class="form-label fw-bold">Alamat Tujuan</label>
                                    {{-- <input type="text" class="form-control" id="alamat" style="width: 100%"
                                        value="" placeholder="Masukkan Alamat Tujuan"> --}}
                                    <textarea type="text" class="form-control" id="alamat" style="width: 100%"
                                    value="" placeholder="Masukkan Alamat Tujuan" cols="30" rows="10"></textarea>
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
                                        @foreach ($listTipePembayaran as $tipepembayaran)
                                            <option value="{{ $tipepembayaran->id }}">
                                                {{ $tipepembayaran->tipe_pembayaran }}</option>
                                        @endforeach
                                    </select>
                                    <div id="metodePembayaranError" class="text-danger mt-1 d-none">Silahkan pilih metode
                                        pembayaran</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3" id="rekeningSection" style="display: none;">
                                    <label for="rekening" class="form-label fw-bold col-12">Pilih Rekening</label>
                                    <select class="form-control select2singgle col-8" id="rekening" style="width: 65%">
                                        <option value="" selected disabled>Pilih Rekening</option>
                                        @foreach ($listRekening as $rekening)
                                            <option value="{{ $rekening->id }}">
                                                {{ $rekening->pemilik }} - {{ $rekening->nomer_rekening }} |
                                                {{ $rekening->nama_bank }}</option>
                                        @endforeach
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
@section('script')
    <script>
        $(document).ready(function() {
            // Store names of selected options
            var selectedProvinsiName = '';
            var selectedKabupatenKotaName = '';
            var selectedKecamatanName = '';
            var selectedKelurahanName = '';

            // Tarik data untuk Provinsi
            $.ajax({
                url: 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
                method: 'GET',
                success: function(data) {
                    var $provinsi = $('#provinsi');
                    $provinsi.empty().append(
                        '<option value="" selected disabled>Pilih Provinsi</option>');
                    $.each(data, function(index, provinsi) {
                        $provinsi.append('<option value="' + provinsi.id + '">' + provinsi
                            .name + '</option>');
                    });
                }
            });

            // Tarik data untuk Kabupaten/Kota berdasarkan Provinsi
            $('#provinsi').change(function() {
                var provinsiId = $(this).val();
                selectedProvinsiName = $('#provinsi option:selected').text(); // Store the name
                if (provinsiId) {
                    $.ajax({
                        url: 'https://www.emsifa.com/api-wilayah-indonesia/api/regencies/' +
                            provinsiId + '.json',
                        method: 'GET',
                        success: function(data) {
                            var $kabupatenKota = $('#kabupatenKota');
                            $kabupatenKota.empty().append(
                                '<option value="" selected disabled>Pilih Kabupaten/Kota</option>'
                            );
                            $.each(data, function(index, regency) {
                                $kabupatenKota.append('<option value="' + regency.id +
                                    '">' + regency.name + '</option>');
                            });
                        }
                    });
                }
            });

            // Tarik data untuk Kecamatan berdasarkan Kabupaten/Kota
            $('#kabupatenKota').change(function() {
                var regencyId = $(this).val();
                selectedKabupatenKotaName = $('#kabupatenKota option:selected').text(); // Store the name
                if (regencyId) {
                    $.ajax({
                        url: 'https://www.emsifa.com/api-wilayah-indonesia/api/districts/' +
                            regencyId + '.json',
                        method: 'GET',
                        success: function(data) {
                            var $kecamatan = $('#kecamatan');
                            $kecamatan.empty().append(
                                '<option value="" selected disabled>Pilih Kecamatan</option>'
                            );
                            $.each(data, function(index, district) {
                                $kecamatan.append('<option value="' + district.id +
                                    '">' + district.name + '</option>');
                            });
                        }
                    });
                }
            });

            // Tarik data untuk Kelurahan berdasarkan Kecamatan
            $('#kecamatan').change(function() {
                var districtId = $(this).val();
                selectedKecamatanName = $('#kecamatan option:selected').text(); // Store the name
                if (districtId) {
                    $.ajax({
                        url: 'https://www.emsifa.com/api-wilayah-indonesia/api/villages/' +
                            districtId + '.json',
                        method: 'GET',
                        success: function(data) {
                            var $kelurahan = $('#kelurahan');
                            $kelurahan.empty().append(
                                '<option value="" selected disabled>Pilih Kelurahan</option>'
                            );
                            $.each(data, function(index, village) {
                                $kelurahan.append('<option value="' + village.id +
                                    '">' + village.name + '</option>');
                            });
                        }
                    });
                }
            });

            $('#kelurahan').change(function() {
                selectedKelurahanName = $('#kelurahan option:selected').text(); // Store the name
            });

            $('.select2singgle').select2({
                width: 'resolve'
            });

            var today = new Date().toISOString().split('T')[0];
            // $('#tanggal').val(today);
            // $('#tanggal').attr('min', today);
            $('#tanggal').datepicker({
                format: 'dd MM yyyy',
                todayBtn: 'linked',
                todayHighlight: true,
                autoclose: true,
            });

            const exchangeRates = {
                1: 1,
                2: 11400,
                3: 2200
            };


            function updateDisplayedTotalHarga() {
                const currencyValue = $('#currencyInvoice').val();
                const totalHargaIDR = $('#totalHargaValue').val();
                let convertedTotal = 0;

                if (!currencyValue) {
                    $('#total-harga').text('-');
                } else if (currencyValue in exchangeRates) {
                    convertedTotal = totalHargaIDR / exchangeRates[currencyValue];
                    let currencySymbol = "";

                    if (currencyValue == 1) {
                        currencySymbol = "Rp. ";
                    } else if (currencyValue == 2) {
                        currencySymbol = "$ ";
                    } else if (currencyValue == 3) {
                        currencySymbol = "¥ ";
                    }

                    $('#total-harga').text(currencySymbol + convertedTotal.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                } else {
                    $('#total-harga').text('-');
                }
            }

            $('#currencyInvoice').change(function() {
                updateDisplayedTotalHarga();
            });

            updateDisplayedTotalHarga();


            $('#toggleSwitch').change(function() {
                if ($(this).is(':checked')) {
                    $('#idlabel').text('Volume');
                    $('#volumeDiv').removeClass('d-none');
                    $('#rowDimensi').removeClass('d-none');
                    $('#weightDiv').addClass('d-none');
                } else {
                    $('#idlabel').text('Berat');
                    $('#volumeDiv').addClass('d-none');
                    $('#rowDimensi').addClass('d-none');
                    $('#weightDiv').removeClass('d-none');
                }
            });

            $('#beratBarang').on('input', function() {
                this.value = this.value.replace(/[^0-9,.]/g, '');
                this.value = this.value.replace('.', ',');

                if (this.value) {
                    $('#panjang, #lebar, #tinggi').val('');
                }
                updateTotalHargaBerat();
                updateDisplayedTotalHarga();

                var berat = parseFloat(this.value.replace(',', '.')) || 0;

            });

            $('#panjang, #lebar, #tinggi').on('input', function() {
                this.value = this.value.replace(/[^0-9,.]/g, '');
                this.value = this.value.replace('.', ',');

                $('#beratBarang').val('');
                updateTotalHargaVolume();
                updateDisplayedTotalHarga();
            });

            $('#pembagiVolume, #rateVolume').change(function() {
                updateTotalHargaVolume();
                updateDisplayedTotalHarga();
            });

            function updateTotalHargaBerat() {
                let beratRaw = $('#beratBarang').val().replace(',', '.');
                let berat = parseFloat(beratRaw);

                if (beratRaw.trim() === '' || isNaN(berat)) {
                    $('#total-harga').text('Rp. 0');
                    $('#totalHargaValue').val(0);
                } else {
                    berat = Math.max(2, berat);
                    let hargaPerKg = 15000;
                    let totalHarga = berat * hargaPerKg;
                    if (totalHarga > 250000) {
                        showMessage("error", "Barang Diatas 250.000 pakai Volume")
                        $('#beratBarang').val('');
                        $('#total-harga').text('Rp. 0');
                        $('#toggleSwitch').click();
                    } else {
                        $('#totalHargaValue').val(totalHarga)
                        updateDisplayedTotalHarga();
                    }

                }
            }

            function updateTotalHargaVolume() {
                // Ambil nilai dari input dan konversi ke angka
                var panjang = parseFloat($('#panjang').val().replace(',', '.')) || 0;
                var lebar = parseFloat($('#lebar').val().replace(',', '.')) || 0;
                var tinggi = parseFloat($('#tinggi').val().replace(',', '.')) || 0;

                // Hitung volume
                var volume = panjang * lebar * tinggi;

                var pembagi = parseFloat($('#pembagiVolume').val()) || 1;
                var rate = parseFloat($('#rateVolume').val()) || 1;

                // Hitung total harga volume
                var totalHargaVolume = (volume / pembagi) * rate;

                $('#dimensiValue').text(volume);
                updateDisplayedTotalHarga();
                $('#totalHargaValue').val(totalHargaVolume);
            }



            function updateSections() {
                if ($('#delivery').is(':checked')) {
                    $('#driverSection').show();
                    $('#alamatSection, #lokasiSection').show();
                } else {
                    $('#driverSection').hide();
                    $('#alamatSection, #lokasiSection').hide();
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
                if ($(this).val() === '2') {
                    $('#rekeningSection').show();
                } else {
                    $('#rekeningSection').hide();
                }
            });

            $('#buatInvoice').click(function() {

                // Collect all form data
                const noResi = $('#noResi').val().trim();
                const tanggal = $('#tanggal').val().trim();
                const customer = $('#selectCostumer').val();
                const currencyInvoice = $('#currencyInvoice').val();
                const beratBarang = $('#beratBarang').val().trim();
                const panjang = $('#panjang').val().trim();
                const lebar = $('#lebar').val().trim();
                const tinggi = $('#tinggi').val().trim();
                const metodePengiriman = $('#delivery').is(':checked') ? 'Delivery' : 'Pickup';
                const metodePembayaran = $('#metodePembayaran').val();
                const driver = metodePengiriman === 'Delivery' ? $('#driver').val() : null;
                const alamat = metodePengiriman === 'Delivery' ? $('#alamat').val().trim() : null;
                const provinsi = metodePengiriman === 'Delivery' ? $('#provinsi').val() : null;
                const kabupatenKota = metodePengiriman === 'Delivery' ? $('#kabupatenKota').val() : null;
                const kecamatan = metodePengiriman === 'Delivery' ? $('#kecamatan').val() : null;
                const kelurahan = metodePengiriman === 'Delivery' ? $('#kelurahan').val() : null;
                const rekening = metodePembayaran === '2' ? $('#rekening').val() : null;
                const pembagiVolume = $('#toggleSwitch').is(':checked') ? $('#pembagiVolume').val() : null;
                const rateVolume = $('#toggleSwitch').is(':checked') ? $('#rateVolume').val() : null;
                let totalharga = $('#totalHargaValue').val();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let isValid = true;

                if (noResi === '') {
                    $('#noResiError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#noResiError').addClass('d-none');
                }

                if (tanggal === '') {
                    $('#tanggalError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#tanggalError').addClass('d-none');
                }

                if (customer === null) {
                    $('#customerError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#customerError').addClass('d-none');
                }

                if (currencyInvoice === null) {
                    $('#currencyInvoiceError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#currencyInvoiceError').addClass('d-none');
                }

                if (metodePengiriman === 'Delivery') {
                    if (driver === null) {
                        $('#driverError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#driverError').addClass('d-none');
                    }

                    if (alamat === '') {
                        $('#alamatError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#alamatError').addClass('d-none');
                    }

                    if (provinsi === null) {
                        $('#provinsiError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#provinsiError').addClass('d-none');
                    }

                    if (kabupatenKota === null) {
                        $('#kotaError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#kotaError').addClass('d-none');
                    }

                    if (kecamatan === null) {
                        $('#kecamatanError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#kecamatanError').addClass('d-none');
                    }

                    if (kelurahan === null) {
                        $('#kelurahanError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#kelurahanError').addClass('d-none');
                    }
                }

                if (metodePembayaran === null) {
                    $('#metodePembayaranError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#metodePembayaranError').addClass('d-none');
                }

                if (metodePembayaran === '2' && rekening === null) {
                    $('#rekeningError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#rekeningError').addClass('d-none');
                }

                if ($('#toggleSwitch').is(':checked')) {
                    if (panjang === '' || lebar === '' || tinggi === '') {
                        $('#volumeError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#volumeError').addClass('d-none');
                    }

                    if (pembagiVolume === null) {
                        $('#pembagiErrorVolume').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#pembagiErrorVolume').addClass('d-none');
                    }

                    if (rateVolume === null) {
                        $('#raterErrorVolume').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#raterErrorVolume').addClass('d-none');
                    }
                } else {
                    if (beratBarang === '') {
                        $('#beratError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#beratError').addClass('d-none');
                    }
                }

                if (!isValid) {
                    Swal.fire({
                        title: "Periksa input yang masih kosong.",
                        icon: "error"
                    });
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('tambainvoice') }}",
                    data: {
                        noResi: noResi,
                        tanggal: tanggal,
                        customer: customer,
                        currencyInvoice: currencyInvoice,
                        beratBarang: beratBarang,
                        panjang: panjang,
                        lebar: lebar,
                        tinggi: tinggi,
                        metodePengiriman: metodePengiriman,
                        driver: driver,
                        alamat: alamat,
                        provinsi: selectedProvinsiName,
                        kabupatenKota: selectedKabupatenKotaName,
                        kecamatan: selectedKecamatanName,
                        kelurahan: selectedKelurahanName,
                        metodePembayaran: metodePembayaran,
                        rekening: rekening,
                        totalharga: totalharga,
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showMessage("success", "Invoice berhasil dibuat").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal membuat invoice",
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "Gagal membuat invoice",
                            text: "Terjadi kesalahan. Mohon coba lagi.",
                            icon: "error"
                        });
                    }
                });
            });
        });
    </script>

@endsection
