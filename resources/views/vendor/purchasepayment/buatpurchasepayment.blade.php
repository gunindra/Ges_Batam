@extends('layout.main')

@section('title', 'Buat Payment')

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

<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Payment</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Vendor</li>
            <li class="breadcrumb-item"><a href="{{ route('purchasePayment') }}">Payment</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Payment</li>
        </ol>
    </div>

    <a class="btn btn-primary mb-3" href="{{ route('purchasePayment') }}">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-3">
                                <label for="Invoice" class="form-label fw-bold">Vendor</label>
                                <select class="form-control select2" id="selectVendor">
                                    <option value="" selected disabled>Pilih Vendor</option>
                                    @foreach ($listVendor as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                                <div id="errVendorPayment" class="text-danger mt-1 d-none">Silahkan Pilih Vendor</div>
                            </div>
                            <div class="mt-3">
                                <label for="Invoice" class="form-label fw-bold">No. Voucher</label>
                                <select id="selectInvoice" name="invoices[]" class="form-control" multiple>
                                </select>
                                <div id="errInvoicePayment" class="text-danger mt-1 d-none">Silahkan Pilih No. Voucher
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="tanggalPayment" class="form-label fw-bold">Tanggal Payment</label>
                                <input type="text" class="form-control" id="tanggalPayment" placeholder="Pilih Tanggal">
                                <div id="errTanggalPayment" class="text-danger mt-1 d-none">Silahkan isi Tanggal</div>
                            </div>
                            <div class="mt-3">
                                <label for="amountPayment" class="form-label fw-bold">Payment Amount</label>
                                <input type="number" class="form-control" id="payment"
                                    placeholder="Masukkan nominal pembayaran" required>
                                <div id="errAmountPayment" class="text-danger mt-1 d-none">Silahkan isi Amount</div>
                            </div>
                            <div class="mt-3">
                                <label for="paymentMethod" class="form-label fw-bold">Metode Pembayaran</label>
                                <select class="form-control select2" id="selectMethod">
                                    <option value="" selected disabled>Pilih Metode Pembayaran</option>
                                    @foreach ($coas as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->code_account_id }} -
                                            {{ $coa->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="errMethodPayment" class="text-danger mt-1 d-none">Silahkan Pilih Metode</div>
                            </div>
                            <div class="input-group mt-3">
                                <label for="keteranganPaymentSup" class="form-label fw-bold p-1">Keterangan</label>
                            </div>
                            <textarea id="keteranganPaymentSup" class="form-control" aria-label="With textarea"
                                placeholder="Masukkan keterangan" rows="4"></textarea>
                        </div>

                        <div class="col-md-6">
                            <h5 class="fw-bold mt-3">Preview Invoice</h5>
                            <div id="invoicePreview" class="border p-4 rounded mt-3 shadow-sm"
                                style="background-color: #f9f9f9;">
                                <p><strong class="text-primary">Nomor Invoice :</strong> <span
                                        id="previewInvoiceNumber">-</span></p>
                                <p><strong class="text-primary">Tanggal Invoice :</strong> <span
                                        id="previewInvoiceDate">-</span></p>
                                <p><strong class="text-primary">Status Invoice :</strong> <span
                                        id="previewInvoiceStatus">-</span></p>
                                <p><strong class="text-primary">Jumlah Amount :</strong> <span id="previewInvoiceAmount"
                                        class="fw-bold text-success">-</span></p>
                                <p><strong class="text-primary">Total Sudah Bayar :</strong> <span id="previewTotalPaid"
                                        class="fw-bold text-info">-</span></p>
                                <p><strong class="text-primary">Sisa Pembayaran :</strong> <span
                                        id="previewRemainingPayment" class="fw-bold text-danger">-</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="divider mt-4">
                        <span>Manual Jurnal</span>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Code Account</th>
                                    <th>Tipe Account</th>
                                    <th>Description</th>
                                    <th>Nominal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="items-container">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-primary" id="add-item-button">Add
                                            Item</button>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <label>Total:</label>
                                        <input type="text" class="form-control" id="total_payment" name="total_debit"
                                            value="" disabled>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <td colspan="4">
                                        <div class="col-5 mt-3">
                                            <label for="keteranganPayment" class="form-label fw-bold">Keterangan</label>
                                            <textarea id="keteranganPayment" class="form-control"
                                                aria-label="With textarea" placeholder="Masukkan keterangan"
                                                rows="4"></textarea>
                                        </div>
                                    </td>
                                </tr> --}}
                            </tfoot>
                        </table>
                        <div id="tableError" class="alert alert-danger d-none">
                            Harap isi semua kolom di tabel sebelum melanjutkan.
                        </div>
                    </div>
                    <div class="col-12 mt-4 mb-5">
                        <div class="col-4 float-right">
                            <button id="buatPayment" class="btn btn-primary p-3" style="width: 100%;">Buat
                                Payment</button>
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
        $('#selectVendor').on('change', function () {
            const idVendor = $(this).val();
            if (idVendor) {
                loadInvoicesByVendor(idVendor);
            } else {
                $('#selectInvoice').empty().append('<option value="" disabled>Select a vendor first</option>');
            }
        });

        function loadInvoicesByVendor(idVendor) {
            $.ajax({
                url: "{{ route('getInoviceByVendor') }}",
                type: 'GET',
                data: {
                    idVendor: idVendor
                },
                beforeSend: function () {
                    $('#selectInvoice').html('<option value="" disabled>Loading...</option>');
                },
                success: function (response) {
                    const $selectInvoice = $('#selectInvoice').empty();
                    if (response.success) {
                        response.invoices.forEach(invoice => {
                            $selectInvoice.append(
                                `<option value="${invoice.invoice_no}">${invoice.invoice_no}</option>`
                            );
                        });
                    } else {
                        $selectInvoice.append('<option value="" disabled>No Voucher available</option>');
                    }
                },
                error: function () {
                    showMessage('error!', 'Failed to load Voucher.');
                    $('#selectInvoice').empty().append(
                        '<option value="" disabled>Error loading Voucher</option>');
                }
            });
        }

        var today = new Date();
        $('#tanggalPayment').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        }).datepicker('setDate', today);

        $('.select2').select2();
        $('#selectInvoice').select2({
            placeholder: "Pilih Voucher",
            allowClear: true
        });

        $('#selectInvoice').on('change', function () {
            var invoiceNo = $(this).val();
            if (invoiceNo) {
                $.ajax({
                    url: "{{ route('getSupInvoiceAmount') }}",
                    type: 'GET',
                    data: {
                        no_invoice: invoiceNo
                    },
                    success: function (response) {
                        if (response.success) {

                            $('#previewInvoiceNumber').text(
                                response.data.invoice_numbers.replaceAll(';', ', ')
                            );
                            $('#previewInvoiceAmount').text(response.data.total_harga);
                            $('#previewTotalPaid').text(response.data.total_bayar);
                            $('#previewRemainingPayment').text(response.data
                                .sisa_bayar);
                        } else {
                            // alert(response.message || 'Data tidak ditemukan');
                            resetPreview();
                        }
                    },
                });
            } else {
                resetPreview();
            }
        });

        function resetPreview() {
            $('#previewInvoiceNumber').text('-');
            $('#previewInvoiceAmount').text('-');
            $('#previewTotalPaid').text('-');
            $('#previewRemainingPayment').text('-');
        }

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
                         <select class="form-control" name="tipeAccount" id="tipeAccount" required>
                            <option value="" disabled>Pilih Akun</option>
                            <option value="Credit">Credit</option>
                            <option value="Debit">Debit</option>
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
            $('.removeItemButton').show();
            updateTotals();
        });

        $(document).on('click', '.removeItemButton', function () {
            if ($('#items-container tr').length > 0) {
                $(this).closest('tr').remove();
            }

            if ($('#items-container tr').length === 0) {
                $('.removeItemButton').hide();
            }

            updateTotals();
        });

        // Fungsi untuk menghitung total
        function updateTotals() {
            let totalDebit = 0;
            $('#items-container tr').each(function () {
                const debitValue = parseFloat($(this).find('input[name="debit"]').val()) || 0;
                totalDebit += debitValue;
            });
            const paymentAmount = parseFloat($('#payment').val()) || 0;
            // const discountPayment = parseFloat($('#discountPayment').val()) || 0;

            const grandTotal = totalDebit + paymentAmount;
            $('#total_payment').val(grandTotal.toFixed(0));
        }

        $(document).on('input', 'input[name="debit"]', function () {
            updateTotals();
        });

        $('#payment').on('input', updateTotals);


        $('#buatPayment').click(function (e) {
            e.preventDefault();
            var invoice = $('#selectInvoice').val();
            var tanggalPayment = $('#tanggalPayment').val();
            var paymentAmount = $('#payment').val();
            var paymentMethod = $('#selectMethod').val();
            var keteranganPaymentSup = $('#keteranganPaymentSup').val();
            let totalAmmount = parseFloat($('#total_payment').val()) || 0;

            let valid = true;
            if (!invoice) {
                $('#errInvoicePayment').removeClass('d-none');
                valid = false;
            }
            if (!tanggalPayment) {
                $('#errTanggalPayment').removeClass('d-none');
                valid = false;
            }
            if (!paymentAmount) {
                $('#errAmountPayment').removeClass('d-none');
                valid = false;
            }
            if (!paymentMethod) {
                $('#errMethodPayment').removeClass('d-none');
                valid = false;
            }

            let items = [];
            $('#items-container tr').each(function () {
                let account = $(this).find('select[name="account"]').val();
                let itemDesc = $(this).find('input[name="item_desc"]').val();
                let debit = $(this).find('input[name="debit"]').val();
                let tipeAccount = $(this).find('select[name="tipeAccount"]').val();

                if (!account || !itemDesc || !debit || !tipeAccount) {
                    valid = false;
                }

                if (!valid) {
                    $('#tableError').removeClass('d-none');
                } else {
                    $('#tableError').addClass('d-none');
                }

                items.push({
                    account: account,
                    item_desc: itemDesc,
                    debit: debit,
                    tipeAccount: tipeAccount
                });
            });

            if (valid) {
                $.ajax({
                    url: "{{ route('paymentSup') }}",
                    method: 'POST',
                    data: {
                        invoice: invoice,
                        tanggalPayment: tanggalPayment,
                        paymentAmount: paymentAmount,
                        paymentMethod: paymentMethod,
                        items: items,
                        keteranganPaymentSup: keteranganPaymentSup,
                        totalAmmount: totalAmmount,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            showMessage("success", "Payment berhasil dibuat").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        let responseJSON = xhr.responseJSON;
                        if (responseJSON && responseJSON.message) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: responseJSON.message
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: 'Error tidak diketahui terjadi'
                            });
                        }
                    }
                });
            }
        });
    </script>
    @endsection