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
    </style>

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Buat Invoice</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Vendor</li>
                <li class="breadcrumb-item"><a href="{{ route('supplierInvoice') }}">Invoice</a></li>
                <li class="breadcrumb-item active" aria-current="page">Buat Invoice</li>
            </ol>
        </div>

        <a class="btn btn-primary mb-3" href="{{ route('supplierInvoice') }}">
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
                                        <h2 class="fw-bold" id="noInvoice">-</h2>
                                        <a class="pt-2" id="btnRefreshInvoice" href=""><span
                                                class="pl-2 text-success"><i class="fas fa-sync-alt"></i></span></a>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                                    <input type="text" class="form-control col-8" id="tanggalVendor" value=""
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
                                            <option value="{{ $currency->id }}" {{ $currency->id == 1 ? 'selected' : '' }}>
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
                            <span>Vendor</span>
                        </div>
                        <div class="d-flex">
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="customer" class="form-label fw-bold col-12">Vendor</label>
                                    <select class="form-control select2singgle" id="selectVendor" style="width: 67%">
                                        <option value="" selected disabled>Pilih Vendor</option>
                                        @foreach ($listVendor as $vendor)
                                            <option value="{{ $vendor }}">{{ $vendor }}</option>
                                        @endforeach
                                    </select>
                                    <div id="customerError" class="text-danger mt-1 d-none">Silahkan Pilih Vendor</div>
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
                            <span>Detail Invoice</span>
                        </div>
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Code Account</th>
                                    <th>Description</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Memo</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="items-container">
                                <!-- Two default rows without the "Remove" button initially -->
                                <tr>
                                    <td>
                                        <select class="form-control select2singgle" name="account" style="width: 15vw;"
                                            required>
                                            <option value="">Pilih Akun</option>
                                            @foreach ($coas as $coa)
                                                <option value="{{ $coa->id }}">
                                                    {{ $coa->code_account_id }} - {{ $coa->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="item_desc"
                                            placeholder="Input Description" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="debit" value="0"
                                            placeholder="0.00" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="credit" value="0"
                                            placeholder="" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="memo" placeholder="">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1"
                                            style="display:none;">Remove</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control select2singgle" name="account" style="width: 15vw;"
                                            required>
                                            <option value="">Pilih Akun</option>
                                            @foreach ($coas as $coa)
                                                <option value="{{ $coa->id }}">
                                                    {{ $coa->code_account_id }} - {{ $coa->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="item_desc"
                                            placeholder="Input Description" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="debit" value="0"
                                            placeholder="" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="credit" value="0"
                                            placeholder="" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="memo" placeholder="">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1"
                                            style="display:none;">Remove</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-primary" id="add-item-button">Add
                                            Item</button>
                                    </td>
                                    <td></td>
                                    <td>
                                        <label>Total:</label>
                                        <input type="text" class="form-control-flush" id="total_debit"
                                            name="total_debit" value="" disabled>
                                    </td>
                                    <td>
                                        <label>Total:</label>
                                        <input type="text" class="form-control-flush" id="total_credit"
                                            name="total_credit" value="" disabled>
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="col-12 mt-5">
                            <div class="col-4 float-right">
                                <button id="buatJournal" class="btn btn-primary p-3 float-right mt-3"
                                    style="width: 80%;">Buat Invoice</button>
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
        $(document).ready(function() {

            const loadSpin = `<div class="d-flex justify-content-center align-items-center pl-5">
                        <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
                     </div>`;

            function generateInvoice() {
                $.ajax({
                    url: "{{ route('generateSupInvoice') }}",
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        $('#noInvoice').html(loadSpin);
                    },
                    success: function(response) {

                        if (response.status === 'success') {

                            $('#noInvoice').text(response.no_invoice);
                        } else {
                            alert('Gagal mendapatkan nomor invoice.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan: ' + error);
                    },
                    complete: function() {
                        $('#noInvoice').find('.spinner-border').remove();
                    }
                });
            }

            generateInvoice();

            $('#btnRefreshInvoice').on('click', function(e) {
                e.preventDefault();
                generateInvoice();
            });


            $('.select2singgle').select2({
                width: 'resolve'
            });

            var today = new Date();
            $('#tanggalVendor').datepicker({
                format: 'dd MM yyyy',
                todayBtn: 'linked',
                todayHighlight: true,
                autoclose: true,
            }).datepicker('setDate', today);

            function updateTotals() {
                var totalDebit = 0;
                var totalCredit = 0;
                $('#items-container tr').each(function() {
                    var debitValue = parseFloat($(this).find('input[name="debit"]').val()) || 0;
                    var creditValue = parseFloat($(this).find('input[name="credit"]').val()) || 0;

                    totalDebit += debitValue;
                    totalCredit += creditValue;
                });

                $('#total_debit').val(totalDebit.toFixed(0));
                $('#total_credit').val(totalCredit.toFixed(0));
            }
            $('.select2singgle').select2();
            $('#add-item-button').click(function() {
                var newRow = `
    <tr>
        <td>
            <select class="form-control select2singgle" name="account" style="width: 15vw;" required>
                <option value="">Pilih Akun</option>
                @foreach ($coas as $coa)
                    <option value="{{ $coa->id }}">
                        {{ $coa->code_account_id }} - {{ $coa->name }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="text" class="form-control" name="item_desc" placeholder="Input Description" required>
        </td>
        <td>
            <input type="number" class="form-control" name="debit" value="0" placeholder="0.00" required>
        </td>
        <td>
            <input type="number" class="form-control" name="credit" value="0" placeholder="0.00" required>
        </td>
        <td>
            <input type="text" class="form-control" name="memo" placeholder="">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button>
        </td>
    </tr>
    `;
                $('#items-container').append(newRow);
                $('.select2singgle').last().select2();
                if ($('#items-container tr').length > 2) {
                    $('.removeItemButton').show();
                }
                updateTotals();
            });

            $(document).on('click', '.removeItemButton', function() {
                var rowCount = $('#items-container tr').length;
                if (rowCount > 2) {
                    $(this).closest('tr').remove();
                }

                rowCount = $('#items-container tr').length;

                if (rowCount === 2) {
                    $('.removeItemButton').hide();
                }

                updateTotals();
            });

            $(document).on('input', 'input[name="debit"], input[name="credit"]', function() {
                updateTotals();
            });

            $('#currencyInvoice').change(function() {
                let selectedCurrencyId = $(this).val();

                if (selectedCurrencyId != 1) {
                    $('#rateCurrencySection').show();
                } else {
                    $('#rateCurrencySection').hide();
                }
            });

            function getFormValues() {
                let invoiceNumber = $('#noInvoice').text();
                let tanggal = $('#tanggalVendor').val();
                let currency = $('#currencyInvoice').val();
                let rateCurrency = $('#rateCurrency').val();
                let vendor = $('#selectVendor').val();

                let items = [];
                $('#items-container tr').each(function() {
                    let account = $(this).find('select[name="account"]').val();
                    let itemDesc = $(this).find('input[name="item_desc"]').val();
                    let debit = $(this).find('input[name="debit"]').val();
                    let credit = $(this).find('input[name="credit"]').val();
                    let memo = $(this).find('input[name="memo"]').val();

                    items.push({
                        account: account,
                        itemDesc: itemDesc,
                        debit: debit,
                        credit: credit,
                        memo: memo
                    });
                });

                console.log({
                    invoiceNumber: invoiceNumber,
                    tanggal: tanggal,
                    currency: currency,
                    rateCurrency: rateCurrency,
                    vendor: vendor,
                    items: items
                });

                return {
                    invoiceNumber: invoiceNumber,
                    tanggal: tanggal,
                    currency: currency,
                    rateCurrency: rateCurrency,
                    vendor: vendor,
                    items: items
                };
            }

            $('#buatJournal').click(function(e) {
                e.preventDefault();
                let formData = getFormValues();
                $.ajax({
                    url: "{{ route('supInvoice.store') }}",
                    type: 'POST',
                    data: {
                        invoice_no: formData.invoiceNumber,
                        tanggal: formData.tanggal,
                        vendor: formData.vendor,
                        matauang_id: formData.currency,
                        rateCurrency: formData.rateCurrency,
                        items: formData.items,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showMessage("success", "Invoice berhasil dibuat").then(() => {
                                location.reload();
                            });
                        } else {
                            showMessage('error','Gagal menyimpan invoice');
                        }
                    },
                    error: function(xhr, status, error) {
                        showMessage('error','Terjadi kesalahan');
                    }
                });
            });
        });
    </script>
@endsection
