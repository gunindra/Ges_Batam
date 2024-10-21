@extends('layout.main')

@section('title', 'Vendor | SupplierInvoice')

@section('main')

    <style>
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }
    </style>
    <div class="container-fluid" id="container-wrapper">


        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Vendor Invoice</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Vendor</li>
                <li class="breadcrumb-item active" aria-current="page">Supplier Invoice</li>
            </ol>
        </div>
        <div class="modal fade" id="modalFilterTanggal" tabindex="-1" role="dialog"
            aria-labelledby="modalFilterTanggalTitle" aria-hidden="true">
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
                            <a class="btn btn-primary" id="" href="{{ route('addSupplierInvoice') }}"><span
                                    class="pr-2"><i class="fas fa-plus"></i></span>Tambah Vendor Invoice</a>
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
                            <table class="table align-items-center table-flush table-hover" id="supplierInvoiceTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nomor Invoice</th>
                                        <th>Vendor</th>
                                        <th>Tanggal</th>
                                        <th>Mata Uang</th>
                                        <th>Total Debit</th>
                                        <th>Total Credit</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
            let table = $('#supplierInvoiceTable').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ route('getlistSupplierInvoice') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'invoice_no',
                        name: 'invoice_no'
                    }, // Nomor Invoice
                    {
                        data: 'vendor',
                        name: 'vendor'
                    }, // Vendor
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    }, // Tanggal
                    {
                        data: 'matauang',
                        name: 'matauang'
                    }, // Mata Uang
                    {
                        data: 'total_debit',
                        name: 'total_debit'
                    }, // Total Debit
                    {
                        data: 'total_credit',
                        name: 'total_credit'
                    }, // Total Credit
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    } // Aksi
                ],
                order: [],
                lengthChange: false,
                language: {
                    info: "_START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    emptyTable: "No data available in table",
                    loadingRecords: "Loading...",
                    zeroRecords: "No matching records found"
                }
            });

            $('#txSearch').keyup(function() {
                var searchValue = $(this).val();
                table.search(searchValue).draw();
            });

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
