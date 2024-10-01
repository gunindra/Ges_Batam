@extends('layout.main')

@section('title', 'Vendor | Purchase Payment')

@section('main')

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Debit Note</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Vendor</li>
            <li class="breadcrumb-item"><a href="{{ route('debitnote') }}">Debit Note</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Debit Note</li>
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
                                <label for="supplierDebit" class="form-label fw-bold">Supplier</label>
                                <select class="form-control col-8" name="" id="supplierDebit">
                                    <option value="" selected disabled>Pilih Supplier</option>
                                    <option value="">PT cahaya</option>
                                    <option value="">PT Bang</option>
                                </select>
                                <div id="supplierDebitError" class="text-danger mt-1 d-none">Silahkan Pilih Supplier
                                    terlebih dahulu</div>
                            </div>
                            <div class="mt-3">
                                <label for="accountDebit" class="form-label fw-bold">Account</label>
                                <select class="form-control col-8" name="" id="accountDebit">
                                    <option value="" selected disabled>Pilih Account</option>
                                    <option value="">1.0.000 ASET</option>
                                    <option value="">1.1.000 ASET LANCAR</option>

                                </select>
                                <div id="accountDebitError" class="text-danger mt-1 d-none">Silahkan Pilih Account
                                    terlebih dahulu</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mt-3">
                                <label for="invoiceDebit" class="form-label fw-bold">Invoice</label>
                                <select class="form-control col-8" name="" id="invoiceDebit">
                                    <option value="" selected disabled>Pilih Invoice</option>
                                    <option value="">001</option>
                                    <option value="">002</option>

                                </select>
                                <div id="invoiceDebitError" class="text-danger mt-1 d-none">Silahkan Pilih Invoice
                                    terlebih dahulu</div>
                            </div>
                            <div class="mt-3">
                                <label for="currencyDebit" class="form-label fw-bold">Currency</label>
                                <select class="form-control col-8" name="" id="currencyDebit">
                                    <option value="" selected disabled>Pilih Currency</option>
                                    <option value="">IDR</option>
                                    <option value="">RM</option>

                                </select>
                                <div id="currencyDebitError" class="text-danger mt-1 d-none">Silahkan Pilih Currency
                                    terlebih dahulu</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-label ms-5 mt-5 mb-3">Item List</div>
                    <div class="table-responsive col-12 ms-5">
                        <table class="table mb-0" style="border-bottom : 1px solid black;">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>QTY</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="items-container">


                                <tr class="tr">
                                    <td>
                                        <select class="form-control select2singgle" id="selectInvoice"
                                            style="width:120%;">
                                            <option value="" selected disabled>Pilih Item</option>
                                            <option value="">001</option>
                                            <option value="">002</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input required="" type="text" class="form-control" name="item_desc[]">
                                    </td>
                                    <td>
                                        <input required="" type="number" step="any" class="form-control" name="price[]">
                                    </td>
                                    <td>
                                        <input required="" value="1" type="number" step="any" class="form-control"
                                            name="qty[]">
                                    </td>
                                    <td>
                                        <input disabled="" type="text" class="form-control" name="item-subtotal[]">
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <br>
                        <button type="button" class="btn btn-primary" onclick="addItem()" fdprocessedid="9gjeh">Add
                            Item</button>

                    </div>
                    <div class="row">
                        <div class="col-5 mr-5">
                            <div class="input-group pt-2 mt-3">
                                <label for="noteDebit" class="form-label fw-bold p-1">Note</label>
                            </div>
                            <textarea id="noteDebit" class="form-control" aria-label="With textarea"
                                placeholder="Masukkan content" rows="4"></textarea>
                        </div>
                        <div class="col-4 ms-5 mt-5 ml-5">
                            <div class="mb-3" style="border-bottom:1px solid black;">
                                <label class="inline-label">Subtotal : </label>
                                <input disabled="" type="text" id="subtotal" class="form-control-flush inline-input"
                                    name="subtotal" placeholder="0" fdprocessedid="nox2">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tax : </label>
                                <select class="form-control w-100" id="selectInvoice"
                                            style="width:120%;">
                                            <option value="" selected disabled>Pilih Item</option>
                                            <option value="">001</option>
                                            <option value="">002</option>
                                        </select>
                            </div>
                            <div class="mb-3">
                                <label class="inline-label">Grand Total</label>
                                <input disabled="" type="text" id="grandTotal" class="form-control-flush inline-input"
                                    name="grand_total" placeholder="0" fdprocessedid="d2ujtj">
                            </div>
                        </div>
                        <div id="noteDebitError" class="text-danger mt-1 d-none">Silahkan isi Note</div>
                        <div class="col-12 mt-4">
                            <div class="col-4 float-right">
                                <button id="buatDebit" class="btn btn-primary p-3 float-right mt-3"
                                    style="width: 100%;">Buat
                                    Debit</button>
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
    @endsection