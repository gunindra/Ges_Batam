@extends('layout.main')

@section('title', 'Carousel')

@section('main')
<div class="container-fluid" id="container-wrapper">

<!-- Modal tambah -->
<div class="modal fade" id="modalTambahCarousel" tabindex="-1" role="dialog" aria-labelledby="modalTambahCarouselTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahCarousel">Tambah Carousel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="judulCarousel" class="form-label fw-bold">Judul</label>
                            <input type="text" class="form-control" id="judulCarousel" value="">
                            <div id="judulCarouselError" class="text-danger mt-1 d-none">Silahkan isi Judul</div>
                        </div>
                        <div class="mt-3">
                            <label for="isiCarousel" class="form-label fw-bold">Content</label>
                            <textarea class="form-control" id="isiCarousel" rows="3"></textarea>
                            <div id="isiCarouselError" class="text-danger mt-1 d-none">Silahkan isi </div>
                        </div>
                        <div class="mt-3">
                            <label for="imageCarousel" class="form-label fw-bold">Gambar</label>
                            <input type="file" class="form-control" id="imageCarousel" value="">
                            <div id="imageCarouselError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
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
        <div class="modal fade" id="modalEditCarousel" tabindex="-1" role="dialog"
            aria-labelledby="modalEditCarouselTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditCarouselTitle">Edit Carousel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="carouselIdEdit">
                        <div class="mt-3">
                            <label for="judulCarousel" class="form-label fw-bold">Judul</label>
                            <input type="text" class="form-control" id="judulCarouselEdit" value="">
                            <div id="judulCarouselErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Judul</div>
                        </div>
                        <div class="mt-3">
                            <label for="isiCarousel" class="form-label fw-bold">Isi</label>
                            <textarea class="form-control" id="isiCarouselEdit" rows="3"></textarea>
                            <div id="isiCarouselErrorEdit" class="text-danger mt-1 d-none">Silahkan isi </div>
                        </div>
                        <div class="mt-3">
                            <label for="imageCarousel" class="form-label fw-bold">Gambar</label>
                            <p class="">Nama Gambar : <span id="textNamaEdit"></span></p>
                            <input type="file" class="form-control" id="imageCarouselEdit" value="">
                            <div id="imageCarouselErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Gambar
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
            <h1 class="h3 mb-0 text-gray-800 px-4">Carousel</h1>
        </div>
        <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card mx-4">
                <div class="card-body">
                <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#modalTambahCarousel" id="#modalCenter"><span class="pr-3"><i
                    class="fas fa-plus"></i></span>Tambah Carousel</button>
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
    $(document).ready(function() {
            const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

            const getlistCarousel = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                    url: "{{ route('getlistCarousel') }}",
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

        getlistCarousel();

        
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

            if (!imageCarousel) {
                $('#imageCarouselError').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageCarouselError').addClass('d-none');
            }

            // Jika semua input valid, lanjutkan aksi simpan
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
                        formData.append('judulCarousel', judulCarousel);
                        formData.append('isiCarousel', isiCarousel);
                        formData.append('imageCarousel', imageCarousel);
                        formData.append('_token', csrfToken);

                        $.ajax({
                            type: "POST",
                            url: "{{ route('addCarousel') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                if (response.status === 'success') {
                                    showMessage("success", "Data Berhasil Disimpan");
                                    getlistCarousel();
                                    $('#modalTambahCarousel').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Gagal Menambahkan Data",
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


            $(document).on('click', '.btnUpdateCarousel', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let judul_carousel = $(this).data('judul_carousel');
                let isi_carousel = $(this).data('isi_carousel');
                let image_carousel = $(this).data('image_carousel');

                $('#judulCarouselEdit').val(judul_carousel);
                $('#isiCarouselEdit').val(isi_carousel);
                $('#textNamaEdit').text(image_carousel);
                $('#carouselIdEdit').val(id);

                $(document).on('click', '#saveEditCarousel', function(e) {

                    let id =$('#carouselIdEdit').val();
                    let judulCarousel = $('#judulCarouselEdit').val();
                    let isiCarousel = $('#isiCarouselEdit').val();
                    let imageCarousel = $('#imageCarouselEdit')[0].files[0];
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');

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
                            formData.append('judulCarousel', judulCarousel);
                            formData.append('isiCarousel', isiCarousel);
                            formData.append('imageCarousel', imageCarousel);
                            formData.append('_token', csrfToken);

                            $.ajax({
                                type: "POST",
                                url: "{{ route('updateCarousel') }}",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    if (response.status === 'success') {
                                        showMessage("success",
                                            "Data Berhasil Diubah");
                                        getlistCarousel();
                                        $('#modalEditCarousel').modal(
                                            'hide');
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
                })

                // validateInformationsInput('modalEditInformations');
                $('#modalEditCarousel').modal('show');
            });

        
        $(document).on('click', '.btnDestroyCarousel', function(e) {
            let id = $(this).data('id');

            Swal.fire({
                title: "Apakah Kamu Yakin Ingin Hapus Customer Ini?",
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
                        type: "GET",
                        url: "{{ route('destroyCarousel') }}",
                        data: {
                            id: id,
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showMessage("success", "Berhasil menghapus");
                                getlistCarousel();
                            } else {
                                showMessage("error", "Gagal menghapus");
                            }
                        }
                    });
                }
        });
            
     });
     $('#saveCarousel').click(function() {
            const judulCarousel = $('#judulCarousel').val().trim();
            const isiCarousel = $('#isiCarousel').val().trim();
            const imageCarousel = $('#imageCarousel').val().trim();
 
            let isValid = true;

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

                if (imageCarousel === '') {
                    $('#imageCarouselError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageCarouselError').addClass('d-none');
                }

                
                if (!isValid) {
                    Swal.fire({
                        title: "Periksa input yang masih kosong.",
                        icon: "error"
                    });
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('addCarousel') }}",
                    data: {
                        judulCarousel: judulCarousel,
                        isiCarousel: isiCarousel,
                        imageCarousel: imageCarousel,
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showMessage("success", "Carousel berhasil dibuat").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal membuat Carousel",
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "Gagal membuat Carousel",
                            text: "Terjadi kesalahan. Mohon coba lagi.",
                            icon: "error"
                        });
                    }
                });
            });

            $('#saveEditCarousel').click(function() {
            const judulCarouselEdit = $('#judulCarouselEdit').val().trim();
            const isiCarouselEdit = $('#isiCarouselEdit').val().trim();
            const imageCarouselEdit = $('#imageCarouselEdit').val().trim();
 
            let isValid = true;

                if (judulCarouselEdit === '') {
                    $('#judulCarouselErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#judulCarouselErrorEdit').addClass('d-none');
                }

                if (isiCarouselEdit === '') {
                    $('#isiCarouselErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#isiCarouselErrorEdit').addClass('d-none');
                }

                if (imageCarouselEdit === '') {
                    $('#imageCarouselErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imageCarouselErrorEdit').addClass('d-none');
                }

                
                if (!isValid) {
                    Swal.fire({
                        title: "Periksa input yang masih kosong.",
                        icon: "error"
                    });
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('updateCarousel') }}",
                    data: {
                        judulCarouselEdit: judulCarouselEdit,
                        isiCarouselEdit: isiCarouselEdit,
                        imageCarouselEdit: imageCarouselEdit,
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showMessage("success", "Carousel berhasil mengubah").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal memngubah Carousel",
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "Gagal membuat Carousel",
                            text: "Terjadi kesalahan. Mohon coba lagi.",
                            icon: "error"
                        });
                    }
                });
            });
    });
</script>
@endsection