@extends('layout.main')

@section('title', 'Iklan')

@section('main')

<div class="container-fluid" id="container-wrapper">


 <!-- Modal tambah -->
 <div class="modal fade" id="modalTambahIklan" tabindex="-1" role="dialog"
            aria-labelledby="modalTambahIklanTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahIklan">Tambah Iklan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="judulIklan" class="form-label fw-bold">Judul</label>
                            <input type="text" class="form-control" id="judulIklan" value="">
                            <div id="judulIklanError" class="text-danger mt-1 d-none">Silahkan isi Judul</div>
                        </div>
                        <div class="mt-3">
                            <label for="imageIklan" class="form-label fw-bold">Gambar</label>
                            <input type="file" class="form-control" id="imageIklan" value="">
                            <div id="imageIklanError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
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
     <div class="modal fade" id="modalEditIklan" tabindex="-1" role="dialog"
            aria-labelledby="modalEditIklanTitle" aria-hidden="true">
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
                            <label for="judulIklan" class="form-label fw-bold">Judul</label>
                            <input type="text" class="form-control" id="judulIklanEdit" value="">
                            <div id="judulIklanErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Judul</div>
                        </div>
                        <div class="mt-3">
                            <label for="imageIklan" class="form-label fw-bold">Gambar</label>
                            <p class="">Nama Gambar : <span id="textNamaEdit"></span></p>
                            <input type="file" class="form-control" id="imageIklanEdit" value="">
                            <div id="imageIklanErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Gambar
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
        </div>
        <div class="row mb-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahIklan" id="#modalCenter"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah Iklan</button>
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
    $(document).ready(function() {
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

            if (!imageIklan) {
                $('#imageIklanError').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageIklanError').addClass('d-none');
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
                        formData.append('judulIklan', judulIklan);
                        formData.append('imageIklan', imageIklan);
                        formData.append('_token', csrfToken);

                        $.ajax({
                            type: "POST",
                            url: "{{ route('addIklan') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                if (response.status === 'success') {
                                    showMessage("success", "Data Berhasil Disimpan");
                                    getlistIklan();
                                    $('#modalTambahIklan').modal('hide');
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

        

        $('#saveEditIklan').click(function() {
                    // Ambil nilai input
                    let id = $('#iklanIdEdit').val();
                    let judulIklan = $('#judulIklanEdit').val().trim();
                    let imageIklan = $('#imageIklanEdit')[0].files[0];
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Validasi input
                    var isValid = true;

                    if (judulIklan === '') {
                        $('#judulIklanEditError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#judulIklanEditError').addClass('d-none');
                    }

                    if (!imageIklan) {
                        $('#imageIklanEditError').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#imageIklanEditError').addClass('d-none');
                    }

                    if (isValid) {
                        // Konfirmasi dengan SweetAlert2
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
                                formData.append('id', id);
                                formData.append('judulIklan', judulIklan);
                                formData.append('imageIklan', imageIklan);
                                formData.append('_token', csrfToken);

                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('updateIklan') }}",
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            Swal.fire({
                                                title: "Berhasil!",
                                                text: "Data Berhasil Diperbarui",
                                                icon: "success"
                                            }).then(() => {
                                                getlistIklan();  // Update the list
                                                $('#modalEditIklan').modal('hide');  // Close the modal
                                            });
                                        } else {
                                            Swal.fire({
                                                title: "Gagal Mengubah Data",
                                                icon: "error"
                                            });
                                        }
                                    }
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            title: "Periksa Input",
                            text: "Tolong periksa input yang kosong",
                            icon: "warning"
                        });
                    }
                });

            $('#modalTambahIklan').on('hidden.bs.modal', function() {
                $('#judulIklan,#imageIklan').val('');
                validateInput('modalTambahIklan');
            });



        
        $(document).on('click', '.btnDestroyIklan', function(e) {
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
                        url: "{{ route('destroyIklan') }}",
                        data: {
                            id: id,
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showMessage("success", "Berhasil menghapus");
                                getlistIklan();
                            } else {
                                showMessage("error", "Gagal menghapus");
                            }
                        }
                    });
                }
        });
            
     });

    });
</script>
@endsection