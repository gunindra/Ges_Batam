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
                            @foreach ($listMarking as $markingList)
                                <option value="{{ $markingList->marking }}">{{ $markingList->marking }}
                                    ({{ $markingList->nama_pembeli }})
                                </option>
                            @endforeach
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
                            @foreach ($savedPaymentAccounts as $coa)
                                <option value="{{ $coa->coa_id }}">
                                    {{ $coa->code_account_id }} - {{ $coa->name }}
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
        <button id="buatPayment" class="btn btn-primary p-3 w-50">Buat Payment</button>
    </div>
</div>
<!---Container Fluid-->


@endsection
@section('script')
<script>
    $(document).ready(function () {
        // Handle marking selection change
        $('#selectMarking').on('change', function () {
            const marking = $(this).val();
            if (marking) {
                loadInvoicesByMarking(marking);
            }
        });

        // Load invoices based on marking
        // Fungsi untuk memuat invoice berdasarkan marking
        function loadInvoicesByMarking(marking) {
            $.ajax({
                url: "{{ route('getInvoiceByMarking') }}",
                type: 'GET',
                data: {
                    marking
                },
                success: function (response) {
                    const $selectInvoice = $('#selectInvoice').empty();
                    if (response.success) {
                        response.invoices.forEach(invoice => {
                            $selectInvoice.append(
                                `<option value="${invoice.no_invoice}">${invoice.no_invoice}</option>`
                            );
                        });
                    } else {
                        $selectInvoice.append(
                            '<option value="" disabled>No invoices available</option>');
                    }
                },
                error: function () {
                    showMessage('error!', 'Gagal memuat invoice.');
                }
            });
        }

        $('#add-item-button').click(function () {
            const newRow = `
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
            const discountPayment = parseFloat($('#discountPayment').val()) || 0;

            const grandTotal = totalDebit + paymentAmount;
            $('#total_payment').val(grandTotal.toFixed(0));
        }

        // Event handler untuk tombol hapus
        $(document).on('click', '.removeItemButton', function () {
            $(this).closest('tr').remove();
            updateTotals();
        });

        // Update total saat input nilai debit
        $(document).on('input', 'input[name="debit"]', function () {
            updateTotals();
        });

        $('#payment, #discountPayment').on('input change', function() {
            updateTotals();
        });


        // Generate kode pembayaran
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
                    }
                },
                error: function () {
                    showMessage('error', 'Terjadi kesalahan dalam generate kode pembayaran.');
                }
            });
        }
        generateKodePembayaran();

        $('.select2').select2();
        $('#selectInvoice').select2({
            placeholder: "Pilih Invoice",
            allowClear: true
        });

        // Set tanggal saat ini
        const today = new Date();
        $('#tanggalPayment').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        }).datepicker('setDate', today);

        flatpickr("#tanggalPaymentBuat", {
            enableTime: true,
            dateFormat: "d F Y H:i",
            defaultDate: new Date(),
            minuteIncrement: 1,
            time_24hr: true,
            locale: "id",
        });

        // Muat detail invoice saat dipilih
        $('#selectInvoice').on('change', function () {
            const invoiceNo = $(this).val();
            if (invoiceNo) {
                loadInvoiceDetails(invoiceNo);
            }
        });

        function loadInvoiceDetails(invoiceNo) {
            $.ajax({
                url: "{{ route('getInvoiceAmount') }}",
                type: 'GET',
                data: {
                    no_invoice: invoiceNo
                },
                success: function (response) {
                    const invoice = response.summary;
                    $('#previewInvoiceNumber').text(invoice.no_invoice.replace(/;/g, ', '));
                    $('#previewInvoiceAmount').text(invoice.total_harga);
                    $('#previewTotalPaid').text(invoice.total_bayar);
                    $('#previewRemainingPayment').text(invoice.sisa_bayar);
                },
                error: function () {
                    showMessage('error', 'Terjadi kesalahan saat memuat data invoice.');
                }
            });
        }

        // Handle payment method change
        $('#selectMethod').on('change', function () {
            const selectedMethod = $(this).val();
            const sectionPoin = $('#section_poin');
            const paymentInput = $('#payment');
            const discountInput = $('#discountPayment');

            if (selectedMethod === "162") {
                sectionPoin.removeClass("d-none");
                paymentInput.prop("disabled", true);
                discountInput.prop("disabled", true);
            } else {
                sectionPoin.addClass("d-none");
                paymentInput.prop("disabled", false);
                discountInput.prop("disabled", false);
                $('#amountPoin').val("");
                paymentInput.val("");
                discountInput.val("");
            }
        });

        // Submit payment points
        $('#submitAmountPoin').on('click', function () {
            const invoiceNo = $('#selectInvoice').val();
            const amountPoin = $('#amountPoin').val();

            if (!amountPoin) {
                showMessage('error', 'Silahkan masukkan nominal poin terlebih dahulu.');
                return;
            }
            if (!invoiceNo) {
                showMessage('error', 'Silakan pilih nomor invoice terlebih dahulu.');
                $('#amountPoin').val('');
                $('#payment').val('');
                return;
            }

            $.ajax({
                url: "{{ route('amountPoin') }}",
                type: 'GET',
                data: {
                    amountPoin,
                    invoiceNo
                },
                success: function (response) {
                    if (response.total_nominal) {
                        $('#payment').val(response.total_nominal);
                        updateTotals();
                    } else {
                        showMessage('error', 'Data tidak ditemukan');
                    }
                },
                error: function (xhr) {
                    const errorMsg = xhr.responseJSON?.error ||
                        'Error tidak diketahui terjadi';
                    showMessage('error', errorMsg);
                    $('#payment').val('');
                }
            });
        });

        // Submit payment
        $('#buatPayment').click(function (e) {
            e.preventDefault();

            // Reset semua pesan error
            $(".text-danger").addClass("d-none");

            let isValid = true;

            // Validasi Kode Payment
            if (!$("#KodePayment").val().trim()) {
                $("#errKodePayment").removeClass("d-none");
                isValid = false;
            }

            // Validasi Marking
            if (!$("#selectMarking").val()) {
                $("#errMarkingPayment").removeClass("d-none");
                isValid = false;
            }

            // Validasi Invoice
            if (!$("#selectInvoice").val()) {
                $("#errInvoicePayment").removeClass("d-none");
                isValid = false;
            }

            // Validasi Tanggal Payment
            if (!$("#tanggalPayment").val().trim()) {
                $("#errTanggalPayment").removeClass("d-none");
                isValid = false;
            }

            // Validasi Payment Method
            if (!$("#selectMethod").val()) {
                $("#errMethodPayment").removeClass("d-none");
                isValid = false;
            }

            // Validasi Amount Poin (jika poin aktif)
            if (!$("#section_poin").hasClass("d-none") && !$("#amountPoin").val().trim()) {
                $("#erramountPoin").removeClass("d-none");
                isValid = false;
            }

            // Validasi Payment Amount
            if (!$("#payment").val().trim()) {
                $("#errAmountPayment").removeClass("d-none");
                isValid = false;
            }

            // Kumpulkan data dari setiap row dalam bentuk array objek
            let items = [];
            $('#items-container tr').each(function () {
                let account = $(this).find('select[name="account"]').val();
                let itemDesc = $(this).find('input[name="item_desc"]').val();
                let debit = $(this).find('input[name="debit"]').val();

                items.push({
                    account: account,
                    item_desc: itemDesc,
                    debit: debit
                });
            });

            if (isValid) {
                const totalforntend = parseFloat($('#total_payment').val()) || 0;
                const backendDiscout = parseFloat($('#discountPayment').val()) || 0;

                const totalAmmount = totalforntend + backendDiscout

                const data = {
                    kode: $('#KodePayment').val(),
                    invoice: $('#selectInvoice').val(),
                    marking: $('#selectMarking').val(),
                    tanggalPayment: $('#tanggalPayment').val(),
                    tanggalPaymentBuat: $('#tanggalPaymentBuat').val(),
                    paymentAmount: parseFloat($('#payment').val()) || 0,
                    discountPayment: parseFloat($('#discountPayment').val()) || 0,
                    paymentMethod: $('#selectMethod').val(),
                    amountPoin: $('#amountPoin').val(),
                    totalAmmount: totalAmmount,
                    items: items,
                    _token: '{{ csrf_token() }}'
                };
                $.ajax({
                    url: "{{ route('buatpembayaran') }}",
                    method: 'POST',
                    data,
                    beforeSend: function () {
                        $('#buatPayment').prop('disabled', true).text('Proses...');
                    },
                    success: function (response) {
                        $('#buatPayment').prop('disabled', false).text('Buat Payment');
                        if (response.success) {
                            showMessage('success', 'Payment berhasil dibuat').then(() =>
                                location.reload());
                        } else {
                            showMessage('error', response.message);
                        }
                    },
                    error: function (xhr) {
                        $('#buatPayment').prop('disabled', false).text('Buat Payment');
                        const errorMsg = xhr.responseJSON?.message ||
                            'Error tidak diketahui terjadi';
                        showMessage('error', errorMsg);
                    }
                });

            }
        });
    });
</script>
@endsection
