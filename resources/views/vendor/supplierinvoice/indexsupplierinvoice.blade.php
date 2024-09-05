@extends('layout.main')

@section('title', 'Vendor | SupplierInvoice')

@section('main')
<div class="container-fluid" id="container-wrapper">

        <!-- Modal Center -->
        <div class="modal fade" id="modalTambahSupplierInvoice" tabindex="-1" role="dialog" aria-labelledby="modalTambahSupplierInvoiceTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahSupplierInvoiceTitle">Modal Tambah Supplier Invoice</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="namaRekening" class="form-label fw-bold">Pemilik</label>
                            <input type="text" class="form-control" id="namaRekening" value=""
                                placeholder="Masukkan Nama Pemilik">
                            <div id="err-NamaRekening" class="text-danger mt-1 d-none">Silahkan isi nama pemilik</div>
                        </div>
                        <div class="mt-3">
                            <label for="noRek" class="form-label fw-bold">No. Rekening</label>
                            <input type="text" class="form-control" id="noRekening" value=""
                                placeholder="Masukkan No. Rekening">
                            <div id="err-noRekening" class="text-danger mt-1 d-none">Silahkan isi no. Rekening</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Bank</label>
                            <input type="text" class="form-control" id="bankRekening" value="" placeholder="Masukkan nama bank">
                            <div id="err-bankRekening" class="text-danger mt-1 d-none">Silahkan masukkan nama bank</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveRekening">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Center -->
        <div class="modal fade" id="modalEditSupplierInvoice" tabindex="-1" role="dialog"
            aria-labelledby="modalEditSupplierInvoiceTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditSupplierInvoiceTitle">Modal Edit Supplier Invoice</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="supplierInvoiceIdEdit">
                        <div class="mt-3">
                            <label for="namaRekening" class="form-label fw-bold">Pemilik</label>
                            <input type="text" class="form-control" id="namaRekeningEdit" value="" placeholder="Masukkan Nama Pemilik">
                            <div id="err-NamaRekeningEdit" class="text-danger mt-1 d-none">Silahkan isi nama pemilik</div>
                        </div>
                        <div class="mt-3">
                            <label for="noRek" class="form-label fw-bold">No. Rekening</label>
                            <input type="text" class="form-control" id="noRekeningEdit" value="" placeholder="Masukkan No. Rekening">
                            <div id="err-noRekeningEdit" class="text-danger mt-1 d-none">Silahkan isi no. Rekening</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Bank</label>
                            <input type="text" class="form-control" id="bankRekeningEdit" value="" placeholder="Masukkan nama bank">
                            <div id="err-bankRekeningEdit" class="text-danger mt-1 d-none">Silahkan masukkan nama bank</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveEditRekening">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Supplier Invoice</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Vendor</li>
                <li class="breadcrumb-item active" aria-current="page">Supplier Invoice</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahSupplierInvoice" id="#modalCenter"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah Supplier Invoice</button>
                        </div>
                        <div class="float-left">
                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                class="form-control rounded-3" placeholder="Search">
                        </div>
                        <div id="containerSupplierInvoice" class="table-responsive px-3 ">
                            <table class="table align-items-center table-flush table-hover" id="tableRekening">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Pemilik</th>
                                        <th>No. Rekening</th>
                                        <th>Bank</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Ilham</td>
                                        <td>291037292</td>
                                        <td>BCA</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tio</td>
                                        <td>1192837432</td>
                                        <td>Mandiri</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
            const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

            const getListSupplierInvoice = () => {
                const txtSearch = $('#txSearch').val();

                $.ajax({
                        url: "{{ route('getlistSupplierInvoice') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch
                        },
                        beforeSend: () => {
                            $('#containerSupplierInvoice').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerSupplierInvoice').html(res)
                        $('#tableSupplierInvoice').DataTable({
                            searching: false,
                            lengthChange: false,
                            "bSort": true,
                            "aaSorting": [],
                            pageLength: 7,
                            "lengthChange": false,
                            responsive: true,
                            language: {
                                search: ""
                            }
                        });
                    })
            }

            getListSupplierInvoice();
        });
</script>
@endsection
