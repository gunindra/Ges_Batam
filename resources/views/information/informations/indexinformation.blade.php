@extends('layout.main')

@section('title', 'Information')

@section('main')

<div class="container-fluid" id="container-wrapper">


 <!-- Modal tambah -->
 <div class="modal fade" id="modalTambahInformations" tabindex="-1" role="dialog" aria-labelledby="modalTambahInformationsTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahInformations">Tambah Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form id="informationForm" enctype="multipart/form-data">
                        <div class="mt-3">
                            <label for="judulInformations" class="form-label fw-bold">Judul</label>
                            <input type="text" class="form-control" id="judulInformations" value="">
                            <div id="errJudulInformations" class="text-danger mt-1">Silahkan isi Judul</div>
                        </div>
                        <div class="mt-3">
                            <label for="isiInformations" class="form-label fw-bold">Isi</label>
                            <textarea class="form-control" id="isiInformations" rows="3"></textarea>
                            <div id="errIsiInformations" class="text-danger mt-1">Silahkan isi</div>
                        </div>
                        <div class="mt-3">
                            <label for="imageInformations" class="form-label fw-bold">Gambar</label>
                            <input type="file" class="form-control" id="imageInformations" value="">
                            <div id="errImageInformations" class="text-danger mt-1">Silahkan isi Gambar</div>
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
                            <label for="judulInformations" class="form-label fw-bold">Judul</label>
                            <input type="text" class="form-control" id="judulInformationsEdit" value="">
                            <div id="errJudulInformations" class="text-danger mt-1">Silahkan isi Judul</div>
                        </div>
                        <div class="mt-3">
                            <label for="isiInformations" class="form-label fw-bold">Isi</label>
                            <textarea class="form-control" id="isiInformationsEdit" rows="3"></textarea>
                            <div id="errIsiInformations" class="text-danger mt-1">Silahkan isi </div>
                        </div>
                        <div class="mt-3">
                            <label for="imageInformations" class="form-label fw-bold">Gambar</label>
                            <input type="file" class="form-control" id="imageInformationsEdit" value="">
                            <div id="errImageInformations" class="text-danger mt-1">Silahkan isi Gambar
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
        </div>
        <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#modalTambahInformations" id="#modalCenter"><span class="pr-2"><i
                    class="fas fa-plus"></i></span>Tambah Information</button>
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
     $(document).ready(function() {
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
        $('#judulInformations, #isiInformations','imageInformations').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // // Fungsi untuk validasi input
        // function validateInformationsInput(modal) {
        //     let isValid = true;

        //     // Nama Driver
        //     if ($(`#${modal} #judulInformations, #${modal} #judulInformationsEdit`).val().trim() === '') {
        //         $(`#${modal} #err-judulInformations`).show();
        //         isValid = false;
        //     } else {
        //         $(`#${modal} #err-judulInformations`).hide();
        //     }

        //     // Alamat Driver
        //     if ($(`#${modal} #isiInformations, #${modal} #isiInformationsEdit`).val().trim() === '') {
        //         $(`#${modal} #err-isiInformations`).show();
        //         isValid = false;
        //     } else {
        //         $(`#${modal} #err-isiInformations`).hide();
        //     }

        //     // No. Telpon Driver
        //     if ($(`#${modal} #imageInformations, #${modal} #imageInformationsEdit`).val().trim() === '') {
        //         $(`#${modal} #err-imageInformations`).show();
        //         isValid = false;
        //     } else {
        //         $(`#${modal} #err-imageInformations`).hide();
        //     }

        //     return isValid;
        // }

        // validateInformationsInput('modalTambahInformations');
        // validateInformationsInput('modalEditInformations');

        // $('#judulInformations, #isiInformations, #imageInformations').on('input change', function() {
        //     validateInformationsInput('modalTambahInformations');
        // });

        // $('#judulInformationsEdit, #isiInformationsEdit, #imageInformationsEdit').on('input change', function() {
        //     validateInformationsInput('modalEditInformations');
        // });


        $('#saveInformations').click(function() {
            $('#judulInformations, #isiInformations, #imageInformations').data('touched', true);

            let judulInformations = $('#judulInformations').val();
            let isiInformations = $('#isiInformations').val();
            let imageInformations = $('#imageInformations')[0].files[0];
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (judulInformations && isiInformations && imageInformations) {
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
                formData.append('judulInformations', judulInformations);
                formData.append('isiInformations', isiInformations);
                formData.append('imageInformations', imageInformations);
                formData.append('_token', csrfToken);

                $.ajax({
                    type: "POST",
                    url: "{{ route('addInformations') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            showMessage("success", "Data Berhasil Disimpan");
                            getlistInformations();
                            $('#modalTambahInformations').modal('hide');
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
        $(document).on('click', '.btnUpdateInformations', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let judul_informations = $(this).data('judul_informations');
                let isi_informations = $(this).data('isi_informations');
                let image_informations = $(this).data('image_informations');

                $('#judulInformationsEdit').val(judul_informations);
                $('#isiInformationsEdit').val(isi_informations);
                $('#imageInformationsEdit').val(image_informations);
                $('#informationsIdEdit').val(id);

                // validateInformationsInput('modalEditInformations');
                $('#modalEditInformations').modal('show');
            });


        $(document).on('click', '.btnDestroyInformations', function(e) {
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
                        url: "{{ route('destroyInformations') }}",
                        data: {
                            id: id,
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showMessage("success", "Berhasil menghapus Customer");
                                getlistInformations();
                            } else {
                                showMessage("error", "Gagal menghapus Customer");
                            }
                        }
                    });
                }
        });
            
     });
    });
</script>
@endsection