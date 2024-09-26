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
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Buat Invoice</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Costumer</li>
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
                                    <label for="noResi" class="form-label fw-bold">No Invoice :</label>
                                    <div class="d-flex">
                                        <h2 class="fw-bold" id="noInvoice">{{ $newNoinvoice }}</h2>
                                        <a class="pt-2" id="btnRefreshInvoice" href=""><span
                                                class="pl-2 text-success"><i class="fas fa-sync-alt"></i></span></a>
                                    </div>
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
                                        @foreach ($listPembeli as $pembeli)
                                            <option value="{{ $pembeli->id }}"
                                                data-metode="{{ $pembeli->metode_pengiriman }}"
                                                data-minrate="{{ $pembeli->minimum_rate }}"
                                                data-alamat="{{ $pembeli->alamat }}"
                                                data-jumlahalamat="{{ $pembeli->jumlah_alamat }}">
                                                {{ $pembeli->marking }} - {{ $pembeli->nama_pembeli }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="customerError" class="text-danger mt-1 d-none">Silahkan Pilih Customer</div>
                                </div>
                                <div class="mt-3" id="alamatContainer"></div>

                            </div>
                            <div class="col-6">
                                <div class="mt-5" id="pickupDelivery" style="display: none;">
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
                                    @foreach ($listRateVolume as $rate)
                                        @if ($rate->rate_for == 'Berat')
                                            <option value="{{ $rate->nilai_rate }}">
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
                                    @foreach ($lisPembagi as $pembagi)
                                        <option value="{{ $pembagi->nilai_pembagi }}">
                                            {{ number_format($pembagi->nilai_pembagi, 0, ',', '.') }}</option>
                                    @endforeach
                                </select>
                                <div id="pembagiVolumeError" class="text-danger mt-1 d-none">Silahkan Pilih Rate</div>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-sm-6">
                                <label for="rate" class="form-label fw-bold">Rate (Volume)</label>
                                <select class="form-control" id="rateVolume">
                                    <option value="" selected disabled>Pilih Rate</option>
                                    @foreach ($listRateVolume as $rate)
                                        @if ($rate->rate_for == 'Volume')
                                            <option value="{{ $rate->nilai_rate }}">
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
                                    <th>Berat/Dimensi</th>
                                    <th>Hitungan</th>
                                    <th>Harga</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="barang-list">
                            </tbody>
                        </table>
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
            $('#btnRefreshInvoice').click(function(e) {
                e.preventDefault();

                // Define the loading spinner HTML
                const loadSpin = `<div class="d-flex justify-content-center align-items-center pl-5">
                          <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
                      </div>`;

                // Hide the refresh button
                $('#btnRefreshInvoice').addClass('d-none');

                // Insert the loading spinner into the noInvoice element
                $('#noInvoice').html(loadSpin);

                $.ajax({
                    url: "{{ route('generateInvoice') }}",
                    method: 'GET', // Use GET or POST based on your API design
                    success: function(response) {
                        if (response.status === 'success') {
                            // Update the noInvoice text with the response
                            $('#noInvoice').text(response.no_invoice);
                        } else {
                            console.error(response.message);
                            // Show error message if the request fails
                            $('#noInvoice').html(
                                '<span class="text-danger">Failed to load invoice</span>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Gagal menghasilkan nomor invoice: ' + xhr.responseJSON
                            .message);
                        // Show error message if there's an error
                        $('#noInvoice').html(
                            '<span class="text-danger">Gagal menghasilkan nomor invoice</span>'
                        );
                    },
                    complete: function() {
                        // Show the refresh button again after the request is complete
                        $('#btnRefreshInvoice').removeClass('d-none');
                    }
                });
            });

            $('.select2singgle').select2({
                width: 'resolve'
            });

            let globalMinrate = 0;
            $('#pickupDelivery').hide();

            $('#selectCostumer').change(function() {
                var selectedCustomer = $(this).val();
                var metodePengiriman = $('#selectCostumer option:selected').data('metode');
                var alamat = $('#selectCostumer option:selected').data('alamat');
                var jumlahAlamat = $('#selectCostumer option:selected').data('jumlahalamat');
                var minrate = $('#selectCostumer option:selected').data('minrate') || 0;
                globalMinrate = minrate;

                if (selectedCustomer) {
                    $('#pickupDelivery').show();
                    if (metodePengiriman === 'Pickup') {
                        $('#pickupDelivery h2').text('Pick Up');
                        $('#alamatContainer').empty();
                    } else if (metodePengiriman === 'Delivery' && jumlahAlamat == 1) {
                        $('#pickupDelivery h2').text('Delivery');
                        $('#alamatContainer').html('<p>' + alamat + '</p>');
                    } else if (metodePengiriman === 'Delivery' && jumlahAlamat > 1) {
                        $('#pickupDelivery h2').text('Delivery');
                        var alamatList = alamat.split(', ');
                        var selectAlamat = '<label for="alamatSelect" class="form-label">Alamat</label>';
                        selectAlamat += '<select id="alamatSelect" class="form-control col-9">';
                        selectAlamat += '<option value="" selected disabled>Pilih Alamat</option>';
                        alamatList.forEach(function(alamatItem) {
                            selectAlamat += '<option value="' + alamatItem + '">' + alamatItem +
                                '</option>';
                        });
                        selectAlamat += '</select>';
                        $('#alamatContainer').html(selectAlamat);
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
                    updateDisplayedTotalHarga();
                } else {
                    $('#pickupDelivery').hide();
                    $('#alamatContainer').empty();
                }


            });

            var today = new Date();
            $('#tanggal').datepicker({
                format: 'dd MM yyyy',
                todayBtn: 'linked',
                todayHighlight: true,
                autoclose: true,
            }).datepicker('setDate', today);

            const initialCurrency = $('#currencyInvoice').val();

            // Tampilkan atau sembunyikan elemen berdasarkan mata uang awal
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

                updateDisplayedTotalHarga(); // Update total yang ditampilkan
            });


            $('#currencyInvoice').val('1').trigger('change');

            $('#rateCurrency').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                updateDisplayedTotalHarga();
            });

            let itemIndex = 1;

            $('#scanNoresi').on('keydown', function(event) {
                if (event.key === "Enter" || event.keyCode === 13) {
                    const scannedNoResi = $(this).val().trim();
                    let isAlreadyInTable = false;

                    // Cek apakah sudah ada di tabel
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
                                // Tangani respons dari server
                                if (response.status ===
                                    'success') { // Periksa apakah status adalah 'success'
                                    addItemRow(scannedNoResi);
                                } else {
                                    showMessage("error", response.message);
                                }
                                $('#scanNoresi').val(
                                    ''); // Reset nilai input setelah berhasil atau gagal
                            },
                            error: function(xhr, status, error) {
                                showMessage("error", "Terjadi kesalahan: " + error);
                                $('#scanNoresi').val('');
                            }
                        });

                    }
                    event.preventDefault();
                }
            });


            function addItemRow(noResi) {
                const newRow = `
    <tr data-index="${itemIndex}">
        <td class="item-number">${itemIndex}</td>
        <td name="noResi[]" >${noResi}</td> <!-- No more input field, just displaying the resi number -->
        <td>
            <select class="form-control selectBeratDimensi" data-index="${itemIndex}">
                <option value="berat">Berat</option>
                <option value="dimensi">Dimensi</option>
            </select>
        </td>
        <td class="hitungan" data-index="${itemIndex}">
            <input type="number" class="form-control beratBarang" data-index="${itemIndex}" name="beratBarang[]" placeholder="Masukkan Berat (Kg)" min="0" step="0.01">
        </td>
        <td><span class="hargaBarang">Rp. 0</span></td>
        <td><button type="button" class="btn btn-danger remove-item"><span class="pr-2"><i class="fas fa-trash"></i></span>Hapus</button></td>
    </tr>`;

                $('#barang-list').append(newRow);
                itemIndex++;

                setRemoveItemButton();
                attachInputEvents();
                attachSelectChangeEvent();
            }

            function attachSelectChangeEvent() {
                $('.selectBeratDimensi').off('change').on('change', function() {
                    const row = $(this).closest('tr');
                    const selectedValue = $(this).val();
                    const index = $(this).data('index');

                    if (selectedValue === 'berat') {
                        row.find('.hitungan').html(`
                    <input type="number" class="form-control beratBarang" data-index="${index}" name="beratBarang[]" placeholder="Masukkan Berat (Kg)" min="0" step="0.01">
                `);
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
                    }
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
                const hargaPerKg = parseFloat($('#rateBerat').val()) || 0;

                if (berat > 0 && hargaPerKg) {
                    let totalHarga = berat * hargaPerKg;
                    totalHarga = Math.max(totalHarga, globalMinrate);
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
                const pembagi = parseFloat($('#pembagiVolume').val()) || 1;
                const rate = parseFloat($('#rateVolume').val()) || 1;

                if (volume > 0 && rate) {
                    let totalHargaVolume = (volume / pembagi) * rate;
                    row.find('.hargaBarang').text("Rp. " + totalHargaVolume.toLocaleString('id-ID'));
                } else {
                    row.find('.hargaBarang').text("Rp. 0");
                }
                updateDisplayedTotalHarga();
            }


            function updateDisplayedTotalHarga() {
                let totalHarga = 0;

                $('.hargaBarang').each(function() {
                    let harga = $(this).text().replace(/[^0-9,-]+/g, "");
                    totalHarga += parseFloat(harga) || 0;
                });

                $('#totalHargaValue').val(totalHarga);

                const currencyValue = $('#currencyInvoice').val();
                const totalHargaIDR = totalHarga;
                const customRate = $('#rateCurrency').val();
                let convertedTotal = 0;

                // Update bagian Total IDR
                if (!totalHargaIDR || isNaN(totalHargaIDR) || totalHargaIDR === 0) {
                    $('#idrCurrentCy').text('Rp. 0');
                    $('#total-harga').text('-');
                    return;
                }

                $('#idrCurrentCy').text("Rp. " + parseFloat(totalHargaIDR).toLocaleString('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));

                // Update total yang dikonversi
                if (!currencyValue) {
                    $('#total-harga').text('-');
                } else if (currencyValue === '1') {
                    $('#total-harga').text("Rp. " + parseFloat(totalHargaIDR).toLocaleString('id-ID', {
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
                    $(this).closest('tr').remove();
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




            function validateForm() {
                let isValid = true;

                // Loop melalui setiap baris item
                $('#barang-list tr').each(function() {
                    const selectedValue = $(this).find('.selectBeratDimensi').val();
                    const berat = $(this).find('.beratBarang').val();
                    const panjang = $(this).find('.panjangVolume').val();
                    const lebar = $(this).find('.lebarVolume').val();
                    const tinggi = $(this).find('.tinggiVolume').val();
                    const rateBerat = $('#rateBerat').val();
                    const rateVolume = $('#rateVolume').val();
                    const pembagiVolume = $('#pembagiVolume').val();

                    // Validasi untuk berat jika dipilih
                    if (selectedValue === 'berat' && (!berat || !rateBerat)) {
                        showMessage("error", 'Pastikan rate berat sudah dipilih.');
                        isValid = false;
                        return false;
                    }

                    // Validasi untuk dimensi jika dipilih
                    if (selectedValue === 'dimensi' && (panjang || lebar || tinggi) && (!rateVolume || !
                            pembagiVolume)) {
                        showMessage("error",
                            'Pastikan rate volume serta pembagi volume sudah diisi.');
                        isValid = false;
                        return false;
                    }

                    // Pastikan jika salah satu berat atau volume ada yang terisi
                    if (!berat && (!panjang || !lebar || !tinggi)) {
                        showMessage("error", 'Silakan masukkan berat atau dimensi yang valid.');
                        isValid = false;
                        return false;
                    }
                });

                return isValid;
            }




            $('#buatInvoice').click(function() {
                const noResi = [];
                const beratBarang = [];
                const panjang = [];
                const lebar = [];
                const tinggi = [];
                const hargaBarang = [];




                $('#barang-list tr').each(function() {
                    noResi.push($(this).find('[name="noResi[]"]').text());
                    beratBarang.push($(this).find('.beratBarang').val());
                    panjang.push($(this).find('.panjangVolume').val());
                    lebar.push($(this).find('.lebarVolume').val());
                    tinggi.push($(this).find('.tinggiVolume').val());
                    hargaBarang.push($(this).find('.hargaBarang').text().replace(/[^0-9,-]+/g, "")
                        .trim());
                });

                const noInvoice = $('#noInvoice').text().trim();
                const tanggal = $('#tanggal').val().trim();
                const customer = $('#selectCostumer').val();
                const currencyInvoice = $('#currencyInvoice').val();
                const rateCurrency = $('#rateCurrency').val();
                var metodePengiriman = $('#selectCostumer option:selected').data('metode');
                var alamat = null;

                if (metodePengiriman === 'Delivery') {
                    if ($('#alamatSelect').length > 0) {
                        alamat = $('#alamatSelect').val();
                    } else {
                        alamat = $('#alamatContainer p').text().trim();
                    }
                }

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

                if (currencyInvoice === '1' && rateCurrency === null) {
                    $('#rateCurrencyError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#rateCurrencyError').addClass('d-none');
                }

                if (!validateForm()) {
                    e.preventDefault();
                    return;
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
                        noInvoice: noInvoice,
                        noResi: noResi,
                        tanggal: tanggal,
                        customer: customer,
                        currencyInvoice: currencyInvoice,
                        rateCurrency: rateCurrency,
                        beratBarang: beratBarang,
                        panjang: panjang,
                        lebar: lebar,
                        tinggi: tinggi,
                        metodePengiriman: metodePengiriman,
                        alamat: alamat,
                        totalharga: totalharga,
                        hargaBarang: hargaBarang,
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
                        let errorMessage =
                        "Terjadi Kesalahan membuat invoice";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            title: errorMessage,
                            icon: "error"
                        });
                    }
                });
            });
        });
    </script>
@endsection
