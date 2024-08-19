@extends('layout.main')

@section('title', 'Master Data | Costumer')

@section('main')

<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">

    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambahCustomer" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahCustomerTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahCustomerTitle">Tambah Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="namaCustomer" class="form-label fw-bold">Marking</label>
                        <input type="text" class="form-control" id="markingCustomer" value="" disabled>
                    </div>
                    <div class="mt-3">
                        <label for="namaCustomer" class="form-label fw-bold">Nama Customer</label>
                        <input type="text" class="form-control" id="namaCustomer" value="">
                        <div id="namaCustomerError" class="text-danger mt-1 d-none">Silahkan isi nama costumer</div>
                    </div>
                    <div class="mt-3">
                        <label for="alamat" class="form-label fw-bold">Alamat</label>
                        <textarea class="form-control" id="alamatCustomer" rows="3"></textarea>
                        <div id="alamatCustomerError" class="text-danger mt-1 d-none">Silahkan isi alamat costumer</div>
                    </div>
                    <div class="mt-3">
                        <label for="noTelpon" class="form-label fw-bold">No. Telpon</label>
                        <input type="text" class="form-control" id="noTelpon" value="">
                        <div id="notelponCustomerError" class="text-danger mt-1 d-none">Silahkan isi no. telepon
                            costumer</div>
                    </div>
                    <div class="mt-3">
                        <label for="category" class="form-label fw-bold">Category</label>
                        <select class="form-control" id="categoryCustomer">
                            <option value="" selected disabled>Select Category Costumer</option>
                            <option value="Normal">Normal</option>
                            <option value="VIP">VIP</option>
                        </select>
                        <div id="categoryCustomerError" class="text-danger mt-1 d-none">Silahkan pilih category costumer
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveCostumer" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!--End Modal Tambah -->


    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditCustomer" tabindex="-1" role="dialog" aria-labelledby="modalEditCustomerTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditCustomerTitle">Edit Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="customerIdEdit">
                    <div class="mt-3">
                        <label for="namaCustomer" class="form-label fw-bold">Nama Customer</label>
                        <input type="text" class="form-control" id="namaCustomerEdit" value="">
                        <div id="namaCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan isi nama costumer</div>
                    </div>
                    <div class="mt-3">
                        <label for="alamat" class="form-label fw-bold">Alamat</label>
                        <textarea class="form-control" id="alamatCustomerEdit" rows="3"></textarea>
                        <div id="alamatCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan isi alamat costumer
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="noTelpon" class="form-label fw-bold">No. Telpon</label>
                        <input type="text" class="form-control" id="noTelponEdit" value="">
                        <div id="notelponCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan isi no. telepon
                            costumer
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="categoryCustomerEdit" class="form-label fw-bold">Category</label>
                        <select class="form-control" id="categoryCustomerEdit">
                            <option value="" selected disabled>Select Category Costumer</option>
                            <option value="Normal">Normal</option>
                            <option value="VIP">VIP</option>
                        </select>
                        <div id="categoryCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan pilih category
                            costumer
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditCostumer" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!--End Modal Edit -->

    <!-- Modal Point -->
    <div class="modal fade" id="modalPointCostumer" tabindex="-1" role="dialog"
        aria-labelledby="modalPointCostumerTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPointCostumerTitle">Point Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center text-center">
                        <H1 id="pointValue">?</H1>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!--End Modal Point -->



    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Customer</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active" aria-current="page">Customer</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahCustomer" id="modalTambahCost"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Tambah Customer</button>
                    </div>
                    <div id="containerCustomer" class="table-responsive px-3">
                        {{-- <table class="table align-items-center table-flush table-hover" id="tableCostumer">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>No. Telp</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Ilham</td>
                                    <td>Jl.Central Legenda poin blok j No. 13 </td>
                                    <td>0893483478283</td>
                                    <td><span class="badge badge-primary">VIP</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Show point</a>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tio</td>
                                    <td>Jl.Central Legenda poin blok j No. 12 </td>
                                    <td>08123476282221</td>
                                    <td>Normal</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Show point</a>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>





</div>
<!---Container Fluid-->





@endsection


@section('script')
<script>
    $(document).ready(function() {
    const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

    const getListCustomer = () => {
        const txtSearch = $('#txSearch').val();

        $.ajax({
            url: "{{ route('getlistCostumer') }}",
            method: "GET",
            data: {
                txSearch: txtSearch
            },
            beforeSend: () => {
                $('#containerCustomer').html(loadSpin)
            }
        })
            .done(res => {
                $('#containerCustomer').html(res)
                $('#tableCostumer').DataTable({
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

    getListCustomer();

    $(document).on('click', '#modalTambahCost', function (e) {
        $.ajax({
            type: "GET",
            url: "{{ route('generateMarking') }}",
            success: function (response) {
                let valuemarking = response.new_marking
                $('#markingCustomer').val(valuemarking);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    // Validasi input untuk nomor telepon
    $('#noTelpon, #noTelponEdit').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });


    $('#saveCostumer').click(function () {
        $('#namaCustomer, #alamatCustomer, #noTelpon, #categoryCustomer').data('touched', true);

        var markingCostmer = $('#markingCustomer').val();
        var namaCustomer = $('#namaCustomer').val();
        var alamatCustomer = $('#alamatCustomer').val();
        var noTelpon = $('#noTelpon').val().trim();
        var categoryCustomer = $('#categoryCustomer').val();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        var isValid = true;

        if (namaCustomer === '') {
            $('#namaCustomerError').removeClass('d-none');
            isValid = false;
        } else {
            $('#namaCustomerError').addClass('d-none');
        }
        if (alamatCustomer === '') {
            $('#alamatCustomerError').removeClass('d-none');
            isValid = false;
        } else {
            $('#alamatCustomerError').addClass('d-none');
        }

        if (noTelpon === '') {
            $('#notelponCustomerError').removeClass('d-none');
            isValid = false;
        } else {
            $('#notelponCustomerError').addClass('d-none');
        }

        if (categoryCustomer === '') {
             $('#categoryCustomerError').removeClass('d-none');
             isValid = false;
        } else {
            $('#categoryCustomerError').addClass('d-none');
        }


        if (validateInput('modalTambahCustomer')) {
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
                        formData.append('namaCustomer', namaCustomer);
                        formData.append('alamatCustomer', alamatCustomer);
                        formData.append('noTelpon', noTelpon);
                        formData.append('categoryCustomer', categoryCustomer);
                        formData.append('_token', csrfToken);

                        $.ajax({
                            type: "POST",
                            url: "{{ route('addCostumer') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                if (response.status === 'success') {
                                    showMessage("success",
                                        "Data Berhasil Disimpan");
                                    getListCustomer();
                                    $('#modalTambahCustomer').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Gagal Menambahkan Data",
                                        text: response
                                            .message,
                                        icon: "error",
                                    });
                                }
                            },
                            error: function (xhr) {
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
        }
    });
        


    $('#modalTambahCustomer').on('hidden.bs.modal', function () {
        $('#namaCustomer, #alamatCustomer, #noTelpon, #categoryCustomer').val('');
        if (!$('#namaCustomerError').hasClass('d-none')) {
            $('#namaCustomerError').addClass('d-none');

        }
        if (!$('#alamatCustomerError').hasClass('d-none')) {
            $('#alamatCustomerError').addClass('d-none');

        }
        if (!$('#notelponCustomerError').hasClass('d-none')) {
            $('#notelponCustomerError').addClass('d-none');

        }
        if (!$('#categoryCustomerError').hasClass('d-none')) {
            $('#categoryCustomerError').addClass('d-none');

        }
        // validateInput('modalTambahCustomer');
    });

    $('#modalEditCustomer').on('hidden.bs.modal', function () {
        $('#namaCustomerEdit, #alamatCustomerEdit, #noTelponEdit, #categoryCustomerEdit').val('');
        if (!$('#namaCustomerErrorEdit').hasClass('d-none')) {
            $('#namaCustomerErrorEdit').addClass('d-none');

        }
        if (!$('#alamatCustomerErrorEdit').hasClass('d-none')) {
            $('#alamatCustomerErrorEdit').addClass('d-none');

        }
        if (!$('#notelponCustomerErrorEdit').hasClass('d-none')) {
            $('#notelponCustomerErrorEdit').addClass('d-none');

        }
        if (!$('#categoryCustomerErrorEdit').hasClass('d-none')) {
            $('#categoryCustomerErrorEdit').addClass('d-none');

        }
        // validateInput('modalEditCustomer');
    });

    $(document).on('click', '.btnUpdateCustomer', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let nama = $(this).data('nama');
        let noTelp = $(this).data('notelp');
        let alamat = $(this).data('alamat');
        let category = $(this).data('category');

        $('#namaCustomerEdit').val(nama);
        $('#noTelponEdit').val(noTelp);
        $('#alamatCustomerEdit').val(alamat);
        $('#categoryCustomerEdit').val(category);
        $('#customerIdEdit').val(id);


        // validateInput('modalEditCustomer');
        $('#modalEditCustomer').modal('show');
    });



    $(document).on('click', '.btnPointCostumer', function (e) {
        let point = $(this).data('poin');
        $('#pointValue').text(point !== null ? point : '-');
        $('#modalPointCostumer').modal('show');
    })

    $(document).on('click', '.btnDestroyCustomer', function (e) {
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
                    url: "{{ route('destroyCostumer') }}",
                    data: {
                        id: id,
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            showMessage("success", "Berhasil menghapus Customer");
                            getListCustomer();
                        } else {
                            showMessage("error", "Gagal menghapus Customer");
                        }
                    }
                });
            }
        })
    });



        $(document).on('click', '.btnUpdateCustomer', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            let noTelp = $(this).data('notelp');
            let alamat = $(this).data('alamat');
            let category = $(this).data('category');

            // Mengisi nilai pada field modal edit
            $('#namaCustomerEdit').val(nama);
            $('#noTelponEdit').val(noTelp);
            $('#alamatCustomerEdit').val(alamat);
            $('#categoryCustomerEdit').val(category);
            $('#customerIdEdit').val(id);

            // Menampilkan modal edit
            $('#modalEditCustomer').modal('show');
        });

        // Menyimpan data customer yang diedit
        $(document).on('click', '#saveEditCostumer', function (e) {
            e.preventDefault();

            let id = $('#customerIdEdit').val();
            let namaCustomer = $('#namaCustomerEdit').val();
            let alamatCustomer = $('#alamatCustomerEdit').val();
            let noTelponCustomer = $('#noTelponEdit').val();
            let categoryCustomer = $('#CategoryCustomerEdit').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            let isValid = true;

            // Validasi input
            if (namaCustomer === '') {
                $('#namaCustomerErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#namaCustomerErrorEdit').addClass('d-none');
            }

            if (alamatCustomer === '') {
                $('#alamatCustomerErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#alamatCustomerErrorEdit').addClass('d-none');
            }

            if (noTelponCustomer === '') {
                $('#notelponCustomerErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#notelponCustomerErrorEdit').addClass('d-none');
            }

            if (categoryCustomer === '') {
                $('#CategoryCustomerErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#CategoryCustomerErrorEdit').addClass('d-none');
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
                        formData.append('namaCustomer', namaCustomer);
                        formData.append('alamatCustomer', alamatCustomer);
                        formData.append('noTelpon', noTelponCustomer);
                        formData.append('categoryCustomer', categoryCustomer);
                        formData.append('_token', csrfToken);

                        $.ajax({
                            type: "POST",
                            url: "{{ route('updateCostumer') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                if (response.status === 'success') {
                                    showMessage("success", "Data Berhasil Diubah");
                                    getListCustomer();
                                    $('#modalEditCustomer').modal('hide');
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

        // Reset form saat modal ditutup
        $('#modalEditCustomer').on('hidden.bs.modal', function () {
            $('#namaCustomerEdit, #alamatCustomerEdit, #noTelponEdit, #categoryCustomerEdit').val('');
            $('.text-danger').addClass('d-none');
        });
    });
    
    
</script>
@endsection