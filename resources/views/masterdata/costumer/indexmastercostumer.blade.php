@extends('layout.main')

@section('title', 'Master Data | Costumer')

@section('main')
    <style>
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }
    </style>
    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <!-- Modal Tambah Customer -->
        <div class="modal fade" id="modalTambahCustomer" tabindex="-1" role="dialog" aria-labelledby="modalTambahCustomerTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahCustomerTitle">Tambah Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Mulai Row -->
                        <div class="row">
                            <!-- Kolom Pertama -->
                            <div class="col-md-6">
                                <div class="mt-3">
                                    <label for="markingCustomer" class="form-label fw-bold">Marking</label>
                                    <input type="text" class="form-control" id="markingCustomer" value="">
                                    <div id="markingCostumerError" class="text-danger mt-1 d-none">Silahkan isi marking
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="namaCustomer" class="form-label fw-bold">Nama Customer</label>
                                    <input type="text" class="form-control" id="namaCustomer" value=""
                                        placeholder="Masukkan nama customer">
                                    <div id="namaCustomerError" class="text-danger mt-1 d-none">Silahkan isi nama customer
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="emailCustomer" class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" id="emailCustomer" value=""
                                        placeholder="Masukkan email customer">
                                    <div id="emailCustomerError" class="text-danger mt-1 d-none">Silahkan isi email customer
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="passwordCustomer" class="form-label fw-bold">Password</label>
                                    <input type="password" class="form-control" id="passwordCustomer"
                                        placeholder="Masukkan password">
                                    <div id="passwordCustomerError" class="text-danger mt-1 d-none">Silahkan isi password
                                        customer </div>
                                </div>
                                <div class="mt-3">
                                    <label for="passwordConfirmationCustomer" class="form-label fw-bold">Konfirmasi
                                        Password</label>
                                    <input type="password" class="form-control" id="passwordConfirmationCustomer"
                                        placeholder="Konfirmasi password">
                                    <div id="passwordConfirmationError" class="text-danger mt-1 d-none">Konfirmasi password
                                        tidak cocok</div>
                                </div>
                            </div>
                            <!-- Kolom Kedua -->
                            <div class="col-md-6">
                                <div class="mt-3">
                                    <label for="metodePengiriman" class="form-label fw-bold">Metode Pengiriman</label>
                                    <select class="form-control" id="metodePengiriman">
                                        <option value="" selected disabled>Pilih Metode Pengiriman</option>
                                        <option value="Pickup">Pickup</option>
                                        <option value="Delivery">Delivery</option>
                                    </select>
                                    <div id="metodePengirimanError" class="text-danger mt-1 d-none">Silahkan pilih metode
                                        pengiriman</div>
                                </div>
                                <div id="alamatSection">
                                    <div id="alamatContainer">
                                        <div class="mt-3 alamat-item">
                                            <label for="alamatCustomer" class="form-label fw-bold">Alamat</label>
                                            <textarea class="form-control" name="alamatCustomer[]" placeholder="Masukkan alamat" rows="3"></textarea>
                                            <div class="text-danger mt-1 d-none alamat-error">Silahkan isi alamat customer
                                            </div>
                                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-alamat-btn"
                                                style="display: none;"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </div>
                                    <button type="button" id="addAlamatButton" class="btn btn-secondary mt-3"
                                        style="display:none;">
                                        <i class="fas fa-plus"></i></button>
                                </div>
                                <div class="mt-3">
                                    <label for="noTelpon" class="form-label fw-bold">No. Telpon</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="nomor">+62</span>
                                        <input type="text" placeholder="8**********" class="form-control"
                                            id="noTelpon" value="">
                                    </div>
                                    <div id="notelponCustomerError" class="text-danger mt-1 d-none">Silahkan isi no.
                                        telepon customer</div>
                                </div>
                                <div class="mt-3">
                                    <label for="categoryCustomer" class="form-label fw-bold">Category</label>
                                    <select class="form-control" id="categoryCustomer">
                                        <option value="" selected disabled>Pilih Category Customer</option>
                                        @foreach ($listCategory as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="categoryCustomerError" class="text-danger mt-1 d-none">Silahkan pilih
                                        category customer</div>
                                </div>
                            </div>
                        </div>
                        <!-- Akhir Row -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveCostumer" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Tambah -->



        <!-- Modal Edit Customer -->
        <div class="modal fade" id="modalEditCustomer" tabindex="-1" role="dialog"
            aria-labelledby="modalEditCustomerTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> <!-- Ubah menjadi modal-lg -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditCustomerTitle">Edit Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="customerIdEdit">
                        <!-- Mulai Row -->
                        <div class="row">
                            <!-- Kolom Pertama -->
                            <div class="col-md-6">
                                <div class="mt-3">
                                    <label for="namaCustomerEdit" class="form-label fw-bold">Nama Customer</label>
                                    <input type="text" class="form-control" id="namaCustomerEdit" value=""
                                        placeholder="Masukkan nama customer">
                                    <div id="namaCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan isi nama
                                        customer</div>
                                </div>
                                <div class="mt-3">
                                    <label for="metodePengirimanEdit" class="form-label fw-bold">Metode Pengiriman</label>
                                    <select class="form-control" id="metodePengirimanEdit">
                                        <option value="" selected disabled>Pilih Metode Pengiriman</option>
                                        <option value="Pickup">Pickup</option>
                                        <option value="Delivery">Delivery</option>
                                    </select>
                                    <div id="metodePengirimanErrorEdit" class="text-danger mt-1 d-none">Silahkan pilih
                                        metode pengiriman</div>
                                </div>
                                <div id="alamatSectionEdit">
                                    <div id="alamatContainerEdit">
                                        <div class="mt-3 alamat-item">
                                            <label for="alamatCustomerEdit" class="form-label fw-bold">Alamat</label>
                                            <textarea class="form-control" name="alamatCustomerEdit[]" placeholder="Masukkan alamat" rows="3"></textarea>
                                            <div class="text-danger mt-1 d-none alamat-error">Silahkan isi alamat customer
                                            </div>
                                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-alamat-btn"
                                                style="display: none;"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </div>
                                    <button type="button" id="addAlamatButtonEdit" class="btn btn-secondary mt-3">
                                        <i class="fas fa-plus"></i> Tambah Alamat
                                    </button>
                                </div>
                            </div>
                            <!-- Kolom Kedua -->
                            <div class="col-md-6">

                                <div class="mt-3">
                                    <label for="noTelponEdit" class="form-label fw-bold">No. Telpon</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="nomorEdit">+62</span>
                                        <input type="text" placeholder="8**********" class="form-control"
                                            id="noTelponEdit" value="">
                                    </div>
                                    <div id="notelponCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan isi no.
                                        telepon customer</div>
                                </div>
                                <div class="mt-3">
                                    <label for="categoryCustomerEdit" class="form-label fw-bold">Category</label>
                                    <select class="form-control" id="categoryCustomerEdit">
                                        <option value="" selected disabled>Pilih Category Customer</option>
                                        @foreach ($listCategory as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="categoryCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan pilih
                                        category customer</div>
                                </div>
                            </div>
                        </div>
                        <!-- Akhir Row -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveEditCostumer" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Edit -->



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
                                <p class="text-muted">Kuota</p>
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

        <!-- Modal untuk Menampilkan Alamat -->
        <div class="modal fade" id="modalAlamatCustomer" tabindex="-1" role="dialog"
            aria-labelledby="modalAlamatCustomerTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAlamatCustomerTitle">Daftar Alamat Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul id="alamatList" class="list-group">
                            <!-- Alamat akan ditambahkan di sini -->
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Import -->
        <div class="modal fade" id="modalImportExcel" tabindex="-1" aria-labelledby="modalImportExcelLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="modalImportExcelLabel">Import Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Input File -->
                        <input type="file" class="form-control" id="importFileExcel" name="importFileExcel"
                            accept=".xlsx, .xls">

                        <!-- Preview Data -->
                        <div id="previewDataTable" class="mt-3" style="display: none;">
                            <h5>Preview Data yang Akan Diimpor:</h5>
                            <p>Jumlah Data: <span id="previewDataCount">0</span></p>
                            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6;">
                                <table id="tablePreview" class="table table-bordered">
                                    <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                                        <tr>
                                            <th>Marking Customer</th>
                                            <th>Nama Customer</th>
                                            <th>Email</th>
                                            <th>No. Telepon</th>
                                            <th>Alamat</th>
                                        </tr>
                                    </thead>
                                    <tbody id="previewDataBody">
                                        <!-- Data preview akan ditampilkan di sini -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div id="progressContainer" class="mt-3" style="display: none;">
                            <h5>Proses Import:</h5>
                            <div style="width: 100%; background: #e0e0e0; height: 25px; margin-top: 10px;">
                                <div id="progress-bar"
                                    style="width: 0%; height: 100%; background: green; text-align: center; color: white;">
                                    0%</div>
                            </div>
                            <p id="status-text">Menunggu proses import...</p>
                        </div>

                        <!-- Data yang Tertolak -->
                        <div id="invalidDataTable" class="mt-3" style="display: none;">
                            <h5>Data yang Tertolak:</h5>
                            <p>Jumlah Data: <span id="invalidDataCount">0</span></p>
                            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #dee2e6;">
                                <table id="tableInvalid" class="table table-bordered">
                                    <thead style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1;">
                                        <tr>
                                            <th>Marking Customer</th>
                                            <th>Nama Customer</th>
                                            <th>Email</th>
                                            <th>No. Telepon</th>
                                            <th>Alamat</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invalidDataBody">
                                        <!-- Data invalid akan ditampilkan di sini -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" id="btnImportFileExcel" class="btn btn-success">Import</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Import -->

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Customer</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Master Data</li>
                <li class="breadcrumb-item active" aria-current="page">Customer</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                            <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                data-target="#modalTambahCustomer" id="modalTambahCost"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah Customer</button>
                            <button type="button" id="" class="btn btn-primary btnModalImportExcel">Import
                                Data</button>
                        </div>
                        <div class="float-left">
                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                class="form-control rounded-3" placeholder="Search">
                        </div>
                        <div class="float-left ps-4">
                            <select class="form-control ml-2" id="filterStatus" name="status" style="width: 200px;">
                                <option value="" selected disabled>Pilih Status</option>
                                <option value="Active">Active</option>
                                <option value="Non Active">Non Active</option>
                            </select>
                        </div>
                        <div class="float-left ps-4">
                            <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">
                                Reset
                            </button>
                        </div>
                        <div id="containerCustomer" class="table-responsive px-3">
                            <table id="tableCustomer" class="table align-items-center table-flush table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Marking</th>
                                        <th>Nama</th>
                                        <th>Pengiriman</th>
                                        <th>Alamat</th>
                                        <th>Transaksi Terakhir</th>
                                        <th>Status</th>
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
    <!---Container Fluid-->
@endsection


@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#tableCustomer').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('getlistCostumer') }}",
                    method: 'GET',
                    data: function(d) {
                        d.status = $('#filterStatus').val();
                    },
                    error: function(xhr, error, thrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load customer data. Please try again!',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                columns: [{
                        data: 'marking',
                        name: 'marking'
                    },
                    {
                        data: 'nama_pembeli',
                        name: 'nama_pembeli'
                    },
                    {
                        data: 'metode_pengiriman',
                        name: 'metode_pengiriman',
                    },
                    {
                        data: 'alamat_cell',
                        name: 'alamat_cell',
                    },
                    {
                        data: 'tanggal_bayar',
                        name: 'tanggal_bayar',
                        render: function(data, type, row) {
                            return data ? data : '-';
                        },
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'status_cell',
                        name: 'status_cell',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
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

            $('#txSearch').keyup(function(e) {
                var searchValue = $(this).val();
                table.search(searchValue).draw();
            });
            $('#filterStatus').change(function() {
                table.ajax.reload();
            });


            $(document).on('click', '#modalTambahCost', function(e) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('generateMarking') }}",
                    success: function(response) {
                        let valuemarking = response.new_marking
                        $('#markingCustomer').val(valuemarking);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });

            $('#noTelpon, #noTelponEdit').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            function toggleRemoveButton() {
                const alamatItems = $('#alamatContainer').children('.alamat-item');
                if (alamatItems.length > 1) {
                    $('.remove-alamat-btn').show();
                } else {
                    $('.remove-alamat-btn').hide();
                }
            }

            $('#metodePengiriman').change(function() {
                var metodePengiriman = $(this).val();
                if (metodePengiriman === 'Delivery') {
                    $('#alamatSection').show();
                    $('#addAlamatButton').show();
                } else if (metodePengiriman === 'Pickup') {
                    $('#alamatSection').show();
                    $('#addAlamatButton').hide();
                    $('#alamatContainer').find('textarea').val('');
                    $('#alamatContainer').children('.alamat-item:gt(0)').remove();
                } else {
                    $('#alamatSection').hide();
                    $('#alamatContainer').find('textarea').val('');
                    $('#alamatContainer').children('.alamat-item:gt(0)').remove();
                    toggleRemoveButton();
                }
                toggleRemoveButton();
            });

            $('#addAlamatButton').click(function() {
                let alamatContainer = $('#alamatContainer');

                let newAlamat = `<div class="mt-3 alamat-item">
                            <label for="alamatCustomer" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" name="alamatCustomer[]" placeholder="Masukkan alamat" rows="3"></textarea>
                            <div class="text-danger mt-1 d-none alamat-error">Silahkan isi alamat customer</div>
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-alamat-btn"><i class="fas fa-trash-alt"></i></button>
                        </div>`;
                alamatContainer.append(newAlamat);
                toggleRemoveButton();
            });

            $(document).on('click', '.remove-alamat-btn', function() {
                $(this).closest('.alamat-item').remove();
                toggleRemoveButton();
            });

            $('#saveCostumer').click(function() {
                var markingCostmer = $('#markingCustomer').val();
                var namaCustomer = $('#namaCustomer').val();
                var nomorTelpon = $('#noTelpon').val().trim();
                var emailCustomer = $('#emailCustomer').val();
                var passwordCustomer = $('#passwordCustomer').val();
                var passwordConfirmation = $('#passwordConfirmationCustomer').val();
                var categoryCustomer = $('#categoryCustomer').val();
                var metodePengiriman = $('#metodePengiriman').val();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let nomors = $('#nomor').text();
                let clearplus = nomors.replace('+', '');
                let valueNotlep = clearplus + nomorTelpon;

                var isValid = true;
                $('.text-danger').addClass('d-none');

                if (markingCostmer === '') {
                    $('#markingCostumerError').removeClass('d-none');
                    isValid = false;
                }

                if (namaCustomer === '') {
                    $('#namaCustomerError').removeClass('d-none');
                    isValid = false;
                }

                if (nomorTelpon === '') {
                    $('#notelponCustomerError').removeClass('d-none');
                    isValid = false;
                }

                if (emailCustomer === '') {
                    $('#emailCustomerError').removeClass('d-none');
                    isValid = false;
                }

                if (passwordCustomer === '') {
                    $('#passwordCustomerError').removeClass('d-none');
                    isValid = false;
                } else if (passwordCustomer.length < 6) {
                    $('#passwordCustomerError').text('Password harus memiliki minimal 6 karakter')
                        .removeClass('d-none');
                    isValid = false;
                } else {
                    $('#passwordCustomerError').addClass('d-none');
                }

                if (passwordCustomer !== passwordConfirmation) {
                    $('#passwordConfirmationError').text('Password dan konfirmasi password tidak cocok')
                        .removeClass('d-none');
                    isValid = false;
                } else {
                    $('#passwordConfirmationError').addClass('d-none');
                }

                if (categoryCustomer === '' || categoryCustomer === null) {
                    $('#categoryCustomerError').removeClass('d-none');
                    isValid = false;
                }

                if (metodePengiriman === '' || metodePengiriman === null) {
                    $('#metodePengirimanError').removeClass('d-none');
                    isValid = false;
                }

                var alamatCustomer = [];
                if (metodePengiriman === 'Delivery') {
                    $('textarea[name="alamatCustomer[]"]').each(function() {
                        var alamat = $(this).val().trim();
                        if (alamat === '') {
                            $(this).siblings('.alamat-error').removeClass('d-none');
                            isValid = false;
                        } else {
                            alamatCustomer.push(alamat);
                        }
                    });
                } else if (metodePengiriman === 'Pickup') {
                    var alamat = $('textarea[name="alamatCustomer[]"]').first().val().trim();
                    if (alamat === '') {
                        $('textarea[name="alamatCustomer[]"]').siblings('.alamat-error').removeClass(
                            'd-none');
                        isValid = false;
                    } else {
                        alamatCustomer.push(alamat);
                    }
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
                            var formData = new FormData();
                            formData.append('markingCostmer', markingCostmer);
                            formData.append('namaCustomer', namaCustomer);
                            formData.append('noTelpon', valueNotlep);
                            formData.append('email', emailCustomer);
                            formData.append('password', passwordCustomer);
                            formData.append('password_confirmation', passwordConfirmation);
                            formData.append('categoryCustomer', categoryCustomer);
                            formData.append('metodePengiriman', metodePengiriman);
                            formData.append('_token', csrfToken);

                            if (metodePengiriman === 'Delivery' || metodePengiriman === 'Pickup') {
                                alamatCustomer.forEach((alamat, index) => {
                                    formData.append('alamatCustomer[]', alamat);
                                });
                            }

                            Swal.fire({
                                title: 'Checking...',
                                html: 'Please wait while we process your data customer.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                type: "POST",
                                url: "{{ route('addCostumer') }}",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    Swal.close();
                                    if (response.status === 'success') {
                                        showMessage("success",
                                            "Data Berhasil Disimpan");
                                        table.ajax.reload();
                                        $('#modalTambahCustomer').modal('hide');
                                    } else {
                                        Swal.fire({
                                            title: "Gagal Menambahkan Data",
                                            text: response.message,
                                            icon: "error",
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    Swal.close();
                                    var response = xhr.responseJSON;

                                    if (response.errors) {
                                        if (response.errors.markingCostmer) {
                                            $('#markingCostumerError').text(response
                                                    .errors.markingCostmer[0])
                                                .removeClass('d-none');
                                        }
                                        if (response.errors.email) {
                                            $('#emailCustomerError').text(response
                                                .errors.email[0]).removeClass(
                                                'd-none');
                                        }
                                    } else {
                                        Swal.fire({
                                            title: "Gagal Menambahkan Data",
                                            text: "Terjadi kesalahan, silakan coba lagi.",
                                            icon: "error",
                                        });
                                    }
                                }
                            });
                        }
                    });
                } else {
                    showMessage("error", "Mohon periksa input yang kosong");
                }
            });



            $('#modalTambahCustomer').on('hidden.bs.modal', function() {
                $('#namaCustomer, #noTelpon, #categoryCustomer, #markingCustomer, #emailCustomer, #passwordCustomer, #passwordConfirmationCustomer')
                    .val('');
                $('#alamatContainer').children('.alamat-item:gt(0)').remove();
                $('#alamatContainer').children('.alamat-item').first().find('textarea').val('');
                $('#alamatSection').hide();
                $('#metodePengiriman').val('').trigger('change');
                $('.text-danger').addClass('d-none');
            });


            $(document).on('click', '.btnPointCostumer', function(e) {
                e.preventDefault();
                let poinValue = $(this).data('poin') || 0;
                let category = $(this).data('category');
                let transaksi = $(this).data('transaksi');
                let status = $(this).data('status');

                $('#pointValue').text(poinValue).show();

                if (!transaksi) {
                    $('#transaksiDate').text('Tanggal belum tersedia');
                } else {
                    let transaksiDate = new Date(transaksi);
                    let currentDate = new Date();
                    let timeDifference = currentDate - transaksiDate;

                    if (isNaN(transaksiDate.getTime())) {
                        $('#transaksiDate').text('Tanggal transaksi tidak valid');
                    } else {
                        let seconds = Math.floor(timeDifference / 1000);
                        let minutes = Math.floor(seconds / 60);
                        let hours = Math.floor(minutes / 60);
                        let days = Math.floor(hours / 24);
                        let months = Math.floor(days / 30);
                        let years = Math.floor(months / 12);

                        let result;
                        if (seconds < 60) {
                            result = seconds === 1 ? "Sedetik yang lalu" : `${seconds} detik yang lalu`;
                        } else if (minutes < 60) {
                            result = minutes === 1 ? "Semenit yang lalu" : `${minutes} menit yang lalu`;
                        } else if (hours < 24) {
                            result = hours === 1 ? "Sejam yang lalu" : `${hours} jam yang lalu`;
                        } else if (days < 30) {
                            result = days === 1 ? "Sehari yang lalu" : `${days} hari yang lalu`;
                        } else if (months < 12) {
                            result = months === 1 ? "Sebulan yang lalu" : `${months} bulan yang lalu`;
                        } else {
                            result = years === 1 ? "Setahun yang lalu" : `${years} tahun yang lalu`;
                        }

                        $('#transaksiDate').text(result);
                    }
                }

                if (category === "VIP") {
                    $('#pointValue').text(point).show();
                } else if (category === "Normal") {
                    $('#pointValue').hide();
                }

                // $('#transaksiDate').text(result);
                $('#statusValue').html(
                    `<span class="badge ${status === 1 ? 'badge-success' : 'badge-danger'}">${status === 1 ? 'Aktif' : 'Non Aktif'}</span>`
                );

                // Show modal
                $('#modalPointCostumer').modal('show');
            });


            $(document).on('click', '.btnUpdateCustomer', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let nama = $(this).data('nama');
                let noTelp = $(this).data('notelp');
                noTelp = String(noTelp);
                let noTelpWithoutCode = noTelp.slice(2);
                let alamat = $(this).data('alamat') || '';
                let category = $(this).data('category');
                let pengiriman = $(this).data('metode_pengiriman');

                // Memisahkan string alamat menjadi array
                let alamatArray = typeof alamat === 'string' ? alamat.split(';') : [];

                // Kosongkan container alamat dan tambahkan textarea sesuai jumlah alamat
                $('#alamatContainerEdit').empty();
                alamatArray.forEach((alamatItem, index) => {
                    let newAlamat = `<div class="mt-3 alamat-item">
                                    <label for="alamatCustomerEdit${index}" class="form-label fw-bold">Alamat</label>
                                    <textarea class="form-control" id="alamatCustomerEdit${index}" name="alamatCustomerEdit[]" placeholder="Masukkan alamat" rows="3">${alamatItem.trim()}</textarea>
                                    <div class="text-danger mt-1 d-none alamat-error">Silahkan isi alamat customer</div>
                                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-alamat-btn"><i class="fas fa-trash-alt"></i></button>
                                </div>`;
                    $('#alamatContainerEdit').append(newAlamat);
                });

                // Logika untuk menampilkan dan menyembunyikan tombol hapus alamat
                function toggleRemoveButtonEdit() {
                    const alamatItems = $('#alamatContainerEdit').children('.alamat-item');
                    if (alamatItems.length > 1) {
                        $('.remove-alamat-btn').show();
                    } else {
                        $('.remove-alamat-btn').hide();
                    }
                }

                // Menangani perubahan metode pengiriman
                $('#metodePengirimanEdit').change(function() {
                    var metodePengiriman = $(this).val();
                    if (metodePengiriman === 'Delivery') {
                        $('#alamatSectionEdit').show();
                        $('#addAlamatButtonEdit').show();
                    } else if (metodePengiriman === 'Pickup') {
                        $('#alamatSectionEdit').show();
                        $('#addAlamatButtonEdit').hide();
                        $('#alamatContainerEdit').children('.alamat-item:gt(0)').remove();
                    } else {
                        $('#alamatSectionEdit').hide();
                        $('#alamatContainerEdit').find('textarea').val('');
                        $('#alamatContainerEdit').children('.alamat-item:gt(0)').remove();
                    }
                    toggleRemoveButtonEdit();
                });

                $('#addAlamatButtonEdit').off('click').on('click', function() {
                    let alamatContainer = $('#alamatContainerEdit');
                    let newAlamat = `<div class="mt-3 alamat-item">
                            <label for="alamatCustomerEditNew" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" name="alamatCustomerEdit[]" placeholder="Masukkan alamat" rows="3"></textarea>
                            <div class="text-danger mt-1 d-none alamat-error">Silahkan isi alamat customer</div>
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-alamat-btn"><i class="fas fa-trash-alt"></i></button>
                        </div>`;
                    alamatContainer.append(newAlamat);
                    toggleRemoveButtonEdit();
                });

                // Hapus alamat saat tombol 'Hapus Alamat' ditekan pada modal edit
                $(document).on('click', '.remove-alamat-btn', function() {
                    $(this).closest('.alamat-item').remove();
                    toggleRemoveButtonEdit();
                });

                // Isi form edit dengan data yang diterima
                $('#namaCustomerEdit').val(nama);
                $('#noTelponEdit').val(noTelpWithoutCode);
                $('#categoryCustomerEdit').val(category);
                $('#metodePengirimanEdit').val(pengiriman);
                $('#customerIdEdit').val(id);

                // Trigger event change untuk memastikan tampilan sesuai dengan metode pengiriman yang dipilih
                $('#metodePengirimanEdit').trigger('change');

                toggleRemoveButtonEdit();

                $('#modalEditCustomer').modal('show');
            });



            $(document).on('click', '#saveEditCostumer', function(e) {
                e.preventDefault();

                let id = $('#customerIdEdit').val();
                let namaCustomerEdit = $('#namaCustomerEdit').val();
                let noTelponInput = $('#noTelponEdit').val().trim();
                let noTelponCustomer = '62' + noTelponInput;
                let metodePengiriman = $('#metodePengirimanEdit').val();
                let categoryCustomer = $('#categoryCustomerEdit').val();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let isValid = true;

                // Validasi Nama Customer
                if (namaCustomerEdit === '') {
                    $('#namaCustomerErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#namaCustomerErrorEdit').addClass('d-none');
                }
                // Validasi Nomor Telepon
                if (noTelponInput === '') {
                    $('#notelponCustomerErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#notelponCustomerErrorEdit').addClass('d-none');
                }

                // Validasi Kategori Customer
                if (categoryCustomer === '' || categoryCustomer === null) {
                    $('#CategoryCustomerErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#CategoryCustomerErrorEdit').addClass('d-none');
                }

                // Validasi dan kumpulkan semua alamat
                let alamatCustomer = [];
                if (metodePengiriman === 'Delivery' || metodePengiriman === 'Pickup') {
                    $('#alamatContainerEdit').find('textarea').each(function() {
                        let alamat = $(this).val().trim();
                        if (alamat === '') {
                            $(this).siblings('.alamat-error').removeClass('d-none');
                            isValid = false;
                        } else {
                            $(this).siblings('.alamat-error').addClass('d-none');
                            alamatCustomer.push(alamat);
                        }
                    });
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
                            let formData = new FormData();
                            formData.append('id', id);
                            formData.append('namaCustomer', namaCustomerEdit);
                            formData.append('noTelpon', noTelponCustomer);
                            formData.append('metodePengiriman', metodePengiriman);
                            formData.append('categoryCustomer', categoryCustomer);
                            formData.append('_token', csrfToken);

                            alamatCustomer.forEach((alamat, index) => {
                                formData.append('alamatCustomer[]', alamat);
                            });

                            Swal.fire({
                                title: 'Checking...',
                                html: 'Please wait while we process update your data customer.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                type: "POST",
                                url: "{{ route('updateCostumer') }}",
                                data: formData,
                                contentType: false,
                                processData: false,
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
                                        showMessage("success", "Data Berhasil Diubah");
                                        table.ajax.reload();
                                        $('#modalEditCustomer').modal('hide');
                                    } else {
                                        Swal.fire({
                                            title: "Gagal Menambahkan",
                                            icon: "error"
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        title: "Gagal Menambahkan Data",
                                        text: xhr.responseJSON.message,
                                        icon: "error",
                                    });
                                }
                            });
                        }
                    });
                } else {
                    showMessage("error", "Mohon periksa input yang kosong");
                }
            });


            $(document).on('click', '.btnModalImportExcel', function(e) {
                e.preventDefault();
                $("#modalImportExcel").modal('show');
            });

            $(document).on('change', "#importFileExcel", function(e) {
                e.preventDefault();
                var file = e.target.files[0];
                if (!file) return;

                // Tampilkan loading indicator
                Swal.fire({
                    title: 'Memproses file...',
                    html: 'Silakan tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Nonaktifkan tombol
                $("#btnImportFileExcel").prop("disabled", true);

                var reader = new FileReader();
                reader.onload = function(e) {
                    var data = new Uint8Array(e.target.result);
                    var workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    var sheetName = workbook.SheetNames[0];
                    var sheet = workbook.Sheets[sheetName];

                    var columnData = XLSX.utils.sheet_to_json(sheet, {
                            header: 1
                        })
                        .slice(1) // Mengabaikan header
                        .map(row => ({
                            marking_costumer: row[1], // Sesuai dengan validasi backend
                            alamat_customer: row[2],
                            no_telpon: (row[3] || '').toString().replace(/[+\-\s]/g, '')
                        .trim(), // Hapus +, -, spasi
                            nama_customer: row[4],
                            email: row[5],
                            password: 'password',
                            category_customer: 1,
                            metode_pengiriman: 'Delivery'
                        }));

                    if (columnData.length === 0) {
                        Swal.fire("File kosong atau format tidak sesuai!", "", "error");
                        $("#btnImportFileExcel").prop("disabled", false); // Aktifkan tombol
                        return;
                    }

                    // Tampilkan preview data
                    $("#previewDataTable").show();
                    $("#previewDataBody").empty(); // Kosongkan isi tabel sebelumnya

                    columnData.forEach(function(row) {
                        $("#previewDataBody").append(`
                <tr>
                    <td>${row.marking_costumer}</td>
                    <td>${row.nama_customer}</td>
                    <td>${row.email}</td>
                    <td>${row.no_telpon}</td>
                    <td>${row.alamat_customer}</td>
                </tr>
            `);
                    });

                    // Update count data
                    $("#previewDataCount").text(columnData.length);

                    window.dataImport =
                        columnData; // Simpan dalam global variable agar bisa diakses nanti

                    // Tutup loading indicator
                    Swal.close();

                    // Aktifkan tombol
                    $("#btnImportFileExcel").prop("disabled", false);
                };

                reader.onerror = function() {
                    Swal.fire("Gagal memproses file!", "Terjadi kesalahan saat membaca file.", "error");
                    $("#btnImportFileExcel").prop("disabled", false); // Aktifkan tombol
                };

                reader.readAsArrayBuffer(file);
            });

            $(document).on('click', '#btnImportFileExcel', function(e) {
                e.preventDefault();

                if (!window.dataImport || window.dataImport.length === 0) {
                    Swal.fire("Tidak ada data untuk diimport!", "", "error");
                    return;
                }

                // Tampilkan progress bar
                $("#progressContainer").show();
                $("#progress-bar").css("width", "0%").text("0%");
                $("#status-text").text("Sedang mengimpor data...");

                $("#btnImportFileExcel").prop("disabled", true); // Nonaktifkan tombol

                let progress = 0;
                let interval = setInterval(() => {
                    if (progress < 90) {
                        progress += 10;
                        $("#progress-bar").css("width", progress + "%").text(progress + "%");
                    } else {
                        clearInterval(interval);
                    }
                }, 500);

                // Kirim data ke backend
                $.ajax({
                    type: "POST",
                    url: "{{ route('costumer.import') }}",
                    data: JSON.stringify({
                        data: window.dataImport,
                        _token: '{{ csrf_token() }}'
                    }),
                    contentType: "application/json",
                    processData: false,
                    dataType: "JSON",
                    success: function(RES) {
                        clearInterval(interval);
                        $("#progress-bar").css("width", "100%").text("100%");
                        $("#status-text").text("Import selesai!");

                        if (RES.success) {
                            Swal.fire("Proses import selesai!", RES.message, "success");

                            // Sembunyikan tabel preview & tampilkan hasil yang ditolak jika ada
                            if (RES.invalid_data && RES.invalid_data.length > 0) {
                                $("#invalidDataTable").show();
                                $("#invalidDataBody").empty();

                                RES.invalid_data.forEach(function(row) {
                                    $("#invalidDataBody").append(
                                        `<tr>
                                <td>${row.marking_costumer}</td>
                                <td>${row.nama_customer}</td>
                                <td>${row.email}</td>
                                <td>${row.no_telpon}</td>
                                <td>${row.alamat_customer}</td>
                                <td>${row.keterangan}</td>
                            </tr>`
                                    );
                                });

                                $("#invalidDataCount").text(RES.invalid_data.length);
                                $("#previewDataTable").hide();
                            } else {
                                $("#invalidDataTable").hide();
                                $("#previewDataTable").hide();
                            }

                            table.ajax.reload();
                        } else {
                            Swal.fire("Gagal mengimpor data!", RES.message ||
                                "Terjadi kesalahan.", "error");
                            $("#status-text").text("Terjadi kesalahan dalam proses import.");
                        }
                    },
                    error: function(XHR) {
                        clearInterval(interval);
                        $("#progress-bar").css("width", "0%").text("Gagal");
                        $("#status-text").text("Gagal mengimpor data!");
                        Swal.fire("Gagal mengimpor data!", XHR.responseJSON?.message ||
                            "Terjadi kesalahan.", "error");
                    },
                    complete: function() {
                        $("#btnImportFileExcel").prop("disabled", false);
                    }
                });
            });

            $('#modalImportExcel').on('hidden.bs.modal', function() {
                // Reset input file
                $('#importFileExcel').val('');

                // Sembunyikan preview data table
                $('#previewDataTable').hide();

                // Kosongkan isi tabel preview
                $('#previewDataBody').empty();

                // Reset jumlah data preview
                $('#previewDataCount').text('0');

                // Sembunyikan progress bar
                $('#progressContainer').hide();

                // Reset progress bar
                $('#progress-bar').css('width', '0%').text('0%');

                // Reset status text
                $('#status-text').text('Menunggu proses import...');

                // Sembunyikan tabel data yang tertolak
                $('#invalidDataTable').hide();

                // Kosongkan isi tabel data yang tertolak
                $('#invalidDataBody').empty();

                // Reset jumlah data yang tertolak
                $('#invalidDataCount').text('0');
            });

            $('#modalEditCustomer').on('hidden.bs.modal', function() {
                $('#namaCustomerEdit, #noTelponEdit, #categoryCustomerEdit, #markingCustomerEdit')
                    .val('');
                $('#alamatContainerEdit').children('.alamat-item:gt(0)').remove();
                $('#alamatContainerEdit').children('.alamat-item').first().find('textarea').val(
                    '');
                $('.text-danger').addClass('d-none');
                $('#alamatSectionEdit').hide();
                $('#metodePengirimanEdit').val('').trigger('change');
                toggleRemoveButtonEdit();
            });

            $(document).on('click', '.show-address-modal', function() {
                let alamat = $(this).data('alamat');
                let alamatArray = alamat.split(';');

                let alamatList = $('#alamatList');
                alamatList.empty(); // Kosongkan daftar alamat

                // Tambahkan setiap alamat sebagai item dalam daftar
                alamatArray.forEach(function(item) {
                    alamatList.append('<li class="list-group-item">' + item + '</li>');
                });

                // Tampilkan modal
                $('#modalAlamatCustomer').modal('show');
            });
        });
    </script>
@endsection
