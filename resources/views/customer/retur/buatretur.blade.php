@extends('layout.main')

@section('title', 'Customer | Rertur')

@section('main')


    <style>
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
    </style>

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Buat Retur</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Customer</li>
                <li class="breadcrumb-item"><a href="{{ route('retur.index') }}">Rertur</a></li>
                <li class="breadcrumb-item active" aria-current="page">Buat Rertur</li>
            </ol>
        </div>
        <a class="btn btn-primary mb-3" href="{{ route('retur.index') }}">
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
                                    <label for="invoiceRetur" class="form-label fw-bold">Invoice</label>
                                    <select class="form-control select2" name="" id="invoiceRetur">
                                        <option value="" selected disabled>Pilih Invoice (marking - nama)</option>
                                        @foreach ($listInvoice as $invoice)
                                            <option value="{{ $invoice->id }}">{{ $invoice->no_invoice }}
                                                ({{ $invoice->marking }} - {{ $invoice->nama_pembeli }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="invoiceReturError" class="text-danger mt-1 d-none">Silahkan Pilih Invoice
                                        terlebih dahulu</div>
                                </div>

                                <div class="mt-3">
                                    <label for="currencyRetur" class="form-label fw-bold">Currency</label>
                                    <select class="form-control col-8" name="" id="currencyRetur">
                                        @foreach ($listCurrency as $currency)
                                            <option value="{{ $currency->id }}">
                                                {{ $currency->nama_matauang }} ({{ $currency->singkatan_matauang }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="currencyReturError" class="text-danger mt-1 d-none">Silahkan Pilih Currency
                                        terlebih dahulu</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="accountRetur" class="form-label fw-bold">Account</label>
                                    <select class="form-control select2" name="" id="accountRetur" style="">
                                        <option value="" selected disabled>Pilih Account</option>
                                        @foreach ($coas as $coa)
                                            <option value="{{ $coa->id }}">{{ $coa->code_account_id }} -
                                                {{ $coa->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="accountReturError" class="text-danger mt-1 d-none">Silahkan Pilih Account
                                        terlebih dahulu</div>
                                </div>
                                <div class="mt-3">
                                    <div class="mt-3" id="rateCurrencySection" style="display: none;">
                                        <label for="rateCurrency" class="form-label fw-bold">Rate Currency</label>
                                        <input type="text" class="form-control col-8" id="rateCurrency" value=""
                                            placeholder="Masukkan rate">
                                        <div id="rateCurrencyError" class="text-danger mt-1 d-none">Rate tidak boleh kosong
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-label ml-3 mt-5 mb-3">Item List</div>
                        <div class="table-responsive col-12 ms-5">
                            <table class="table mb-0" style="border-bottom : 1px solid black;">
                                <thead>
                                    <tr>
                                        <th>No Resi</th>
                                        <th>Preview Nominal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody id="items-container">
                                    <!-- Kosong, akan diisi JS -->
                                </tbody>

                            </table>
                            <div id="tableError" class="text-danger mt-2 d-none">
                                Silahkan isi semua field pada tabel sebelum melanjutkan.
                            </div>

                            <br>
                            <button type="button" class="btn btn-primary" id="add-item-button">Add
                                Item</button>

                        </div>
                        <div class="row">
                            <div class="col-5 mr-5">
                                <div class="input-group pt-2 mt-3">
                                    <label for="deskripsiRetur" class="form-label fw-bold p-1">Deskripsi</label>
                                </div>
                                <textarea id="deskripsiRetur" class="form-control" aria-label="With textarea" placeholder="Masukkan note"
                                    rows="4"></textarea>
                            </div>
                            <div class="col-4 ms-5 mt-5 ml-5">
                                <div class="mb-3" style="border-bottom:1px solid black;">
                                    <label class="inline-label">Total keseluruhan : </label>
                                    <input disabled="" type="text" id="Total"
                                        class="form-control-flush inline-input" name="subtotal" placeholder="0"
                                        fdprocessedid="nox2">
                                </div>
                            </div>
                            <div id="noteReturError" class="text-danger mt-1 d-none">Silahkan isi Note</div>
                            <div class="col-12 mt-4">
                                <div class="col-4 float-right">
                                    <button id="buatRetur" class="btn btn-primary p-3 float-right mt-3"
                                        style="width: 100%;">Buat
                                        Retur</button>
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
            $('.select2').select2();

            $('#currencyRetur').change(function() {
                const selectedCurrency = $(this).val();

                if (selectedCurrency !== '1') {
                    $('#rateCurrencySection').show();
                } else {
                    $('#rateCurrencySection').hide();
                }
            });

            let selectedInvoiceId = null;

            // Saat invoice dipilih
            $('#invoiceRetur').on('change', function() {
                selectedInvoiceId = $(this).val();

                // Kosongkan semua item dulu
                $('#items-container').html('');

                // Tambahkan baris pertama otomatis
                addNewItemRow();
            });

            // Fungsi tambah baris item
            function addNewItemRow() {
                let newRow = `
                <tr class="tr">
                    <td>
                        <select class="form-control select2-resi" name="resi_id[]" required>
                            <option value="">Pilih Resi</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="harga[]" class="form-control" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button>
                    </td>
                </tr>
            `;
                $('#items-container').append(newRow);

                // Init Select2 di elemen baru
                let $select = $('.select2-resi').last();
                $select.select2({
                    placeholder: "Pilih Resi",
                    ajax: {
                        url: '{{ route('retur.listresi') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                invoice_id: selectedInvoiceId,
                                search: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(item => ({
                                    id: item.id,
                                    text: `${item.no_resi} - Rp ${item.harga.toLocaleString()}`
                                }))
                            };
                        },
                        cache: true
                    }
                });
            }

            // Event saat pilih resi → isi harga otomatis
            $('#items-container').on('change', '.select2-resi', function() {
                const resiId = $(this).val();
                const $row = $(this).closest('tr');

                // Ambil info resi dari AJAX
                $.ajax({
                    url: '{{ route('retur.listresi') }}',
                    data: {
                        invoice_id: selectedInvoiceId,
                        search: '' // kosongkan pencarian agar semua resi terload
                    },
                    success: function(data) {
                        const selected = data.find(d => d.id == resiId);
                        if (selected) {
                            $row.find('input[name="harga[]"]').val(selected.harga);
                            calculateTotal();
                        }
                    }
                });
            });

            // Tombol tambah item
            $('#add-item-button').on('click', function() {
                addNewItemRow();
            });

            // Tombol hapus baris item
            $('#items-container').on('click', '.removeItemButton', function() {
                $(this).closest('tr').remove();
                calculateTotal();
            });

            // Fungsi hitung total
            function calculateTotal() {
                let total = 0;
                $('input[name="harga[]"]').each(function() {
                    let val = parseFloat($(this).val()) || 0;
                    total += val;
                });
                $('#Total').val(total.toLocaleString());
            }

            // Jalankan saat halaman pertama kali load
            $(document).ready(function() {
                $('#items-container').html('');
            });


            $('#buatRetur').on('click', function(e) {
                e.preventDefault();

                let invoiceId = $('#invoiceRetur').val();
                let currencyId = $('#currencyRetur').val();
                let accountId = $('#accountRetur').val();
                let deskripsi = $('#deskripsiRetur').val();

                let items = [];
                $('.select2-resi').each(function() {
                    const resiId = $(this).val();
                    if (resiId) {
                        items.push({
                            resi_id: resiId
                        });
                    }
                });

                if (!invoiceId || !currencyId || !accountId || items.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Mohon lengkapi semua data.'
                    });
                    return;
                }

                let data = {
                    invoice_id: invoiceId,
                    currency_id: currencyId,
                    account_id: accountId,
                    deskripsi: deskripsi,
                    items: items
                };

                // Kirim pakai AJAX
                $.ajax({
                    type: 'POST',
                    url: '/retur/store',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Retur berhasil dibuat!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan',
                            text: 'Terjadi kesalahan saat menyimpan retur.'
                        });
                    }
                });
            });

        </script>


    @endsection
