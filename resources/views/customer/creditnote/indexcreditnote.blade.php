@extends('layout.main')

@section('title', 'Customer | Credit Note')

@section('main')

    <style>
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }
    </style>
    <div class="container-fluid" id="container-wrapper">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Credit Note</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Customer</li>
                <li class="breadcrumb-item active" aria-current="page">Credit Note</li>
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
                                    <label for="Tanggal" class="form-label fw-bold">Pilih Tanggal:</label>
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
                            <a class="btn btn-primary" href="{{ route('addCreditNote') }}" id=""><span
                                    class="pr-2"><i class="fas fa-plus"></i></span>Buat Credit Note</a>
                        </div>
                        <div class="d-flex mb-3">
                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                class="form-control rounded-3" placeholder="Search">
                            <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                                <option value="" selected disabled>Pilih Filter</option>
                                @foreach ($listStatus as $status)
                                    <option value="{{ $status->name }}">
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                            <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">
                                Reset
                            </button>
                        </div>
                        <div id="containerCreditNote" class="table-responsive px-3">
                            <table id="creditNoteTable" class="table align-items-center table-flush table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No. Credit Note</th>
                                        <th>No. Invoice</th>
                                        <th>Nama Account</th>
                                        <th>Currency</th>
                                        <th>Tanggal</th>
                                        <th>Action</th>
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

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var table = $('#creditNoteTable').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('getCreditNotes') }}",
                    type: 'GET',
                    data: function(d) {
                        d.status = $('#filterStatus').val();
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                    },
                    error: function(xhr, error, thrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load credit note data. Please try again!',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                columns: [{
                        data: 'no_creditnote',
                        name: 'cn.no_creditnote'
                    },
                    {
                        data: 'invoice',
                        name: 'inv.no_invoice'
                    },
                    {
                        data: 'coa_name',
                        name: 'coa.name'
                    },
                    {
                        data: 'currency',
                        name: 'mu.singkatan_matauang'
                    },
                    {
                        data: 'tanggal',
                        name: 'cn.created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'asc']
                ],
                lengthChange: false,
                pageLength: 7,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
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



            $('#saveFilterTanggal').on('click', function() {
                table.ajax.reload();
                $('#modalFilterTanggal').modal('hide');
            });

            $(document).on('click', '#filterTanggal', function(e) {
                $('#modalFilterTanggal').modal('show');
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
                        showMessage(error,
                            "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                        $('#endDate').val('');
                    }
                }
            });

            $('#filterStatus').change(function() {
                table.ajax.reload();
            });


            $(document).on('click', '.btnedit', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                var url = "{{ route('updatepage', ':id') }}";
                url = url.replace(':id', id);
                window.location.href = url;
            });

        })
    </script>
@endsection
