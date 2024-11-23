@extends('layout.main')

@section('title', 'Content | Advertisement')

@section('main')

<div class="container-fluid" id="container-wrapper">


    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambahAdvertisement" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahAdvertisementTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahAdvertisement">Tambah Advertisement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="titleAdvertisement" class="form-label fw-bold">Judul</label>
                        <input type="text" class="form-control" id="titleAdvertisement" value=""
                            placeholder="Masukkan judul iklan">
                        <div id="titleAdvertisementError" class="text-danger mt-1 d-none">Silahkan isi Judul</div>
                    </div>
                    <div class="mt-3">
                        <label for="imageAdvertisement" class="form-label fw-bold">Gambar</label>
                        <input type="file" class="form-control" id="imageAdvertisement" value="">
                        <div id="imageAdvertisementError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveAdvertisement" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditAdvertisement" tabindex="-1" role="dialog"
        aria-labelledby="modalEditAdvertisementTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditAdvertisementTitle">Edit Advertisement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="titleAdvertisement" class="form-label fw-bold">Judul</label>
                        <input type="text" class="form-control" id="titleAdvertisementEdit" value=""
                            placeholder="Masukkan judul iklan">
                        <div id="titleAdvertisementErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Judul
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="imageAdvertisement" class="form-label fw-bold">Gambar</label>
                        <p class="">Name Image : <span id="textNamaEdit"></span></p>
                        <input type="file" class="form-control" id="imageAdvertisementEdit" value="">
                        <div id="imageAdvertisementErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Gambar
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditAdvertisement" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 px-4">Advertisement</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Advertisement</li>
        </ol>
    </div>
    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahAdvertisement" id="#modalCenter"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Tambah Advertisement</button>
                    </div>
                    <div id="containerAdvertisement" class="table-responsive px-2">
                        <!-- <table class="table align-items-center table-flush table-hover" id="tableIklan">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Judul</th>
                                        <th>Isi Iklan</th>
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
    $(document).ready(function () {
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

        const getlistAdvertisement = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "{{ route('getlistAdvertisement') }}",
                method: "GET",
                data: {
                    txSearch: txtSearch
                },
                beforeSend: () => {
                    $('#containerAdvertisement').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerAdvertisement').html(res)
                    $('#tableAdvertisement').DataTable({
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

        getlistAdvertisement();

        $('#saveAdvertisement').click(function () {
            var titleAdvertisement = $('#titleAdvertisement').val().trim();
            var imageAdvertisement = $('#imageAdvertisement')[0].files[0];

            var isValid = true;

            if (titleAdvertisement === '') {
                $('#titleAdvertisementError').removeClass('d-none');
                isValid = false;
            } else {
                $('#titleAdvertisementError').addClass('d-none');
            }
            if (imageAdvertisement) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'];
                if (!validExtensions.includes(imageAdvertisement.type)) {
                    $('#imageAdvertisementError').text('Hanya file JPG, JPEG, atau PNG yang diizinkan.')
                        .removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageAdvertisementError').addClass('d-none');
                }
            } else {
                $('#imageAdvertisementError').removeClass('d-none');
                isValid = false;
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
                            text: 'Please wait while we are saving the data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        var formData = new FormData();
                        formData.append('titleAdvertisement', titleAdvertisement);
                        formData.append('imageAdvertisement', imageAdvertisement);
                        formData.append('_token', '{{ csrf_token() }}');

                        $.ajax({
                            url: '/content/Advertisement/store',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success",
                                        "Berhasil ditambahkan");
                                        $('#modalTambahAdvertisement').modal('hide');
                                        getlistAdvertisement();
                                }
                            },
                            error: function (response) {
                                Swal.close();
                                if (response.status === 422) {
                                    showMessage("error", "Judul yang dimasukkan sudah ada. Silakan masukkan judul yang berbeda.");
                                } else {
                                showMessage("error","Terjadi kesalahan, coba lagi nanti");
                                }
                            }
                        });
                    }
                });
            }
        });


        $(document).on('click', '.btnUpdateAdvertisement', function (e) {
            var advertisementid = $(this).data('id');
            $.ajax({
                url: '/content/Advertisement/' + advertisementid,
                method: 'GET',
                success: function (response) {
                    $('#titleAdvertisementEdit').val(response.title_Advertisement);
                    $('#textNamaEdit').text(response.image_Advertisement);
                    $('#modalEditAdvertisement').modal('show');
                    $('#saveEditAdvertisement').data('id', advertisementid);
                },
                error: function () {
                    showMessage("error",
                        "Terjadi kesalahan saat mengambil data");
                }
            });
        });
        $('#saveEditAdvertisement').on('click', function () {
            var advertisementid = $(this).data('id');
            var titleAdvertisement = $('#titleAdvertisementEdit').val();
            var imageAdvertisement = $('#imageAdvertisementEdit')[0].files[0];

            let isValid = true;

            if (titleAdvertisement === '') {
                $('#titleAdvertisementErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#titleAdvertisementErrorEdit').addClass('d-none');
            }

            if (imageAdvertisement) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png' , 'image/svg+xml'];
                if (!validExtensions.includes(imageAdvertisement.type)) {
                    $('#imageAdvertisementErrorEdit').text(
                        'Hanya file JPG, JPEG, atau PNG yang diizinkan.').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageAdvertisementErrorEdit').addClass('d-none');
                }
            } else if ($('#textNamaEdit').text() === '') {
                $('#imageAdvertisementErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageAdvertisementErrorEdit').addClass('d-none');
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
                        var formData = new FormData();
                        formData.append('titleAdvertisement', titleAdvertisement);
                        if (imageAdvertisement) {
                            formData.append('imageAdvertisement', imageAdvertisement);
                        }
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            }
                        });

                        $.ajax({
                            url: '/content/Advertisement/update/' + advertisementid,
                            method: 'POST',
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success", response
                                        .message);
                                    $('#modalEditAdvertisement').modal('hide');
                                    getlistAdvertisement();
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
        $('#modalTambahAdvertisement').on('hidden.bs.modal', function () {
            $('#titleAdvertisement,#imageAdvertisement').val('');
            if (!$('#titleAdvertisementError').hasClass('d-none')) {
                $('#titleAdvertisementError').addClass('d-none');

            }
            if (!$('#imageAdvertisementError').hasClass('d-none')) {
                $('#imageAdvertisementError').addClass('d-none');

            }
        });
        $('#modalEditAdvertisement').on('hidden.bs.modal', function () {
            $('#titleAdvertisementEdit,#imageAdvertisementEdit').val('');
            if (!$('#titleAdvertisementErrorEdit').hasClass('d-none')) {
                $('#titleAdvertisementErrorEdit').addClass('d-none');

            }
            if (!$('#imageAdvertisementErrorEdit').hasClass('d-none')) {
                $('#imageAdvertisementErrorEdit').addClass('d-none');

            }
        });




        $(document).on('click', '.btnDestroyAdvertisement', function (e) {
            let id = $(this).data('id');

            Swal.fire({
                title: "Apakah Anda yakin ingin menghapus ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5D87FF',
                cancelButtonColor: '#49BEFF',
                confirmButtonText: 'Iya',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Please wait while we process delete your data Advertisement.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url:'/content/Advertisement/destroy/'+id,
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: id,
                        },
                        success: function (response) {
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
                                showMessage("success", "Berhasil Menghapus Data");
                                getlistAdvertisement();
                            } else {
                                showMessage("error", "Gagal Menghapus Data");
                            }
                        }
                    });
                }
            });

        });

    });
</script>
@endsection
