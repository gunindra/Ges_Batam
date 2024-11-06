@extends('layout.main')

@section('title', 'Buat Payment')

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
                                    <select class="form-control select2" id="selectInvoice">
                                        <option value="" selected disabled>Pilih Invoice</option>
                                        @foreach ($listInvoice as $invoice)
                                            <option value="{{ $invoice->no_invoice }}">{{ $invoice->no_invoice }}</option>
                                        @endforeach
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
                                    <label for="paymentMethod" class="form-label fw-bold">Metode Pembayaran</label>
                                    <select class="form-control select2" id="selectMethod">
                                        <option value="" selected disabled>Pilih Metode Pembayaran</option>
                                        @foreach ($coas as $coa)
                                            <option value="{{ $coa->id }}">{{ $coa->code_account_id }} -
                                                {{ $coa->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="errMethodPayment" class="text-danger mt-1 d-none">Silahkan Pilih Metode</div>
                                </div>
                                <div class="mt-3 d-none" id="section_poin">
                                    <label for="" class="form-label fw-bold">Poin (Kg)</label>
                                    <input type="number" class="form-control" id="amountPoin"
                                        placeholder="Masukkan nominal poin" value="">
                                    <div id="erramountPoin" class="text-danger mt-1 d-none">Silahkan isi masukkan nominal
                                        Poin</div>
                                    <button type="button" class="btn btn-primary mt-2" id="submitAmountPoin">Hitung
                                        Payment</button>
                                </div>
                                <div class="mt-3">
                                    <label for="amountPayment" class="form-label fw-bold">Payment Amount</label>
                                    <input type="number" class="form-control" id="payment" name="" value=""
                                        placeholder="Masukkan nominal pembayaran" required>
                                    <div id="errAmountPayment" class="text-danger mt-1 d-none">Silahkan isi Amount</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <h5 class="fw-bold mt-3">Preview Invoice</h5>
                                <div id="invoicePreview" class="border p-4 rounded mt-3 shadow-sm"
                                    style="background-color: #f9f9f9;">
                                    <p><strong class="text-primary">Nomor Invoice :</strong> <span
                                            id="previewInvoiceNumber">-</span></p>
                                    <p><strong class="text-primary">Tanggal Invoice :</strong> <span
                                            id="previewInvoiceDate">-</span></p>
                                    <p><strong class="text-primary">Status Invoice :</strong> <span
                                            id="previewInvoiceStatus">-</span></p>
                                    <p><strong class="text-primary">Total Berat (Kg) :</strong> <span
                                            id="previewTotalWeight">-</span> (<span id="previewCountWeight">0</span> Resi)
                                    </p>
                                    <p><strong class="text-primary">Total Dimensi :</strong> <span
                                            id="previewTotalDimension">-</span> (<span id="previewCountDimension">0</span>
                                        Resi)</p>
                                    <p><strong class="text-primary">Jumlah Amount :</strong> <span id="previewInvoiceAmount"
                                            class="fw-bold text-success">-</span></p>
                                    <p><strong class="text-primary">Total Sudah Bayar :</strong> <span id="previewTotalPaid"
                                            class="fw-bold text-info">-</span></p>
                                    <p><strong class="text-primary">Sisa Pembayaran :</strong> <span
                                            id="previewRemainingPayment" class="fw-bold text-danger">-</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4 mb-5">
                        <div class="col-4 float-right">
                            <button id="buatPayment" class="btn btn-primary p-3 float-right mt-3"
                                style="width: 100%;">Buat
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
        $('.select2').select2();

        var today = new Date();
        $('#tanggalPayment').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        }).datepicker('setDate', today);


        $(document).ready(function() {
            $('#selectInvoice').on('change', function() {
                var invoiceNo = $(this).val();

                if (invoiceNo) {
                    $.ajax({
                        url: "{{ route('getInvoiceAmount') }}",
                        type: 'GET',
                        data: {
                            no_invoice: invoiceNo
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#previewInvoiceNumber').text(response.invoice[0]
                                    .no_invoice || '-');
                                $('#previewInvoiceAmount').text(response.invoice[0]
                                    .total_harga || '0');
                                $('#previewInvoiceDate').text(response.invoice[0]
                                    .tanggal_bayar || '-');
                                $('#previewInvoiceStatus').text(response.invoice[0]
                                    .status_name || '-');
                                $('#previewTotalPaid').text(response.invoice[0].total_bayar ||
                                    '0');
                                $('#previewRemainingPayment').text(response.invoice[0]
                                    .sisa_bayar || '0');
                                $('#previewTotalWeight').text(response.invoice[0].total_berat ||
                                    '0');
                                $('#previewTotalDimension').text(response.invoice[0]
                                    .total_dimensi || '0');
                                $('#previewCountWeight').text(response.invoice[0].count_berat ||
                                    '0');
                                $('#previewCountDimension').text(response.invoice[0]
                                    .count_dimensi || '0'
                                    );
                            } else {
                                showMessage("error", "Data tidak ditemukan");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            });


            $('#selectMethod').on('change', function() {
                const selectedMethod = $(this).val();
                const sectionPoin = $('#section_poin');
                const paymentInput = $('#payment');

                if (selectedMethod === "167") {
                    sectionPoin.removeClass("d-none");
                    paymentInput.prop("disabled", true);
                } else {
                    sectionPoin.addClass("d-none");
                    paymentInput.prop("disabled", false);
                    $('#amountPoin').val("");
                    paymentInput.val("")
                }
            });

            $('#submitAmountPoin').on('click', function() {
                let invoiceNo = $('#selectInvoice').val();
                let amountPoin = $('#amountPoin').val();

                if (!amountPoin) {
                    $('#payment').val('');
                    Swal.fire({
                        icon: 'error',
                        title: 'Silahkan masukkan nominal poin terlebih dahulu.',
                    });
                    return;
                }

                if (!invoiceNo) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Silakan pilih nomor invoice terlebih dahulu.',
                    });
                    $('#amountPoin').val('');
                    $('#payment').val('');
                    return;
                }

                $.ajax({
                    url: "{{ route('amountPoin') }}",
                    type: 'GET',
                    data: {
                        amountPoin: amountPoin,
                        invoiceNo: invoiceNo
                    },
                    success: function(response) {
                        if (response.total_nominal) {
                            $('#payment').val(response.total_nominal);
                        } else {
                            console.log('Data tidak ditemukan');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON.error
                            });
                            $('#payment').val('');
                        } else {
                            console.error('Terjadi kesalahan:', error);
                        }
                    }
                });
            });
        });

        $('#buatPayment').click(function(e) {
            e.preventDefault();

            var invoice = $('#selectInvoice').val();
            var tanggalPayment = $('#tanggalPayment').val();
            var paymentAmount = $('#payment').val();
            var paymentMethod = $('#selectMethod').val();
            var amountPoin = $('#amountPoin').val();

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

            if (valid) {
                $.ajax({
                    url: "{{ route('buatpembayaran') }}",
                    method: 'POST',
                    data: {
                        invoice: invoice,
                        tanggalPayment: tanggalPayment,
                        paymentAmount: paymentAmount,
                        paymentMethod: paymentMethod,
                        amountPoin: amountPoin,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            showMessage("success", "Payment berhasil dibuat").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response
                                    .message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        let responseJSON = xhr.responseJSON;
                        if (responseJSON && responseJSON.message) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: responseJSON
                                    .message
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
