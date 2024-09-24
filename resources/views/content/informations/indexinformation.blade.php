@extends('layout.main')

@section('title', ' Content | Information')

@section('main')

<div class="container-fluid" id="container-wrapper">


    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambahInformations" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahInformationsTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahInformations">Add Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="informationForm" enctype="multipart/form-data">
                        <div class="mt-3">
                            <label for="titleInformations" class="form-label fw-bold">Title</label>
                            <input type="text" class="form-control" id="titleInformations" value=""
                                placeholder="Masukkan judul information">
                            <div id="titleInformationsError" class="text-danger mt-1 d-none">Please fill in the Title</div>
                        </div>
                        <div class="mt-3">
                            <label for="contentInformations" class="form-label fw-bold">Content</label>
                            <textarea class="form-control" id="contentInformations" rows="3"
                                placeholder="Masukkan content"></textarea>
                            <div id="contentInformationsError" class="text-danger mt-1 d-none">Please fill in the Content</div>
                        </div>
                        <div class="mt-3">
                            <label for="imageInformations" class="form-label fw-bold">Image</label>
                            <input type="file" class="form-control" id="imageInformations" value="">
                            <div id="imageInformationsError" class="text-danger mt-1 d-none">Please fill in the Image</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                            <button type="button" id="saveInformations" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditInformations" tabindex="-1" role="dialog"
        aria-labelledby="modalEditInformationsTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditInformationsTitle">Edit Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="informationsIdEdit">
                    <div class="mt-3">
                        <label for="titleInformations" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="titleInformationsEdit" value=""
                            placeholder="Masukkan judul information">
                        <div id="titleInformationsErrorEdit" class="text-danger mt-1 d-none">Please fill in the Title</div>
                    </div>
                    <div class="mt-3">
                        <label for="contentInformations" class="form-label fw-bold">Content</label>
                        <textarea class="form-control" id="contentInformationsEdit" rows="3"
                            placeholder="Masukkan content"></textarea>
                        <div id="contentInformationsErrorEdit" class="text-danger mt-1 d-none">Please fill in the Content</div>
                    </div>
                    <div class="mt-3">
                        <label for="imageInformations" class="form-label fw-bold">Image</label>
                        <p class="">Name Image : <span id="textNamaEdit"></span></p>
                        <input type="file" class="form-control" id="imageInformationsEdit" value="">
                        <div id="imageInformationsErrorEdit" class="text-danger mt-1 d-none">Please fill in the Image
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditInformations" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Information</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Information</li>
        </ol>
    </div>
    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahInformations" id="#modalCenter"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Add Information</button>
                    </div>
                    {{-- <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#modalTambahInformations" id="#modalCenter"><span class="pr-2"><i
                                class="fas fa-plus"></i></span>Tambah Information</button> --}}
                    <div id="containerInformations" class="table-responsive px-2">
                        <!-- <table class="table align-items-center table-flush table-hover" id="tableinformations">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        <th>No.</th>
                                                                        <th>Judul</th>
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

        const getlistInformations = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "{{ route('getlistInformations') }}",
                method: "GET",
                data: {
                    txSearch: txtSearch
                },
                beforeSend: () => {
                    $('#containerInformations').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerInformations').html(res)
                    $('#tableInformations').DataTable({
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

        getlistInformations();
        $('#titleInformations, #contentInformations', 'imageInformations').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });


        $('#saveInformations').click(function () {
            // Ambil nilai input
            var titleInformations = $('#titleInformations').val().trim();
            var contentInformations = $('#contentInformations').val().trim();
            var imageInformations = $('#imageInformations')[0].files[0]; // Mendapatkan file

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (titleInformations === '') {
                $('#titleInformationsError').removeClass('d-none');
                isValid = false;
            } else {
                $('#titleInformationsError').addClass('d-none');
            }
            if (contentInformations === '') {
                $('#contentInformationsError').removeClass('d-none');
                isValid = false;
            } else {
                $('#contentInformationsError').addClass('d-none');
            }
            if (imageInformations) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validExtensions.includes(imageInformations.type)) {
                    $('#imageInformationsError').text('Only JPG, JPEG, or PNG files are allowed, and the image cannot be empty.').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageInformationsError').addClass('d-none');
                }
            } else if (!imageInformations) {
                $('#imageInformationsError').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageInformationsError').addClass('d-none');
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
                        formData.append('titleInformations', titleInformations);
                        formData.append('contentInformations', contentInformations);
                        formData.append('imageInformations', imageInformations);
                        formData.append('_token', csrfToken);
                        Swal.fire({
                            title: 'Loading...',
                            text: 'Please wait while we process your data information.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: "{{ route('addInformations') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                if (response.status === 'success') {
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
                                    showMessage("success",
                                        "Data successfully saved");
                                    getlistInformations();
                                    $('#modalTambahInformations').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Failed to add data.",
                                        text: response
                                            .message,
                                        icon: "error",
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: "Failed to add data.",
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

        $(document).on('click', '.btnUpdateInformations', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let title_informations = $(this).data('title_informations');
            let content_informations = $(this).data('content_informations');
            let image_informations = $(this).data('image_informations');

            $('#titleInformationsEdit').val(title_informations);
            $('#contentInformationsEdit').val(content_informations);
            $('#textNamaEdit').text(image_informations);
            $('#informationsIdEdit').val(id);

            $(document).on('click', '#saveEditInformations', function (e) {

                let id = $('#informationsIdEdit').val();
                let titleInformations = $('#titleInformationsEdit').val();
                let contentInformations = $('#contentInformationsEdit').val();
                let imageInformations = $('#imageInformationsEdit')[0].files[0];
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let isValid = true;

                if (titleInformations === '') {
                    $('#titleInformationsErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#titleInformationsErrorEdit').addClass('d-none');
                }

                // Validasi Content
                if (contentInformations === '') {
                    $('#contentInformationsErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#contentInformationsErrorEdit').addClass('d-none');
                }

                if (imageInformations) {
                    var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validExtensions.includes(imageInformations.type)) {
                        $('#imageInformationsErrorEdit').text('Only JPG, JPEG, or PNG files are allowed, and the image cannot be empty.').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#imageInformationsErrorEdit').addClass('d-none');
                    }
                } else if (imageInformations === 0 && $('#textNamaEdit').text() === '') {
                    $('#imageInformationsErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageInformationsErrorEdit').addClass('d-none');
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
                            formData.append('titleInformations', titleInformations);
                            formData.append('contentInformations', contentInformations);
                            if(imageInformations){
                            formData.append('imageInformations', imageInformations);
                            }
                            formData.append('_token', csrfToken);
                            Swal.fire({
                                title: 'Loading...',
                                text: 'Please wait while we process update your data information.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: "{{ route('updateInformations') }}",
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
                                        getlistInformations();
                                        $('#modalEditInformations').modal(
                                            'hide');
                                    } else {
                                        Swal.fire({
                                            title: "Failed to updated",
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
            $('#modalEditInformations').modal('show');
        });
        $('#modalTambahInformations').on('hidden.bs.modal', function () {
            $('#titleInformations,#contentInformations,#imageInformations').val('');
            if (!$('#titleInformationsError').hasClass('d-none')) {
                $('#titleInformationsError').addClass('d-none');
            }
            if (!$('#contentInformationsError').hasClass('d-none')) {
                $('#contentInformationsError').addClass('d-none');
            }
            if (!$('#imageInformationsError').hasClass('d-none')) {
                $('#imageInformationsError').addClass('d-none');
            }
        });
        $('#modalEditInformations').on('hidden.bs.modal', function () {
            $('#titleInformationsEdit,#contentInformationsEdit,#imageInformationsEdit').val('');
            if (!$('#titleInformationsErrorEdit').hasClass('d-none')) {
                $('#titleInformationsErrorEdit').addClass('d-none');
            }
            if (!$('#contentInformationsErrorEdit').hasClass('d-none')) {
                $('#contentInformationsErrorEdit').addClass('d-none');
            }
            if (!$('#imageInformationsErrorEdit').hasClass('d-none')) {
                $('#imageInformationsErrorEdit').addClass('d-none');
            }
        });




        $(document).on('click', '.btnDestroyInformations', function (e) {
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
                                text: 'Please wait while we process delete your data information.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('destroyInformations') }}",
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
                                showMessage("success",
                                    "Successfully deleted");
                                getlistInformations();
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