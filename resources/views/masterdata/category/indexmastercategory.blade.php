@extends('layout.main')

@section('title', 'Master Data | Category')

@section('main')
<div class="container-fluid" id="container-wrapper">

   <!-- Modal tambah -->
   <div class="modal fade" id="modalTambahCategory" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahCategoryTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahCategory">Tambah Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="namaCategory" class="form-label fw-bold">Nama Category</label>
                        <input type="text" class="form-control" id="namaCategory" value="" placeholder="Masukkan nama">
                        <div id="namaCategoryError" class="text-danger mt-1 d-none">Silahkan isi nama</div>
                    </div>
                    <div class="mt-3">
                        <label for="minimumRateCategory" class="form-label fw-bold">Rate</label>
                        <textarea class="form-control" id="minimumRateCategory" rows="3" placeholder="Masukkan content"></textarea>
                        <div id="minimumRateCategoryError" class="text-danger mt-1 d-none">Silahkan isi Rate </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveCategory" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEditCategory" tabindex="-1" role="dialog"
            aria-labelledby="modalEditCategoryTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditCategoryTitle">Edit Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="categoryIdEdit">
                        <div class="mt-3">
                            <label for="namaCategory" class="form-label fw-bold">nama</label>
                            <input type="text" class="form-control" id="namaCategoryEdit" value=""  placeholder="Masukkan Nama Category">
                            <div id="namaCategoryErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Nama</div>
                        </div>
                        <div class="mt-3">
                            <label for="minimumRateCategory" class="form-label fw-bold">Rate</label>
                            <textarea class="form-control" id="minimumRateCategoryEdit" rows="3" placeholder="Masukkan Rate"></textarea>
                            <div id="minimumRateCategoryErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Rate</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveEditCategory" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 px-4">Category</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Category</li>
            <li class="breadcrumb-item active" aria-current="page">Category</li>
        </ol>
    </div>
    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahCategory" id="#modalCenter"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Tambah Category</button>
                    </div>
                    <div id="containerCategory" class="table-responsive px-2">
                        <!-- <table class="table align-items-center table-flush table-hover" id="tableVendor">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Judul</th>
                                        <th>Isi Vendor</th>
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

        const getlistCategory = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "{{ route('getlistCategory') }}",
                method: "GET",
                data: {
                    txSearch: txtSearch
                },
                beforeSend: () => {
                    $('#containerCategory').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerCategory').html(res)
                    $('#tableCategory').DataTable({
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

        getlistCategory();
        // $('#minimumRateCategory').on('input', function() {
        //         this.value = this.value.replace(/[^0-9]/g, '');
        //     });
            $('#saveCategory').click(function() {
                // Ambil nilai input
                var namaCategory = $('#namaCategory').val().trim();
                var minimumRateCategory = $('#minimumRateCategory').val().trim();

                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                var isValid = true;

                if (namaCategory === '') {
                    $('#namaCategoryError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#namaCategoryError').addClass('d-none');
                }
                if (minimumRateCategory === '') {
                    $('#minimumRateCategoryError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#minimumRateCategoryError').addClass('d-none');
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
                            formData.append('namaCategory', namaCategory);
                            formData.append('minimumRateCategory', minimumRateCategory);
                            formData.append('_token', csrfToken);

                            $.ajax({
                                type: "POST",
                                url: "{{ route('addCategory') }}",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    if (response.status === 'success') {
                                        showMessage("success",
                                            "Data Berhasil Disimpan");
                                        getlistCategory();
                                        $('#modalTambahCategory').modal('hide');
                                    } else {
                                        Swal.fire({
                                            title: "Gagal Menambahkan Data",
                                            text: response
                                                .message,
                                            icon: "error",
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        title: "Gagal Menambahkan Data",
                                        text: xhr.responseJSON
                                            .message,
                                        icon: "error",
                                    });
                                }
                            });
                        }
                    });
                } else {
                    showMessage("error", "Mohon periksa input yang kosong");
                }
            });
            $(document).on('click', '.btnUpdateCategory', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let category_name = $(this).data('category_name');
                let minimum_rate = $(this).data('minimum_rate');

                $('#namaCategoryEdit').val(category_name);
                $('#minimumRateCategoryEdit').val(minimum_rate);
                $('#categoryIdEdit').val(id);

                $(document).on('click', '#saveEditCategory', function(e) {

                    let id = $('#categoryIdEdit').val();
                    let namaCategory = $('#namaCategoryEdit').val();
                    let minimumRateCategory = $('#minimumRateCategoryEdit').val();
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');

                    let isValid = true;

                    if (namaCategory === '') {
                        $('#namaCategoryErrorEdit').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#namaCategoryErrorEdit').addClass('d-none');
                    }

                    // Validasi Content
                    if (minimumRateCategory === '') {
                        $('#minimumRateCategoryErrorEdit').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#minimumRateCategoryErrorEdit').addClass('d-none');
                    }


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
                                let formData = new FormData();
                                formData.append('id', id);
                                formData.append('namaCategory', namaCategory);
                                formData.append('minimumRateCategory', minimumRateCategory);
                                formData.append('_token', csrfToken);

                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('updateCategory') }}",
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            showMessage("success",
                                                "Data Berhasil Diubah");
                                            getlistCategory();
                                            $('#modalEditCategory').modal(
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
                    } else {
                        showMessage("error", "Mohon periksa input yang kosong");
                    }
                })
            $('#modalEditCategory').modal('show');

            $('#modalTambahCategory').on('hidden.bs.modal', function() {
                $('#namaCategory,#minimumRateCategory').val('');
                if (!$('#namaCategoryError').hasClass('d-none')) {
                    $('#namaCategoryError').addClass('d-none');
                }
                if (!$('#minimumRateCategoryError').hasClass('d-none')) {
                    $('#minimumRateCategoryError').addClass('d-none');
                }
            });
            $('#modalEditCategory').on('hidden.bs.modal', function() {
                $('#namaCategoryEdit,#minimumRateCategoryEdit').val('');
                if (!$('#namaCategoryErrorEdit').hasClass('d-none')) {
                    $('#namaCategoryErrorEdit').addClass('d-none');
                }
                if (!$('#minimumRateCategoryErrorEdit').hasClass('d-none')) {
                    $('#minimumRateCategoryErrorEdit').addClass('d-none');
                }
            });
            

    });
    $(document).on('click', '.btnDestroyCategory', function(e) {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Apakah Kamu Yakin Ingin Hapus Ini?",
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
                            url: "{{ route('destroyCategory') }}",
                            data: {
                                id: id,
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    showMessage("success",
                                        "Berhasil menghapus");
                                    getlistCategory();
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