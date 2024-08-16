@extends('layout.main')

@section('title', 'Service')

@section('main')

<div class="container-fluid" id="container-wrapper">
    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambahService" tabindex="-1" role="dialog"
            aria-labelledby="modalTambahServiceTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahService">Tambah Service</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="serviceForm" enctype="multipart/form-data">
                            <div class="mt-3">
                                <label for="judulService" class="form-label fw-bold">Judul</label>
                                <input type="text" class="form-control" id="judulService" value="">
                                <div id="judulServiceError" class="text-danger mt-1 d-none">Silahkan isi Judul</div>
                            </div>
                            <div class="mt-3">
                                <label for="isiService" class="form-label fw-bold">Content</label>
                                <textarea class="form-control" id="isiService" rows="3"></textarea>
                                <div id="isiServiceError" class="text-danger mt-1 d-none">Silahkan isi</div>
                            </div>
                            <div class="mt-3">
                                <label for="imageService" class="form-label fw-bold">Gambar</label>
                                <input type="file" class="form-control" id="imageService" value="">
                                <div id="imageServiceError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                <button type="button" id="saveService" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Service</h1>
        </div>
        <div class="row mb-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahService" id="#modalCenter"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah Service</button>
                        </div>
                    <div id="containerService" class="table-responsive px-3">    
                            <!-- <table class="table align-items-center table-flush table-hover" id="tableAboutUs">
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

            const getlistService = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                    url: "{{ route('getlistService') }}",
                    method: "GET",
                    data: {
                        txSearch: txtSearch
                    },
                    beforeSend: () => {
                        $('#containerService').html(loadSpin)
                    }
                })
                .done(res => {
                    $('#containerService').html(res)
                    $('#tableService').DataTable({
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

        getlistService();

        $('#saveService').click(function () {
            // Ambil nilai input
            var judulService = $('#judulService').val().trim();
            var isiService = $('#isiService').val().trim();
            var imageService = $('#imageService')[0].files[0];

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (judulService === '') {
                $('#judulServiceError').removeClass('d-none');
                isValid = false;
            } else {
                $('#judulServiceError').addClass('d-none');
            }
            if (isiService === '') {
                $('#isiServiceError').removeClass('d-none');
                isValid = false;
            } else {
                $('#isiServiceError').addClass('d-none');
            }

            if (!imageService) {
                $('#imageServiceError').removeClass('d-none');
                isValid = false;
            } else {
                $('#imageServiceError').addClass('d-none');
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
                        formData.append('judulService', judulService);
                        formData.append('isiService', isiService);
                        formData.append('imageService', imageService);
                        formData.append('_token', csrfToken);

                        $.ajax({
                            type: "POST",
                            url: "{{ route('addService') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                if (response.status === 'success') {
                                    showMessage("success", "Data Berhasil Disimpan");
                                    getlistService();
                                    $('#modalTambahService').modal('hide');
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

    });
</script>
@endsection