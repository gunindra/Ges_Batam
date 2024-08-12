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
                        <div class="mt-3">
                            <label for="imageInformations" class="form-label fw-bold">Image</label>
                            <input type="file" class="form-control" id="imageInformations" value="">
                            <div id="errImageInformations" class="text-danger mt-1">Silahkan isi Image</div>
                        </div>
                        <div class="mt-3">
                            <label for="judulInformations" class="form-label fw-bold">Judul</label>
                            <input type="text" class="form-control" id="judulInformations" value="">
                            <div id="errjudulInformations" class="text-danger mt-1">Silahkan isi Judul</div>
                        </div>
                        <div class="mt-3">
                            <label for="isiInformations" class="form-label fw-bold">Isi</label>
                            <textarea class="form-control" id="isiInformations" rows="3"></textarea>
                            <div id="errisiInformations" class="text-danger mt-1">Silahkan isi</div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveCostumer" class="btn btn-primary">Save changes</button>
                    </div>
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