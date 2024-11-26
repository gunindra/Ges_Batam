@extends('layout.main')

@section('title', 'Periode')

@section('main')
<style>
    .dataTables_length,
    .dataTables_filter {
        display: none;
    }
</style>

<!-- Modal Tambah Periode -->
<div class="modal fade" id="modalTambahPeriode" tabindex="-1" role="dialog" aria-labelledby="modalTambahPeriodeTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPeriodeTitle">Tambah Periode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-3">
                    <label for="periode" class="form-label fw-bold">Periode</label>
                    <input type="text" class="form-control" id="periode" value="" placeholder="Masukkan Periode">
                    <div id="periodeError" class="text-danger mt-1 d-none">Silahkan isi Periode</div>
                </div>
                <div class="mt-3">
                    <label for="periodeStart" class="form-label fw-bold">Periode Start</label>
                    <input type="text" class="form-control" id="periodeStart" value=""
                        placeholder="Masukkan Periode Start">
                    <div id="periodeStartError" class="text-danger mt-1 d-none">Silahkan isi Periode Start</div>
                </div>
                <div class="mt-3">
                    <label for="periodeEnd" class="form-label fw-bold">Periode End</label>
                    <input type="text" class="form-control" id="periodeEnd" value="" placeholder="Masukkan Periode End">
                    <div id="periodeEndError" class="text-danger mt-1 d-none">Silahkan isi Periode End</div>
                </div>
                <div class="mt-3">
                    <label for="status" class="form-label fw-bold">Status</label>
                    <input type="text" class="form-control" id="status" value="Open" placeholder="Masukkan Status">
                    <div id="statusError" class="text-danger mt-1 d-none">Silahkan isi Status</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                <button type="button" id="savePeriode" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!--End Modal Tambah Periode-->

<!-- Modal Edit Periode -->
<div class="modal fade" id="modalEditPeriode" tabindex="-1" role="dialog" aria-labelledby="modalEditPeriodeTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditPeriodeTitle">Edit Periode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-3">
                    <label for="periodeEdit" class="form-label fw-bold">Periode</label>
                    <input type="text" class="form-control" id="periodeEdit" value="" placeholder="Masukkan Periode">
                    <div id="periodeEditError" class="text-danger mt-1 d-none">Silahkan isi Periode</div>
                </div>
                <div class="mt-3">
                    <label for="periodeStartEdit" class="form-label fw-bold">Periode Start</label>
                    <input type="text" class="form-control" id="periodeStartEdit" value=""
                        placeholder="Masukkan Periode Start">
                    <div id="periodeStartEditError" class="text-danger mt-1 d-none">Silahkan isi Periode Start</div>
                </div>
                <div class="mt-3">
                    <label for="periodeEndEdit" class="form-label fw-bold">Periode End</label>
                    <input type="text" class="form-control" id="periodeEndEdit" value=""
                        placeholder="Masukkan Periode End">
                    <div id="periodeEndEditError" class="text-danger mt-1 d-none">Silahkan isi Periode End</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                <button type="button" id="saveUpdatePeriode" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!--End Modal Edit Periode-->

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 px-4">Periode</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active" aria-current="page">Periode</li>
        </ol>
    </div>
    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahPeriode" id="#modalCenter"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Tambah Periode</button>
                    </div>
                    <div class="float-left d-flex">
                        <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                            class="form-control rounded-3" placeholder="Search">
                        <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                            <option value="" selected disabled>Pilih Status</option>

                        </select>
                        <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                            onclick="window.location.reload()">
                            Reset
                        </button>
                    </div>
                    <div id="containerPeriode" class="table-responsive px-2">
                        <table class="table align-items-center table-flush table-hover" id="tablePeriode">
                            <thead class="thead-light">
                                <tr>
                                    <th>Periode</th>
                                    <th>Periode Start</th>
                                    <th>Periode End</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td>GES293492384</td>
                                    <td>08339248</td>
                                    <td>Sedang Perjalanan</td>
                                    <td>Estimasi Sampai Sekitar 2 Bulan</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        <a href="#" class="btn btn-sm btn-primary btnGambar"><i
                                                class="fas fa-eye"></i></a>
                                    </td>
                                </tr> --}}
                            </tbody>
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
    $(document).ready(function () {
        let table = $('#tablePeriode').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('getPeriode') }}",
                type: 'GET',
                data: function (d) {
                    d.status = $('#filterStatus').val();
                    d.startDate = $('#startDate').val();
                    d.endDate = $('#endDate').val();
                },
                error: function (xhr, error, thrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load periode data. Please try again!',
                        confirmButtonText: 'OK'
                    });
                }
            },
            columns: [{
                data: 'periode',
                name: 'periode'
            },
            {
                data: 'periode_start',
                name: 'periode_start'
            },
            {
                data: 'periode_end',
                name: 'periode_end',
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
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
        $('#savePeriode').click(function () {
            var periode = $('#periode').val();
            var periodeStart = $('#periodeStart').val();
            var periodeEnd = $('#periodeEnd').val();
            var status = $('#status').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var isValid = true;

            if (periode === '') {
                $('#periodeError').removeClass('d-none');
                isValid = false;
            } else {
                $('#periodeError').addClass('d-none');
            }

            if (periodeStart === '') {
                $('#periodeStartError').removeClass('d-none');
                isValid = false;
            } else {
                $('#periodeStartError').addClass('d-none');
            }

            if (periodeEnd === '') {
                $('#periodeEndError').removeClass('d-none');
                isValid = false;
            } else {
                $('#periodeEndError').addClass('d-none');
            }
            if (isValid) {
                Swal.fire({
                    title: "Apakah Kamu Yakin?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5D87FF',
                    cancelButtonColor: '#49BEFF',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            title: 'Sedang memproses...',
                            text: 'Harap menunggu hingga proses selesai',
                            icon: 'info',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            url: '/masterdata/periode/store',
                            method: 'POST',
                            data: {
                                periode: periode,
                                periodeStart: periodeStart,
                                periodeEnd: periodeEnd,
                                status: status,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    $('#modalTambahPeriode').modal('hide');
                                    showMessage("success",
                                        "Berhasil ditambahkan");
                                    $('#modalTambahPeriode').modal('hide');
                                    table.ajax.reload();
                                }
                            },
                            error: function (response) {
                                Swal.close();
                                showMessage("error",
                                    "Terjadi kesalahan, coba lagi nanti");
                            }
                        });
                    }
                });
            }
        });
        var today = new Date();
        $('#periodeStart, #periodeEnd').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        }).datepicker('setDate', today);
    });

</script>


@endsection