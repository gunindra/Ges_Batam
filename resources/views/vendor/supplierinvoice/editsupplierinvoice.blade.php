@extends('layout.main')

@section('title', 'Update Invoice')

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
            <h1 class="h3 mb-0 text-gray-800">Update Invoice</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Vendor</li>
                <li class="breadcrumb-item"><a href="{{ route('supplierInvoice') }}">Invoice</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Invoice</li>
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
                        <input type="hidden" id="invoiceId" value="{{ $invoice->id }}">
                        <div class="d-flex flex-row">
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="noInvoice" class="form-label fw-bold">No. Voucher :</label>
                                    <div class="d-flex">
                                        <input type="text" id="noInvoice" class="form-control col-8"
                                            value="{{ $invoice->invoice_no }}">
                                        <a class="pt-2" id="btnRefreshInvoice" href="">
                                            <span class="pl-2 text-success"><i class="fas fa-sync-alt"></i></span>
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="tanggalVendor" class="form-label fw-bold">Tanggal</label>
                                    <input type="text" class="form-control col-8" id="tanggalVendor"
                                        value="{{ \Carbon\Carbon::parse($invoice->tanggal)->translatedFormat('d F Y') }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="currencyInvoice" class="form-label fw-bold">Currency</label>
                                    <select class="form-control col-8" id="currencyInvoice">
                                        <option value="" disabled>Pilih Currency</option>
                                        @foreach ($listCurrency as $currency)
                                            <option value="{{ $currency->id }}"
                                                {{ $currency->id == $invoice->matauang_id ? 'selected' : '' }}>
                                                {{ $currency->nama_matauang }} ({{ $currency->singkatan_matauang }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3" id="rateCurrencySection"
                                    style="display: {{ $invoice->rate_matauang ? 'block' : 'none' }};">
                                    <label for="rateCurrency" class="form-label fw-bold">Rate Currency</label>
                                    <input type="text" class="form-control col-8" id="rateCurrency"
                                        value="{{ $invoice->rate_matauang }}">
                                </div>
                            </div>
                        </div>

                        <div class="divider mt-4">
                            <span>Vendor</span>
                        </div>
                        <div class="d-flex">
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="selectVendor" class="form-label fw-bold col-12">Vendor</label>
                                    <select class="form-control select2singgle" id="selectVendor" style="width: 67%">
                                        <option value="" disabled>Pilih Vendor</option>
                                        @foreach ($listVendor as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ $id == $invoice->vendor_id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="noReferenceVendor" class="form-label fw-bold">No. Invoice Vendor</label>
                                    <input type="text" class="form-control col-8" id="noReferenceVendor"
                                        value="{{ $invoice->no_ref }}">
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
                                @foreach ($invoice->items->filter(fn($item) => $item->debit > 0) as $item)
                                    <tr>
                                        <td>
                                            <select class="form-control select2singgle" name="account" style="width: 30vw;"
                                                required>
                                                <option value="">Pilih Akun</option>
                                                @foreach ($coas as $coa)
                                                    <option value="{{ $coa->id }}"
                                                        {{ $coa->id == $item->coa_id ? 'selected' : '' }}>
                                                        {{ $coa->code_account_id }} - {{ $coa->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="item_desc"
                                                value="{{ $item->description }}" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="debit"
                                                value="{{ $item->debit }}" required>
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
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
                                        <input type="text" class="form-control-flush" id="total_debit" name="total_debit"
                                            value="{{ $invoice->total_harga }}" disabled>
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="col-12 mt-5">
                            <div class="col-4 float-right">
                                <button id="updateJournal" class="btn btn-primary p-3 float-right mt-3"
                                    style="width: 80%;">Update Invoice</button>
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
            const invoiceId = $('#invoiceId').val();

            const loadSpin = `<div class="d-flex justify-content-center align-items-center pl-5">
                    <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
                 </div>`;


            // function generateInvoice() {
            //     $.ajax({
            //         url: "{{ route('generateSupInvoice') }}",
            //         type: 'GET',
            //         dataType: 'json',
            //         beforeSend: function() {
            //             $('#noInvoice').html(loadSpin);
            //         },
            //         success: function(response) {
            //             if (response.status === 'success') {
            //                 $('#noInvoice').val(response.no_invoice);
            //             } else {
            //                 showMessage("error", "Gagal mendapatkan nomor invoice.")
            //             }
            //         },
            //         error: function(xhr, status, error) {
            //             showMessage("error", "Terjadi kesalahan:" + error);
            //         },
            //         complete: function() {
            //             $('#noInvoice').find('.spinner-border').remove();
            //         }
            //     });
            // }

            // generateInvoice();

            // $('#btnRefreshInvoice').on('click', function(e) {
            //     e.preventDefault();
            //     generateInvoice();
            // });

            $('.select2singgle').select2({
                width: 'resolve'
            });

            $('#tanggalVendor').datepicker({
                format: 'dd MM yyyy',
                todayBtn: 'linked',
                todayHighlight: true,
                autoclose: true,
                language: 'id'
            }).on('changeDate', function(e) {
                $(this).trigger('change');
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

                $('#items-container tr').each(function() {
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

            $('#updateJournal').click(function(e) {
                e.preventDefault();
                let formData = getFormValues();
                let isValid = true;

                // Validasi
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

                if (isValid) {
                    $.ajax({
                        url: `/vendor/supplierInvoice/update/${invoiceId}`,
                        type: 'PUT',
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
                        success: function(response) {
                            if (response.status === 'success') {
                                showMessage("success", "Invoice berhasil diperbarui").then(
                                    () => {
                                        location.reload();
                                    });
                            } else {
                                showMessage('error', 'Gagal memperbarui invoice');
                            }
                        },
                        error: function() {
                            showMessage('error', 'Terjadi kesalahan saat memperbarui invoice');
                        }
                    });
                }
            });

            $('#add-item-button').click(function() {
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

            $(document).on('click', '.removeItemButton', function() {
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
                $('#items-container tr').each(function() {
                    let debitValue = parseFloat($(this).find('input[name="debit"]').val()) || 0;
                    totalDebit += debitValue;
                });
                $('#total_debit').val(totalDebit.toFixed(0));
            }

            $(document).on('input', 'input[name="debit"]', function() {
                updateTotals();
            });


        });
    </script>
@endsection
