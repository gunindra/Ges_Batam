@extends('layout.main')

@section('title', 'Customer | Rertur')

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
            <h1 class="h3 mb-0 text-gray-800">Update Retur</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Customer</li>
                <li class="breadcrumb-item"><a href="{{ route('retur.index') }}">Rertur</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Rertur</li>
            </ol>
        </div>
        <a class="btn btn-primary mb-3" href="{{ route('retur.index') }}">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="editReturForm" action="{{ route('retur.updateRetur', $returData->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="d-flex flex-row">
                                <div class="col-6">
                                    <div class="mt-3">
                                        <label for="invoiceRetur" class="form-label fw-bold">Invoice</label>
                                        <select class="form-control select2" name="" id="invoiceRetur" disabled>
                                            <option value="" selected disabled>Pilih Invoice (marking - nama)</option>
                                            @foreach ($listInvoice as $invoice)
                                                <option value="{{ $invoice->id }}"
                                                    {{ $returData->invoice_id == $invoice->id ? 'selected' : '' }}>
                                                    {{ $invoice->no_invoice }} ({{ $invoice->marking }} -
                                                    {{ $invoice->nama_pembeli }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- <div class="mt-3">
                                        <label for="currencyRetur" class="form-label fw-bold">Currency</label>
                                        <select class="form-control col-8" name="" id="currencyRetur" disabled>
                                            @foreach ($listCurrency as $currency)
                                                <option value="{{ $currency->id }}"
                                                    {{ $returData->currency_id == $currency->id ? 'selected' : '' }}>
                                                    {{ $currency->nama_matauang }} ({{ $currency->singkatan_matauang }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                </div>
                                <div class="col-6">
                                    <div class="mt-3">
                                        <label for="accountRetur" class="form-label fw-bold">Account</label>
                                        <select name="account_id" id="accountRetur" class="form-control select2">
                                            @foreach ($savedPaymentAccounts as $coa)
                                               <option value="{{ $coa->coa_id }}"
                                                    {{ $returData->account_id == $coa->coa_id ? 'selected' : '' }}>
                                                    {{ $coa->code_account_id }} - {{ $coa->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="accountReturError" class="text-danger mt-1 d-none">Silahkan Pilih Account
                                            terlebih dahulu</div>
                                    </div>
                                    {{-- <div class="mt-3">
                                        <div class="mt-3" id="rateCurrencySection"
                                            style="{{ $returData->currency_id != 1 ? 'display: block;' : 'display: none;' }}">
                                            <label for="rateCurrency" class="form-label fw-bold">Rate Currency</label>
                                            <input type="text" class="form-control col-8" id="rateCurrency"
                                                value="{{ $returData->rate_currency }}" placeholder="Masukkan rate"
                                                disabled>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="form-label ml-3 mt-5 mb-3">Item List</div>
                            <div class="table-responsive col-12 ms-5">
                                <table class="table mb-0" style="border-bottom : 1px solid black;">
                                    <thead>
                                        <tr>
                                            <th>No Resi</th>
                                            <th>Preview Nominal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody id="items-container">
                                        @foreach ($returData->items as $item)
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control no-resi"
                                                        value="{{ $item->resi->no_resi ?? 'N/A' }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control nominal"
                                                        value="{{ isset($item->resi->harga) ? number_format($item->resi->harga, 2) : '0.00' }}"
                                                        readonly>
                                                </td>
                                                <td>
                                                    <!-- Kosong karena tidak ada action -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                            <div class="row">
                                <div class="col-5 mr-5">
                                    <div class="input-group pt-2 mt-3">
                                        <label for="deskripsiRetur" class="form-label fw-bold p-1">Deskripsi</label>
                                    </div>
                                    <textarea name="deskripsi" id="deskripsiRetur" class="form-control">{{ old('deskripsi', $returData->deskripsi) }}</textarea>
                                </div>
                                <div class="col-4 ms-5 mt-5 ml-5">
                                    <div class="mb-3" style="border-bottom:1px solid black;">
                                        <label class="inline-label">Total keseluruhan : </label>
                                        <input disabled type="text" id="Total"
                                            class="form-control-flush inline-input" name="subtotal"
                                            value="{{ number_format($returData->total_nominal, 2) }}" fdprocessedid="nox2">
                                    </div>
                                </div>
                                <div id="noteReturError" class="text-danger mt-1 d-none">Silahkan isi Note</div>
                                <div class="col-12 mt-4">
                                    <div class="col-4 float-right">
                                        <button type="submit" id="simpanRetur" class="btn btn-primary p-3 float-right mt-3"
                                            style="width: 100%;">
                                            Simpan Perubahan
                                        </button>
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
                $('.select2').select2();

                $('#editReturForm').on('submit', function(e) {
                    e.preventDefault();

                    const accountSelect = $('#accountRetur');
                    const deskripsi = $('#deskripsiRetur').val().trim();

                    // Validasi client-side
                    if (!accountSelect.val()) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Silakan pilih account terlebih dahulu!',
                            confirmButtonColor: '#3085d6',
                        });
                        return;
                    }

                    // Tampilkan loading indicator
                    Swal.fire({
                        title: 'Menyimpan perubahan...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Kirim data via AJAX
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonColor: '#3085d6',
                            }).then((result) => {
                                // Refresh halaman setelah alert ditutup
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage,
                                confirmButtonColor: '#3085d6',
                            });
                        }
                    });
                });
            });
        </script>
    @endsection
