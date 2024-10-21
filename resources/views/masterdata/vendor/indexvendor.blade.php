@extends('layout.main')

@section('title', 'Master Data | Costumer')

@section('main')

    <style>
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }
    </style>
    </style>

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">

        <!-- Modal Tambah Vendor -->
        <div class="modal fade" id="modalTambahVendor" tabindex="-1" role="dialog" aria-labelledby="modalTambahVendorTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahVendorTitle">Tambah Vendor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Nama Vendor -->
                        <div class="mt-3">
                            <label for="namaVendor" class="form-label fw-bold">Nama Vendor</label>
                            <input type="text" class="form-control" id="namaVendor" name="name"
                                placeholder="Masukkan nama Vendor">
                            <div id="namaVendorError" class="text-danger mt-1 d-none">Silahkan isi nama Vendor</div>
                        </div>

                        <!-- Alamat Vendor -->
                        <div class="mt-3">
                            <label for="alamatVendor" class="form-label fw-bold">Alamat Vendor</label>
                            <textarea class="form-control" id="alamatVendor" name="address" placeholder="Masukkan alamat Vendor" rows="3"></textarea>
                            <div id="alamatVendorError" class="text-danger mt-1 d-none">Silahkan isi alamat Vendor</div>
                        </div>

                        <!-- Nomor Telepon Vendor -->
                        <div class="mt-3">
                            <label for="noTelpon" class="form-label fw-bold">No. Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text" id="nomor">+62</span>
                                <input type="text" placeholder="8**********" class="form-control" id="noTelpon"
                                    name="phone">
                            </div>
                            <div id="notelponVendorError" class="text-danger mt-1 d-none">Silahkan isi no. telepon Vendor
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Tutup</button>
                        <button type="button" id="saveVendor" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Edit -->
        <div class="modal fade" id="modalEditVendor" tabindex="-1" role="dialog" aria-labelledby="modalEditVendorTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditVendorTitle">Edit Vendor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="VendorIdEdit">
                        <div class="mt-3">
                            <label for="namaVendorEdit" class="form-label fw-bold">Nama Vendor</label>
                            <input type="text" class="form-control" id="namaVendorEdit" value=""
                                placeholder="Masukkan nama Vendor">
                            <div id="namaVendorErrorEdit" class="text-danger mt-1 d-none">Silahkan isi nama Vendor
                            </div>
                        </div>

                        <div id="alamatSectionEdit">
                            <div id="alamatContainerEdit">
                                <div class="mt-3 alamat-item">
                                    <label for="alamatVendorEdit" class="form-label fw-bold">Alamat</label>
                                    <textarea class="form-control" id="alamatVendorEdit" name="alamatVendorEdit" placeholder="Masukkan alamat"
                                        rows="3"></textarea>
                                    <div class="text-danger mt-1 d-none alamat-error">Silahkan isi alamat Vendor</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label for="noTelponEdit" class="form-label fw-bold">No. Telpon</label>
                            <div class="input-group">
                                <span class="input-group-text" id="nomorEdit">+62</span>
                                <input type="text" placeholder="8**********" class="form-control" id="noTelponEdit"
                                    value="">
                            </div>
                            <div id="notelponVendorErrorEdit" class="text-danger mt-1 d-none">Silahkan isi no. telepon
                                Vendor
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveEditCostumer" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End Modal Edit -->


        <!-- Modal Detail -->
        <div class="modal fade" id="modalPointCostumer" tabindex="-1" role="dialog"
            aria-labelledby="modalPointCostumerTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg rounded-lg">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPointCostumerTitle">Detail Costumer</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <div class="mb-4">
                                <h1 id="pointValue" class="display-3 font-weight-bold text-primary" value="0">0</h1>
                                <p class="text-muted">Poin</p>
                            </div>
                            <!-- <div>
                                                                                        <p id="statusValue" class="h5"></p>
                                                                                    </div> -->
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-primary btn-lg px-4"
                            data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End Modal Detail -->


        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Vendor</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Master Data</li>
                <li class="breadcrumb-item active" aria-current="page">Vendor</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahVendor" id="modalTambahCost"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah Vendor</button>
                        </div>
                        <div class="float-left">
                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                class="form-control rounded-3" placeholder="Search">
                        </div>
                        {{-- <div class="float-left ps-4">
                            <select class="form-control ml-2" id="filterStatus" name="status" style="width: 200px;">
                                <option value="" selected disabled>Pilih Status</option>
                                <option value="1">Active</option>
                                <option value="0">Non Active</option>
                            </select>
                        </div>
                        <div class="float-left ps-4">
                            <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">
                                Reset
                            </button>
                        </div> --}}
                        <div id="containerVendor" class="table-responsive px-3">
                            <table class="table align-items-center table-flush table-hover" id="vendorTable">
                                <thead class="thead-light">
                                    <tr>

                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Aksi</th>
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
    <!---Container Fluid-->
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            let table = $('#vendorTable').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ route('vendors.getVendors') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [],
                lengthChange: false,
                pageLength: 7,
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

            // Event ketika tombol edit diklik
            $('body').on('click', '.editVendor', function() {
                let vendorId = $(this).data('id'); // Ambil ID dari tombol edit

                // Lakukan AJAX untuk mendapatkan data vendor berdasarkan ID
                $.ajax({
                    url: "{{ route('vendors.getVendorById') }}", // Route yang akan dibuat untuk mengambil data vendor
                    type: "GET",
                    data: {
                        id: vendorId
                    }, // Kirim ID vendor
                    success: function(response) {
                        if (response.status === 'success') {
                            // Isi form modal dengan data yang diterima
                            $('#VendorIdEdit').val(response.data.id);
                            $('#namaVendorEdit').val(response.data.name);
                            $('#noTelponEdit').val(response.data.phone);
                            $('#alamatContainerEdit textarea').val(response.data.address);

                            // Simpan ID vendor di data attribute tombol Save Edit
                            $('#saveEditCostumer').data('vendor-id', response.data.id);

                            $('#modalEditVendor').modal('show');
                        } else {
                            showMessage('error', 'Gagal mengambil data vendor');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Terjadi kesalahan:', error);
                        showMessage('error', 'Gagal mengambil data vendor');
                    }
                });
            });


            $('#saveVendor').click(function(e) {
                e.preventDefault();

                // Ambil nilai input
                let nama = $('#namaVendor').val();
                let alamat = $('#alamatVendor').val();
                let phone = $('#noTelpon').val();

                // Reset error messages
                $('.text-danger').addClass('d-none');
                let valid = true;

                // Validasi input
                if (nama === "") {
                    $('#namaVendorError').removeClass('d-none');
                    valid = false;
                }

                if (alamat === "") {
                    $('#alamatVendorError').removeClass('d-none');
                    valid = false;
                }

                if (phone === "") {
                    $('#notelponVendorError').removeClass('d-none');
                    valid = false;
                }

                if (!valid) {
                    showMessage('error', 'Harap isi semua field yang diperlukan.');
                    return;
                }

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
                            text: 'Mohon tunggu, sedang memperbarui data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: "{{ route('vendors.store') }}",
                            type: "POST",
                            data: {
                                name: nama,
                                address: alamat,
                                phone: phone,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                showMessage('success', 'Vendor berhasil disimpan!');
                                $('#modalTambahVendor').modal('hide');
                                table.ajax.reload();

                            },
                            error: function(error) {
                                showMessage('error',
                                    'Terjadi kesalahan, silahkan coba lagi.');
                            }
                        });
                    }
                });
            });

            $('#saveEditCostumer').click(function(e) {
                e.preventDefault();

                // Ambil ID vendor dari data attribute di tombol Save Edit
                let vendorId = $(this).data('vendor-id');

                // Ambil nilai input dari form edit
                let nama = $('#namaVendorEdit').val();
                let alamat = $('#alamatVendorEdit').val();

                console.log(alamat);

                let phone = $('#noTelponEdit').val();

                // Reset error messages
                $('.text-danger').addClass('d-none');
                let valid = true;

                // Validasi input
                if (nama === "") {
                    $('#namaVendorErrorEdit').removeClass('d-none');
                    valid = false;
                }

                if (alamat === "") {
                    $('#alamatVendorErrorEdit').removeClass('d-none');
                    valid = false;
                }

                if (phone === "") {
                    $('#notelponVendorErrorEdit').removeClass('d-none');
                    valid = false;
                }

                if (!valid) {
                    showMessage('error', 'Harap isi semua field yang diperlukan.');
                    return;
                }

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
                            text: 'Mohon tunggu, sedang memperbarui data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: "/vendors/edit/" + vendorId,
                            type: "PUT",
                            data: {
                                name: nama,
                                address: alamat,
                                phone: phone,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.status ===
                                    'success') {
                                    showMessage('success', response
                                    .message);
                                    $('#modalEditVendor').modal('hide');
                                    table.ajax.reload();
                                } else {
                                    showMessage('error', response
                                    .message);
                                }
                            },
                            error: function(error) {
                                showMessage('error',
                                    'Terjadi kesalahan, silahkan coba lagi.');
                            }
                        });
                    }
                });
            });

        });
    </script>
@endsection
