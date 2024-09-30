@extends('layout.main')

@section('title', 'Accounting | Accounting Setting')

@section('main')


<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Accounting Setting</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./">Accounting</a></li>
            <li class="breadcrumb-item active" aria-current="page">Accounting Setting</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="containerBooking" class="table-responsive px-3">
                        <h5 class="modal-title" id="modalTambahCustomerTitle">Accounting Sales Setting</h5>
                        <div class="mt-3">
                            <label for="salesAccount" class="form-label fw-bold">Sales Account</label>
                            <select class="form-control" name="Sales[]" required>
                                <option value="1">1.0.00 ASET</option>
                                <option value="7">1.1.00 ASET LANCAR</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="receivableSalesAccount" class="form-label fw-bold">Receivable Sales
                                Account</label>
                            <select class="form-control" name="Receivable[]" required>
                                <option value="1">1.0.00 ASET</option>
                                <option value="7">1.1.00 ASET LANCAR</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="returnAccount" class="form-label fw-bold">Customer Sales Return Account</label>
                            <select class="form-control" name="Return[]" required>
                                <option value="1">1.0.00 ASET</option>
                                <option value="7">1.1.00 ASET LANCAR</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="discountAccount" class="form-label fw-bold">Discount Sales Account</label>
                            <select class="form-control" name="Discount[]" required>
                                <option value="1">1.0.00 ASET</option>
                                <option value="7">1.1.00 ASET LANCAR</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="salesProfitAccount" class="form-label fw-bold">Sales Profit Rate Account</label>
                            <select class="form-control" name="ProfitRate[]" required>
                                <option value="1">1.0.00 ASET</option>
                                <option value="7">1.1.00 ASET LANCAR</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="salesRateAccount" class="form-label fw-bold">Sales Loss Rate Account</label>
                            <select class="form-control" name="LossRate[]" required>
                                <option value="1">1.0.00 ASET</option>
                                <option value="7">1.1.00 ASET LANCAR</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <div id="containerBooking" class="table-responsive px-3">
                        <h5 class="modal-title" id="modalTambahCustomerTitle">Accounting Vendor</h5>
                        <div class="mt-3">
                            <label for="purchaseAccount" class="form-label fw-bold">Purchase Account</label>
                            <select class="form-control" name="Purchase[]" required>
                                <option value="1">1.0.00 ASET</option>
                                <option value="7">1.1.00 ASET LANCAR</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="debtAccount" class="form-label fw-bold">Debt Account</label>
                            <select class="form-control" name="Debt[]" required>
                                <option value="1">1.0.00 ASET</option>
                                <option value="7">1.1.00 ASET LANCAR</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="supplierAccount" class="form-label fw-bold">Supplier Purchase Return
                                Account</label>
                            <select class="form-control" name="Supplier[]" required>
                                <option value="1">1.0.00 ASET</option>
                                <option value="7">1.1.00 ASET LANCAR</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="discountPurchaseAccount" class="form-label fw-bold">Discount Purchase
                                Account</label>
                            <select class="form-control" name="DiscountPurchase[]" required>
                                <option value="1">1.0.00 ASET</option>
                                <option value="7">1.1.00 ASET LANCAR</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="purchaseProfitAccount" class="form-label fw-bold">Purchase Profit Rate
                                Account</label>
                            <select class="form-control" name="PurchaseRate[]" required>
                                <option value="1">1.0.00 ASET</option>
                                <option value="7">1.1.00 ASET LANCAR</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="purchaseLossAccount" class="form-label fw-bold">Purchase Loss Rate
                                Account</label>
                            <select class="form-control" name="PurchaseLoss[]" required>
                                <option value="1">1.0.00 ASET</option>
                                <option value="7">1.1.00 ASET LANCAR</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>
      
    </div>
    <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div id="containerBooking" class="table-responsive px-3">
                            <div class="d-flex justify-content-center mb-2 mr-3">
                                <button id="buatInvoice" class="btn btn-primary p-3 float-right mt-3"
                                    style="width: 30%;">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!---Container Fluid-->

    @endsection