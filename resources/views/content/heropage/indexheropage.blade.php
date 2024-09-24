@extends('layout.main')

@section('title', 'Content | Hero page')

@section('main')
<div class="container-fluid" id="container-wrapper">

    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambahHeropage" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahHeropageTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahHeropage">Add Hero page</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="titleHeroPage" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="titleHeroPage" value=""
                            placeholder="Masukkan judul Hero page">
                        <div id="titleHeroPageError" class="text-danger mt-1 d-none">Please fill in the Title</div>
                    </div>
                    <div class="mt-3">
                        <label for="contentHeroPage" class="form-label fw-bold">Content</label>
                        <textarea class="form-control" id="contentHeroPage" rows="3"
                            placeholder="Masukkan content"></textarea>
                        <div id="contentHeroPageError" class="text-danger mt-1 d-none">Please fill in the Content </div>
                    </div>
                    <div class="mt-3">
                        <label for="imageHeroPage" class="form-label fw-bold">Image</label>
                        <input type="file" class="form-control" id="imageHeroPage" value="">
                        <div id="imageHeroPageError" class="text-danger mt-1 d-none">Please fill in the Image</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveHeroPage" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditHeropage" tabindex="-1" role="dialog" aria-labelledby="modalEditHeropageTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditHeropageTitle">Edit Hero page</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="heropageIdEdit">
                    <div class="mt-3">
                        <label for="titleHeroPage" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="titleHeroPageEdit" value=""
                            placeholder="Masukkan judul Hero page">
                        <div id="titleHeroPageErrorEdit" class="text-danger mt-1 d-none">Please fill in the Title</div>
                    </div>
                    <div class="mt-3">
                        <label for="contentHeroPage" class="form-label fw-bold">Content</label>
                        <textarea class="form-control" id="contentHeroPageEdit" rows="3"
                            placeholder="Masukkan content"></textarea>
                        <div id="contentHeroPageErrorEdit" class="text-danger mt-1 d-none">Please fill in the Content </div>
                    </div>
                    <div class="mt-3">
                        <label for="imageHeroPage" class="form-label fw-bold">Image</label>
                        <p class="">Name Image : <span id="textNamaEdit"></span></p>
                        <input type="file" class="form-control" id="imageHeroPageEdit" value="">
                        <div id="imageHeroPageErrorEdit" class="text-danger mt-1 d-none">Please fill in the Image
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditHeroPage" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 px-4">Hero page</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Hero page</li>
        </ol>
    </div>
    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahHeropage" id="#modalCenter"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Add Hero page</button>
                    </div>
                    <div id="containerHeropage" class="table-responsive px-2">
                        <!-- <table class="table align-items-center table-flush table-hover" id="tableCarousel">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Judul</th>
                                        <th>Isi Carousel</th>
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

        const getlistHeroPage = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "{{ route('getlistHeroPage') }}",
                method: "GET",
                data: {
                    txSearch: txtSearch
                },
                beforeSend: () => {
                    $('#containerHeropage').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerHeropage').html(res)
                    $('#tableHeropage').DataTable({
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

        getlistHeroPage();


        $('#saveHeroPage').click(function () {
            // Ambil nilai input
            var titleHeroPage = $('#titleHeroPage').val().trim();
            var contentHeroPage = $('#contentHeroPage').val().trim();
            var imageHeroPage = $('#imageHeroPage')[0].files[0];

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (titleHeroPage === '') {
                $('#titleHeroPageError').removeClass('d-none');
                isValid = false;
            } else {
                $('#titleHeroPageError').addClass('d-none');
            }
            if (contentHeroPage === '') {
                $('#contentHeroPageError').removeClass('d-none');
                isValid = false;
            } else {
                $('#contentHeroPageError').addClass('d-none');
            }
            if (imageHeroPage) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validExtensions.includes(imageHeroPage.type)) {
                    $('#imageHeroPageError').text('Only JPG, JPEG, or PNG files are allowed, and the image cannot be empty.').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageHeroPageError').addClass('d-none');
                }
            } else if (!imageHeroPage) {
                $('#imageHeropageError').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageHeroPageError').addClass('d-none');
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
                        var formData = new FormData();
                        formData.append('titleHeroPage', titleHeroPage);
                        formData.append('contentHeroPage', contentHeroPage);
                        formData.append('imageHeroPage', imageHeroPage);
                        formData.append('_token', csrfToken);
                        Swal.fire({
                            title: 'Loading...',
                            text: 'Please wait while we process your data Heropage.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            type: "POST",
                            url: "{{ route('addHeroPage') }}",
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
                                    getlistHeroPage();
                                    $('#modalTambahHeropage').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Failed to add data.",
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
        });

        $(document).on('click', '.btnDestroyHeroPage', function (e) {
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
                        text: 'Please wait while we process delete your data Heropage.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('destroyHeroPage') }}",
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
                                showMessage("success", "Successfully deleted");
                                getlistHeroPage();
                            } else {
                                showMessage("error", "Failed to delete");
                            }
                        }
                    });
                }
            });

        });

        $(document).on('click', '.btnUpdateHeroPage', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let title_heropage = $(this).data('title_heropage');
            let content_heropage  = $(this).data('content_heropage');
            let image_heropage  = $(this).data('image_heropage');

            $('#titleHeroPageEdit').val(title_heropage);
            $('#contentHeroPageEdit').val(content_heropage);
            $('#textNamaEdit').text(image_heropage);
            $('#heropageIdEdit').val(id);

            $(document).on('click', '#saveEditHeroPage', function (e) {

                let id = $('#heropageIdEdit').val();
                let titleHeroPage = $('#titleHeroPageEdit').val();
                let contentHeroPage = $('#contentHeroPageEdit').val();
                let imageHeroPage = $('#imageHeroPageEdit')[0].files[0];
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let isValid = true;

                if (titleHeroPage === '') {
                    $('#titleHeroPageErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#titleHeroPageErrorEdit').addClass('d-none');
                }

                // Validasi Content
                if (contentHeroPage === '') {
                    $('#contentHeroPageErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#contentHeroPageErrorEdit').addClass('d-none');
                }

                if (imageHeroPage) {
                    var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validExtensions.includes(imageHeroPage.type)) {
                        $('#imageHeroPageErrorEdit').text('Only JPG, JPEG, or PNG files are allowed, and the image cannot be empty.').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#imageHeroPageErrorEdit').addClass('d-none');
                    }
                } else if (imageHeroPage === 0 && $('#textNamaEdit').text() === '') {
                    $('#imageHeroPageErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageHeroPageErrorEdit').addClass('d-none');
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
                            formData.append('titleHeroPage', titleHeroPage);
                            formData.append('contentHeroPage', contentHeroPage);
                            if(imageHeroPage){
                            formData.append('imageHeroPage', imageHeroPage);
                            }
                            formData.append('_token', csrfToken);
                            Swal.fire({
                                title: 'Loading...',
                                text: 'Please wait while we process update your data Heropage.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: "{{ route('updateHeroPage') }}",
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
                                            getlistHeroPage();
                                        $('#modalEditHeropage').modal(
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
            $('#modalEditHeropage').modal('show');
        });
        $('#modalTambahHeropage').on('hidden.bs.modal', function () {
            $('#titleHeroPage,#contentHeroPage,#imageHeroPage').val('');
            if (!$('#titleHeroPageError').hasClass('d-none')) {
                $('#titleHeroPageError').addClass('d-none');

            }
            if (!$('#contentHeroPageError').hasClass('d-none')) {
                $('#contentHeroPageError').addClass('d-none');

            }
            if (!$('#imageHeroPageError').hasClass('d-none')) {
                $('#imageHeroPageError').addClass('d-none');

            }
        });
        $('#modalEditHeropage').on('hidden.bs.modal', function () {
            $('#titleHeroPageEdit,#contentHeroPageEdit,#imageHeroPageEdit').val('');
            if (!$('#titleHeroPageErrorEdit').hasClass('d-none')) {
                $('#titleHeroPageErrorEdit').addClass('d-none');

            }
            if (!$('#contentHeroPageErrorEdit').hasClass('d-none')) {
                $('#contentHeroPageErrorEdit').addClass('d-none');

            }
            if (!$('#imageHeroPageErrorEdit').hasClass('d-none')) {
                $('#imageHeroPageErrorEdit').addClass('d-none');

            }
        });

    });
</script>
@endsection