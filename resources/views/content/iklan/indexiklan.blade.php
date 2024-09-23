@extends('layout.main')

@section('title', 'Content | Iklan')

@section('main')

<div class="container-fluid" id="container-wrapper">


    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambahIklan" tabindex="-1" role="dialog" aria-labelledby="modalTambahIklanTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahIklan">Add Iklan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="judulIklan" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="judulIklan" value=""
                            placeholder="Masukkan judul iklan">
                        <div id="judulIklanError" class="text-danger mt-1 d-none">Please fill in the Title</div>
                    </div>
                    <div class="mt-3">
                        <label for="imageIklan" class="form-label fw-bold">Image</label>
                        <input type="file" class="form-control" id="imageIklan" value="">
                        <div id="imageIklanError" class="text-danger mt-1 d-none">Please fill in the Image</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveIklan" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditIklan" tabindex="-1" role="dialog" aria-labelledby="modalEditIklanTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditIklanTitle">Edit Iklan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="iklanIdEdit">
                    <div class="mt-3">
                        <label for="judulIklan" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="judulIklanEdit" value=""
                            placeholder="Masukkan judul iklan">
                        <div id="judulIklanErrorEdit" class="text-danger mt-1 d-none">Please fill in the Title</div>
                    </div>
                    <div class="mt-3">
                        <label for="imageIklan" class="form-label fw-bold">Image</label>
                        <p class="">Name Image : <span id="textNamaEdit"></span></p>
                        <input type="file" class="form-control" id="imageIklanEdit" value="">
                        <div id="imageIklanErrorEdit" class="text-danger mt-1 d-none">Please fill in the Image
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditIklan" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 px-4">Iklan</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Iklan</li>
        </ol>
    </div>
    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahIklan" id="#modalCenter"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Add Iklan</button>
                    </div>
                    <div id="containerIklan" class="table-responsive px-2">
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

        const getlistIklan = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "{{ route('getlistIklan') }}",
                method: "GET",
                data: {
                    txSearch: txtSearch
                },
                beforeSend: () => {
                    $('#containerIklan').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerIklan').html(res)
                    $('#tableIklan').DataTable({
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

        getlistIklan();

        $('#saveIklan').click(function () {
            // Ambil nilai input
            var judulIklan = $('#judulIklan').val().trim();
            var imageIklan = $('#imageIklan')[0].files[0];

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (judulIklan === '') {
                $('#judulIklanError').removeClass('d-none');
                isValid = false;
            } else {
                $('#judulIklanError').addClass('d-none');
            }
            if (imageIklan) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'];
                if (!validExtensions.includes(imageIklan.type)) {
                    $('#imageIklanError').text('Only JPG, JPEG, or PNG files are allowed, and the image cannot be empty.').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageIklanError').addClass('d-none');
                }
            } else if (!imageIklan) {
                $('#imageIklanError').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageIklanError').addClass('d-none');
            }

            // Jika semua input valid, lanjutkan aksi simpan
            if (isValid) {
                Swal.fire({
                    title: "Are you sure?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5D87FF',
                    cancelButtonColor: '#49BEFF',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData();
                        formData.append('judulIklan', judulIklan);
                        formData.append('imageIklan', imageIklan);
                        formData.append('_token', csrfToken);
                        Swal.fire({
                            title: 'Loading...',
                            text: 'Please wait while we process your data iklan.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: "{{ route('addIklan') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
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
                                    showMessage("success", "Data successfully saved");
                                    getlistIklan();
                                    $('#modalTambahIklan').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Failed to add data",
                                        text: response
                                            .message,
                                        icon: "error"
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: "Failed to add data",
                                    text: xhr.responseJSON
                                        .message,
                                    icon: "error",
                                });
                            }
                        });
                    }
                });
            } else {
                showMessage("error", "Please check for empty inputs");
            }
        });



        $(document).on('click', '.btnUpdateIklan', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let judul_iklan = $(this).data('judul_iklan');
            let image_iklan = $(this).data('image_iklan');

            $('#judulIklanEdit').val(judul_iklan);
            $('#textNamaEdit').text(image_iklan);
            $('#iklanIdEdit').val(id);

            $(document).on('click', '#saveEditIklan', function (e) {

                let id = $('#iklanIdEdit').val();
                let judulIklan = $('#judulIklanEdit').val();
                let imageIklan = $('#imageIklanEdit')[0].files[0];
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let isValid = true;

                if (judulIklan === '') {
                    $('#judulIklanErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#judulIklanErrorEdit').addClass('d-none');
                }

                if (imageIklan) {
                    var validExtensions = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'];
                    if (!validExtensions.includes(imageIklan.type)) {
                        $('#imageIklanErrorEdit').text('Only JPG, JPEG, or PNG files are allowed, and the image cannot be empty.').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#imageIklanErrorEdit').addClass('d-none');
                    }
                } else if (imageIklan === 0 && $('#textNamaEdit').text() === '') {
                    $('#imageIklanErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageIklanErrorEdit').addClass('d-none');
                }

                if (isValid) {
                    Swal.fire({
                        title: "Are you sure?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#5D87FF',
                        cancelButtonColor: '#49BEFF',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let formData = new FormData();
                            formData.append('id', id);
                            formData.append('judulIklan', judulIklan);
                            if(imageIklan){
                            formData.append('imageIklan', imageIklan);
                            }
                            formData.append('_token', csrfToken);
                            Swal.fire({
                                title: 'Loading...',
                                text: 'Please wait while we process update your data iklan.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: "{{ route('updateIklan') }}",
                                data: formData,
                                contentType: false,
                                processData: false,
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
                                        showMessage("success",
                                            "Data successfully updated");
                                        getlistIklan();
                                        $('#modalEditIklan').modal(
                                            'hide');
                                    } else {
                                        Swal.fire({
                                            title: "Failed to update",
                                            icon: "error"
                                        });
                                    }
                                }
                            });
                        }
                    });
                } else {
                    showMessage("error", "Please check for empty inputs");
                }
            })

            // validateInformationsInput('modalEditInformations');
            $('#modalEditIklan').modal('show');
        });

        $('#modalTambahIklan').on('hidden.bs.modal', function () {
            $('#judulIklan,#imageIklan').val('');
            if (!$('#judulIklanError').hasClass('d-none')) {
                $('#judulIklanError').addClass('d-none');

            }
            if (!$('#imageIklanError').hasClass('d-none')) {
                $('#imageIklanError').addClass('d-none');

            }
        });
        $('#modalEditIklan').on('hidden.bs.modal', function () {
            $('#judulIklanEdit,#imageIklanEdit').val('');
            if (!$('#judulIklanErrorEdit').hasClass('d-none')) {
                $('#judulIklanErrorEdit').addClass('d-none');

            }
            if (!$('#imageIklanErrorEdit').hasClass('d-none')) {
                $('#imageIklanErrorEdit').addClass('d-none');

            }
        });




        $(document).on('click', '.btnDestroyIklan', function (e) {
            let id = $(this).data('id');

            Swal.fire({
                title: "Are you sure you want to delete this?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5D87FF',
                cancelButtonColor: '#49BEFF',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Please wait while we process delete your data iklan.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        type: "GET",
                        url: "{{ route('destroyIklan') }}",
                        data: {
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
                                showMessage("success", "Successfully deleted");
                                getlistIklan();
                            } else {
                                showMessage("error", "Failed to delete");
                            }
                        }
                    });
                }
            });

        });

    });
</script>
@endsection