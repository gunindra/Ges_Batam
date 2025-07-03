@extends('layout.main')

@section('title', 'Customer | Edit Credit Note')

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
            <h1 class="h3 mb-0 text-gray-800">Edit Credit Note</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Customer</li>
                <li class="breadcrumb-item"><a href="{{ route('creditnote') }}">Credit Note</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Credit Note</li>
            </ol>
        </div>
        <a class="btn btn-primary mb-3" href="{{ route('creditnote') }}">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-6">
                                {{-- <div class="mt-3">
                                <label for="supplierCredit" class="form-label fw-bold">Supplier</label>
                                <select class="form-control col-8" name="" id="supplierCredit">
                                    <option value="" selected disabled>Pilih Supplier</option>
                                    <option value="">PT cahaya</option>
                                    <option value="">PT Bang</option>
                                </select>
                                <div id="supplierCreditError" class="text-danger mt-1 d-none">Silahkan Pilih Supplier
                                    terlebih dahulu</div>
                            </div> --}}
                                <div class="mt-3">
                                    <label for="invoiceCredit" class="form-label fw-bold">Invoice</label>
                                    <select class="form-control select2" name="" id="invoiceCredit">
                                        @foreach ($listInvoice as $invoice)
                                            <option value="{{ $invoice->id }}">{{ $invoice->no_invoice }}
                                                ({{ $invoice->marking }} - {{ $invoice->nama_pembeli }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="invoiceCreditError" class="text-danger mt-1 d-none">Silahkan Pilih Invoice
                                        terlebih dahulu</div>
                                </div>

                                <div class="mt-3">
                                    <label for="currencyCredit" class="form-label fw-bold">Currency</label>
                                    <select class="form-control col-8" name="" id="currencyCredit">
                                        @foreach ($listCurrency as $currency)
                                            <option value="{{ $currency->id }}">
                                                {{ $currency->nama_matauang }} ({{ $currency->singkatan_matauang }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="currencyCreditError" class="text-danger mt-1 d-none">Silahkan Pilih Currency
                                        terlebih dahulu</div>
                                </div>

                                <div class="mt-3">
                                    <div class="mt-3" id="rateCurrencySection" style="display: none;">
                                        <label for="rateCurrency" class="form-label fw-bold">Rate Currency</label>
                                        <input type="text" class="form-control col-8" id="rateCurrency" value=""
                                            placeholder="Masukkan rate">
                                        <div id="rateCurrencyError" class="text-danger mt-1 d-none">Rate tidak boleh kosong
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="accountCredit" class="form-label fw-bold">Account</label>
                                    <select class="form-control select2" name="" id="accountCredit" style="">
                                        <option value="" selected disabled>Pilih Account</option>
                                        @foreach ($coas as $coa)
                                            <option value="{{ $coa->id }}">{{ $coa->code_account_id }} -
                                                {{ $coa->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="accountCreditError" class="text-danger mt-1 d-none">Silahkan Pilih Account
                                        terlebih dahulu</div>
                                </div>
                                <div class="mt-3">
                                    <label for="tanggalCreditNote" class="form-label fw-bold">Tanggal Credit Note</label>
                                    <input type="date" class="form-control" id="tanggalCreditNote"
                                        name="tanggalCreditNote">
                                    <div id="tanggalCreditNoteError" class="text-danger mt-1 d-none">Silahkan isi tanggal
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
                                        <th>Nominal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody id="items-container">
                                    {{-- <tr class="tr">
                                    <td>
                                        <input required="" type="text" class="form-control" name="noresi">
                                    </td>
                                    <td>
                                        <input required="" type="text" class="form-control" name="deskripsi">
                                    </td>
                                    <td>
                                        <input required="" type="number" class="form-control" name="harga">
                                    </td>
                                    <td>
                                        <input required="" value="1" type="number" class="form-control" name="jumlah">
                                    </td>
                                    <td>
                                        <input disabled="" type="text" class="form-control" name="item-subtotal">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1"
                                            style="display:none;">Remove</button>
                                    </td>
                                </tr> --}}
                                </tbody>
                            </table>
                            <div id="tableError" class="text-danger mt-2 d-none">
                                Silahkan isi semua field pada tabel sebelum melanjutkan.
                            </div>

                            <br>
                            <button type="button" class="btn btn-primary" id="add-item-button">Add
                                Item</button>

                        </div>
                        <div class="row">
                            <div class="col-5 mr-5">
                                <div class="input-group pt-2 mt-3">
                                    <label for="noteCredit" class="form-label fw-bold p-1">Note</label>
                                </div>
                                <textarea id="noteCredit" class="form-control" aria-label="With textarea" placeholder="Masukkan content" rows="4"></textarea>
                            </div>
                            <div class="col-4 ms-5 mt-5 ml-5">
                                <div class="mb-3" style="border-bottom:1px solid black;">
                                    <label class="inline-label">Total keseluruhan : </label>
                                    <input disabled="" type="text" id="Total"
                                        class="form-control-flush inline-input" name="subtotal" placeholder="0"
                                        fdprocessedid="nox2">
                                </div>
                            </div>
                            <div id="noteCreditError" class="text-danger mt-1 d-none">Silahkan isi Note</div>
                            <div class="col-12 mt-4">
                                <div class="col-4 float-right">
                                    {{-- <button id="publish" class="btn btn-success p-3 float-right mt-3"
                                    style="width: 100%;">Publish</button> --}}
                                    <button id="buatCredit" class="btn btn-primary p-3 float-right mt-3"
                                        style="width: 100%;">Update Credit</button>
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

                var creditNote = @json($creditNote);
                var coas = @json($coas);

                // Inisialisasi select2 global
                $('.select2').select2();

                // Update total function
                const updateTotals = () => {
                    let totalKeseluruhan = 0;

                    $('#items-container tr').each(function() {
                        const harga = parseFloat($(this).find('input[name="harga"]').val()) || 0;
                        const total = harga;

                        totalKeseluruhan += total;

                        $(this).find('input[name="item-subtotal"]').val(total.toFixed(2));
                    });

                    $('#Total').val(totalKeseluruhan.toFixed(2));
                };

                // Prefill data credit note
                if (creditNote) {
                    $('#invoiceCredit').val(creditNote.invoice_id).trigger('change');
                    $('#accountCredit').val(creditNote.account_id).trigger('change');
                    $('#currencyCredit').val(creditNote.matauang_id).trigger('change');
                    $('#rateCurrency').val(creditNote.rate_currency);
                    $('#noteCredit').val(creditNote.note);
                    $('#tanggalCreditNote').val(creditNote.tanggal);

                    creditNote.items.forEach(function(item) {
                        const newRow = `
            <tr>
                <td><input required type="text" class="form-control" name="noresi" value="${item.no_resi}"></td>
                <td><input required type="text" class="form-control" name="deskripsi" value="${item.deskripsi}"></td>
                <td><input required type="number" class="form-control" name="harga" value="${item.harga}"></td>
                <td><button type="button" class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button></td>
            </tr>
        `;
                        $('#items-container').append(newRow);
                    });

                    updateTotals();
                }

                // Tampilkan atau sembunyikan rateCurrency
                $('#currencyCredit').change(function() {
                    const selectedCurrency = $(this).val();
                    $('#rateCurrencySection').toggle(selectedCurrency !== '1');
                    updateTotals();
                });

                // Tambah baris item baru
                $('#add-item-button').click(function() {
                    const newRow = `
        <tr>
            <td>
                <select class="form-control select-noresi" name="noresi" style="width: 100%;">
                </select>
            </td>
            <td><input required type="text" class="form-control" name="deskripsi"></td>
            <td><input required type="number" class="form-control" name="harga"></td>
            <td><button type="button" class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button></td>
        </tr>
    `;

                    const $row = $(newRow);
                    $('#items-container').append($row);

                    // Inisialisasi select2 untuk select-noresi yang baru
                    $row.find('.select-noresi').select2({
                        placeholder: 'Pilih No Resi',
                        ajax: {
                            url: '{{ route('noresi.by.invoice') }}',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    q: params.term,
                                    invoice_id: $('#invoiceCredit').val()
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.map(function(item) {
                                        return {
                                            id: item.no_resi,
                                            text: item.no_resi,
                                            harga: item.harga
                                        };
                                    })
                                };
                            },
                            cache: true
                        }
                    }).on('select2:select', function(e) {
                        const selectedData = e.params.data;
                        const row = $(this).closest('tr');
                        row.find('input[name="harga"]').val(selectedData.harga);
                        updateTotals();
                    });

                    updateTotals();
                });

                // Hapus baris item
                $(document).on('click', '.removeItemButton', function() {
                    $(this).closest('tr').remove();
                    updateTotals();
                });

                // Update total saat input harga berubah
                $(document).on('input', 'input[name="harga"]', function() {
                    updateTotals();
                });


                $('#buatCredit').click(function() {
                    const invoiceCredit = $('#invoiceCredit').val();
                    const accountCredit = $('#accountCredit').val();
                    const currencyCredit = $('#currencyCredit').val();
                    const noteCredit = $('#noteCredit').val();
                    const rateCurrency = $('#rateCurrency').val();
                    const tanggalCreditNote = $('#tanggalCreditNote').val();
                    const items = [];
                    $('.error-message').remove();

                    let isValid = true;

                    if (!invoiceCredit) {
                        $('#invoiceCreditError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#invoiceCreditError').addClass('d-none');
                    }

                    if (!accountCredit) {
                        $('#accountCreditError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#accountCreditError').addClass('d-none');
                    }

                    if (!currencyCredit) {
                        $('#currencyCreditError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#currencyCreditError').addClass('d-none');

                    }
                    if (!rateCurrency) {
                        $('#rateCurrencyError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#rateCurrencyError').addClass('d-none');

                    }

                    $('#items-container tr').each(function() {
                        const noresi = $(this).find('select[name="noresi"]').val();
                        const deskripsi = $(this).find('input[name="deskripsi"]').val();
                        const harga = $(this).find('input[name="harga"]').val();
                        const total = $(this).find('input[name="item-subtotal"]').val();

                        if (!noresi || !deskripsi || !harga) {
                            isValid = false;
                        } else {
                            isValid = true;
                        }

                        if (!isValid) {
                            $('#tableError').removeClass('d-none');
                        } else {
                            $('#tableError').addClass('d-none');
                        }

                        items.push({
                            noresi: noresi,
                            deskripsi: deskripsi,
                            harga: harga,
                            total: total
                        });
                    });
                    if (isValid) {
                        const creditNoteData = {
                            creditNoteId: creditNote ? creditNote.id :
                            null, // Mengirim creditNoteId jika ada
                            invoiceCredit: invoiceCredit,
                            accountCredit: accountCredit,
                            currencyCredit: currencyCredit,
                            tanggalCreditNote: tanggalCreditNote,
                            rateCurrency: rateCurrency,
                            noteCredit: noteCredit,
                            items: items,
                            totalKeseluruhan: $('#Total').val()
                        };

                        const csrfToken = $('meta[name="csrf-token"]').attr('content');

                        $.ajax({
                            url: '{{ route('credit-note.store') }}',
                            type: 'POST',
                            data: creditNoteData,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            dataType: 'json',
                            success: function(response) {
                                Swal.fire({
                                    title: 'Success',
                                    text: 'Debit Note berhasil disimpan!',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                let errorMessage = 'Terjadi kesalahan saat menyimpan data.';

                                // Ambil pesan dari server jika ada
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    title: 'Error',
                                    text: errorMessage,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });
        </script>

    @endsection
