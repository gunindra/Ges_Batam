@extends('layout.main')

@section('title', 'Buat Payment')

@section('main')

<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Payment</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Customer</li>
            <li class="breadcrumb-item"><a href="{{ route('payment') }}">Payment</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Payment</li>
        </ol>
    </div>

    <a class="btn btn-primary mb-3" href="{{ route('payment') }}">
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
                                <label for="Invoice" class="form-label fw-bold">Invoice</label>
                                <select class="form-control select2singgle" id="selectInvoice">
                                    <option value="" selected disabled>Pilih Invoice</option>
                                    <option value="">001</option>
                                    <option value="">002</option>
                                </select>
                                <div id="errInvoicePayment" class="text-danger mt-1 d-none">Silahkan Pilih Invoice</div>
                            </div>
                            <div class="mt-3">
                                <label for="" class="form-label fw-bold">Tanggal Payment</label>
                                <input type="input" class="form-control" id="tanggalPayment" value="">
                                <div id="errTanggalPayment" class="text-danger mt-1 d-none">Silahkan isi Tanggal
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="amountPayment" class="form-label fw-bold">Payment Amount</label>
                                <input type="number" class="form-control" name="Payment[]" value="0" placeholder="0.00"
                                    required>
                                <div id="errAmountPayment" class="text-danger mt-1 d-none">Silahkan isi Amount
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="paymentMethod" class="form-label fw-bold">Payment Method</label>
                                <select class="form-control select2singgle" id="selectMethod">
                                    <option value="" selected disabled>Pilih Method</option>
                                    <option value="">BCA</option>
                                    <option value="">BNI</option>
                                </select>
                                <div id="errMethodPayment" class="text-danger mt-1 d-none">Silahkan Pilih Method</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-4 mb-5">
                    <div class="col-4 float-right">
                        <button id="buatPayment" class="btn btn-primary p-3 float-right mt-3" style="width: 100%;">Buat
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
    
    var today = new Date();
            $('#tanggalPayment').datepicker({
                format: 'dd MM yyyy',
                todayBtn: 'linked',
                todayHighlight: true,
                autoclose: true,
            }).datepicker('setDate', today);

</script>
@endsection