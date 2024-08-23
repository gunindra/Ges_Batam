@extends('layout.main')

@section('title', 'Master Data | Costumer')

@section('main')

    <style>
        /* Custom Checkbox Styling */
        .custom-checkbox input[type="checkbox"] {
            display: none;
        }

        .custom-checkbox label {
            display: inline-block;
            position: relative;
            padding-left: 30px;
            font-size: 16px;
            cursor: pointer;
        }

        .custom-checkbox label::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 20px;
            height: 20px;
            border: 2px solid #007bff;
            border-radius: 5px;
            background: transparent;
            transition: background 0.3s;
        }

        .custom-checkbox input[type="checkbox"]:checked+label::before {
            background: #007bff;
        }

        .custom-checkbox label::after {
            content: '';
            position: absolute;
            left: 6px;
            top: 2px;
            width: 6px;
            height: 12px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg) scale(0);
            transition: transform 0.3s ease;
        }

        .custom-checkbox input[type="checkbox"]:checked+label::after {
            transform: rotate(45deg) scale(1);
        }
    </style>

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">

        <!-- Modal tambah -->
        <div class="modal fade" id="modalTambahCustomer" tabindex="-1" role="dialog" aria-labelledby="modalTambahCustomerTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahCustomerTitle">Tambah Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="namaCustomer" class="form-label fw-bold">Marking</label>
                            <input type="text" class="form-control" id="markingCustomer" value="">
                            <div id="markingCostumerError" class="text-danger mt-1 d-none">Silahkan isi marking</div>
                        </div>
                        <div class="mt-3">
                            <label for="namaCustomer" class="form-label fw-bold">Nama Customer</label>
                            <input type="text" class="form-control" id="namaCustomer" value=""
                                placeholder="Masukkan nama customer">
                            <div id="namaCustomerError" class="text-danger mt-1 d-none">Silahkan isi nama customer</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" placeholder="Masukkan alamat" id="alamatCustomer" rows="3"></textarea>
                            <div id="alamatCustomerError" class="text-danger mt-1 d-none">Silahkan isi alamat custumer</div>
                        </div>
                        <div class="mt-3">
                            <label for="noTelpon" class="form-label fw-bold">No. Telpon</label>
                            <input type="text" placeholder="08*********" class="form-control" id="noTelpon"
                                value="">
                            <div id="notelponCustomerError" class="text-danger mt-1 d-none">Silahkan isi no. telepon
                                customer</div>
                        </div>
                        <div class="mt-3">
                            <label for="category" class="form-label fw-bold">Category</label>
                            <select class="form-control" id="categoryCustomer">
                                <option value="" selected disabled>Pilih Category Customer</option>
                                <option value="Normal">Normal</option>
                                <option value="VIP">VIP</option>
                            </select>
                            <div id="categoryCustomerError" class="text-danger mt-1 d-none">Silahkan pilih category customer
                            </div>
                        </div>
                        <div class="mt-3 custom-checkbox">
                            <input type="checkbox" id="isactive">
                            <label for="isactive" class="form-label fw-bold">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveCostumer" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End Modal Tambah -->


        <!-- Modal Edit -->
        <div class="modal fade" id="modalEditCustomer" tabindex="-1" role="dialog"
            aria-labelledby="modalEditCustomerTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditCustomerTitle">Edit Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="customerIdEdit">
                        <div class="mt-3">
                            <label for="namaCustomer" class="form-label fw-bold">Nama Customer</label>
                            <input type="text" class="form-control" id="namaCustomerEdit" value="" placeholder="Masukkan nama customer">
                            <div id="namaCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan isi nama customer
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamatCustomerEdit" rows="3" placeholder="Masukkan alamat"></textarea>
                            <div id="alamatCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan isi alamat customer
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="noTelpon" class="form-label fw-bold">No. Telpon</label>
                            <input type="text" class="form-control" id="noTelponEdit" value="" placeholder="08**********">
                            <div id="notelponCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan isi no. telepon
                                customer
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="categoryCustomerEdit" class="form-label fw-bold">Category</label>
                            <select class="form-control" id="categoryCustomerEdit">
                                <option value="" selected disabled>Select Category Customer</option>
                                <option value="Normal">Normal</option>
                                <option value="VIP">VIP</option>
                            </select>
                            <div id="categoryCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan pilih category
                                customer
                            </div>
                        </div>
                        <div class="mt-3 custom-checkbox">
                            <input type="checkbox" id="isactiveEdit">
                            <label for="isactiveEdit" class="form-label fw-bold">Active</label>
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
                                <h1 id="pointValue" class="display-3 font-weight-bold text-primary">?</h1>
                                <p class="text-muted">Transaksi terakhir : <span id="transaksiDate">-</span></p>
                            </div>
                            <div>
                                <p id="statusValue" class="h5"></p>
                            </div>
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
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahCustomer" id="modalTambahCost"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah Customer</button>
                        </div>
                        <div id="containerCustomer" class="table-responsive px-3">
                            {{-- <table class="table align-items-center table-flush table-hover" id="tableCostumer">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>No. Telp</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Ilham</td>
                                    <td>Jl.Central Legenda poin blok j No. 13 </td>
                                    <td>0893483478283</td>
                                    <td><span class="badge badge-primary">VIP</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Show point</a>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tio</td>
                                    <td>Jl.Central Legenda poin blok j No. 12 </td>
                                    <td>08123476282221</td>
                                    <td>Normal</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Show point</a>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
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
    <!---Container Fluid-->
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

            const getListCustomer = () => {
                const txtSearch = $('#txSearch').val();

                $.ajax({
                        url: "{{ route('getlistCostumer') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch
                        },
                        beforeSend: () => {
                            $('#containerCustomer').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerCustomer').html(res)
                        $('#tableCostumer').DataTable({
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

            getListCustomer();

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

            $('#saveCostumer').click(function() {

                var markingCostmer = $('#markingCustomer').val();
                var namaCustomer = $('#namaCustomer').val();
                var alamatCustomer = $('#alamatCustomer').val();
                var noTelpon = $('#noTelpon').val().trim();
                var categoryCustomer = $('#categoryCustomer').val();
                let isActive = $('#isactive').prop('checked') ? 1 : 0;
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                var isValid = true;

                if (markingCostmer === '') {
                    $('#markingCostumerError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#markingCostumerError').addClass('d-none');
                }
                if (namaCustomer === '') {
                    $('#namaCustomerError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#namaCustomerError').addClass('d-none');
                }
                if (alamatCustomer === '') {
                    $('#alamatCustomerError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#alamatCustomerError').addClass('d-none');
                }

                if (noTelpon === '') {
                    $('#notelponCustomerError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#notelponCustomerError').addClass('d-none');
                }

                if (categoryCustomer === '' || categoryCustomer === null) {
                    $('#categoryCustomerError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#categoryCustomerError').addClass('d-none');
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
                            formData.append('alamatCustomer', alamatCustomer);
                            formData.append('noTelpon', noTelpon);
                            formData.append('categoryCustomer', categoryCustomer);
                            formData.append('isActive', isActive);
                            formData.append('_token', csrfToken);

                            $.ajax({
                                type: "POST",
                                url: "{{ route('addCostumer') }}",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    if (response.status === 'success') {
                                        showMessage("success",
                                            "Data Berhasil Disimpan");
                                        getListCustomer();
                                        $('#modalTambahCustomer').modal('hide');
                                    } else {
                                        Swal.fire({
                                            title: "Gagal Menambahkan Data",
                                            text: response
                                                .message,
                                            icon: "error",
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        title: "Gagal Menambahkan Data",
                                        text: xhr.responseJSON
                                            .message,
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


            $('#modalTambahCustomer').on('hidden.bs.modal', function() {
                $('#namaCustomer, #alamatCustomer, #noTelpon, #categoryCustomer').val('');
                if (!$('#namaCustomerError').hasClass('d-none')) {
                    $('#namaCustomerError').addClass('d-none');

                }
                if (!$('#alamatCustomerError').hasClass('d-none')) {
                    $('#alamatCustomerError').addClass('d-none');

                }
                if (!$('#notelponCustomerError').hasClass('d-none')) {
                    $('#notelponCustomerError').addClass('d-none');

                }
                if (!$('#categoryCustomerError').hasClass('d-none')) {
                    $('#categoryCustomerError').addClass('d-none');

                }
            });

            $(document).on('click', '.btnPointCostumer', function(e) {
                let category = $(this).data('category');
                let point = $(this).data('poin');
                let transaksi = $(this).data('transaksi');
                let status = $(this).data('status');

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
                let alamat = $(this).data('alamat');
                let category = $(this).data('category');
                let status = $(this).data('status');

                $('#namaCustomerEdit').val(nama);
                $('#noTelponEdit').val(noTelp);
                $('#alamatCustomerEdit').val(alamat);
                $('#categoryCustomerEdit').val(category);
                $('#customerIdEdit').val(id);

                if (status === 1) {
                    $('#isactiveEdit').prop('checked', true);
                } else {
                    $('#isactiveEdit').prop('checked', false);
                }

                $('#modalEditCustomer').modal('show');
            });


            $(document).on('click', '#saveEditCostumer', function(e) {
                e.preventDefault();

                let id = $('#customerIdEdit').val();
                let namaCustomerEdit = $('#namaCustomerEdit').val();
                let alamatCustomer = $('#alamatCustomerEdit').val();
                let noTelponCustomer = $('#noTelponEdit').val();
                let categoryCustomer = $('#categoryCustomerEdit').val();
                let isactiveEdit = $('#isactiveEdit').prop('checked') ? 1 : 0;
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let isValid = true;
                if (namaCustomerEdit === '') {
                    $('#namaCustomerErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#namaCustomerErrorEdit').addClass('d-none');
                }

                if (alamatCustomer === '') {
                    $('#alamatCustomerErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#alamatCustomerErrorEdit').addClass('d-none');
                }

                if (noTelponCustomer === '') {
                    $('#notelponCustomerErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#notelponCustomerErrorEdit').addClass('d-none');
                }

                if (categoryCustomer === '') {
                    $('#CategoryCustomerErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#CategoryCustomerErrorEdit').addClass('d-none');
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
                            formData.append('alamatCustomer', alamatCustomer);
                            formData.append('noTelpon', noTelponCustomer);
                            formData.append('categoryCustomer', categoryCustomer);
                            formData.append('isactiveEdit', isactiveEdit);
                            formData.append('_token', csrfToken);

                            $.ajax({
                                type: "POST",
                                url: "{{ route('updateCostumer') }}",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    if (response.status === 'success') {
                                        showMessage("success", "Data Berhasil Diubah");
                                        getListCustomer();
                                        $('#modalEditCustomer').modal('hide');
                                    } else {
                                        Swal.fire({
                                            title: "Gagal Menambahkan",
                                            icon: "error"
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
            $('#modalEditCustomer').on('hidden.bs.modal', function() {
                $('#namaCustomerEdit, #alamatCustomerEdit, #noTelponEdit, #categoryCustomerEdit').val('');
                if (!$('#namaCustomerErrorEdit').hasClass('d-none')) {
                    $('#namaCustomerErrorEdit').addClass('d-none');

                }
                if (!$('#alamatCustomerErrorEdit').hasClass('d-none')) {
                    $('#alamatCustomerErrorEdit').addClass('d-none');

                }
                if (!$('#notelponCustomerErrorEdit').hasClass('d-none')) {
                    $('#notelponCustomerErrorEdit').addClass('d-none');

                }
            });
        });
    </script>
@endsection
