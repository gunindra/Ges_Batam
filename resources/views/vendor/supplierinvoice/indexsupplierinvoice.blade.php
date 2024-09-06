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
    <div class="modal fade" id="modalFilterTanggal" tabindex="-1" role="dialog" aria-labelledby="modalFilterTanggalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilterTanggalTitle">Filter Tanggal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mt-3">
                                <label for="pembayaranStatus" class="form-label fw-bold">Pilih Tanggal:</label>
                                <div class="d-flex align-items-center">
                                    <input type="date" id="startDate" class="form-control"
                                        placeholder="Pilih tanggal mulai" style="width: 200px;">
                                    <span class="mx-2">sampai</span>
                                    <input type="date" id="endDate" class="form-control"
                                        placeholder="Pilih tanggal akhir" style="width: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveFilterTanggal" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
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
                        <div class="float-left">
                        <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                                    <option value="" selected disabled>Pilih Status</option>
                                    @foreach ($listStatus as $status)
                                        <option value="{{ $status->status_name }}">{{ $status->status_name }}</option>
                                    @endforeach
                        </select>
                        </div>
                        <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                        <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                    onclick="window.location.reload()">
                                    Reset
                        </button>
                        <div id="containerSupplierInvoice" class="table-responsive px-3 ">
                            <!-- <table class="table align-items-center table-flush table-hover" id="tableRekening">
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
                            </table> -->
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
                const filterStatus = $('#filterStatus').val();
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();

                $.ajax({
                        url: "{{ route('getlistSupplierInvoice') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch,
                            status: filterStatus,
                            startDate: startDate,
                            endDate: endDate
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

           $('#txSearch').keyup(function(e) {
            var inputText = $(this).val();
            if (inputText.length >= 1 || inputText.length == 0) {
                getListSupplierInvoice();
            }
        })
        $('#filterStatus').change(function() {
                getListSupplierInvoice();
            });
            flatpickr("#startDate", {
                dateFormat: "d M Y",
                onChange: function(selectedDates, dateStr, instance) {

                    $("#endDate").flatpickr({
                        dateFormat: "d M Y",
                        minDate: dateStr
                    });
                }
            });

            flatpickr("#endDate", {
                dateFormat: "d MM Y",
                onChange: function(selectedDates, dateStr, instance) {
                    var startDate = new Date($('#startDate').val());
                    var endDate = new Date(dateStr);
                    if (endDate < startDate) {
                        showwMassage(error,
                            "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                        $('#endDate').val('');
                    }
                }
            });
            $(document).on('click', '#filterTanggal', function(e) {
                $('#modalFilterTanggal').modal('show');
            });
            $('#saveFilterTanggal').click(function() {
                getListSupplierInvoice();
                $('#modalFilterTanggal').modal('hide');
            });
        });
</script>
@endsection
