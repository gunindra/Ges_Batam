@extends('layout.main')

@section('title', 'Master Data | Category')

@section('main')
    <div class="container-fluid" id="container-wrapper">

        <!-- Modal tambah -->
        <div class="modal fade" id="modalTambahCategory" tabindex="-1" role="dialog" aria-labelledby="modalTambahCategoryTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahCategory">Tambah Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="nameCategory" class="form-label fw-bold">Nama Category</label>
                            <input type="text" class="form-control" id="nameCategory" value=""
                                placeholder="Masukkan nama">
                            <div id="nameCategoryError" class="text-danger mt-1 d-none">Silahkan isi Nama</div>
                        </div>
                        <div class="mt-3">
                            <label for="minimumRateCategory" class="form-label fw-bold">Minimum Rate</label>
                            <input type="text" class="form-control" id="minimumRateCategory" value=""
                                placeholder="Masukkan Minimum Rate">
                            <div id="minimumRateCategoryError" class="text-danger mt-1 d-none">Silahkan isi Minimum Rate
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="maximumRateCategory" class="form-label fw-bold">Maximum Rate</label>
                            <input type="text" class="form-control" id="maximumRateCategory" value=""
                                placeholder="Masukkan Maximum Rate">
                            <div id="maximumRateCategoryError" class="text-danger mt-1 d-none">Silahkan isi Maximum Rate
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                            <button type="button" id="saveCategory" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEditCategory" tabindex="-1" role="dialog"
            aria-labelledby="modalEditCategoryTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditCategoryTitle">Edit Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="categoryIdEdit">
                        <div class="mt-3">
                            <label for="nameCategory" class="form-label fw-bold">Nama Category</label>
                            <input type="text" class="form-control" id="nameCategoryEdit" value=""
                                placeholder="Masukkan Nama Category">
                            <div id="nameCategoryErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Nama</div>
                        </div>
                        <div class="mt-3">
                            <label for="minimumRateCategory" class="form-label fw-bold">Minimum Rate</label>
                            <input type="text" class="form-control" id="minimumRateCategoryEdit" value=""
                                placeholder="Masukkan Minimum Rate">
                            <div id="minimumRateCategoryErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Minimum Rate
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="maximumRateCategory" class="form-label fw-bold">Maximum Rate</label>
                            <input type="text" class="form-control" id="maximumRateCategoryEdit" value=""
                                placeholder="Masukkan Maximum Rate">
                            <div id="maximumRateCategoryErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Maximum
                                Rate
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveEditCategory" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 px-4">Category</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Category</li>
                <li class="breadcrumb-item active" aria-current="page">Category</li>
            </ol>
        </div>
        <div class="row mb-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahCategory" id="#modalCenter"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah Category</button>
                        </div>
                        <div id="containerCategory" class="table-responsive px-2">
                            <!-- <table class="table align-items-center table-flush table-hover" id="tableVendor">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Judul</th>
                                                    <th>Isi Vendor</th>
                                                    <th>Image</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1.</td>
                                                    <td>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Distinctio natus aspernatur eligendi, aperiam voluptatibus quia! Facere eveniet consequuntur nostrum molestias, asperiores cupiditate quibusdam dolore molestiae quod modi? Assumenda, tenetur repudiandae?</td>
                                                    <td><img src="/img/Aboutus.jpg" width="50px"></td>
                                                    <td>
                                                    <a href="#" class="btn btn-sm btn-secondary"><i
                                                                class="fas fa-edit"></i></a>
                                                    <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                                    <a href="#" class="btn btn-sm btn-primary btnGambar"><i class="fas fa-eye"></i></a>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table> -->
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
            const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
            <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
        </div>`;

            const getlistCategory = () => {
                const txtSearch = $('#txSearch').val();

                $.ajax({
                        url: "{{ route('getlistCategory') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch
                        },
                        beforeSend: () => {
                            $('#containerCategory').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerCategory').html(res)
                        $('#tableCategory').DataTable({
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

            getlistCategory();
            $('#minimumRateCategory,#minimumRateCategoryEdit,#maximumRateCategory,#maximumRateCategoryEdit').on(
                'input',
                function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });

            $('#saveCategory').click(function() {
                // Ambil nilai input
                var nameCategory = $('#nameCategory').val().trim();
                var minimumRateCategory = $('#minimumRateCategory').val().trim();
                var maximumRateCategory = $('#maximumRateCategory').val().trim();

                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                var isValid = true;

                if (nameCategory === '') {
                    $('#nameCategoryError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#nameCategoryError').addClass('d-none');
                }

                if (minimumRateCategory === '') {
                    $('#minimumRateCategoryError').removeClass('d-none').text(
                        "Minimum Rate Category tidak boleh kosong");
                    isValid = false;
                } else if (isNaN(minimumRateCategory) || parseFloat(minimumRateCategory) < 0) {
                    $('#minimumRateCategoryError').removeClass('d-none').text(
                        "Minimum Rate Category harus berupa angka positif");
                    isValid = false;
                } else if (parseFloat(minimumRateCategory) > 100000000000000000) {
                    $('#minimumRateCategoryError').removeClass('d-none').text(
                        "Karakter maksimum tidak boleh melebihi 15 ");
                    isValid = false;
                } else {
                    $('#minimumRateCategoryError').addClass('d-none');
                }

                // Only validate maximumRateCategory if it's not empty
                if (maximumRateCategory !== '') {
                    if (isNaN(maximumRateCategory) || parseFloat(maximumRateCategory) < 0) {
                        $('#maximumRateCategoryError').removeClass('d-none').text(
                            "Maximum Rate Category harus berupa angka positif");
                        isValid = false;
                    } else if (parseFloat(maximumRateCategory) > 1000000000000000) {
                        $('#maximumRateCategoryError').removeClass('d-none').text(
                            "Karakter maksimum tidak boleh melebihi 15 ");
                        isValid = false;
                    } else {
                        $('#maximumRateCategoryError').addClass('d-none');
                    }
                } else {
                    $('#maximumRateCategoryError').addClass('d-none'); // Hide error if field is empty
                }

                // Jika semua input valid, lanjutkan aksi simpan
                if (isValid) {
                    Swal.fire({
                        title: "Apakah Anda yakin?",
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
                                text: 'Please wait while we process save your data.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            $.ajax({
                                url: '/masterdata/category/store',
                                method: 'POST',
                                data: {
                                    nameCategory: nameCategory,
                                    minimumRateCategory: minimumRateCategory,
                                    maximumRateCategory: maximumRateCategory ||
                                        null, // Send null if empty
                                    _token: '{{ csrf_token() }}',
                                },
                                success: function(response) {
                                    Swal.close();
                                    if (response.success) {
                                        showMessage("success",
                                            "Berhasil ditambahkan");
                                        $('#modalTambahCategory').modal('hide');
                                        getlistCategory();
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


            $(document).on('click', '.btnUpdateCategory', function(e) {
                var categoryid = $(this).data('id');
                $.ajax({
                    url: '/masterdata/category/' + categoryid,
                    method: 'GET',
                    success: function(response) {
                        $('#nameCategoryEdit').val(response.category_name);
                        $('#minimumRateCategoryEdit').val(response.minimum_rate);
                        $('#maximumRateCategoryEdit').val(response.maximum_rate);
                        $('#modalEditCategory').modal('show');
                        $('#saveEditCategory').data('id', categoryid);
                    },
                    error: function() {
                        showMessage("error", "Terjadi kesalahan saat mengambil data");
                    }
                });
            });

            $('#saveEditCategory').on('click', function() {
                var categoryid = $(this).data('id');
                var nameCategory = $('#nameCategoryEdit').val();
                var minimumRateCategory = $('#minimumRateCategoryEdit').val();
                var maximumRateCategory = $('#maximumRateCategoryEdit').val();

                let isValid = true;

                if (nameCategory === '') {
                    $('#nameCategoryErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#nameCategoryErrorEdit').addClass('d-none');
                }

                if (minimumRateCategory === '') {
                    $('#minimumRateCategoryErrorEdit').removeClass('d-none').text(
                        "Minimum Rate Category tidak boleh kosong");
                    isValid = false;
                } else if (isNaN(minimumRateCategory) || parseFloat(minimumRateCategory) < 0) {
                    $('#minimumRateCategoryErrorEdit').removeClass('d-none').text(
                        "Minimum Rate Category harus berupa angka positif");
                    isValid = false;
                } else if (parseFloat(minimumRateCategory) > 100000000000000000) {
                    $('#minimumRateCategoryErrorEdit').removeClass('d-none').text(
                        "Karakter maksimum tidak boleh melebihi 15 ");
                    isValid = false;
                } else {
                    $('#minimumRateCategoryErrorEdit').addClass('d-none');
                }

                // Only validate maximumRateCategory if it's not empty
                if (maximumRateCategory !== '') {
                    if (isNaN(maximumRateCategory) || parseFloat(maximumRateCategory) < 0) {
                        $('#maximumRateCategoryErrorEdit').removeClass('d-none').text(
                            "Maximum Rate Category harus berupa angka positif");
                        isValid = false;
                    } else if (parseFloat(maximumRateCategory) > 100000000000000000) {
                        $('#maximumRateCategoryErrorEdit').removeClass('d-none').text(
                            "Karakter maksimum tidak boleh melebihi 15 ");
                        isValid = false;
                    } else {
                        $('#maximumRateCategoryErrorEdit').addClass('d-none');
                    }
                } else {
                    $('#maximumRateCategoryErrorEdit').addClass('d-none'); // Hide error if field is empty
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

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                }
                            });

                            $.ajax({
                                url: '/masterdata/category/update/' + categoryid,
                                method: 'PUT',
                                data: {
                                    nameCategory: nameCategory,
                                    minimumRateCategory: minimumRateCategory,
                                    maximumRateCategory: maximumRateCategory ||
                                    null, // Send null if empty
                                    _token: '{{ csrf_token() }}',
                                },
                                success: function(response) {
                                    Swal.close();
                                    if (response.success) {
                                        showMessage("success", response.message);
                                        $('#modalEditCategory').modal('hide');
                                        getlistCategory();
                                    }
                                },
                                error: function(xhr) {
                                    if (xhr.status === 422) {
                                        let errors = xhr.responseJSON.errors;
                                        let errorMessage = '';
                                        Object.values(errors).forEach(error => {
                                            errorMessage += error[0] + '\n';
                                        });
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Validasi Gagal!',
                                            text: errorMessage,
                                        });
                                    }
                                }
                            });
                        }
                    });
                }
            });

            $('#modalTambahCategory').on('hidden.bs.modal', function() {
                $('#nameCategory,#minimumRateCategory,#maximumRateCategory').val('');
                if (!$('#nameCategoryError').hasClass('d-none')) {
                    $('#nameCategoryError').addClass('d-none');
                }
                if (!$('#minimumRateCategoryError').hasClass('d-none')) {
                    $('#minimumRateCategoryError').addClass('d-none');
                }
                if (!$('#maximumRateCategoryError').hasClass('d-none')) {
                    $('#maximumRateCategoryError').addClass('d-none');
                }
            });
            $('#modalEditCategory').on('hidden.bs.modal', function() {
                $('#nameCategoryEdit,#minimumRateCategoryEdit,#maximumRateCategoryEdit').val('');
                if (!$('#nameCategoryErrorEdit').hasClass('d-none')) {
                    $('#nameCategoryErrorEdit').addClass('d-none');
                }
                if (!$('#minimumRateCategoryErrorEdit').hasClass('d-none')) {
                    $('#minimumRateCategoryErrorEdit').addClass('d-none');
                }
                if (!$('#maximumRateCategoryErrorEdit').hasClass('d-none')) {
                    $('#maximumRateCategoryErrorEdit').addClass('d-none');
                }
            });

            $(document).on('click', '.btnDestroyCategory', function(e) {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Apakah Anda yakin ingin menghapus ini?",
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
                            text: 'Please wait while we process delete your data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            type: "GET",
                            url: '/masterdata/category/destroy/' + id,
                            data: {
                                id: id,
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
                                        "Berhasil dihapus");
                                    getlistCategory();
                                } else {
                                    showMessage("error", "Gagal untuk menghapus");
                                }
                            },
                            error: function(xhr) {
                                if (xhr.status === 422) {
                                    // Ambil pesan error dari respons JSON
                                    let errors = xhr.responseJSON.errors;
                                    let errorMessage = '';

                                    // Gabungkan semua pesan error menjadi satu
                                    Object.values(errors).forEach(error => {
                                        errorMessage += error[0] + '\n';
                                    });

                                    // Tampilkan pesan error di SweetAlert
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Validasi Gagal!',
                                        text: errorMessage,
                                    });
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
