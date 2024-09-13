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
                                    <h2 class="fw-bold" id="noInvoice">{{ $newNoinvoce }}</h2>
                                    {{-- <input type="text" class="form-control col-8" id="noResi" value=""
                                        placeholder="Scan Resi">
                                    <div id="noResiError" class="text-danger mt-1 d-none">Silahkan Scan No Resi terlebih
                                        dahulu</div> --}}
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
                            </div>
                        </div>

                        <!-- Tabel Input Berat dan Dimensi -->
                        <table class="table mt-4">
                            <thead>
                                <tr>
                                    <th>No</th>
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
                        <button type="button" class="btn btn-primary" id="addItemBtn"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Tambah Barang</button>
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
                } else {
                    $('#pickupDelivery').hide();
                    $('#alamatContainer').empty();
                }
                updateDisplayedTotalHarga();
            });

            var today = new Date();
            $('#tanggal').datepicker({
                format: 'dd MM yyyy',
                todayBtn: 'linked',
                todayHighlight: true,
                autoclose: true,
            }).datepicker('setDate', today);

            $('#currencyInvoice').change(function() {
                const selectedCurrency = $(this).val();
                if (selectedCurrency !== '1') {
                    $('#rateCurrencySection').show();
                } else {
                    $('#rateCurrencySection').hide();
                    $('#rateCurrency').val('');
                }
                updateDisplayedTotalHarga();
            });

            $('#rateCurrency').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Menghilangkan karakter non-numerik
                updateDisplayedTotalHarga();
            });


            let itemIndex = 1; // Mulai dari 1 untuk baris pertama

            // Fungsi untuk menambah baris baru, termasuk baris pertama
            function addItemRow(isFirstRow = false) {
                const removeButton = isFirstRow ? '' :
                    '<button type="button" class="btn btn-danger remove-item"><span class="pr-2"><i class="fas fa-trash"></i></span>Hapus</button>';
                const newRow = `
        <tr data-index="${itemIndex}">
            <td class="item-number">${itemIndex}</td>
            <td><input type="text" class="form-control"  name="noResi[]" placeholder="Scan No Resi"></td>
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
             <td>${removeButton}</td>
        </tr>`;

                $('#barang-list').append(newRow);
                if (!isFirstRow) {
                    $('input[name="noResi[]"]').last().focus();
                }
                itemIndex++;

                setRemoveItemButton();
                attachInputEvents(); // Attach event handler ke baris baru
                attachSelectChangeEvent();
            }
            addItemRow(true);

            // Tambah baris baru saat tombol "Tambah Barang" ditekan
            $('#addItemBtn').click(function() {
                addItemRow();
            });

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

            // Attach event handler ke semua elemen input (baik baris baru maupun lama)
            function attachInputEvents() {
                // Event untuk input berat
                $('#barang-list').on('input', '.beratBarang', function() {
                    const row = $(this).closest('tr');
                    const beratValue = $(this).val();

                    // Kosongkan input volume jika berat diisi
                    if (beratValue.trim() !== "") {
                        row.find('.panjangVolume').val(''); // Kosongkan input panjang
                        row.find('.lebarVolume').val(''); // Kosongkan input lebar
                        row.find('.tinggiVolume').val(''); // Kosongkan input tinggi
                    }

                    updateTotalHargaBerat(row); // Hitung total harga berdasarkan berat
                });

                // Event untuk input volume (panjang, lebar, tinggi)
                $('#barang-list').on('input', '.panjangVolume, .lebarVolume, .tinggiVolume', function() {
                    const row = $(this).closest('tr');
                    const panjangValue = row.find('.panjangVolume').val();
                    const lebarValue = row.find('.lebarVolume').val();
                    const tinggiValue = row.find('.tinggiVolume').val();

                    // Kosongkan input berat jika salah satu input volume diisi
                    if (panjangValue.trim() !== "" || lebarValue.trim() !== "" || tinggiValue.trim() !==
                        "") {
                        row.find('.beratBarang').val(''); // Kosongkan input berat
                    }

                    updateTotalHargaVolume(row); // Hitung total harga berdasarkan volume
                });
            }

            // Fungsi untuk mengupdate total harga berdasarkan berat
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

            // Fungsi untuk mengupdate total harga berdasarkan volume
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
                // Hitung total harga dari elemen dengan class hargaBarang
                $('.hargaBarang').each(function() {
                    let harga = $(this).text().replace(/[^0-9,-]+/g, ""); // Mengambil angka dari text
                    totalHarga += parseFloat(harga) || 0;
                });

                // Simpan nilai total harga IDR ke dalam #totalHargaValue
                $('#totalHargaValue').val(totalHarga); // Selalu simpan harga IDR di totalHargaValue

                // Ambil nilai mata uang dan rate konversi
                const currencyValue = $('#currencyInvoice').val();
                const totalHargaIDR = totalHarga; // total harga dalam IDR
                const customRate = $('#rateCurrency').val();
                let convertedTotal = 0;

                // Validasi apakah total harga valid
                if (!totalHargaIDR || isNaN(totalHargaIDR) || totalHargaIDR === 0) {
                    $('#total-harga').text('-');
                    return;
                }

                // Jika tidak ada mata uang yang dipilih
                if (!currencyValue) {
                    $('#total-harga').text('-');
                }
                // Jika mata uang yang dipilih adalah Rupiah (IDR)
                else if (currencyValue == 1) {
                    $('#total-harga').text("Rp. " + parseFloat(totalHargaIDR).toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                }
                // Jika mata uang lain dipilih dan ada rate yang diinputkan
                else if (customRate && currencyValue !== '1') {
                    convertedTotal = totalHargaIDR / customRate;
                    let currencySymbol = "";

                    if (currencyValue == 2) {
                        currencySymbol = "$ "; // Simbol untuk SGD
                    } else if (currencyValue == 3) {
                        currencySymbol = "¥ "; // Simbol untuk RMB
                    }

                    $('#total-harga').text(currencySymbol + convertedTotal.toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                }
                // Jika kondisi lainnya tidak terpenuhi
                else {
                    $('#total-harga').text('-');
                }
            }


            // Fungsi untuk tombol hapus pada baris selain baris pertama
            function setRemoveItemButton() {
                $('.remove-item').off('click').on('click', function() {
                    $(this).closest('tr').remove();
                    renumberItems(); // Renumbering setelah item dihapus
                    updateDisplayedTotalHarga();
                });
            }

            // Fungsi untuk me-reorder nomor item setelah penghapusan atau penambahan
            function renumberItems() {
                let index = 1;
                $('#barang-list tr').each(function() {
                    $(this).find('.item-number').text(index);
                    $(this).attr('data-index', index);
                    index++;
                });
                itemIndex = index;
            }

            $('#buatInvoice').click(function() {
                // Collect all form data
                const noResi = [];
                const beratBarang = [];
                const panjang = [];
                const lebar = [];
                const tinggi = [];
                const hargaBarang = [];

                $('#barang-list tr').each(function() {
                    noResi.push($(this).find('input[name="noResi[]"]').val().trim());
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

                // if ($('#toggleSwitch').is(':checked')) {
                //     if (panjang === '' || lebar === '' || tinggi === '') {
                //         $('#volumeError').removeClass('d-none');
                //         isValid = false;
                //     } else {
                //         $('#volumeError').addClass('d-none');
                //     }

                //     if (pembagiVolume === null) {
                //         $('#pembagiErrorVolume').removeClass('d-none');
                //         isValid = false;
                //     } else {
                //         $('#pembagiErrorVolume').addClass('d-none');
                //     }

                //     if (rateVolume === null) {
                //         $('#raterErrorVolume').removeClass('d-none');
                //         isValid = false;
                //     } else {
                //         $('#raterErrorVolume').addClass('d-none');
                //     }
                // } else {
                //     if (beratBarang === '') {
                //         $('#beratError').removeClass('d-none');
                //         isValid = false;
                //     } else {
                //         $('#beratError').addClass('d-none');
                //     }

                //     if (!rateBerat) {
                //         $('#rateBeratError').removeClass('d-none');
                //         isValid = false;
                //     } else {
                //         $('#rateBeratError').addClass('d-none');
                //     }
                // }

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

                        var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Terjadi kesalahan. Mohon coba lagi.";
                        Swal.fire({
                            title: "Gagal membuat invoice",
                            text: errorMessage,
                            icon: "error"
                        });
                    }
                });
            });
        });
    </script>
@endsection
@section('tidakterpakai')
    <script>
        $(document).ready(function() {

            $('.select2singgle').select2({
                width: 'resolve'
            });

            $('#pickupDelivery').hide();

            let globalMinrate = 0;

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
                        $('#alamatContainer')
                            .empty();

                    } else if (metodePengiriman === 'Delivery' && jumlahAlamat == 1) {
                        $('#pickupDelivery h2').text('Delivery');
                        $('#alamatContainer').html('<p>' + alamat +
                            '</p>');

                    } else if (metodePengiriman === 'Delivery' && jumlahAlamat > 1) {
                        $('#pickupDelivery h2').text('Delivery');

                        var alamatList = alamat.split(', ');
                        var selectAlamat =
                            '<label for="alamatSelect" class="form-label">Alamat</label>';
                        selectAlamat += '<select id="alamatSelect" class="form-control col-9">';
                        selectAlamat +=
                            '<option value="" selected disabled>Pilih Alamat</option>';
                        alamatList.forEach(function(alamatItem) {
                            selectAlamat += '<option value="' + alamatItem + '">' + alamatItem +
                                '</option>';
                        });
                        selectAlamat += '</select>';
                        $('#alamatContainer').html(selectAlamat);
                    }

                } else {
                    $('#pickupDelivery').hide();
                    $('#alamatContainer').empty();
                }
                updateTotalHargaVolume();
                updateTotalHargaBerat();
                updateDisplayedTotalHarga();
            });

            var today = new Date();

            $('#tanggal').datepicker({
                format: 'dd MM yyyy',
                todayBtn: 'linked',
                todayHighlight: true,
                autoclose: true,
            }).datepicker('setDate', today);

            $('#currencyInvoice').change(function() {
                const selectedCurrency = $(this).val();
                if (selectedCurrency !== '1') {
                    $('#rateCurrencySection').show();
                } else {
                    $('#rateCurrencySection').hide();
                    $('#rateCurrency').val('');
                }
                updateDisplayedTotalHarga();
            });

            $('#rateCurrency').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                updateDisplayedTotalHarga();
            });

            function updateDisplayedTotalHarga() {
                const currencyValue = $('#currencyInvoice').val();
                const totalHargaIDR = $('#totalHargaValue').val();
                const customRate = $('#rateCurrency').val();
                let convertedTotal = 0;


                if (!totalHargaIDR || isNaN(totalHargaIDR) || totalHargaIDR.trim() === '') {
                    $('#total-harga').text('-');
                    return; // Kembalikan jika total harga tidak valid
                }

                if (!currencyValue) {
                    $('#total-harga').text('-');
                } else if (currencyValue == 1) {
                    $('#total-harga').text("Rp. " + parseFloat(totalHargaIDR).toLocaleString('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));
                } else if (customRate && currencyValue !==
                    '1') {
                    convertedTotal = totalHargaIDR / customRate;
                    let currencySymbol = "";

                    if (currencyValue == 2) {
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

            let itemCount = 0;
            // Function to add a new item row
            function addItem() {
                itemCount++;

                const newRow = `
        <tr id="row${itemCount}">
            <td>${itemCount}</td>
            <td>
                <input type="text" class="form-control" id="noResi${itemCount}" value="" placeholder="Scan Resi">
            </td>
            <td>
                <input type="number" class="form-control" id="beratBarang${itemCount}" oninput="updateTotalHargaBerat(${itemCount});" placeholder="0,00" min="0" step="0.01">
            </td>
            <td>
                <div class="d-flex">
                    <input type="number" class="form-control me-1" id="panjang${itemCount}" oninput="updateTotalHargaVolume(${itemCount});" placeholder="P" min="0" step="0.01">
                    <span class="mx-1 mt-2">×</span>
                    <input type="number" class="form-control me-1" id="lebar${itemCount}" oninput="updateTotalHargaVolume(${itemCount});" placeholder="L" min="0" step="0.01">
                    <span class="mx-1 mt-2">×</span>
                    <input type="number" class="form-control me-1" id="tinggi${itemCount}" oninput="updateTotalHargaVolume(${itemCount});" placeholder="T" min="0" step="0.01">
                    <span class="ml-2 pt-2">Cm</span>
                </div>
            </td>
            <td>
                <span id="harga${itemCount}">Rp. 0</span>
            </td>
            ${itemCount === 1 ? '<td></td>' : `<td><button type="button" class="btn btn-secondary" onclick="removeItem(${itemCount})">X</button></td>`}
        </tr>`;

                $('#barang-list').append(newRow);
            }

            // Fungsi untuk menghapus baris
            function removeItem(id) {
                $(`#row${id}`).remove();
                recalculateRowNumbers();
            }

            // Fungsi untuk menghitung ulang nomor baris setelah penghapusan
            function recalculateRowNumbers() {
                itemCount = 0;
                $('#barang-list tr').each(function(index) {
                    itemCount++;
                    $(this).attr('id', `row${itemCount}`);
                    $(this).find('td:first').text(itemCount);
                });
            }

            // Fungsi perhitungan total harga berdasarkan berat
            function updateTotalHargaBerat(rowId) {
                let beratRaw = $(`#beratBarang${rowId}`).val();
                let berat = parseFloat(beratRaw);
                let hargaPerKg = parseFloat($('#rateBerat').val());

                if (!beratRaw.trim() || isNaN(berat) || isNaN(hargaPerKg)) {
                    $(`#harga${rowId}`).text('Rp. 0');
                    return;
                }

                let totalHarga = berat * hargaPerKg;
                if (totalHarga < globalMinrate) {
                    totalHarga = globalMinrate;
                }

                $(`#harga${rowId}`).text(`Rp. ${totalHarga.toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })}`);
            }

            // Fungsi perhitungan total harga berdasarkan volume
            function updateTotalHargaVolume(rowId) {
                let panjang = parseFloat($(`#panjang${rowId}`).val()) || 0;
                let lebar = parseFloat($(`#lebar${rowId}`).val()) || 0;
                let tinggi = parseFloat($(`#tinggi${rowId}`).val()) || 0;
                let volume = panjang * lebar * tinggi;
                let pembagi = parseFloat($('#pembagiVolume').val()) || 1;
                let rate = parseFloat($('#rateVolume').val()) || 1;

                let totalHargaVolume = (volume / pembagi) * rate;

                $(`#harga${rowId}`).text(`Rp. ${totalHargaVolume.toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })}`);
            }

            // Event listeners untuk perubahan rate dan pembagi
            $('#rateBerat').change(function() {
                $('#barang-list tr').each(function(index, row) {
                    let rowId = $(row).attr('id').replace('row', '');
                    updateTotalHargaBerat(rowId);
                });
            });

            $('#pembagiVolume, #rateVolume').change(function() {
                $('#barang-list tr').each(function(index, row) {
                    let rowId = $(row).attr('id').replace('row', '');
                    updateTotalHargaVolume(rowId);
                });
            });

            // Baris awal
            addItem();

            // Event listener untuk tombol tambah barang
            $('#addItemBtn').on('click', function() {
                addItem();
            });

            $('#buatInvoice').click(function() {
                // Collect all form data
                const noResi = $('#noResi').val().trim();
                const tanggal = $('#tanggal').val().trim();
                const customer = $('#selectCostumer').val();
                const currencyInvoice = $('#currencyInvoice').val();
                const rateCurrency = $('#rateCurrency').val();
                const beratBarang = $('#beratBarang').val().trim();
                const rateBerat = $('#rateBerat').val();
                const panjang = $('#panjang').val().trim();
                const lebar = $('#lebar').val().trim();
                const tinggi = $('#tinggi').val().trim();
                var metodePengiriman = $('#selectCostumer option:selected').data('metode');
                var alamat = null;

                if (metodePengiriman === 'Delivery') {
                    // Cek apakah ada dropdown untuk memilih alamat
                    if ($('#alamatSelect').length > 0) {
                        // Jika ada dropdown, ambil nilai dari dropdown
                        alamat = $('#alamatSelect').val();
                    } else {
                        // Jika tidak ada dropdown, ambil nilai alamat dari teks yang ditampilkan
                        alamat = $('#alamatContainer p').text().trim();
                    }
                }
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

                if (currencyInvoice === '1' && rateCurrency === null) {
                    $('#rateCurrencyError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#rateCurrencyError').addClass('d-none');
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

                    if (!rateBerat) {
                        $('#rateBeratError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#rateBeratError').addClass('d-none');
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
                        rateCurrency: rateCurrency,
                        beratBarang: beratBarang,
                        panjang: panjang,
                        lebar: lebar,
                        tinggi: tinggi,
                        metodePengiriman: metodePengiriman,
                        alamat: alamat,
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
