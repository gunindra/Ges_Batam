@extends('layout.main')

@section('title', 'Tracking')

@section('main')

    <!-- Modal Tambah Tracking -->
    <div class="modal fade" id="modalTambahTracking" tabindex="-1" role="dialog" aria-labelledby="modalTambahTrackingTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahTrackingTitle">Tambah Tracking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="noDeliveryOrder" class="form-label fw-bold">No. Delivery Order</label>
                        <input type="text" class="form-control" id="noDeliveryOrder" value=""
                            placeholder="Masukkan No. Delivery Order">
                        <div id="noDeliveryOrderError" class="text-danger mt-1 d-none">Silahkan isi No. Delivery Order</div>
                    </div>
                    <div class="mt-3">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <input type="text" class="form-control" id="status" value="Dalam Perjalanan"
                            placeholder="Masukkan Status">
                        <div id="statusError" class="text-danger mt-1 d-none">Silahkan isi Status</div>
                    </div>
                    <div class="mt-3">
                        <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" cols="20" rows="10"
                            placeholder="Masukkan keterangan"></textarea>
                        <div id="keteranganError" class="text-danger mt-1 d-none">Silahkan isi Keterangan</div>
                    </div>
                    <div class="mt-3">
                        <label for="noResi" class="form-label">No Resi</label>
                        <input type="text" class="form-control" id="tags" name="noResi"
                            placeholder="Masukkan Nomor Resi" style="padding: 10px; border-radius: 5px;">
                        <div id="noResiError" class="text-danger d-none">
                            Silahkan masukkan Nomor Resi</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveTracking" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!--End Modal Tambah Tracking-->

    <!-- Modal Edit Tracking -->
    <div class="modal fade" id="modalEditTracking" tabindex="-1" role="dialog" aria-labelledby="modalEditTrackingTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditTrackingTitle">Edit Tracking : <span id="noResiEdit">-</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idTracking" id="idTrackingEdit">
                    <div class="mt-3">
                        <label for="noDeliveryOrderEdit" class="form-label fw-bold">No. Delivery Order</label>
                        <input type="text" class="form-control" id="noDeliveryOrderEdit" value=""
                            placeholder="Masukkan No. Delivery Order">
                        <div id="noDeliveryOrderErrorEdit" class="text-danger mt-1 d-none">Silahkan isi No. Delivery Order
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="keteranganEdit" class="form-label fw-bold">Keterangan</label>
                        <textarea class="form-control" name="keteranganEdit" id="keteranganEdit" cols="20" rows="10"
                            placeholder="Masukkan keterangan"></textarea>
                        <div id="keteranganErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Keterangan</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveUpdateTracking" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!--End Modal Edit Tracking-->

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 px-4">Tracking</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Tracking</li>
            </ol>
        </div>
        <div class="row mb-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahTracking" id="#modalCenter"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah Tracking</button>
                        </div>
                        <div id="containerTracking" class="table-responsive px-2">
                            {{-- <table class="table align-items-center table-flush table-hover" id="tableVendor">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No. Resi</th>
                                        <th>No. DO</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>GES293492384</td>
                                        <td>08339248</td>
                                        <td>Sedang Perjalanan</td>
                                        <td>Estimasi Sampai Sekitar 2 Bulan</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i
                                                    class="fas fa-trash"></i></a>
                                            <a href="#" class="btn btn-sm btn-primary btnGambar"><i
                                                    class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')

    <script>
        jQuery(document).ready(function($) {
            var tags = $('#tags').inputTags({
                tags: [],
                create: function() {
                    $('span', '#events').text('create');
                },
                update: function() {
                    $('span', '#events').text('update');
                },
                destroy: function() {
                    $('span', '#events').text('destroy');
                },
                selected: function() {
                    $('span', '#events').text('selected');
                },
                unselected: function() {
                    $('span', '#events').text('unselected');
                },
                change: function(elem) {
                    $('.results').empty().html('<strong>Tags:</strong> ' + elem.tags.join(' - '));
                },
                autocompleteTagSelect: function(elem) {
                    console.info('autocompleteTagSelect');
                }
            });
            var autocomplete = $('#tags').inputTags('options', 'autocomplete');
            $('span', '#autocomplete').text(autocomplete.values.join(', '));
        });

        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
            <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
        </div> `;

        const getlistTracking = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                    url: "{{ route('getlistTracking') }}",
                    method: "GET",
                    data: {
                        txSearch: txtSearch
                    },
                    beforeSend: () => {
                        $('#containerTracking').html(loadSpin)
                    }
                })
                .done(res => {
                    $('#containerTracking').html(res)
                    $('#tableTracking').DataTable({
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

        getlistTracking();

        $('#saveTracking').click(function() {

            var noDeliveryOrder = $('#noDeliveryOrder').val().trim();
            var status = $('#status').val();
            var keterangan = $('#keterangan').val();
            var noResi = $('#tags').inputTags('tags');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var isValid = true;

            if (noDeliveryOrder === '') {
                $('#noDeliveryOrderError').removeClass('d-none');
                isValid = false;
            } else {
                $('#noDeliveryOrderError').addClass('d-none');
            }

            if (status === '') {
                $('#statusError').removeClass('d-none');
                isValid = false;
            } else {
                $('#statusError').addClass('d-none');
            }

            if (keterangan === '') {
                $('#keteranganError').removeClass('d-none');
                isValid = false;
            } else {
                $('#keteranganError').addClass('d-none');
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
                        $.ajax({
                            url: "{{ route('addTracking') }}",
                            method: 'POST',
                            data: {
                                _token: csrfToken,
                                noDeliveryOrder: noDeliveryOrder,
                                status: status,
                                keterangan: keterangan,
                                noResi: noResi,
                            },
                            success: function(response) {

                                showMessage("success", "Data Berhasil Disimpan");
                                $('#modalTambahTracking').modal('hide');
                                getlistTracking();
                            },
                            error: function(xhr) {

                            }
                        });
                    }
                });
            } else {
                showMessage("error", "Mohon periksa input yang kosong");
            }
        });



        $(document).on('click', '.btnUpdateTracking', function(e) {
            let id = $(this).data('id')
            let no_do = $(this).data('no_do')
            let keterangan = $(this).data('keterangan');
            let no_resi =  $(this).data('no_resi');


            $('#noDeliveryOrderEdit').val(no_do);
            $('#keteranganEdit').val(keterangan);
            $('#idTrackingEdit').val(id)
            $('#noResiEdit').text(no_resi)
            $('#modalEditTracking').modal('show');
        })



        $('#saveUpdateTracking').click(function() {

            // Ambil nilai dari input
            var idTracking = $('#idTrackingEdit').val();
            var noDeliveryOrder = $('#noDeliveryOrderEdit').val().trim();
            var keterangan = $('#keteranganEdit').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            // Validasi No. Delivery Order
            if (noDeliveryOrder === '') {
                $('#noDeliveryOrderErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#noDeliveryOrderErrorEdit').addClass('d-none');
            }

            // Validasi Keterangan
            if (keterangan === '') {
                $('#keteranganErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#keteranganErrorEdit').addClass('d-none');
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
                        $.ajax({
                            url: "{{ route('updateTracking') }}",
                            method: 'POST',
                            data: {
                                _token: csrfToken,
                                id: idTracking,
                                noDeliveryOrder: noDeliveryOrder,
                                keterangan: keterangan,
                            },
                            success: function(response) {
                                showMessage("success", "Data Berhasil Diperbarui");
                                $('#modalEditTracking').modal('hide');
                                getlistTracking();
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            }
        });


        $(document).on('click', '.btnDestroyTracking', function(e) {
            let id = $(this).data('id');

            Swal.fire({
                title: "Apakah Kamu Yakin Ingin Hapus Tracking Ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5D87FF',
                cancelButtonColor: '#49BEFF',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('deleteTracking') }}",
                        data: {
                            id: id,
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showMessage("success",
                                    "Berhasil menghapus");
                                getlistTracking();
                            } else {
                                showMessage("error", "Gagal menghapus");
                            }
                        }
                    });
                }
            })
        });
    </script>


@endsection
