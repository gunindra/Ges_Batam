@extends('layout.main')

@section('title', 'Edit Invoice')

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


        #pickupDelivery h2 {
            font-size: 2rem;
            color: #298be2;
            font-weight: bold;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }
    </style>


    <!---Container Fluid-->
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Invoice</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Costumer</li>
                <li class="breadcrumb-item"><a href="{{ route('invoice') }}">Invoice</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Invoice</li>
            </ol>
        </div>

        <div class="d-flex justify-content-between">
            <a class="btn btn-primary mb-3" href="{{ route('invoice') }}">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="noResi" class="form-label fw-bold">No Invoice : </label>
                                    <div class="d-flex">
                                        <h2 class="fw-bold" id="noInvoice">{{ $invoice->no_invoice }}</h2>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                                    <input type="text" class="form-control col-8" id="tanggal" value=""
                                        placeholder="Pilih tanggal">
                                    <div id="tanggalError" class="text-danger mt-1 d-none">Tanggal tidak boleh kosong</div>
                                </div>
                                <div class="mt-3">
                                    <label for="tanggal" class="form-label fw-bold">Tanggal Buat</label>
                                    <input type="text" class="form-control col-8" id="tanggalBuat" value=""
                                        placeholder="Pilih tanggal" disabled>
                                </div>
                            </div>
                            <div class="col-6">
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
                            <span>Costumer</span>
                        </div>
                        <div class="d-flex">
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="customer" class="form-label fw-bold col-12">Customer</label>
                                    <select class="form-control select2singgle" id="selectCostumer" style="width: 67%">
                                        <option value="" selected disabled>Pilih Customer</option>
                                        @foreach ($listAlamat as $alamat)
                                            <option value="{{ $alamat->id }}"
                                                data-metode="{{ $alamat->metode_pengiriman }}"
                                                data-alamat="{{ $alamat->alamat }}"
                                                data-jumlahalamat="{{ $alamat->jumlah_alamat }}"
                                                data-minrate="{{ $alamat->minimum_rate ?? 0 }}"
                                                data-maxrate="{{ $alamat->maximum_rate ?? 0 }}">
                                                {{ $alamat->marking }} - {{ $alamat->nama_pembeli }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="customerError" class="text-danger mt-1 d-none">Silahkan Pilih Customer</div>
                                </div>
                                <div class="mt-3" id="alamatContainer">
                                </div>
                                <div id="alamatError" class="text-danger mt-1 d-none">Silahkan Pilih Alamat</div>

                            </div>
                            <div class="col-6">
                                <div class="mt-5" id="pickupDelivery">
                                    <h2></h2>
                                </div>
                            </div>
                        </div>
                        <div class="divider mt-4">
                            <span>Detail Barang</span>
                        </div>
                        <!-- Pilihan Rate Berat dan Volume -->
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="rateSelect" class="form-label fw-bold">Rate (Berat)</label>
                                <select class="form-control" id="rateBerat">
                                    <option value="" selected disabled>Pilih Rate</option>
                                    @foreach ($listRateBerat as $rate)
                                        @if ($rate->rate_for == 'Berat')
                                            <option value="{{ $rate->id }}" data-nilai-rate="{{ $rate->nilai_rate }}"
                                                @if ($rate->id == $invoice->rateberat_id) selected @endif>
                                                {{ number_format($rate->nilai_rate, 0, ',', '.') }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="rateBeratError" class="text-danger mt-1 d-none">Silahkan Pilih Rate</div>
                            </div>
                            <div class="col-sm-6">
                                <label for="pembagiVolume" class="form-label fw-bold">Pembagi</label>
                                <select class="form-control" id="pembagiVolume">
                                    <option value="" selected disabled>Pilih Pembagi</option>
                                    @foreach ($listPembagi as $pembagi)
                                        <option value="{{ $pembagi->id }}"
                                            data-nilai-pembagi="{{ $pembagi->nilai_pembagi }}">
                                            {{ number_format($pembagi->nilai_pembagi, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="pembagiVolumeError" class="text-danger mt-1 d-none">Silahkan Pilih Pembagi</div>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-sm-6">
                                <label for="rate" class="form-label fw-bold">Rate (Volume)</label>
                                <select class="form-control" id="rateVolume">
                                    <option value="" selected disabled>Pilih Rate</option>
                                    @foreach ($listRateVolume as $rate)
                                        @if ($rate->rate_for == 'Volume')
                                            <option value="{{ $rate->id }}"
                                                data-nilai-ratevolume="{{ $rate->nilai_rate }}"
                                                @if ($rate->id == $invoice->ratevolume_id) selected @endif>
                                                {{ number_format($rate->nilai_rate, 0, ',', '.') }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="rateVolumeError" class="text-danger mt-1 d-none">Silahkan Pilih Rate</div>
                            </div>
                        </div>

                        <div class="form-grup row mt-3">
                            <div class="col-sm-6">
                                <label for="scanResi" class="form-label fw-bold">Scan No Resi</label>
                                <input type="text" class="form-control" id="scanNoresi"
                                    placeholder="Silahkan scan resi disini">
                            </div>
                        </div>

                        <!-- Tabel Input Berat dan Dimensi -->
                        <table class="table mt-4">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No. Resi</th>
                                    <th>No. Do</th>
                                    <th>Berat/Dimensi</th>
                                    <th>Hitungan</th>
                                    <th>Harga</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="barang-list">
                            </tbody>
                        </table>
                        <div class="alert alert-info mt-4" id="infoHarga" style="display: none;">
                            <i class="fas fa-info-circle"></i> Harga yang akan tertera pada tabel telah dibulatkan ke atas
                            dalam kelipatan
                            1.000.
                        </div>
                        <!-- Tombol Tambah Barang -->
                        {{-- <button type="button" class="btn btn-primary" id="addItemBtn"><span class="pr-2"><i
                                class="fas fa-plus"></i></span>Tambah Barang</button> --}}
                        <div class="row">
                            <div class="col-12 mt-4" id="totalIdr" style="display: none;">
                                <div class="col-2 offset-8 me-1">
                                    <p class="mb-0">Total Idr</p>
                                    <div class="box bg-light text-dark p-3 mt-2"
                                        style="border: 1px solid; border-radius: 8px; font-size: 1.5rem;">
                                        <span id="idrCurrentCy" style="font-weight: bold; color: #555;">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="col-4 float-right">
                                <p class="mb-0">Total Harga</p>
                                <div class="box bg-light text-dark p-3 mt-2"
                                    style="border: 1px solid; border-radius: 8px; font-size: 1.5rem;">
                                    <span id="total-harga" style="font-weight: bold; color: #555;">Rp.0</span>
                                </div>
                                <input type="hidden" name="" id="totalHargaValue">
                                <button id="updateInvoice" class="btn btn-primary p-3 float-right mt-3"
                                    style="width: 100%;">Update
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
            var invoice = @json($invoice);

            $('.select2').select2();
            $('#tanggal, #tanggalBuat').datepicker({
                format: 'dd MM yyyy',
                todayBtn: 'linked',
                todayHighlight: true,
                autoclose: true,
            });

            $('#currencyInvoice').val(invoice.matauang_id).trigger('change');
            $('#rateBerat').val(invoice.rateberat_id).trigger('change');
            $('#rateVolume').val(invoice.ratevolume_id).trigger('change');
            $('#pembagiVolume').val(invoice.pembagi_id).trigger('change');

            if (invoice.tanggal_invoice) {
                const rawDate = new Date(invoice.tanggal_invoice);
                $('#tanggal').datepicker('setDate', rawDate);
            }
            if (invoice.tanggal_buat) {
                const rawDate = new Date(invoice.tanggal_buat);
                $('#tanggalBuat').datepicker('setDate', rawDate);
            }

            $('#rateCurrency').val(invoice.rate_matauang).trigger('change');

            const selectedMethod = invoice.metode_pengiriman;

            if (selectedMethod === 'Pickup') {
                $('#pickupDelivery').show();
                $('#pickupDelivery h2').text('Pickup');
            } else if (selectedMethod === 'Delivery') {
                $('#pickupDelivery').show();
                $('#pickupDelivery h2').text('Delivery');
            } else {
                $('#pickupDelivery').hide();
            }

            $(document).on('click', '.remove-item', function() {
                $(this).closest('tr').remove();
            });

            $('.select2singgle').select2({
                width: 'resolve'
            });

            let globalMinrate = 0;
            let globalMaxrate = 0;

            $('#selectCostumer').change(function() {
                var selectedCustomer = $(this).val();
                var metodePengiriman = $('#selectCostumer option:selected').data('metode');
                var alamat = $('#selectCostumer option:selected').data('alamat');
                var jumlahAlamat = $('#selectCostumer option:selected').data('jumlahalamat');
                var minrate = Math.floor($('#selectCostumer option:selected').data('minrate') || 0);
                var maxrate = Math.floor($('#selectCostumer option:selected').data('maxrate') || 0);

                globalMinrate = minrate;
                globalMaxrate = maxrate;

                $('#alamatError').addClass('d-none');

                if (selectedCustomer) {
                    $('#pickupDelivery').show();

                    if (metodePengiriman === 'Pickup') {
                        $('#pickupDelivery h2').text('Pick Up');
                        $('#alamatContainer').empty();
                    } else if (metodePengiriman === 'Delivery') {
                        $('#pickupDelivery h2').text('Delivery');

                        if (jumlahAlamat == 1) {
                            var selectAlamat =
                                '<label for="alamatSelect" class="form-label">Alamat</label>';
                            selectAlamat +=
                                '<select id="alamatSelect" class="form-control col-9" disabled>';
                            selectAlamat += '<option value="' + alamat + '" selected>' + alamat +
                                '</option>';
                            selectAlamat += '</select>';
                            $('#alamatContainer').html(selectAlamat);
                        } else if (jumlahAlamat > 1) {
                            var alamatList = alamat.split('; ');
                            var selectAlamat =
                                '<label for="alamatSelect" class="form-label">Alamat</label>';
                            selectAlamat += '<select id="alamatSelect" class="form-control col-9">';
                            selectAlamat += '<option value="" selected disabled>Pilih Alamat</option>';
                            alamatList.forEach(function(alamatItem) {
                                selectAlamat += '<option value="' + alamatItem + '">' +
                                    alamatItem +
                                    '</option>';
                            });
                            selectAlamat += '</select>';
                            $('#alamatContainer').html(selectAlamat);

                        }
                    }

                    $('#barang-list tr').each(function() {
                        const row = $(this);
                        row.find('.beratBarang').val('');
                        row.find('.panjangVolume').val('');
                        row.find('.lebarVolume').val('');
                        row.find('.tinggiVolume').val('');
                        updateTotalHargaBerat(row);
                        updateTotalHargaVolume(row);
                    });
                } else {
                    $('#pickupDelivery').hide();
                    $('#alamatContainer').empty();
                }
            });

            if (invoice) {

                $('#updateInvoice').attr('data-id', invoice.id);


                $('#selectCostumer').val(invoice.pembeli_id).trigger('change');
                $('#alamatSelect').val(invoice.alamat).trigger('change');
            }

            $('#currencyInvoice').change(function() {
                const selectedCurrency = $(this).val();

                if (selectedCurrency == '2' || selectedCurrency === '3') {
                    $('#rateCurrencySection').show();
                    $('#totalIdr').show();
                } else {
                    $('#rateCurrencySection').hide();
                    $('#totalIdr').hide();
                    $('#rateCurrency').val('');
                    $('#idrCurrentCy').text('Rp. 0');
                    $('#total-harga').text("Rp. " + parseFloat($('#totalHargaValue').val())
                        .toLocaleString(
                            'id-ID', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 3
                            }));
                }
            });
            const initialCurrency = $('#currencyInvoice').val();

            if (initialCurrency !== '1') {
                $('#rateCurrencySection').show();
                $('#totalIdr').show();
            } else {
                $('#rateCurrencySection').hide();
                $('#totalIdr').hide();
            }

            updateDisplayedTotalHarga();

            $('#currencyInvoice').change(function() {
                const selectedCurrency = $(this).val();

                if (selectedCurrency !== '1') {
                    $('#rateCurrencySection').show();
                    $('#totalIdr').show();
                } else {
                    $('#rateCurrencySection').hide();
                    $('#totalIdr').hide();
                    $('#rateCurrency').val('');
                    $('#idrCurrentCy').text('Rp. 0');
                    $('#total-harga').text("Rp. " + parseFloat($('#totalHargaValue').val()).toLocaleString(
                        'id-ID', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }));
                }

                updateDisplayedTotalHarga();
            });


            $('#currencyInvoice').val('1').trigger('change');

            $('#rateCurrency').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                updateDisplayedTotalHarga();
            });


            let itemIndex = 1;

            function addItemRow(noResi, no_do, berat = '', panjang = '', lebar = '', tinggi = '', harga = 0) {
                const isBeratNull = berat === null || berat === undefined || berat === '';
                const newRow = `
                <tr data-index="${itemIndex}">
                    <td class="item-number">${itemIndex}</td>
                    <td name="noResi[]">${noResi}</td>
                    <td name="noDo[]" disabled>${no_do}</td>
                    <td>
                        <select class="form-control selectBeratDimensi" data-index="${itemIndex}">
                            <option value="berat" ${!isBeratNull ? 'selected' : ''}>Berat</option>
                            <option value="dimensi" ${isBeratNull ? 'selected' : ''}>Dimensi</option>
                        </select>
                    </td>
                    <td class="hitungan" data-index="${itemIndex}">
                        ${isBeratNull
                    ? `<div class="d-flex">
                                                                                    <input type="number" class="form-control me-1 panjangVolume" data-index="${itemIndex}" placeholder="P" value="${panjang}" min="0" step="0.01">
                                                                                    <span class="mx-1 mt-2">×</span>
                                                                                    <input type="number" class="form-control me-1 lebarVolume" data-index="${itemIndex}" placeholder="L" value="${lebar}" min="0" step="0.01">
                                                                                    <span class="mx-1 mt-2">×</span>
                                                                                    <input type="number" class="form-control me-1 tinggiVolume" data-index="${itemIndex}" placeholder="T" value="${tinggi}" min="0" step="0.01">
                                                                                    <span class="ml-2 pt-2">Cm</span>
                                                                                </div>`
                    : `<input type="number" class="form-control beratBarang" data-index="${itemIndex}" name="beratBarang[]" disabled value="${berat}" placeholder="Masukkan Berat (Kg)" min="0" step="0.01">`
                }
                    </td>
                    <td><span class="hargaBarang">Rp. ${parseFloat(harga).toLocaleString('id-ID')}</span></td>
                    <td>
            <button type="button" class="btn btn-sm btn-primary toggle-ws" data-index="${itemIndex}" data-active="false" style="display: ${!isBeratNull ? 'inline-block' : 'none'};">
                <span class="pr-2"><i class="fas fa-play"></i></span> Start
            </button>
            <button type="button" class="btn btn-sm btn-danger remove-item">
                <span class="pr-2"><i class="fas fa-trash"></i></span> Hapus
            </button>
        </td>
                </tr>`;
                $('#barang-list').append(newRow);
                itemIndex++;

                if ($('#barang-list tr').length > 0) {
                    $('#infoHarga').fadeIn();
                }
                setRemoveItemButton();
                attachInputEvents();
                attachSelectChangeEvent();
                attachWebSocketToggle();
            }

            function attachWebSocketToggle() {
                $('.toggle-ws').off('click').on('click', function() {
                    const button = $(this);
                    const row = button.closest('tr');
                    const index = button.data('index');
                    const isActive = button.data('active');
                    let ws = row.data('ws');

                    if (!isActive) {
                        ws = new WebSocket('ws://127.0.0.1:8080');
                        row.data('ws', ws);

                        ws.onopen = function() {
                            button.html('<span class="pr-2"><i class="fas fa-stop"></i></span> Stop');
                            button.data('active', true);
                            row.find('.beratBarang').prop('disabled', false);
                        };

                        ws.onerror = function(error) {
                            console.error('WebSocket Error:', error.message);
                        };

                        ws.onmessage = function(event) {
                            try {
                                const data = JSON.parse(event.data);
                                const weight = parseFloat(data.weight);
                                if (!isNaN(weight) && weight > 0) {
                                    row.find('.beratBarang').val(weight);
                                    updateTotalHargaBerat(row);
                                    updateDisplayedTotalHarga();
                                } else {
                                    console.error('Invalid weight data received:', data);
                                }
                            } catch (e) {
                                console.error('Error parsing WebSocket data:', e);
                            }
                        };
                    } else {
                        if (ws) {
                            ws.close();
                            row.removeData('ws');
                        }

                        button.html('<span class="pr-2"><i class="fas fa-play"></i></span> Start');
                        button.data('active', false);
                        row.find('.beratBarang').prop('disabled', true);
                    }
                });
            }


            function attachSelectChangeEvent() {
                $('.selectBeratDimensi').off('change').on('change', function() {
                    const row = $(this).closest('tr');
                    const selectedValue = $(this).val();
                    const index = $(this).data('index');
                    row.find('.hargaBarang').text('Rp. 0');
                    if (selectedValue === 'berat') {
                        row.find('.hitungan').html(`
                        <input type="number" class="form-control beratBarang" disabled data-index="${index}" name="beratBarang[]" placeholder="Masukkan Berat (Kg)" min="0" step="0.01">
                    `);
                        row.find('.toggle-ws').show();
                    } else if (selectedValue === 'dimensi') {
                        row.find('.hitungan').html(`
                        <div class="d-flex">
                            <input type="number" class="form-control me-1 panjangVolume" data-index="${index}" placeholder="P" min="0" step="0.01">
                            <span class="mx-1 mt-2">×</span>
                            <input type="number" class="form-control me-1 lebarVolume" data-index="${index}" placeholder="L" min="0" step="0.01">
                            <span class="mx-1 mt-2">×</span>
                            <input type="number" class="form-control me-1 tinggiVolume" data-index="${index}" placeholder="T" min="0" step="0.01">
                            <span class="ml-2 pt-2">Cm</span>
                        </div>
                    `);
                        row.find('.toggle-ws').hide();
                    }
                    attachInputEvents();
                    attachWebSocketToggle();
                    updateDisplayedTotalHarga();
                });
            }

            function attachInputEvents() {
                $('#barang-list').off('input', '.beratBarang').on('input', '.beratBarang', function() {
                    const row = $(this).closest('tr');
                    const beratValue = $(this).val();

                    if (beratValue.trim() !== "") {
                        row.find('.panjangVolume').val('');
                        row.find('.lebarVolume').val('');
                        row.find('.tinggiVolume').val('');
                    }

                    updateTotalHargaBerat(row);
                });

                $('#barang-list').off('input', '.panjangVolume, .lebarVolume, .tinggiVolume').on('input',
                    '.panjangVolume, .lebarVolume, .tinggiVolume',
                    function() {
                        const row = $(this).closest('tr');
                        const panjangValue = row.find('.panjangVolume').val();
                        const lebarValue = row.find('.lebarVolume').val();
                        const tinggiValue = row.find('.tinggiVolume').val();

                        if (panjangValue.trim() !== "" || lebarValue.trim() !== "" || tinggiValue.trim() !==
                            "") {
                            row.find('.beratBarang').val('');
                        }

                        updateTotalHargaVolume(row);
                    });
            }

            function updateTotalHargaBerat(row) {
                const berat = parseFloat(row.find('.beratBarang').val()) || 0;
                const hargaPerKg = parseFloat($('#rateBerat option:selected').data('nilai-rate')) || 0;


                if (berat > 0 && hargaPerKg) {
                    if (berat < 2 && globalMinrate > 0) {
                        totalHarga = globalMinrate;
                    } else {
                        totalHarga = berat * hargaPerKg;
                        totalHarga = Math.max(totalHarga, globalMinrate);
                    }

                    if (globalMaxrate > 0) {
                        totalHarga = Math.min(totalHarga, globalMaxrate);
                    }

                    // Pembulatan ke ribuan terdekat ke atas
                    totalHarga = Math.ceil(totalHarga / 1000) * 1000;

                    row.find('.hargaBarang').text("Rp. " + totalHarga.toLocaleString('id-ID'));
                } else {
                    row.find('.hargaBarang').text("Rp. 0");
                }
                updateDisplayedTotalHarga();
            }

            function updateTotalHargaVolume(row) {
                const panjang = parseFloat(row.find('.panjangVolume').val()) || 0;
                const lebar = parseFloat(row.find('.lebarVolume').val()) || 0;
                const tinggi = parseFloat(row.find('.tinggiVolume').val()) || 0;
                const volume = panjang * lebar * tinggi;
                const pembagi = parseFloat($('#pembagiVolume option:selected').data('nilai-pembagi')) || 0;
                const rate = parseFloat($('#rateVolume option:selected').data('nilai-ratevolume')) || 0;

                if (volume > 0 && rate) {
                    let totalHargaVolume = (volume / pembagi) * rate;
                    // Pembulatan ke ribuan terdekat ke atas
                    totalHargaVolume = Math.ceil(totalHargaVolume / 1000) * 1000;
                    row.find('.hargaBarang').text("Rp. " + totalHargaVolume.toLocaleString('id-ID'));
                } else {
                    row.find('.hargaBarang').text("Rp. 0");
                }
                updateDisplayedTotalHarga();
            }

            function updateDisplayedTotalHarga() {
                let totalHarga = 0;

                $('.hargaBarang').each(function() {
                    let harga = $(this).text().replace(/[^0-9,-]+/g, "").replace(",", ".");
                    totalHarga += parseFloat(harga) || 0;
                });

                $('#totalHargaValue').val(totalHarga.toFixed(2));

                const currencyValue = $('#currencyInvoice').val();
                const totalHargaIDR = totalHarga;
                const customRate = $('#rateCurrency').val();
                let convertedTotal = 0;

                if (!totalHargaIDR || isNaN(totalHargaIDR) || totalHargaIDR === 0) {
                    $('#idrCurrentCy').text('Rp. 0');
                    $('#total-harga').text('-');
                    return;
                }

                $('#idrCurrentCy').text("Rp. " + totalHargaIDR.toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));

                if (!currencyValue) {
                    $('#total-harga').text('-');
                } else if (currencyValue === '1') {
                    $('#total-harga').text("Rp. " + totalHargaIDR.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                } else if (customRate && currencyValue !== '1') {
                    convertedTotal = totalHargaIDR / parseFloat(customRate);
                    let currencySymbol = "";

                    if (currencyValue === '2') {
                        currencySymbol = "$ ";
                    } else if (currencyValue === '3') {
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

            function setRemoveItemButton() {
                $('.remove-item').off('click').on('click', function() {
                    const row = $(this).closest('tr');
                    const ws = row.data('ws');
                    if (ws) {
                        ws.close();
                    }
                    row.remove();

                    if ($('#barang-list tr').length === 0) {
                        $('#infoHarga').fadeOut();
                    }
                    renumberItems();
                    updateDisplayedTotalHarga();
                });
            }

            function renumberItems() {
                let index = 1;
                $('#barang-list tr').each(function() {
                    $(this).find('.item-number').text(index);
                    $(this).attr('data-index', index);
                    index++;
                });
                itemIndex = index;
            }

            invoice.resi.forEach(resi => {
                addItemRow(
                    resi.no_resi,
                    resi.no_do,
                    resi.berat,
                    resi.panjang,
                    resi.lebar,
                    resi.tinggi,
                    resi.harga
                );

                updateDisplayedTotalHarga();
            });



            $('#scanNoresi').on('keydown', function(event) {
                if (event.key === "Enter" || event.keyCode === 13) {
                    const scannedNoResi = $(this).val().trim();
                    let isAlreadyInTable = false;


                    $('#barang-list tr').each(function() {
                        const existingNoResi = $(this).find('td:eq(1)').text().trim();
                        if (existingNoResi === scannedNoResi) {
                            isAlreadyInTable = true;
                        }
                    });

                    if (isAlreadyInTable) {
                        showMessage("error", "Resi ini sudah ada di scan");
                        $(this).val('');
                    } else if (scannedNoResi !== '') {

                        $.ajax({
                            url: "{{ route('cekResiInvoice') }}",
                            method: 'GET',
                            data: {
                                noResi: scannedNoResi
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    addItemRow(scannedNoResi, response.no_do);
                                } else {

                                    showMessage("error", response.message);
                                }
                                $('#scanNoresi').val('');
                            },
                            error: function(xhr) {
                                let errorMessage = "Terjadi kesalahan pada server.";
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                showMessage("error", errorMessage);
                                $('#scanNoresi').val('');
                            }
                        });
                    }
                    event.preventDefault();
                }
            });


            $('#updateInvoice').click(function(e) {
                e.preventDefault();

                const id = $(this).data('id');
                const noInvoice = $('#noInvoice').text();
                const tanggal = $('#tanggal').val();
                const currencyInvoice = $('#currencyInvoice').val();
                const rateCurrency = $('#rateCurrency').val();
                const selectCostumer = $('#selectCostumer').val();
                const metodePengiriman = $('#selectCostumer option:selected').data('metode');
                const alamatSelect = $('#alamatSelect').val();
                const rateBerat = parseFloat($('#rateBerat option:selected').data('nilai-rate')) || 0;
                const pembagiVolume = parseFloat($('#pembagiVolume option:selected').data(
                    'nilai-pembagi')) || 0;
                const rateVolume = parseFloat($('#rateVolume option:selected').data('nilai-ratevolume')) ||
                    0;
                let totalharga = $('#totalHargaValue').val();

                const noResi = [];
                const beratBarang = [];
                const panjang = [];
                const lebar = [];
                const tinggi = [];
                const hargaBarang = [];

                if (tanggal === '') {
                    $('#tanggalError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#tanggalError').addClass('d-none');
                }


                if (!tanggal) {
                    Swal.fire({
                        title: "Validasi Gagal",
                        text: "Tanggal tidak boleh kosong.",
                        icon: "error"
                    });
                    return;
                }

                // Validasi Tabel Barang
                let validTable = true;
                $('#barang-list tr').each(function() {
                    const selectedValue = $(this).find('.selectBeratDimensi').val();
                    const berat = $(this).find('.beratBarang').val();
                    const panjangVal = $(this).find('.panjangVolume').val();
                    const lebarVal = $(this).find('.lebarVolume').val();
                    const tinggiVal = $(this).find('.tinggiVolume').val();


                    if (selectedValue === 'berat' && (!berat || !rateBerat)) {
                        showMessage("error", 'Pastikan berat sudah dimasukkan.');
                        validTable = false;
                        return false;
                    }

                    if (selectedValue === 'dimensi' && (panjangVal || lebarVal || tinggiVal) && (!
                            rateVolume || !
                            pembagiVolume)) {
                        showMessage("error",
                            'Pastikan rate volume serta pembagi volume sudah diisi.');
                        validTable = false;
                        return false;
                    }

                    if (!berat && (!panjangVal || !lebarVal || !tinggiVal)) {
                        showMessage("error", 'Silakan masukkan berat atau dimensi yang valid.');
                        validTable = false;
                        return false;
                    }

                    noResi.push($(this).find('[name="noResi[]"]').text());
                    beratBarang.push(berat);
                    panjang.push(panjangVal);
                    lebar.push(lebarVal);
                    tinggi.push(tinggiVal);
                    hargaBarang.push($(this).find('.hargaBarang').text().replace(/[^0-9,-]+/g, "")
                        .trim());
                });

                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Kirim Data via AJAX
                $.ajax({
                    type: "POST",
                    url: '/invoice/editinvoice/' + id,
                    data: {
                        noInvoice: noInvoice,
                        noResi: noResi,
                        tanggal: tanggal,
                        customer: selectCostumer,
                        currencyInvoice: currencyInvoice,
                        rateCurrency: rateCurrency,
                        beratBarang: beratBarang,
                        panjang: panjang,
                        lebar: lebar,
                        tinggi: tinggi,
                        metodePengiriman: metodePengiriman,
                        alamat: alamatSelect,
                        totalharga: totalharga,
                        hargaBarang: hargaBarang,
                        rateBerat: rateBerat,
                        rateVolume: rateVolume,
                        pembagiVolume: pembagiVolume,
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
                                text: response.message,
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = 'Terjadi kesalahan saat memproses permintaan.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            title: "Gagal memperbarui invoice",
                            text: errorMessage,
                            icon: "error"
                        });
                    }
                });
            });
        });
    </script>

@endsection
