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
            }
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


        let selectedIds = new Set();
        let allIds = [];

        var table = $('#tableTracking').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('tracking.data') }}",
                method: 'GET',
                data: function(d) {
                    d.status = $('#filterStatus').val();
                },
                dataSrc: function(json) {
                    allIds = json.allIds;
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

        // 🔹 **Restore checkbox saat berpindah halaman**
        table.on('draw.dt', function() {
            $('.select-row').each(function() {
                let id = $(this).data('id');
                if (selectedIds.has(id)) {
                    $(this).prop('checked', true);
                }
            });

            updateSelectAllCheckbox();
        });

        // 🔹 **Event: Klik Checkbox Per Baris**
        $('#tableTracking tbody').on('change', '.select-row', function() {
            let id = $(this).data('id');

            if (this.checked) {
                selectedIds.add(id);
            } else {
                selectedIds.delete(id);
            }
            toggleDeleteButton();
            updateSelectAllCheckbox();
        });

        // 🔹 **Event: Klik "Pilih Semua Data"**
        $('#select-all').on('change', function() {
            if (this.checked) {
                selectedIds = new Set(allIds); // Pilih semua ID
                $('.select-row').prop('checked', true);
            } else {
                selectedIds.clear();
                $('.select-row').prop('checked', false);
            }
            toggleDeleteButton();
        });

        function updateSelectAllCheckbox() {
            let isAllSelected = allIds.length > 0 && selectedIds.size === allIds.length;
            $('#select-all').prop('checked', isAllSelected);
            toggleDeleteButton();
        }

        // Function to show/hide delete button
        function toggleDeleteButton() {
            var selectedCount = $('#tableTracking .select-row:checked').length;
            if (selectedCount > 0) {
                $('#action-button').fadeIn(); // Tampilkan tombol dengan efek fade
            } else {
                $('#action-button').fadeOut(); // Sembunyikan tombol dengan efek fade
            }
        }

        // Example action for selected rows
        $('#action-button').on('click', function() {
            if (selectedIds.size > 0) {
                // Show SweetAlert confirmation
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
                            url: "{{ route('deleteTrackingMultipe') }}", // Endpoint to handle deletion
                            method: 'DELETE', // Use DELETE method
                            data: {
                                ids: Array.from(selectedIds), // Kirimkan array dari selectedIds
                                _token: $('meta[name="csrf-token"]').attr(
                                    'content') // Add CSRF token
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', 'The selected rows have been deleted.',
                                    'success');
                                selectedIds.clear(); // Kosongkan daftar ID setelah dihapus
                                location.reload(); // Reload the page
                            },
                            error: function(xhr, status, error) {
                                // Handle error
                                Swal.fire('Error!', 'An error occurred: ' + error, 'error');
                            }
                        });
                    }
                });
            } else {
                Swal.fire('No rows selected!', 'Please select at least one row to delete.', 'info');
            }
        });

        $('#txSearch').keyup(function() {
            var searchValue = $(this).val();
            table.search(searchValue).draw();
        });
        $('#filterStatus').change(function() {
            table.ajax.reload();
        });

        $('#saveTracking').click(function() {
            var noDeliveryOrder = $('#noDeliveryOrder').val().trim();
            var status = $('#status').val();
            var keterangan = $('#keterangan').val();
            var noResi = $('#tags').inputTags('tags');
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            let countResi =  $('#countResi').text();
            var isValid = true;

            // Validation
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
                    title: `Apakah Anda yakin ingin menambahkan ${countResi}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5D87FF',
                    cancelButtonColor: '#49BEFF',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading during the process
                        Swal.fire({
                            title: 'Sedang memproses...',
                            text: 'Harap menunggu hingga proses selesai',
                            icon: 'info',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Disable the button to prevent multiple submissions
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
                                Swal.close();
                                $('#saveTracking').prop('disabled',
                                    false); // Re-enable the button
                                if (response.success) {
                                    $('#modalTambahTracking').modal('hide');
                                    showMessage("success", "Berhasil ditambahkan");
                                    $('#modalTambahTracking').modal('hide');
                                    table.ajax.reload();
                                }
                            },
                            error: function(response) {
                                Swal.close();
                                $('#saveTracking').prop('disabled',
                                    false); // Re-enable the button
                                if (response.status === 400 && response.responseJSON.error) {
                                    showMessage("error", response.responseJSON
                                        .error); // Handle duplicate or other errors
                                } else {
                                    showMessage("error",
                                        "Terjadi kesalahan, coba lagi nanti"); // General error
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

                            if (response.url) {
                                window.open(response.url, '_blank');
                            } else if (response.error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.error
                                });
                            }
                            if (response.status === 'success') {

                                showMessage("success",
                                    "Berhasil menghapus");
                                table.ajax.reload();
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
