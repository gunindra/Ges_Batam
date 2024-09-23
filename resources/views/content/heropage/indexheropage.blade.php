@extends('layout.main')

@section('title', 'Content | Hero page')

@section('main')
<div class="container-fluid" id="container-wrapper">

    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambahCarousel" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahCarouselTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahCarousel">Add Hero page</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="judulCarousel" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="judulCarousel" value=""
                            placeholder="Masukkan judul Hero page">
                        <div id="judulCarouselError" class="text-danger mt-1 d-none">Please fill in the Title</div>
                    </div>
                    <div class="mt-3">
                        <label for="isiCarousel" class="form-label fw-bold">Content</label>
                        <textarea class="form-control" id="isiCarousel" rows="3"
                            placeholder="Masukkan content"></textarea>
                        <div id="isiCarouselError" class="text-danger mt-1 d-none">Please fill in the Content </div>
                    </div>
                    <div class="mt-3">
                        <label for="imageCarousel" class="form-label fw-bold">Image</label>
                        <input type="file" class="form-control" id="imageCarousel" value="">
                        <div id="imageCarouselError" class="text-danger mt-1 d-none">Please fill in the Image</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveCarousel" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditCarousel" tabindex="-1" role="dialog" aria-labelledby="modalEditCarouselTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditCarouselTitle">Edit Hero page</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="carouselIdEdit">
                    <div class="mt-3">
                        <label for="judulCarousel" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="judulCarouselEdit" value=""
                            placeholder="Masukkan judul Hero page">
                        <div id="judulCarouselErrorEdit" class="text-danger mt-1 d-none">Please fill in the Title</div>
                    </div>
                    <div class="mt-3">
                        <label for="isiCarousel" class="form-label fw-bold">Content</label>
                        <textarea class="form-control" id="isiCarouselEdit" rows="3"
                            placeholder="Masukkan content"></textarea>
                        <div id="isiCarouselErrorEdit" class="text-danger mt-1 d-none">Please fill in the Content </div>
                    </div>
                    <div class="mt-3">
                        <label for="imageCarousel" class="form-label fw-bold">Image</label>
                        <p class="">Name Image : <span id="textNamaEdit"></span></p>
                        <input type="file" class="form-control" id="imageCarouselEdit" value="">
                        <div id="imageCarouselErrorEdit" class="text-danger mt-1 d-none">Please fill in the Image
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditCarousel" class="btn btn-primary">Save changes</button>
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
                            data-target="#modalTambahCarousel" id="#modalCenter"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Add Hero page</button>
                    </div>
                    <div id="containerCarousel" class="table-responsive px-2">
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
                    $('#containerCarousel').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerCarousel').html(res)
                    $('#tableCarousel').DataTable({
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


        $('#saveCarousel').click(function () {
            // Ambil nilai input
            var judulCarousel = $('#judulCarousel').val().trim();
            var isiCarousel = $('#isiCarousel').val().trim();
            var imageCarousel = $('#imageCarousel')[0].files[0];

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (judulCarousel === '') {
                $('#judulCarouselError').removeClass('d-none');
                isValid = false;
            } else {
                $('#judulCarouselError').addClass('d-none');
            }
            if (isiCarousel === '') {
                $('#isiCarouselError').removeClass('d-none');
                isValid = false;
            } else {
                $('#isiCarouselError').addClass('d-none');
            }
            if (imageCarousel) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validExtensions.includes(imageCarousel.type)) {
                    $('#imageCarouselError').text('Only JPG, JPEG, or PNG files are allowed, and the image cannot be empty.').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageCarouselError').addClass('d-none');
                }
            } else if (!imageCarousel) {
                $('#imageCarouselError').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageCarouselError').addClass('d-none');
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
                        formData.append('judulCarousel', judulCarousel);
                        formData.append('isiCarousel', isiCarousel);
                        formData.append('imageCarousel', imageCarousel);
                        formData.append('_token', csrfToken);
                        Swal.fire({
                            title: 'Loading...',
                            text: 'Please wait while we process your data carousel.',
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
                                    $('#modalTambahCarousel').modal('hide');
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

        $(document).on('click', '.btnDestroyCarousel', function (e) {
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
                        text: 'Please wait while we process delete your data carousel.',
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

        $(document).on('click', '.btnUpdateCarousel', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let judul_carousel = $(this).data('judul_carousel');
            let isi_carousel = $(this).data('isi_carousel');
            let image_carousel = $(this).data('image_carousel');

            $('#judulCarouselEdit').val(judul_carousel);
            $('#isiCarouselEdit').val(isi_carousel);
            $('#textNamaEdit').text(image_carousel);
            $('#carouselIdEdit').val(id);

            $(document).on('click', '#saveEditCarousel', function (e) {

                let id = $('#carouselIdEdit').val();
                let judulCarousel = $('#judulCarouselEdit').val();
                let isiCarousel = $('#isiCarouselEdit').val();
                let imageCarousel = $('#imageCarouselEdit')[0].files[0];
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let isValid = true;

                if (judulCarousel === '') {
                    $('#judulCarouselErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#judulCarouselErrorEdit').addClass('d-none');
                }

                // Validasi Content
                if (isiCarousel === '') {
                    $('#isiCarouselErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#isiCarouselErrorEdit').addClass('d-none');
                }

                if (imageCarousel) {
                    var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validExtensions.includes(imageCarousel.type)) {
                        $('#imageCarouselErrorEdit').text('Only JPG, JPEG, or PNG files are allowed, and the image cannot be empty.').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#imageCarouselErrorEdit').addClass('d-none');
                    }
                } else if (imageCarousel === 0 && $('#textNamaEdit').text() === '') {
                    $('#imageCarouselErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageCarouselErrorEdit').addClass('d-none');
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
                            formData.append('judulCarousel', judulCarousel);
                            formData.append('isiCarousel', isiCarousel);
                            if(imageCarousel){
                            formData.append('imageCarousel', imageCarousel);
                            }
                            formData.append('_token', csrfToken);
                            Swal.fire({
                                title: 'Loading...',
                                text: 'Please wait while we process update your data carousel.',
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
                                        $('#modalEditCarousel').modal(
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
            $('#modalEditCarousel').modal('show');
        });
        $('#modalTambahCarousel').on('hidden.bs.modal', function () {
            $('#judulCarousel,#isiCarousel,#imageCarousel').val('');
            if (!$('#judulCarouselError').hasClass('d-none')) {
                $('#judulCarouselError').addClass('d-none');

            }
            if (!$('#isiCarouselError').hasClass('d-none')) {
                $('#isiCarouselError').addClass('d-none');

            }
            if (!$('#imageCarouselError').hasClass('d-none')) {
                $('#imageCarouselError').addClass('d-none');

            }
        });
        $('#modalEditCarousel').on('hidden.bs.modal', function () {
            $('#judulCarouselEdit,#isiCarouselEdit,#imageCarouselEdit').val('');
            if (!$('#judulCarouselErrorEdit').hasClass('d-none')) {
                $('#judulCarouselErrorEdit').addClass('d-none');

            }
            if (!$('#isiCarouselErrorEdit').hasClass('d-none')) {
                $('#isiCarouselErrorEdit').addClass('d-none');

            }
            if (!$('#imageCarouselErrorEdit').hasClass('d-none')) {
                $('#imageCarouselErrorEdit').addClass('d-none');

            }
        });

    });
</script>
@endsection