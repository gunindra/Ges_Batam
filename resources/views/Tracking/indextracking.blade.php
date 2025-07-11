@extends('layout.main')

@section('title', 'Tracking')

@section('main')
    <style>
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }
    </style>

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
                        <textarea class="form-control" name="keterangan" id="keterangan" rows="5" placeholder="Masukkan keterangan"></textarea>
                        <div id="keteranganError" class="text-danger mt-1 d-none">Silahkan isi Keterangan</div>
                    </div>
                    <div class="mt-3">
                        <label for="noResi" class="form-label">No Resi (<span id="countResi">-</span>) </label>
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
                    <div class="mt-3">
                        <label for="noDeliveryOrderEdit" class="form-label fw-bold">No. Delivery Order</label>
                        <input type="text" class="form-control" id="noDeliveryOrderEdit" value=""
                            placeholder="Masukkan No. Delivery Order">
                        <div id="noDeliveryOrderErrorEdit" class="text-danger mt-1 d-none">Silahkan isi No. Delivery Order
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="keteranganEdit" class="form-label fw-bold">Keterangan</label>
                        <textarea class="form-control" name="keteranganEdit" id="keteranganEdit" rows="5"
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
                            @if (in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor']))
                                <button id="action-button" class="btn btn-danger mr-2" style="display: none">Delete
                                    Selected</button>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalTambahTracking" id="#modalCenter"><span class="pr-2"><i
                                            class="fas fa-plus"></i></span>Tambah Tracking</button>
                            @endif
                            @if (in_array(Auth::user()->role, ['customer']))
                                <button type="button" class="btn btn-success" id="exportExcel">Export Excel</button>
                            @endif
                        </div>
                        <div class="float-left d-flex">
                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                class="form-control rounded-3" placeholder="Search">
                            <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                                <option value="" selected disabled>Pilih Status</option>
                                @foreach ($listStatus as $status)
                                    <option value="{{ $status->status }}">{{ $status->status }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">
                                Reset
                            </button>
                        </div>
                        <div id="containerTracking" class="table-responsive px-2">
                            <table class="table align-items-center table-flush table-hover" id="tableTracking">
                                <thead class="thead-light">
                                    <tr>
                                        @if (in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor']))
                                            <th><input type="checkbox" id="select-all"></th>
                                        @endif
                                        <th>No. Resi</th>
                                        <th>No. DO</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Pembayaran</th>
                                        @if (in_array(Auth::user()->role, ['customer']))
                                            <th>Quantitas</th>
                                            <th>Barang diterima</th>
                                        @endif
                                        @if (in_array(Auth::user()->role, ['superadmin', 'admin', 'supervisor']))
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
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
        jQuery(document).ready(function($) {
            var tags = $('#tags').inputTags({
                tags: [],
                create: function() {
                    updateResiCount();
                },
                update: function() {
                    updateResiCount();
                },
                destroy: function() {
                    updateResiCount();
                },
                selected: function() {
                    updateResiCount();
                },
                unselected: function() {
                    updateResiCount();
                },
                change: function(elem) {
                    $('.results').empty().html('<strong>Tags:</strong> ' + elem.tags.join(' - '));
                    updateResiCount();
                },
                autocompleteTagSelect: function(elem) {
                    console.info('autocompleteTagSelect');
                }
            });

            function updateResiCount() {
                var count = $('#tags').inputTags('tags').length;
                $('#countResi').text(count + " Resi");
            }
        });

        var hasActionColumn = @json($hasActionColumn);
        var userRole = @json(Auth::user()->role);
        var selectedIds = new Set();
        var filteredIds = [];

        // Definisi kolom DataTables
        var columns = [{
                data: 'no_resi',
                name: 'no_resi'
            },
            {
                data: 'no_do',
                name: 'no_do'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'keterangan',
                name: 'keterangan'
            },
            {
                data: 'status_bayar',
                name: 'status_bayar',
            },
        ];

        if (hasActionColumn) {
            columns.unshift({
                data: 'select',
                name: 'select',
                orderable: false,
                searchable: false
            });
            columns.push({
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            });
        }

        if (userRole === 'customer') {
            columns.push({
                data: 'quantitas',
                name: 'quantitas',
                orderable: false,
                searchable: false
            }, {
                data: 'tanggal_penerimaan',
                name: 'tanggal_penerimaan',
                orderable: false,
                searchable: false,
                render: data => data ? data : '-'
            });
        }

        var table = $('#tableTracking').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('tracking.data') }}",
                method: 'GET',
                data: d => {
                    d.status = $('#filterStatus').val();
                    d.search = $('#txSearch').val();
                },
                dataSrc: json => {
                    filteredIds = json.filteredIds;
                    return json.data;
                }
            },
            columns: columns,
            order: [],
            lengthChange: false,
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                info: "_START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                emptyTable: "No data available in table",
                loadingRecords: "Loading...",
                zeroRecords: "No matching records found"
            }
        });

        // Restore checkbox saat berpindah halaman
        $('#tableTracking').on('draw.dt', function() {
            $('.select-row').each(function() {
                let id = $(this).data('id');
                $(this).prop('checked', selectedIds.has(id));
            });
            updateSelectAllCheckbox();
        });

        // Event: Klik Checkbox Per Baris
        $('#tableTracking tbody').on('change', '.select-row', function() {
            let id = $(this).data('id');
            this.checked ? selectedIds.add(id) : selectedIds.delete(id);
            toggleDeleteButton();
            updateSelectAllCheckbox();
        });

        // Event: Klik "Pilih Semua Data"
        $('#select-all').on('change', function() {
            if (this.checked) {
                selectedIds = new Set(filteredIds);
                $('.select-row').prop('checked', true);
            } else {
                selectedIds.clear();
                $('.select-row').prop('checked', false);
            }
            toggleDeleteButton();
        });

        function updateSelectAllCheckbox() {
            let isAllSelected = filteredIds.length > 0 && selectedIds.size === filteredIds.length;
            $('#select-all').prop('checked', isAllSelected);
            toggleDeleteButton();
        }

        // Function untuk mengatur tombol delete
        function toggleDeleteButton() {
            $('#action-button').toggle(selectedIds.size > 0);
        }

        // Event: Hapus Data Terpilih
        $('#action-button').on('click', function() {
            if (selectedIds.size > 0) {
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
                            url: "{{ route('deleteTrackingMultipe') }}",
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                ids: Array.from(selectedIds)
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', 'The selected rows have been deleted.',
                                    'success');
                                selectedIds.clear();
                                table.ajax.reload();
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error!', 'An error occurred: ' + error, 'error');
                            }
                        });
                    }
                });
            } else {
                Swal.fire('No rows selected!', 'Please select at least one row to delete.', 'info');
            }
        });

        // Event: Pencarian dan Filter Status
        $('#txSearch').keyup(() => table.search($('#txSearch').val()).draw());
        $('#filterStatus').change(() => table.ajax.reload());

        $('#saveTracking').click(function() {
            var noDeliveryOrder = $('#noDeliveryOrder').val().trim();
            var status = $('#status').val();
            var keterangan = $('#keterangan').val();
            var noResi = $('#tags').inputTags('tags');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            let countResi = $('#countResi').text();
            var isValid = true;

            // Validasi input
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

            if (noResi === undefined || noResi.length === 0) {
                $('#noResiError').removeClass('d-none');
                isValid = false;
            } else {
                $('#noResiError').addClass('d-none');
            }

            if (isValid) {
                Swal.fire({
                    title: `Apakah Anda yakin ingin menambahkan ${countResi} data?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5D87FF',
                    cancelButtonColor: '#49BEFF',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading
                        Swal.fire({
                            title: 'Mengirim data...',
                            text: 'Mohon tunggu sebentar',
                            icon: 'info',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Disable tombol
                        $('#saveTracking').prop('disabled', true);

                        $.ajax({
                            url: '/tracking/store',
                            method: 'POST',
                            data: {
                                noDeliveryOrder: noDeliveryOrder,
                                status: status,
                                keterangan: keterangan,
                                noResi: noResi,
                                _token: csrfToken,
                            },
                            success: function(response) {
                                if (response.success) {
                                    let jobId = response.jobId;

                                    // Tampilkan progress bar
                                    Swal.fire({
                                        title: 'Memproses Data...',
                                        html: `
                                    <p>Harap tunggu hingga proses selesai</p>
                                    <div class="progress">
                                        <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                `,
                                        showConfirmButton: false,
                                        allowOutsideClick: false,
                                    });

                                    // Polling progress setiap 2 detik
                                    let interval = setInterval(function() {
                                        $.get(`/job/status/${jobId}`, function(
                                            response) {
                                            let progress = response.progress;
                                            let failedItems = response.failed ||
                                                [];

                                            $('#progress-bar').css('width',
                                                progress + '%').attr(
                                                'aria-valuenow', progress);

                                            if (progress >= 100) {
                                                clearInterval(interval);

                                                if (failedItems.length > 0) {
                                                    let failedMessages =
                                                        failedItems.map(item =>
                                                            `- ${item.resi}: ${item.error}`
                                                        ).join('<br>');

                                                    Swal.fire({
                                                        title: 'Proses Selesai, Tapi Ada Data Gagal!',
                                                        html: `Beberapa data gagal diproses:<br>${failedMessages}`,
                                                        icon: 'warning',
                                                        confirmButtonText: 'Lihat Detail'
                                                    }).then(() => {
                                                        $('#saveTracking')
                                                            .prop(
                                                                'disabled',
                                                                false);
                                                        $('#modalTambahTracking')
                                                            .modal(
                                                                'hide');
                                                        table.ajax
                                                            .reload();
                                                    });
                                                } else {
                                                    Swal.fire({
                                                        title: 'Selesai!',
                                                        text: 'Semua data berhasil diproses',
                                                        icon: 'success',
                                                        timer: 2000,
                                                        showConfirmButton: false
                                                    }).then(() => {
                                                        $('#saveTracking')
                                                            .prop(
                                                                'disabled',
                                                                false);
                                                        $('#modalTambahTracking')
                                                            .modal(
                                                                'hide');
                                                        table.ajax
                                                            .reload();
                                                    });
                                                }
                                            }
                                        }).fail(function() {
                                            clearInterval(interval);
                                            Swal.fire('Error!',
                                                'Gagal mengambil progress, coba lagi nanti',
                                                'error');
                                            $('#saveTracking').prop('disabled',
                                                false);
                                        });
                                    }, 2000);
                                }
                            },
                            error: function(response) {
                                Swal.close();
                                $('#saveTracking').prop('disabled', false);
                                if (response.status === 400 && response.responseJSON.warning) {
                                    let duplicateResi = response.responseJSON.duplicateResi ||
                                    [];
                                    let duplicateMessage = duplicateResi.length > 0 ?
                                        `No Resi berikut duplikat:<br>${duplicateResi.join('<br>')}` :
                                        '';

                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Peringatan',
                                        html: `${response.responseJSON.warning}<br>${duplicateMessage}`,
                                    });
                                } else {
                                    showMessage("error", "Terjadi kesalahan, coba lagi nanti");
                                }
                            }
                        });
                    }
                });
            }
        });

        $('#modalTambahTracking').on('hidden.bs.modal', function() {
            $('#noDeliveryOrder,#keterangan,#noResi').val('');
            if (!$('#noDeliveryOrderError').hasClass('d-none')) {
                $('#noDeliveryOrderError').addClass('d-none');

            }
            if (!$('#noResiError').hasClass('d-none')) {
                $('#noResiError').addClass('d-none');

            }
            if (!$('#keteranganError').hasClass('d-none')) {
                $('#keteranganError').addClass('d-none');

            }
        });
        $('#modalEditTracking').on('hidden.bs.modal', function() {
            $('#noDeliveryOrderEdit,#keteranganEdit').val('');
            if (!$('#noDeliveryOrderErrorEdit').hasClass('d-none')) {
                $('#noDeliveryOrderErrorEdit').addClass('d-none');

            }
            if (!$('#keteranganErrorEdit').hasClass('d-none')) {
                $('#keteranganErrorEdit').addClass('d-none');

            }
        });


        $(document).on('click', '.btnUpdateTracking', function(e) {
            var trackingid = $(this).data('id');
            $.ajax({
                url: '/tracking/' + trackingid,
                method: 'GET',
                success: function(response) {
                    $('#noDeliveryOrderEdit').val(response.no_do);
                    $('#keteranganEdit').val(response.keterangan);
                    $('#noResiEdit').text(response.no_resi);
                    $('#modalEditTracking').modal('show');
                    $('#saveUpdateTracking').data('id', trackingid);
                },
                error: function() {
                    showMessage("error",
                        "Terjadi kesalahan saat mengambil data");
                }
            });
        });
        $('#saveUpdateTracking').on('click', function() {
            var trackingid = $(this).data('id');
            var noDeliveryOrder = $('#noDeliveryOrderEdit').val().trim();
            var keterangan = $('#keteranganEdit').val();


            var isValid = true;

            if (noDeliveryOrder === '') {
                $('#noDeliveryOrderErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#noDeliveryOrderErrorEdit').addClass('d-none');
            }

            if (keterangan === '') {
                $('#keteranganErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#keteranganErrorEdit').addClass('d-none');
            }

            if (isValid) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
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
                            title: 'Loading...',
                            text: 'Please wait while we are updating the data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: '/tracking/updateTracking/' + trackingid,
                            method: 'PUT',
                            data: {
                                noDeliveryOrder: noDeliveryOrder,
                                keterangan: keterangan,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success", response
                                        .message);
                                    $('#modalEditTracking').modal('hide');
                                    table.ajax.reload();
                                }
                            },
                            error: function(response) {
                                Swal.close();
                                showMessage("error",
                                    "Terjadi kesalahan, coba lagi nanti");
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
                    Swal.fire({
                        title: 'Sedang memproses...',
                        text: 'Harap menunggu hingga proses delete selesai',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        type: "DELETE",
                        url: '/tracking/deleteTracking/' + id,
                        data: {
                            id: id,
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(response) {
                            Swal.close();

                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });

                                table.ajax.reload(); // Reload data di datatable jika ada
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Menghapus',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.close();

                            let errorMessage = "Terjadi kesalahan saat menghapus data.";

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage
                            });
                        }
                    });
                }
            });
        });


        $(document).on('click', '.btnChangeStatus', function(e) {
            let id = $(this).data('id');

               Swal.fire({
                title: "Apakah Kamu Yakin Ingin Update Status Tracking Ini?",
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
                        text: 'Harap menunggu hingga proses delete selesai',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        type: "GET",
                        url: '/tracking/updateData/' + id,
                        data: {
                            id: id,
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(response) {
                            Swal.close();

                          if (response.success === true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });

                                table.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal Mengupdate',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.close();

                            let errorMessage = "Terjadi kesalahan saat Mengupdate data.";

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage
                            });
                        }
                    });
                }
            });
        });


        $(document).on('click', '#exportExcel', function() {
            let status = $('#filterStatus').val();

            let now = new Date();
            let day = String(now.getDate()).padStart(2, '0');
            let month = now.toLocaleString('default', {
                month: 'long'
            });
            let year = now.getFullYear();
            let hours = String(now.getHours()).padStart(2, '0');
            let minutes = String(now.getMinutes()).padStart(2, '0');
            let seconds = String(now.getSeconds()).padStart(2, '0');

            let filename = `Sales_${day} ${month} ${year} ${hours}:${minutes}:${seconds}.xlsx`;

            let form = $('<form>', {
                action: "{{ route('tracking.excel') }}",
                method: 'POST'
            }).append(
                $('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }),
                $('<input>', {
                    type: 'hidden',
                    name: 'status',
                    value: status
                })
            );

            $('body').append(form);
            form.submit();
        });
    </script>


@endsection
