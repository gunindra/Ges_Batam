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
            <li class="breadcrumb-item">Customer</li>
            <li class="breadcrumb-item"><a href="{{ route('payment') }}">Payment</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Payment</li>
        </ol>
    </div>

    <a class="btn btn-primary mb-3" href="{{ route('payment') }}">
        <i class="fas fa-arrow-left"></i> Back
    </a>

    <div class="card mb-4">
        <div class="card-body">
            <!-- Input Form -->
            <div class="row">
                <!-- Column 1 -->
                <div class="col-lg-6">
                    <div class="form-group mt-3">
                        <label for="KodePayment" class="form-label fw-bold">Kode</label>
                        <input type="text" class="form-control" id="KodePayment" placeholder="Masukkan Kode anda">
                        <div id="errKodePayment" class="text-danger mt-1 d-none">Silahkan isi Kode</div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="Marking" class="form-label fw-bold">Marking</label>
                        <select class="form-control select2" id="selectMarking">
                            <option value="" selected disabled>Pilih Marking</option>

                        </select>
                        <div id="errMarkingPayment" class="text-danger mt-1 d-none">Silahkan Pilih Marking</div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="Invoice" class="form-label fw-bold">Invoice</label>
                        <select class="form-control" id="selectInvoice" multiple></select>
                        <div id="errInvoicePayment" class="text-danger mt-1 d-none">Silahkan Pilih Invoice</div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="tanggalPayment" class="form-label fw-bold">Tanggal Payment</label>
                        <input type="text" class="form-control" id="tanggalPayment">
                        <div id="errTanggalPayment" class="text-danger mt-1 d-none">Silahkan isi Tanggal</div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="tanggalPaymentBuat" class="form-label fw-bold">Tanggal Buat</label>
                        <input type="text" class="form-control" id="tanggalPaymentBuat" disabled>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="col-lg-6">
                    <div class="form-group mt-3">
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

                    <div class="form-group mt-3 d-none" id="section_poin">
                        <label for="amountPoin" class="form-label fw-bold">Poin (Kg)</label>
                        <input type="number" class="form-control" id="amountPoin" placeholder="Masukkan nominal poin">
                        <div id="erramountPoin" class="text-danger mt-1 d-none">Silahkan isi nominal Poin</div>
                        <button type="button" class="btn btn-primary mt-2" id="submitAmountPoin">Hitung
                            Payment</button>
                    </div>

                    <div class="form-group mt-3">
                        <label for="amountPayment" class="form-label fw-bold">Payment Amount</label>
                        <input type="number" class="form-control" id="payment" placeholder="Masukkan nominal pembayaran"
                            required>
                        <div id="errAmountPayment" class="text-danger mt-1 d-none">Silahkan isi Amount</div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="discountPayment" class="form-label fw-bold">Discount</label>
                        <input type="number" class="form-control" id="discountPayment" placeholder="Masukkan discount"
                            required>
                    </div>

                    <div class="input-group mt-3">
                        <label for="keteranganPayment" class="form-label fw-bold p-1">Keterangan</label>
                    </div>
                    <textarea id="keteranganPayment" class="form-control" aria-label="With textarea"
                        placeholder="Masukkan keterangan" rows="4"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Invoice -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="fw-bold">Preview Invoice</h5>
            <div id="invoicePreview" class="border p-4 rounded shadow-sm" style="background-color: #f9f9f9;">
                <p><strong class="text-primary">Nomor Invoice :</strong> <span id="previewInvoiceNumber">-</span></p>
                <p><strong class="text-primary">Total Berat (Kg) :</strong> <span id="previewTotalWeight">-</span></p>
                <p><strong class="text-primary">Total Dimensi (m³) :</strong> <span id="previewTotalDimension">-</span>
                </p>
                <p><strong class="text-primary">Jumlah Amount :</strong> <span id="previewInvoiceAmount"
                        class="fw-bold text-success">-</span></p>
                <p><strong class="text-primary">Total Sudah Bayar :</strong> <span id="previewTotalPaid"
                        class="fw-bold text-info">-</span></p>
                <p><strong class="text-primary">Sisa Pembayaran :</strong> <span id="previewRemainingPayment"
                        class="fw-bold text-danger">-</span></p>
            </div>
        </div>
    </div>

    <!-- Detail Invoice -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="fw-bold">Jurnal Manual Payment</h5>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Code Account</th>
                        <th>Description</th>
                        <th>Nominal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="items-container"></tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">
                            <button type="button" class="btn btn-primary" id="add-item-button">Add Item</button>
                        </td>
                        <td>
                            <label>Total Payment:</label>
                            <input type="text" class="form-control" id="total_payment" name="total_payment" value=""
                                disabled>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="text-center my-4">
        <button id="editPayment" class="btn btn-primary p-3 w-50">Edit Payment</button>
    </div>
</div>
<!---Container Fluid-->


@endsection
@section('script')
<script>
    $(document).ready(function () {
        var payment = @json($payment);
        var coas = @json($coas);
        console.log('ini data payment',payment)
        console.log('ini data coa',coas)

        $('.select2').select2();
        $('#tanggalPayment, #tanggalPaymentBuat').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        });

        $('#KodePayment').val(payment.kode_pembayaran).trigger('change');
        $('#selectMarking').val(payment.pembeli_id).trigger('change');
        $('#tanggalPayment').val(payment.payment_date).trigger('change');
        $('#tanggalPaymentBuat').val(payment.payment_buat).trigger('change');
        $('#selectMethod').val(payment.payment_method_id).trigger('change');
        $('#discountPayment').val(payment.discount).trigger('change');
        $('#keteranganPayment').val(payment.Keterangan).trigger('change');
        $('#payment').val(payment.payment_invoices.amount).trigger('change');


        const paymentDate = new Date(payment.payment_date);
        $('#tanggalPayment').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        }).datepicker('setDate', paymentDate);
        
        flatpickr("#tanggalPaymentBuat", {
            enableTime: true,
            dateFormat: "d F Y H:i",
            defaultDate: new Date(),
            minuteIncrement: 1,
            time_24hr: true,
            locale: "id",
        });

    });

</script>
@endsection