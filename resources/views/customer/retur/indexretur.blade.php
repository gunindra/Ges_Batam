@extends('layout.main')

@section('title', 'Customer | Retur')

@section('main')

    <style>
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }
    </style>


    <!-- Modal Detail Retur -->
    <div class="modal fade" id="modalDetailRetur" tabindex="-1" aria-labelledby="detailReturLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="modalImportExcelLabel">Detail Retur</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detail-retur-body">
                    <!-- Konten AJAX akan ditampilkan di sini -->
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="container-wrapper">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Retur</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Customer</li>
                <li class="breadcrumb-item active" aria-current="page">Retur</li>
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
                            <a class="btn btn-primary" href="{{ route('retur.buathalaman') }}" id=""><span
                                    class="pr-2"><i class="fas fa-plus"></i></span>Buat Retur</a>
                        </div>
                        <div class="d-flex mb-3">
                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                class="form-control rounded-3" placeholder="Search">
                            {{-- <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                                <option value="" selected disabled>Pilih Filter</option>
                            </select> --}}
                            {{-- <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button> --}}
                            {{-- <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">
                                Reset
                            </button> --}}
                        </div>
                        <div id="containerCreditNote" class="table-responsive px-3">
                            <table id="returTable" class="table table align-items-center table-flush table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>No Invoice</th>
                                        <th>Mata Uang</th>
                                        <th>Nama Akun</th>
                                        <th>Total</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
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
            let table = $('#returTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('retur.list') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'no_invoice',
                        name: 'i.no_invoice'
                    },
                    {
                        data: 'mata_uang',
                        name: 'm.singkatan_matauang'
                    },
                    {
                        data: 'nama_akun',
                        name: 'a.name'
                    },
                    {
                        data: 'total_nominal',
                        name: 'r.total_nominal'
                    },
                    {
                        data: 'deskripsi',
                        name: 'r.deskripsi'
                    },
                    {
                        data: 'created_at',
                        name: 'r.created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
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

            $(document).on('click', '.btn-lihat-retur', function() {
                let returId = $(this).data('id');

                $.ajax({
                    url: '/retur/showdetail/' + returId,
                    type: 'GET',
                    success: function(data) {
                        let html = `
                <p><strong>No Invoice:</strong> ${data.invoice.no_invoice}</p>
                <p><strong>Mata Uang:</strong> ${data.matauang.singkatan_matauang}</p>
                <p><strong>Akun:</strong> ${data.akun.name}</p>
                <p><strong>Total:</strong> Rp ${parseFloat(data.total_nominal).toLocaleString()}</p>
                <p><strong>Deskripsi:</strong> ${data.deskripsi ?? '-'}</p>
                <hr>
                <h6>Daftar Resi</h6>
                <ul>
                    ${data.items.map(item => `<li>${item.no_resi}</li>`).join('')}
                </ul>
            `;
                        $('#detail-retur-body').html(html);
                        $('#modalDetailRetur').modal('show');
                    },
                    error: function() {
                        Swal.fire('Gagal', 'Gagal mengambil data retur.', 'error');
                    }
                });
            });

        });
    </script>
@endsection
