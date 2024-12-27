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
                                            <option value="{{ $vendor->id }}"
                                                {{ $vendor->id == $selectedVendorId ? 'selected' : '' }}>
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
                                    <input type="text" class="form-control" id="tanggalPayment"
                                        placeholder="Pilih Tanggal">
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
                                            <option value="{{ $coa->id }}"
                                                {{ $coa->id == $payment->paymentMethod->id ? 'selected' : '' }}>
                                                {{ $coa->code_account_id }} - {{ $coa->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="errMethodPayment" class="text-danger mt-1 d-none">Silahkan Pilih Metode</div>
                                </div>
                                <div class="input-group mt-3">
                                    <label for="keteranganPaymentSup" class="form-label fw-bold p-1">Keterangan</label>
                                </div>
                                <textarea id="keteranganPaymentSup" class="form-control" aria-label="With textarea" placeholder="Masukkan keterangan"
                                    rows="4"></textarea>

                                <input type="hidden" id="grandtotal">
                            </div>

                            <div class="col-md-6">
                                <h5 class="fw-bold mt-3">Preview Invoice</h5>
                                <div id="invoicePreview" class="border p-4 rounded mt-3 shadow-sm"
                                    style="background-color: #f9f9f9;">
                                    <p><strong class="text-primary">Nomor Invoice :</strong> <span
                                            id="previewInvoiceNumber">-</span></p>
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
            $(document).ready(function() {
                var payment = @json($payment);

                console.log('ini payment', payment);
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

                $('#selectMethod').trigger('change');

                const paymentDate = new Date(payment.payment_date);
                $('#tanggalPayment').datepicker({
                    format: 'dd MM yyyy',
                    todayBtn: 'linked',
                    todayHighlight: true,
                    autoclose: true,
                }).datepicker('setDate', paymentDate);

                $('#selectVendor').on('change', function() {
                    const idVendor = $(this).val();

                    let invoiceIds = payment.payment_invoices_sup.map(invoice => invoice.invoice_id);


                    if (idVendor) {
                        loadInvoicesByName(idVendor, invoiceIds);
                    } else {
                        $('#selectInvoice').empty().append(
                            '<option value="" disabled>Select a vendor first</option>');
                    }
                });

                function loadInvoicesByName(idVendor, invoiceIds) {
                    $.ajax({
                        url: "{{ route('getInoviceByVendor') }}",
                        type: 'GET',
                        data: {
                            idVendor: idVendor,
                            invoiceIds: invoiceIds
                        },
                        beforeSend: function() {
                            $('#selectInvoice').html('<option value="" disabled>Loading...</option>');
                        },
                        success: function(response) {
                            const $selectInvoice = $('#selectInvoice').empty();
                            let selectedInvoices = [];

                            console.log(response);

                            if (response.success) {
                                response.invoices.forEach(invoice => {
                                    $selectInvoice.append(
                                        `<option value="${invoice.invoice_no}">${invoice.invoice_no}</option>`
                                    );
                                    selectedInvoices.push(invoice.invoice_no);
                                });

                                $selectInvoice.val(selectedInvoices).trigger('change');
                            } else {
                                $selectInvoice.append(
                                    '<option value="" disabled>No invoices available</option>'
                                );
                            }
                        },

                        error: function() {
                            showMessage('error!', 'Failed to load Voucher.');
                            $('#selectInvoice').empty().append(
                                '<option value="" disabled>Error loading Voucher</option>');
                        }
                    });
                }

                $('#selectVendor').trigger('change');
                $('.select2').select2();
                $('#selectInvoice').select2({
                    placeholder: "Pilih Invoice",
                    allowClear: true,
                    width: 'resolve',
                    closeOnSelect: false
                });


                $('#selectInvoice').on('change', function() {
                    var invoiceNo = $(this).val();
                    if (invoiceNo) {
                        $.ajax({
                            url: "{{ route('getSupInvoiceAmount') }}",
                            type: 'GET',
                            data: {
                                no_invoice: invoiceNo
                            },
                            success: function(response) {
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

                // Load items ke tabel
                loadItems(payment);


                // Fungsi menampilkan items dari data backend
                function loadItems(payment) {
                    $('#items-container').empty();
                    payment.payment_sup_item.forEach((item) => {
                        var newRow = `
            <tr>
                <td>
                    <select class="form-control select2singgle" name="account" style="width: 30vw;" required>
                         @foreach ($coas as $coa)
                                            <option value="{{ $coa->id }}" ${item.coa_id === {{ $coa->id }} ? 'selected' : ''}>
                                                {{ $coa->code_account_id }} - {{ $coa->name }}
                                            </option>
                                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control" name="tipeAccount" id="tipeAccount" required>
                        <option value="" disabled>Pilih Akun</option>
                        <option value="Credit" ${item.tipeAccount === 'Credit' ? 'selected' : ''}>Credit</option>
                        <option value="Debit" ${item.tipeAccount === 'Debit' ? 'selected' : ''}>Debit</option>
                    </select>
                    </td>
                <td>
                    <input type="text" class="form-control" name="item_desc" value="${item.description}" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="debit" value="${item.nominal}" required>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button>
                </td>
            </tr>`;
                        $('#items-container').append(newRow);
                    });

                    // Inisialisasi Select2
                    $('.select2singgle').select2();

                    // Update total
                    updateTotals();
                }

                // Fungsi menambah item manual
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
                <input type="number" class="form-control" name="debit" value="0.00" required>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button>
            </td>
        </tr>`;
                    $('#items-container').append(newRow);
                    $('.select2singgle').last().select2();
                    updateTotals();
                });

                // Hapus baris item
                $(document).on('click', '.removeItemButton', function() {
                    $(this).closest('tr').remove();
                    updateTotals();
                });


                // Update total saat nilai debit berubah
                $(document).on('input', 'input[name="debit"]', function() {
                    updateTotals();
                });

                $('#payment').on('input change', function() {
                    updateTotals();
                });
                $('#tipeAccount').on('change', function() {
                    updateTotals();
                });

                function updateTotals() {
                    let totalDebit = 0;

                    $('#items-container tr').each(function() {
                        const debitValue = parseFloat($(this).find('input[name="debit"]').val()) || 0;
                        const accountType = $(this).find('select[name="tipeAccount"]').val();

                        // Periksa tipe account dan hitung berdasarkan Credit atau Debit
                        if (accountType === 'Debit') {
                            totalDebit += debitValue; // Tambahkan nilai untuk Debit
                        } else if (accountType === 'Credit') {
                            totalDebit -=  Math.abs(debitValue); // Kurangi nilai untuk Credit
                        }
                    });

                    const paymentAmount = parseFloat($('#payment').val()) || 0;
                    const grandTotal = totalDebit + paymentAmount;

                    $('#total_payment').val(totalDebit.toFixed(2));
                    $('#grandtotal').val(grandTotal.toFixed(2));
                }
                $('#payment').val(totalAmount).trigger('change');



                // Kirim data ke server
                $('#editPayment').click(function(e) {
                    e.preventDefault();
                    const paymentId = payment.id;

                    const invoice = $('#selectInvoice').val();
                    const tanggalPayment = $('#tanggalPayment').val();
                    const paymentAmount = $('#payment').val();
                    const paymentMethod = $('#selectMethod').val();
                    const keteranganPaymentSup = $('#keteranganPaymentSup').val();
                    let totalAmmount = parseFloat($('#grandtotal').val()) || 0;

                    // Validasi input
                    let valid = true;
                    $('.text-danger').addClass('d-none');
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
                    $('#items-container tr').each(function() {
                        const account = $(this).find('select[name="account"]').val();
                        const itemDesc = $(this).find('input[name="item_desc"]').val();
                        const debit = $(this).find('input[name="debit"]').val();
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
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#5D87FF',
                            cancelButtonColor: '#49BEFF',
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Tidak',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Loading...',
                                    text: 'Please wait while we are saving the data.',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                                $.ajax({
                                    url: "{{ route('editpaymentsup.update') }}",
                                    method: 'POST',
                                    data: {
                                        paymentId,
                                        invoice,
                                        tanggalPayment,
                                        paymentAmount,
                                        paymentMethod,
                                        items,
                                        keteranganPaymentSup,
                                        totalAmmount: totalAmmount,
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        Swal.close();
                                        $('#buatPayment').prop('disabled', false).text(
                                            'Buat Payment');
                                        if (response.success) {
                                            showMessage('success',
                                                'Payment berhasil dibuat').then(() =>
                                                location.reload());
                                        } else {
                                            showMessage('error', response.message);
                                        }
                                    },
                                    error: function(xhr) {
                                        Swal.close();
                                        $('#buatPayment').prop('disabled', false).text(
                                            'Buat Payment');
                                        const errorMsg = xhr.responseJSON?.message ||
                                            'Error tidak diketahui terjadi';
                                        showMessage('error', errorMsg);
                                    }
                                });
                            }
                        })
                    }
                });

            });
        </script>
    @endsection
