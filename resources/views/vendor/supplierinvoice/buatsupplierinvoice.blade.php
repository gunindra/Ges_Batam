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
                                <label for="noResi" class="form-label fw-bold">No. Voucher :</label>
                                <div class="d-flex">
                                    <input type="text" id="noInvoice" class="form-control col-8">
                                    <a class="pt-2" id="btnRefreshInvoice" href=""><span class="pl-2 text-success"><i
                                                class="fas fa-sync-alt"></i></span></a>
                                </div>
                                <div id="noInvoiceError" class="text-danger mt-1 d-none">No. Voucher tidak boleh kosong
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
                                    @foreach ($listVendor as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <div id="vendorError" class="text-danger mt-1 d-none">Silahkan Pilih Vendor</div>
                            </div>
                            <div class="mt-3" id="alamatContainer"></div>
                        </div>
                        <div class="col-6">
                            <div class="mt-3">
                                <label for="NoReference" class="form-label fw-bold">No. Invoice Vendor</label>
                                <input type="text" class="form-control col-8" id="noReferenceVendor" value=""
                                    placeholder="Silahkan isi No Invoice Vendor">
                                <div id="NoReferenceError" class="text-danger mt-1 d-none">No Invoice Vendor tidak boleh
                                    kosong</div>
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
                                <th>Nominal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-container">
                            <tr>
                                <td>
                                    <select class="form-control select2singgle" name="account" style="width: 30vw;"
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
                                    <input type="number" class="form-control" name="debit" value="0" placeholder="0.00"
                                        required>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1"
                                        style="display: none;">Remove</button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    <div id="tableError" class="text-danger mt-1 mb-2 d-none">Silahkan isi semua field
                                        pada tabel sebelum melanjutkan.</div>
                                    <button type="button" class="btn btn-primary" id="add-item-button">Add
                                        Item</button>
                                </td>
                                <td></td>
                                <td>
                                    <label>Total:</label>
                                    <input type="text" class="form-control-flush" id="total_debit" name="total_debit"
                                        value="" disabled>
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
    $(document).ready(function () {

        const loadSpin = `<div class="d-flex justify-content-center align-items-center pl-5">
                        <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
                     </div>`;

        function generateInvoice() {
            $.ajax({
                url: "{{ route('generateSupInvoice') }}",
                type: 'GET',
                dataType: 'json',
                beforeSend: function () {
                    $('#noInvoice').html(loadSpin);
                },
                success: function (response) {

                    if (response.status === 'success') {

                        $('#noInvoice').val(response.no_invoice);
                    } else {
                        // alert('Gagal mendapatkan nomor invoice.');
                        showMessage("error", "Gagal mendapatkan nomor invoice.")
                    }
                },
                error: function (xhr, status, error) {
                    // alert('Terjadi kesalahan: ' + error);
                    showMessage("error", "Terjadi kesalahan:" + error);
                },
                complete: function () {
                    $('#noInvoice').find('.spinner-border').remove();
                }
            });
        }

        generateInvoice();

        $('#btnRefreshInvoice').on('click', function (e) {
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

        $('#add-item-button').click(function () {
            var newRow = `
        <tr>
            <td>
                <select class="form-control select2singgle" name="account" style="width: 30vw;" required>
                    <option value="">Pilih Akun</option>
                    @foreach ($coas as $coa)
                        <option value="{{ $coa->id }}">{{ $coa->code_account_id }} - {{ $coa->name }}</option>
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
                <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button>
            </td>
        </tr>`;
            $('#items-container').append(newRow);
            $('.select2singgle').last().select2();

            if ($('#items-container tr').length > 1) {
                $('.removeItemButton').show();
            }
            updateTotals();
        });

        $(document).on('click', '.removeItemButton', function () {
            if ($('#items-container tr').length > 1) {
                $(this).closest('tr').remove();
            }

            if ($('#items-container tr').length === 1) {
                $('.removeItemButton').hide();
            }

            updateTotals();
        });

        // Update only the Total Debit
        function updateTotals() {
            let totalDebit = 0;
            $('#items-container tr').each(function () {
                let debitValue = parseFloat($(this).find('input[name="debit"]').val()) || 0;
                totalDebit += debitValue;
            });
            $('#total_debit').val(totalDebit.toFixed(0));
        }

        $(document).on('input', 'input[name="debit"]', function () {
            updateTotals();
        });

        $('#currencyInvoice').change(function () {
            let selectedCurrencyId = $(this).val();

            if (selectedCurrencyId != 1) {
                $('#rateCurrencySection').show();
            } else {
                $('#rateCurrencySection').hide();
            }
        });

        function getFormValues() {
            let invoiceNumber = $('#noInvoice').val();
            let tanggal = $('#tanggalVendor').val();
            let noReferenceVendor = $('#noReferenceVendor').val();
            let currency = $('#currencyInvoice').val();
            let rateCurrency = $('#rateCurrency').val();
            let vendor = $('#selectVendor').val();

            let items = [];
            let isItemsValid = true;
            $('#items-container tr').each(function () {
                let account = $(this).find('select[name="account"]').val();
                let itemDesc = $(this).find('input[name="item_desc"]').val();
                let debit = $(this).find('input[name="debit"]').val();

                if (!account || !itemDesc || !debit) {
                    isItemsValid = false;
                }

                items.push({
                    account: account,
                    itemDesc: itemDesc,
                    debit: debit
                });
            });

            return {
                invoiceNumber: invoiceNumber,
                tanggal: tanggal,
                noReferenceVendor: noReferenceVendor,
                currency: currency,
                rateCurrency: rateCurrency,
                vendor: vendor,
                items: items,
                isItemsValid: isItemsValid
            };
        }

        $('#buatJournal').click(function (e) {
            e.preventDefault();
            let formData = getFormValues();

            let isValid = true;

            if (!formData.currency) {
                $('#currencyInvoiceError').removeClass('d-none');
                isValid = false;
            }

            if (!formData.invoiceNumber) {
                $('#noInvoiceError').removeClass('d-none');
                isValid = false;
            }

            if (!formData.tanggal) {
                $('#tanggalError').removeClass('d-none');
                isValid = false;
            }

            if (!formData.vendor) {
                $('#vendorError').removeClass('d-none');
                isValid = false;
            }

            if (!formData.noReferenceVendor) {
                $('#NoReferenceError').removeClass('d-none');
                isValid = false;
            }

            if (!formData.isItemsValid) {
                $('#tableError').removeClass('d-none');
                isValid = false;
            } else {
                $('#tableError').addClass('d-none');
            }

            if (formData.currency) {
                let selectedCurrency = $('#currencyInvoice option:selected').text();
                if ((selectedCurrency.includes("SGD") || selectedCurrency.includes("CNY")) && !formData.rateCurrency) {
                    $('#rateCurrencyError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#rateCurrencyError').addClass('d-none');
                }
            }

            if (isValid) {
                $.ajax({
                    url: "{{ route('supInvoice.store') }}",
                    type: 'POST',
                    data: {
                        invoice_no: formData.invoiceNumber,
                        tanggal: formData.tanggal,
                        noReferenceVendor: formData.noReferenceVendor,
                        vendor: formData.vendor,
                        matauang_id: formData.currency,
                        rateCurrency: formData.rateCurrency,
                        items: formData.items,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            showMessage("success", "Invoice berhasil dibuat").then(() => {
                                location.reload();
                            });
                        } else {
                            showMessage('error', 'Gagal menyimpan invoice');
                        }
                    },
                    error: function (xhr, status, error) {
                        showMessage('error', 'Terjadi kesalahan');
                    }
                });
            }
        });

    });
</script>
@endsection
