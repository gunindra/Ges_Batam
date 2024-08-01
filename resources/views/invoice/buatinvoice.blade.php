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
                                    <input type="text" class="form-control col-8" id="noResi" value="">
                                    <div id="noResiError" class="text-danger mt-1">Silahkan Scan No Resi terlebih dahulu
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                                    <input type="text" class="form-control col-8" id="tanggal" value="">
                                    <div id="tanggalError" class="text-danger mt-1">Tanggal tidak boleh kosong</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="customer" class="form-label fw-bold col-12">Customer</label>
                                    <select class="form-control select2singgle" id="selectCostumer" style="width: 65%">
                                        <option value="" selected disabled>Pilih Customer</option>
                                        @foreach ($listPembeli as $pembeli)
                                            <option value="{{ $pembeli->id }}">
                                                {{ $pembeli->nama_pembeli }} - {{ $pembeli->no_wa }}</option>
                                        @endforeach
                                    </select>
                                    <div id="customerError" class="text-danger mt-1">Silahkan Pilih Customer</div>
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
                                    <input type="number" class="form-control col-8" id="beratBarang" value="">
                                </div>
                            </div>
                            <div class="col-12 d-none" id="volumeDiv">
                                <div class="d-flex flex-row mt-3">
                                    <!-- Volume Section -->
                                    <div class="flex-grow-1 me-3">
                                        <label for="volume" class="form-label fw-bold">Volume</label>
                                        <div class="d-flex align-items-center">
                                            <input type="number" class="form-control col-2 me-1" id="panjang" placeholder="P"
                                                value="">
                                            <span class="mx-1">X</span>
                                            <input type="number" class="form-control col-2 mx-1" id="lebar" placeholder="L"
                                                value="">
                                            <span class="mx-1">X</span>
                                            <input type="number" class="form-control col-2 ms-1" id="tinggi" placeholder="T"
                                                value="">
                                            <span class="ml-2 ">cm</span>
                                        </div>
                                    </div>
                                    <!-- Pembagi Section -->
                                    <div class="flex-grow-1">
                                        <label for="pembagi" class="form-label fw-bold">Pembagi</label>
                                        <select class="form-control" id="pembagi">
                                            <option value="1000000">1.000.000</option>
                                            <option value="6000">6.000</option>
                                        </select>
                                    </div>
                                    <!-- Rate Section -->
                                    <div class="flex-grow-1 ml-3">
                                        <label for="rate" class="form-label fw-bold">Rate</label>
                                        <select class="form-control" id="rate">
                                            <option value="100">100</option>
                                            <option value="200">200</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="divider mt-4">
                            <span>Pengiriman</span>
                        </div>
                        <div class="d-flex flex-row">
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
                            <div class="col-6">
                                <div class="mt-3" id="driverSection" style="display: none;">
                                    <label for="driver" class="form-label fw-bold col-12">Driver</label>
                                    <select class="form-control select2singgle col-8" id="driver" style="width: 65%">
                                        <option value="" selected disabled>Pilih Driver</option>
                                        @foreach ($listSupir as $supir)
                                            <option value="{{ $supir->id }}">
                                                {{ $supir->nama_supir }} - {{ $supir->no_wa }}</option>
                                        @endforeach
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
                                    <label for="rekening" class="form-label fw-bold col-12">Pilih Rekening</label>
                                    <select class="form-control select2singgle col-8" id="rekening" style="width: 65%">
                                        <option value="" selected disabled>Pilih Rekening</option>
                                        @foreach ($listRekening as $rekening)
                                            <option value="{{ $rekening->id }}">
                                                {{ $rekening->pemilik }} - {{ $rekening->nomer_rekening }} |
                                                {{ $rekening->nama_bank }}</option>
                                        @endforeach
                                    </select>
                                    <div id="rekeningError" class="text-danger mt-1">Silahkan pilih rekening</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="col-4 float-right">
                                <p class="mb-0">Total Harga</p>
                                <div class="box bg-light text-dark p-3 mt-2"
                                    style="border: 1px solid; border-radius: 8px; font-size: 1.5rem;">
                                    <span id="total-harga" style="font-weight: bold; color: #555;">Rp 0</span>

                                </div>
                                <input type="hidden" name="" id="totalHargaValue">
                                <button class="btn btn-primary float-right mt-3" style="width: 100%;">Buat
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
            $('.select2singgle').select2({
                width: 'resolve'
            });

            var today = new Date().toISOString().split('T')[0];
            // $('#tanggal').val(today);
            // $('#tanggal').attr('min', today);
            $('#tanggal').datepicker({
                format: 'dd/mm/yyyy',
                todayBtn: 'linked',
                todayHighlight: true,
                autoclose: true,
            });


            $('#toggleSwitch').change(function() {
                if ($(this).is(':checked')) {
                    // Show volume, hide weight
                    $('#idlabel').text('Volume');
                    $('#volumeDiv').removeClass('d-none');
                    $('#weightDiv').addClass('d-none');
                } else {
                    // Show weight, hide volume
                    $('#idlabel').text('Berat');
                    $('#volumeDiv').addClass('d-none');
                    $('#weightDiv').removeClass('d-none');
                }
            });

            $('#beratBarang').on('input', function() {
                this.value = this.value.replace(/[^0-9,.]/g, '');
                this.value = this.value.replace('.', ',');

                if (this.value) {
                    $('#panjang, #lebar, #tinggi').val('');
                }
                updateTotalHarga();
            });

            $('#panjang, #lebar, #tinggi').on('input', function() {
                $('#beratBarang').val('');
                updateTotalHarga();
            });

            function updateTotalHarga() {
                let beratRaw = $('#beratBarang').val().replace(',', '.');
                let berat = parseFloat(beratRaw);

                if (beratRaw.trim() === '' || isNaN(berat)) {
                    $('#total-harga').text('Rp 0');
                    $('#totalHargaValue').val(0);
                } else {
                    berat = Math.max(2, berat);
                    let hargaPerKg = 15000;
                    let totalHarga = berat * hargaPerKg;
                    $('#totalHargaValue').val(totalHarga)
                    $('#total-harga').text('Rp ' + totalHarga.toLocaleString());
                }
            }



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

            function validateInvoiceInput() {
                let isValid = true;

                if ($('#noResi').val().trim() === '') {
                    $('#noResiError').show();
                    isValid = false;
                } else {
                    $('#noResiError').hide();
                }

                if ($('#tanggal').val().trim() === '') {
                    $('#tanggalError').show();
                    isValid = false;
                } else {
                    $('#tanggalError').hide();
                }

                if ($('#selectCostumer').val() === null) {
                    $('#customerError').show();
                    isValid = false;
                } else {
                    $('#customerError').hide();
                }

                // if ($('#namaBarang').val().trim() === '') {
                //     $('#namaBarangError').show();
                //     isValid = false;
                // } else {
                //     $('#namaBarangError').hide();
                // }

                if ($('#delivery').is(':checked')) {
                    if ($('#driver').val() === null) {
                        $('#driverError').show();
                        isValid = false;
                    } else {
                        $('#driverError').hide();
                    }

                    if ($('#alamat').val().trim() === '') {
                        $('#alamatError').show();
                        isValid = false;
                    } else {
                        $('#alamatError').hide();
                    }
                }

                if ($('#metodePembayaran').val() === 'transfer' && $('#rekening').val() === null) {
                    $('#metodePembayaranError').show();
                    $('#rekeningError').show();
                    isValid = false;
                } else {
                    $('#metodePembayaranError').hide();
                    $('#rekeningError').hide();
                }

                return isValid;
            }

            $('#noResi, #tanggal, #selectCostumer, #driver, #alamat, #metodePembayaran, #rekening').on(
                'input change',
                function() {
                    validateInvoiceInput();
                });




            $('button').click(function() {
                if (validateInvoiceInput()) {
                    // Collect all form data
                    let noResi = $('#noResi').val();
                    let tanggal = $('#tanggal').val();
                    let customer = $('#selectCostumer').val();
                    // let namaBarang = $('#namaBarang').val();
                    // let hargaBarang = $('#hargaBarang').val();
                    let beratBarang = $('#beratBarang').val();
                    let panjang = $('#panjang').val();
                    let lebar = $('#lebar').val();
                    let tinggi = $('#tinggi').val();
                    let metodePengiriman = $('#delivery').is(':checked') ? 'delivery' : 'pickup';
                    let metodePembayaran = $('#metodePembayaran').val();
                    let driver = metodePengiriman === 'delivery' ? $('#driver').val() : null;
                    let alamat = metodePengiriman === 'delivery' ? $('#alamat').val() : null;
                    let rekening = metodePembayaran === 'transfer' ? $('#rekening').val() : null;
                    let totalharga = $('#totalHargaValue').val();
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Send data to the controller
                    $.ajax({
                        type: "POST",
                        url: "{{ route('tambainvoice') }}", // Ganti dengan route yang sesuai
                        data: {
                            noResi: noResi,
                            tanggal: tanggal,
                            customer: customer,
                            // namaBarang: namaBarang,
                            // hargaBarang: hargaBarang,
                            beratBarang: beratBarang,
                            panjang: panjang,
                            lebar: lebar,
                            tinggi: tinggi,
                            metodePengiriman: metodePengiriman,
                            driver: driver,
                            alamat: alamat,
                            metodePembayaran: metodePembayaran,
                            rekening: rekening,
                            totalharga: totalharga,
                            _token: csrfToken
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showMessage("success", "Invoice berhasil dibuat");
                                // Reset form atau tindakan lain yang diperlukan
                            } else {
                                Swal.fire({
                                    title: "Gagal membuat invoice",
                                    icon: "error"
                                });
                            }
                        }
                    });
                } else {
                    alert('Mohon periksa input yang kosong.');
                }
            });
        });
    </script>

@endsection
