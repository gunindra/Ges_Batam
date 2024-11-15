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
                <div class="row">
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
                                @foreach ($listMarking as $markingList)
                                    <option value="{{ $markingList->marking }}">{{ $markingList->marking }}</option>
                                @endforeach
                            </select>
                            <div id="errMarkingPayment" class="text-danger mt-1 d-none">Silahkan Pilih Marking</div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="Invoice" class="form-label fw-bold">Invoice</label>
                            <select class="form-control select2" id="selectInvoice">
                                <option value="" selected disabled>Pilih Invoice</option>
                            </select>
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
                        <div class="form-group mt-3">
                            <label for="paymentMethod" class="form-label fw-bold">Metode Pembayaran</label>
                            <select class="form-control select2" id="selectMethod">
                                <option value="" selected disabled>Pilih Metode Pembayaran</option>
                                @foreach ($coas as $coa)
                                    <option value="{{ $coa->id }}">{{ $coa->code_account_id }} - {{ $coa->name }}</option>
                                @endforeach
                            </select>
                            <div id="errMethodPayment" class="text-danger mt-1 d-none">Silahkan Pilih Metode</div>
                        </div>
                        <div class="form-group mt-3 d-none" id="section_poin">
                            <label for="amountPoin" class="form-label fw-bold">Poin (Kg)</label>
                            <input type="number" class="form-control" id="amountPoin" placeholder="Masukkan nominal poin">
                            <div id="erramountPoin" class="text-danger mt-1 d-none">Silahkan isi nominal Poin</div>
                            <button type="button" class="btn btn-primary mt-2" id="submitAmountPoin">Hitung Payment</button>
                        </div>
                        <div class="form-group mt-3">
                            <label for="amountPayment" class="form-label fw-bold">Payment Amount</label>
                            <input type="number" class="form-control" id="payment" placeholder="Masukkan nominal pembayaran" required>
                            <div id="errAmountPayment" class="text-danger mt-1 d-none">Silahkan isi Amount</div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="discountPayment" class="form-label fw-bold">Discount</label>
                            <input type="number" class="form-control" id="discountPayment" placeholder="Masukkan discount" required>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <h5 class="fw-bold mt-3">Preview Invoice</h5>
                        <div id="invoicePreview" class="border p-4 rounded mt-3 shadow-sm" style="background-color: #f9f9f9;">
                            <p><strong class="text-primary">Nomor Invoice :</strong> <span id="previewInvoiceNumber">-</span></p>
                            <p><strong class="text-primary">Tanggal Invoice :</strong> <span id="previewInvoiceDate">-</span></p>
                            <p><strong class="text-primary">Status Invoice :</strong> <span id="previewInvoiceStatus">-</span></p>
                            <p><strong class="text-primary">Total Berat (Kg) :</strong> <span id="previewTotalWeight">-</span></p>
                            <p><strong class="text-primary">Jumlah Amount :</strong> <span id="previewInvoiceAmount" class="fw-bold text-success">-</span></p>
                            <p><strong class="text-primary">Total Sudah Bayar :</strong> <span id="previewTotalPaid" class="fw-bold text-info">-</span></p>
                            <p><strong class="text-primary">Sisa Pembayaran :</strong> <span id="previewRemainingPayment" class="fw-bold text-danger">-</span></p>
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
                       
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <button type="button" class="btn btn-primary" id="add-item-button">Add Item</button>
                            </td>
                            <td>
                                <label>Total:</label>
                                <input type="text" class="form-control" id="total_debit" name="total_debit" value="" disabled>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="col-12 mt-4 mb-5">
                    <div class="col-lg-4 ml-auto">
                        <button id="buatPayment" class="btn btn-primary p-3 w-100 mt-3">Buat Payment</button>
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

    $('#selectMarking').on('change', function () {
        var marking = $(this).val();

        if (marking) {
            $.ajax({
                url: "{{ route('getInvoiceByMarking') }}",
                type: 'GET',
                data: {
                    marking: marking
                },
                success: function (response) {
                    if (response.success) {

                        $('#selectInvoice').empty();
                        $('#selectInvoice').append('<option value="" selected disabled>Pilih Invoice</option>');

                        $.each(response.invoices, function (index, invoice) {
                            $('#selectInvoice').append('<option value="' + invoice.no_invoice + '">' + invoice.no_invoice + '</option>');
                        });
                    } else {

                        $('#selectInvoice').empty();
                        $('#selectInvoice').append('<option value="" disabled>No invoices available</option>');
                    }
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal memuat invoice.'
                    });
                }
            });
        }
    });
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


    function generateKodePembayaran() {
        $.ajax({
            url: "{{ route('generateKodePembayaran') }}",
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                $('#KodePayment').val('Loading...');
            },
            success: function (response) {
                if (response.status === 'success') {
                    $('#KodePayment').val(response.kode_pembayaran);
                } else {
                    sectionPoin.addClass("d-none");
                    paymentInput.prop("disabled", false);
                    $('#amountPoin').val("");
                    paymentInput.val("")
                }
            },
            error: function (xhr, status, error) {
                showMessage("error", "Terjadi kesalahan: " + error);
            },
            complete: function () {
                $('#KodePayment').find('.spinner-border').remove();
            }
        });
    }
    generateKodePembayaran();
    $('.select2').select2();

    var today = new Date();
    $('#tanggalPayment,#tanggalPaymentBuat').datepicker({
        format: 'dd MM yyyy',
        todayBtn: 'linked',
        todayHighlight: true,
        autoclose: true,
    }).datepicker('setDate', today);


    $(document).ready(function () {
        $('#selectInvoice').on('change', function () {
            var invoiceNo = $(this).val();

            if (invoiceNo) {

                $.ajax({
                    url: "{{ route('getInvoiceAmount') }}",
                    type: 'GET',
                    data: {
                        no_invoice: invoiceNo
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#previewInvoiceNumber').text(response.invoice[0].no_invoice);
                            $('#previewInvoiceAmount').text(response.invoice[0]
                                .total_harga);
                            $('#previewInvoiceDate').text(response.invoice[0]
                                .tanggal_bayar);
                            $('#previewInvoiceStatus').text(response.invoice[0]
                                .status_name);
                            $('#previewTotalPaid').text(response.invoice[0]
                                .total_bayar);
                            $('#previewRemainingPayment').text(response.invoice[0]
                                .sisa_bayar);
                            $('#previewTotalWeight').text(response.invoice[0]
                                .total_berat);

                        } else {
                            // alert('Data tidak ditemukan');
                            showMessage("error", "Data tidak ditemukan")
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });


        $('#selectMethod').on('change', function () {
            const selectedMethod = $(this).val();
            const sectionPoin = $('#section_poin');
            const paymentInput = $('#payment');
            const discountInput = $('#discountPayment');


            if (selectedMethod === "167") {
                sectionPoin.removeClass("d-none");
                paymentInput.prop("disabled", true);
            } else {
                sectionPoin.addClass("d-none");
                paymentInput.prop("disabled", false);
                discountInput.prop("disabled", false);
                $('#amountPoin').val("");
                paymentInput.val("");
                discountInput.val("");
            }
        });

        $('#submitAmountPoin').on('click', function () {
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
                success: function (response) {
                    if (response.total_nominal) {
                        $('#payment').val(response.total_nominal);
                    } else {
                        console.log('Data tidak ditemukan');
                    }
                },
                error: function (xhr, status, error) {
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

    $('#buatPayment').click(function (e) {
        e.preventDefault();

        var kode = $('#KodePayment').val();
        var invoice = $('#selectInvoice').val();
        var marking = $('#selectMarking').val();
        var tanggalPayment = $('#tanggalPayment').val();
        var tanggalPaymentBuat = $('#tanggalPaymentBuat').val();
        var paymentAmount = parseFloat($('#payment').val()) || 0;
        var discountPayment = parseFloat($('#discountPayment').val()) || 0;
        var paymentMethod = $('#selectMethod').val();
        var amountPoin = $('#amountPoin').val();

        let valid = true;
        if (!kode) {
            $('#errKodePayment').removeClass('d-none');
            valid = false;
        }
        if (!invoice) {
            $('#errInvoicePayment').removeClass('d-none');
            valid = false;
        }
        if (!marking) {
            $('#errMarkingPayment').removeClass('d-none');
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

        var totalPayment = paymentAmount + discountPayment;

        if (valid) {
            $.ajax({
                url: "{{ route('buatpembayaran') }}",
                method: 'POST',
                data: {
                    kode: kode,
                    invoice: invoice,
                    tanggalPayment: tanggalPayment,
                    tanggalPaymentBuat: tanggalPaymentBuat,
                    paymentAmount: totalPayment,
                    discountPayment: discountPayment,
                    paymentMethod: paymentMethod,
                    amountPoin: amountPoin,
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
                            text: response
                                .message
                        });
                    }
                },
                error: function (xhr, status, error) {
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