@extends('layout.main')

@section('title', 'Accounting | Journal')

@section('main')


<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Journal</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Accounting</li>
            <li class="breadcrumb-item"><a href="{{ route('journal') }}">Journal</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Journal</li>
        </ol>
    </div>
    <a class="btn btn-primary mb-3" href="{{ route('journal') }}">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-12">
                            <div class="mt-3">
                                <label for="" class="form-label fw-bold">Tanggal</label>
                                <input type="date" class="form-control" id="tanggalJournal" value="">
                                <div id="errTanggalJournal" class="text-danger mt-1 d-none">Silahkan isi tanggal
                                </div>
                            </div>
                            <div class="mb-2 mt-3">
                                <label for="" class="form-label fw-bold">Tipe Kode</label>
                                <div class="input-container">
                                    <input type="radio" id="type1" name="code_type" value="BKM" onchange="">
                                    <label for="type1" class="input-label mr-3">BKM</label>
                                    <input type="radio" id="type2" name="code_type" value="BKK" onchange="">
                                    <label for="type2" class="input-label mr-3">BKK</label>
                                    <input type="radio" id="type3" name="code_type" value="JU" onchange="">
                                    <label for="type3" class="input-label mr-3">JU</label>
                                    <input type="radio" id="type1" name="code_type" value="AP" onchange="">
                                    <label for="type1" class="input-label mr-3">AP</label>
                                    <input type="radio" id="type2" name="code_type" value="AR" onchange="">
                                    <label for="type2" class="input-label mr-3">AR</label>
                                    <input type="radio" id="type3" name="code_type" value="CN" onchange="">
                                    <label for="type3" class="input-label mr-3">CN</label>
                                    <input type="radio" id="type3" name="code_type" value="DN" onchange="">
                                    <label for="type3" class="input-label">DN</label>
                                </div>
                                <div id="errTanggalJournal" class="text-danger mt-1 d-none">Silahkan isi Kode</div>
                            </div>
                            <div class="mt-3">
                                <label for="noJournal" class="form-label fw-bold">No.Journal</label>
                                <input type="text" class="form-control" id="noJournal" value=""
                                    placeholder="Masukkan No Journal">
                                <div id="noJournalErrorEdit" class="text-danger mt-1 d-none">Silahkan isi No Journal
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="noRef" class="form-label fw-bold">No.Ref</label>
                                <input type="text" class="form-control" id="noRef" value=""
                                    placeholder="Masukkan No Ref">
                                <div id="noRefErrorEdit" class="text-danger mt-1 d-none">Silahkan isi No Ref</div>
                            </div>
                            <div class="mt-3">
                                <label for="descriptionJournal" class="form-label fw-bold">Description</label>
                                <textarea class="form-control" id="descriptionJournal" rows="3"
                                    placeholder="Masukkan Description"></textarea>
                                <div id="descriptionJournalErrorEdit" class="text-danger mt-1 d-none">Silahkan isi
                                    Description</div>
                            </div>
                            <div class="table-responsive col-12">
                                <table class="table">
                                    <colgroup>
                                        <col style="width: 20%;">
                                        <col style="width: 15%;">
                                        <col style="width: 13%;">
                                        <col style="width: 13%;">
                                        <col style="width: 16%;">
                                        <col style="width: 15%;">
                                        <col style="width: 8%;">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>Code Account</th>
                                            <th>Description</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Memo</th>
                                            <th>Customer Invoice</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-container">
                                        <tr>
                                            <td>
                                                <select class="form-control" name="account[]" required>
                                                    <option value="" hidden>Select Account</option>
                                                    <option value="1">1.0.00 ASET</option>
                                                    <option value="7">1.1.00 ASET LANCAR</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="item_desc[]"
                                                    placeholder="Input Description" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="debit[]" value="0"
                                                    placeholder="0.00" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="credit[]" value="0"
                                                    placeholder="0.00" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="memo[]" placeholder="">
                                            </td>
                                            <td>
                                                <select class=" form-control" name="list_invoice[]"
                                                    onchange="updateCustomerInvoiceTotal()">
                                                    <option value="" hidden>Select Invoice</option>
                                                    <option data-total="100000.00" value="76">INV240001</option>
                                                    <option data-total="1000000.00" value="77">INV240002</option>
                                                    <option data-total="90.00" value="78">INV240003</option>
                                                    <option data-total="12750000.00" value="79">INV240004</option>
                                                    <option data-total="50000.00" value="80">INV240005</option>
                                                    <option data-total="50000.00" value="81">INV240006</option>
                                                    <option data-total="5000.00" value="82">INV240007</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger removeItemButton mt-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icon-tabler-trash-x">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M4 7h16"></path>
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                        <path d="M10 12l4 4m0 -4l-4 4"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select class="form-control" name="account[]" required>
                                                    <option value="" hidden>Select Account</option>
                                                    <option value="1">1.0.00 ASET</option>
                                                    <option value="7">1.1.00 ASET LANCAR</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="item_desc[]"
                                                    placeholder="Input Description" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="debit[]" value="0"
                                                    placeholder="0.00" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="credit[]" value="0"
                                                    placeholder="0.00" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="memo[]" placeholder="">
                                            </td>
                                            <td>
                                                <select class=" form-control" name="list_invoice[]"
                                                    onchange="updateCustomerInvoiceTotal()">
                                                    <option value="" hidden>Select Invoice</option>
                                                    <option data-total="100000.00" value="76">INV240001</option>
                                                    <option data-total="1000000.00" value="77">INV240002</option>
                                                    <option data-total="90.00" value="78">INV240003</option>
                                                    <option data-total="12750000.00" value="79">INV240004</option>
                                                    <option data-total="50000.00" value="80">INV240005</option>
                                                    <option data-total="50000.00" value="81">INV240006</option>
                                                    <option data-total="5000.00" value="82">INV240007</option>
                                                </select>
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-sm btn-danger removeItemButton mt-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="icon icon-tabler icon-tabler-trash-x">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M4 7h16"></path>
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                        <path d="M10 12l4 4m0 -4l-4 4"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-primary" onclick="addItem()">Add
                                                    Item</button>
                                            </td>
                                            <td></td>
                                            <td>
                                                <label>Total:</label>
                                                <input type="text" class="form-control-flush" id="total_debit"
                                                    name="total_debit" value="0.00" disabled>
                                            </td>
                                            <td>
                                                <label>Total:</label>
                                                <input type="text" class="form-control-flush" id="total_credit"
                                                    name="total_credit" value="0.00" disabled>
                                            </td>
                                            <td colspan="3"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div class="col-12 mt-4">
                                    <div class="col-4 float-right">
                                        <button id="approveJournal" class="btn btn-success p-3 float-right mt-3"
                                            style="width: 80%;">Approve</button>
                                        <button id="buatJournal" class="btn btn-primary p-3 float-right mt-3"
                                            style="width: 80%;">Buat Journal</button>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


</div>
<!---Container Fluid-->

@endsection