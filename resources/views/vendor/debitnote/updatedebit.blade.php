@extends('layout.main')

@section('title', 'Vendor | Edit Debit Note')

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

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Debit Note</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Vendor</li>
                <li class="breadcrumb-item"><a href="{{ route('debitnote') }}">Debit Note</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Debit Note</li>
            </ol>
        </div>
        <a class="btn btn-primary mb-3" href="{{ route('debitnote') }}">
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
                                    <label for="invoiceDebit" class="form-label fw-bold">Invoice</label>
                                    <select class="form-control select2" name="invoiceDebit" id="invoiceDebit">
                                        <option value="" selected disabled>Pilih Invoice</option>
                                        @foreach ($listInvoice as $invoice)
                                            <option value="{{ $invoice->id }}">{{ $invoice->invoice_no }}</option>
                                        @endforeach
                                    </select>
                                    <div id="invoiceDebitError" class="text-danger mt-1 d-none">Silahkan Pilih Invoice
                                        terlebih dahulu</div>
                                </div>

                                <div class="mt-3">
                                    <label for="currencyDebit" class="form-label fw-bold">Currency</label>
                                    <select class="form-control col-8" name="currencyDebit" id="currencyDebit">
                                        @foreach ($listCurrency as $currency)
                                            <option value="{{ $currency->id }}">
                                                {{ $currency->nama_matauang }} ({{ $currency->singkatan_matauang }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="currencyDebitError" class="text-danger mt-1 d-none">Silahkan Pilih Currency
                                        terlebih dahulu</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="accountDebit" class="form-label fw-bold">Account</label>
                                    <select class="form-control select2" name="accountDebit" id="accountDebit" style="">
                                        <option value="" selected disabled>Pilih Account</option>
                                        @foreach ($coas as $coa)
                                            <option value="{{ $coa->id }}">{{ $coa->code_account_id }} -
                                                {{ $coa->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="accountDebitError" class="text-danger mt-1 d-none">Silahkan Pilih Account
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
                        <div class="form-label ml-3 mt-5 mb-3">Item List</div>
                        <div class="table-responsive col-12 ms-5">
                            <table class="table mb-0" style="border-bottom : 1px solid black;">
                                <thead>
                                    <tr>
                                        <th>No Resi</th>
                                        <th>Deskripsi</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody id="items-container">
                                    {{-- Isi item dari debit note --}}
                                </tbody>

                            </table>
                            <br>
                            <button type="button" class="btn btn-primary" id="add-item-button">Add Item</button>

                        </div>
                        <div class="row">
                            <div class="col-5 mr-5">
                                <div class="input-group pt-2 mt-3">
                                    <label for="noteDebit" class="form-label fw-bold p-1">Note</label>
                                </div>
                                <textarea id="noteDebit" class="form-control" aria-label="With textarea" placeholder="Masukkan content"
                                    rows="4"></textarea>
                            </div>
                            <div class="col-4 ms-5 mt-5 ml-5">
                                <div class="mb-3" style="border-bottom:1px solid black;">
                                    <label class="inline-label">Total keseluruhan : </label>
                                    <input disabled="" type="text" id="Total"
                                        class="form-control-flush inline-input" name="subtotal" placeholder="0">
                                </div>
                            </div>
                            <div id="noteDebitError" class="text-danger mt-1 d-none">Silahkan isi Note</div>
                            <div class="col-12 mt-4">
                                <div class="col-4 float-right">
                                    <button id="updateDebit" class="btn btn-primary p-3 float-right mt-3"
                                        style="width: 100%;">Update</button>
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
                var debitNote = @json($debitNote);
                var coas = @json($coas);

                console.log("Isi debit note", debitNote);

                // Inisialisasi select2
                $('.select2').select2();

                const updateTotals = () => {
                    let totalKeseluruhan = 0;

                    $('#items-container tr').each(function() {
                        const harga = parseFloat($(this).find('input[name="harga"]').val()) || 0;
                        const jumlah = parseFloat($(this).find('input[name="jumlah"]').val()) || 1;
                        const total = harga * jumlah;
                        totalKeseluruhan += total;

                        $(this).find('input[name="item-subtotal"]').val(total.toFixed(2));
                    });

                    $('#Total').val(totalKeseluruhan.toFixed(2));
                };

                // Prefill data debit note
                if (debitNote) {
                    $('#invoiceDebit').val(debitNote.invoice_id).trigger('change');
                    $('#accountDebit').val(debitNote.account_id).trigger('change');
                    $('#currencyDebit').val(debitNote.matauang_id).trigger('change');
                    $('#rateCurrency').val(debitNote.rate_currency);
                    $('#noteDebit').val(debitNote.note);

                    // Isi item ke dalam tabel
                    debitNote.items.forEach(function(item) {
                        const newRow = `
                        <tr>
                            <td><input required type="text" class="form-control" name="noresi" value="${item.no_resi}"></td>
                            <td><input required type="text" class="form-control" name="deskripsi" value="${item.deskripsi}"></td>
                            <td><input required type="number" class="form-control" name="harga" value="${item.harga}"></td>
                            <td><input required value="${item.jumlah}" type="number" class="form-control" name="jumlah"></td>
                            <td><input disabled type="text" class="form-control" name="item-subtotal" value="${item.total}"></td>
                            <td><button type="button" class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button></td>
                        </tr>
                        `;
                        $('#items-container').append(newRow);
                    });

                    updateTotals();
                }

                $('#currencyDebit').change(function() {
                    const selectedCurrency = $(this).val();
                    if (selectedCurrency !== '1') {
                        $('#rateCurrencySection').show();
                    } else {
                        $('#rateCurrencySection').hide();
                    }
                    updateTotals();
                });

                // Fungsi untuk menambah baris baru
                $('#add-item-button').click(function() {
                    const newRow = `
                    <tr>
                        <td><input required type="text" class="form-control" name="noresi"></td>
                        <td><input required type="text" class="form-control" name="deskripsi"></td>
                        <td><input required type="number" class="form-control" name="harga"></td>
                        <td><input required value="1" type="number" class="form-control" name="jumlah"></td>
                        <td><input disabled type="text" class="form-control" name="item-subtotal"></td>
                        <td><button type="button" class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button></td>
                    </tr>
                    `;
                    $('#items-container').append(newRow);
                    updateTotals();
                });

                $(document).on('click', '.removeItemButton', function() {
                    $(this).closest('tr').remove();
                    updateTotals();
                });

                $(document).on('input', 'input[name="harga"], input[name="jumlah"]', function() {
                    updateTotals();
                });

                $('#updateDebit').click(function() {
                    const invoiceDebit = $('#invoiceDebit').val();
                    const accountDebit = $('#accountDebit').val();
                    const currencyDebit = $('#currencyDebit').val();
                    const noteDebit = $('#noteDebit').val();
                    const rateCurrency = $('#rateCurrency').val();
                    const items = [];

                    $('#items-container tr').each(function() {
                        const noresi = $(this).find('input[name="noresi"]').val();
                        const deskripsi = $(this).find('input[name="deskripsi"]').val();
                        const harga = $(this).find('input[name="harga"]').val();
                        const jumlah = $(this).find('input[name="jumlah"]').val();
                        const total = $(this).find('input[name="item-subtotal"]').val();

                        items.push({
                            noresi: noresi,
                            deskripsi: deskripsi,
                            harga: harga,
                            jumlah: jumlah,
                            total: total
                        });
                    });

                    const debitNoteData = {
                        debitNoteId: debitNote ? debitNote.id : null,
                        invoiceDebit: invoiceDebit,
                        accountDebit: accountDebit,
                        currencyDebit: currencyDebit,
                        rateCurrency: rateCurrency,
                        noteDebit: noteDebit,
                        items: items,
                        totalKeseluruhan: $('#Total').val()
                    };

                    const csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('debit-note.store') }}",
                        type: 'POST',
                        data: debitNoteData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        dataType: 'json',
                        success: function(response) {
                            showMessage('success', 'Debit Note berhasil diupdate!')
                                .then(() => {
                                    window.location.reload();
                                });
                        },
                        error: function(xhr, status, error) {
                            showMessage('error', 'Terjadi kesalahan saat mengupdate data.');
                        }
                    });
                });
            });
        </script>

    @endsection
