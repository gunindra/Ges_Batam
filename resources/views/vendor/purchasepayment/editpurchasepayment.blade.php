@extends('layout.main')

@section('title', 'Edit Payment')

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
        <h1 class="h3 mb-0 text-gray-800">Edit Payment</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Vendor</li>
            <li class="breadcrumb-item"><a href="{{ route('purchasePayment') }}">Payment</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Payment</li>
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
                                <select class="form-control select2" id="selectVendor" disabled>
                                    @foreach ($listVendor as $vendor)
                                        <option value="{{ $vendor->id }}" {{ $vendor->id == $selectedVendorId ? 'selected' : '' }}>
                                            {{ $vendor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="errVendorPayment" class="text-danger mt-1 d-none">Silahkan Pilih Vendor</div>
                            </div>
                            <div class="mt-3">
                                <label for="Invoice" class="form-label fw-bold">No. Voucher</label>
                                <select id="selectInvoice" name="invoices[]" class="form-control" multiple disabled>
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
                                        <option value="{{ $coa->id }}" {{ $coa->id == $payment->paymentMethod->id ? 'selected' : '' }}>
                                            {{ $coa->code_account_id }} - {{ $coa->name }}
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
                    </div>
                    <div class="col-12 mt-4 mb-5">
                        <div class="col-4 float-right">
                            <button id="editPayment" class="btn btn-primary p-3" style="width: 100%;">Edit
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
        $(document).ready(function () {
            var payment = @json($payment);

            $('#selectVendor').on('change', function () {
                const name = $(this).val();
                let invoiceId = payment.payment_invoices_sup.map(invoice => invoice.invoice_id);

                if (name) {
                    loadInvoicesByName(name, invoiceId);
                }
            });

            $('#selectInvoice').select2({
                placeholder: "Pilih Invoice",
                allowClear: true,
                width: 'resolve',
                closeOnSelect: false
            });


            $('.select2').select2();
            $('#tanggalPayment').datepicker({
                format: 'dd MM yyyy',
                todayBtn: 'linked',
                todayHighlight: true,
                autoclose: true,
            });

            $('#tanggalPayment').val(payment.payment_date).trigger('change');
            $('#keteranganPaymentSup').val(payment.Keterangan).trigger('change');
            let totalAmount = payment.payment_invoices_sup
                .reduce((total, item) => {
                    return total + parseFloat(item.amount);
                }, 0);
            $('#payment').val(totalAmount).trigger('change');
            $('#selectMethod').trigger('change');



            const paymentDate = new Date(payment.payment_date);
            $('#tanggalPayment').datepicker({
                format: 'dd MM yyyy',
                todayBtn: 'linked',
                todayHighlight: true,
                autoclose: true,
            }).datepicker('setDate', paymentDate);


            function loadInvoicesByName(name, invoiceId) {
                $.ajax({
                    url: "{{ route('getInvoiceByNameEdit') }}",
                    type: 'GET',
                    data: {
                        name,
                        invoiceId
                    },
                    success: function (response) {
                        const $selectInvoice = $('#selectInvoice').empty();
                        let selectedInvoices = [];

                        if (response.success) {
                            response.invoices.forEach(invoice_no => {
                                $selectInvoice.append(
                                    `<option value="${invoice_no}">${invoice_no}</option>`
                                );
                                selectedInvoices.push(
                                    invoice_no);
                            });

                            $selectInvoice.val(selectedInvoices).trigger('change');
                        } else {
                            $selectInvoice.append(
                                '<option value="" disabled>No invoices available</option>'
                            );
                        }
                    },
                    error: function () {
                        showMessage('error!', 'Gagal memuat invoice.');
                    }
                });
            }
            $('#selectVendor').trigger('change');
        });
    </script>
    @endsection