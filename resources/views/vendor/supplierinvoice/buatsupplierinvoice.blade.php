@extends('layout.main')

@section('title', 'Buat Invoice')

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
</style>

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Invoice</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Vendor</li>
            <li class="breadcrumb-item"><a href="{{ route('supplierInvoice') }}">Invoice</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Invoice</li>
        </ol>
    </div>

    <a class="btn btn-primary mb-3" href="{{ route('supplierInvoice') }}">
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
                                <label for="noResi" class="form-label fw-bold">No Invoice :</label>
                                <div class="d-flex">
                                    <h2 class="fw-bold" id="noInvoice">1000123</h2>
                                    <a class="pt-2" id="btnRefreshInvoice" href=""><span class="pl-2 text-success"><i
                                                class="fas fa-sync-alt"></i></span></a>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                                <input type="text" class="form-control col-8" id="tanggal" value=""
                                    placeholder="Pilih tanggal">
                                <div id="tanggalError" class="text-danger mt-1 d-none">Tanggal tidak boleh kosong</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mt-3">
                                <label for="currencyInvoice" class="form-label fw-bold">Currency</label>
                                <select class="form-control col-8" name="" id="currencyInvoice">
                                    <option value="" selected disabled>Pilih Currency</option>

                                </select>
                                <div id="currencyInvoiceError" class="text-danger mt-1 d-none">Silahkan Pilih Currency
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
                    <div class="divider mt-4">
                        <span>Costumer</span>
                    </div>
                    <div class="d-flex">
                        <div class="col-6">
                            <div class="mt-3">
                                <label for="customer" class="form-label fw-bold col-12">Customer</label>
                                <select class="form-control select2singgle" id="selectCostumer" style="width: 67%">
                                    <option value="" selected disabled>Pilih Customer</option>

                                </select>
                                <div id="customerError" class="text-danger mt-1 d-none">Silahkan Pilih Customer</div>
                            </div>
                            <div class="mt-3" id="alamatContainer"></div>

                        </div>
                        <div class="col-6">
                            <div class="mt-5" id="pickupDelivery" style="display: none;">
                                <h2></h2>
                            </div>
                        </div>
                    </div>
                    <div class="divider mt-4">
                        <span>Detail Barang</span>
                    </div>
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>Code Account</th>
                                <th>Description</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Memo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-container">
                            <!-- Two default rows without the "Remove" button initially -->
                            <tr>
                                <td>
                                    <select class="form-control select2singgle" name="account" style="width: 15vw;"
                                        required>
                                        <option value="">Pilih Akun</option>

                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="item_desc"
                                        placeholder="Input Description" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="debit" value="0" placeholder="0.00"
                                        required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="credit" value="0" placeholder=""
                                        required>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="memo" placeholder="">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1"
                                        style="display:none;">Remove</button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <select class="form-control select2singgle" name="account" style="width: 15vw;"
                                        required>
                                        <option value="">Pilih Akun</option>

                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="item_desc"
                                        placeholder="Input Description" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="debit" value="0" placeholder=""
                                        required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="credit" value="0" placeholder=""
                                        required>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="memo" placeholder="">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1"
                                        style="display:none;">Remove</button>
                                </td>
                            </tr>
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
                                    <input type="text" class="form-control-flush" id="total_debit" name="total_debit"
                                        value="" disabled>
                                </td>
                                <td>
                                    <label>Total:</label>
                                    <input type="text" class="form-control-flush" id="total_credit" name="total_credit"
                                        value="" disabled>
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="col-12 mt-5">
                        <div class="col-4 float-right">
                            <button id="buatJournal" class="btn btn-primary p-3 float-right mt-3"
                                style="width: 80%;">Buat Invoice</button>
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

</script>