@extends('layout.main')

@section('title', 'Master Data | Costumer')

@section('main')

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">

        <!-- Modal tambah -->
        <div class="modal fade" id="modalTambahCustomer" tabindex="-1" role="dialog" aria-labelledby="modalTambahCustomerTitle"
            aria-hidden="true">
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
                            <div id="errNamaCostumer" class="text-danger mt-1">Silahkan isi nama costumer</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamatCustomer" rows="3"></textarea>
                            <div id="errAlamatCostumer" class="text-danger mt-1">Silahkan isi alamat costumer</div>
                        </div>
                        <div class="mt-3">
                            <label for="noTelpon" class="form-label fw-bold">No. Telpon</label>
                            <input type="text" class="form-control" id="noTelpon" value="">
                            <div id="errNoTelpCostumer" class="text-danger mt-1">Silahkan isi no. telepon costumer</div>
                        </div>
                        <div class="mt-3">
                            <label for="noTelpon" class="form-label fw-bold">Category</label>
                            <select class="form-control" id="CategoryCustomer">
                                <option value="" selected disabled>Select Category Costumer</option>
                                <option value="Normal">Normal</option>
                                <option value="VIP">VIP</option>
                            </select>
                            <div id="errCategoryCostumer" class="text-danger mt-1">Silahkan pilih category costumer</div>
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
        <div class="modal fade" id="modalEditCustomer" tabindex="-1" role="dialog"
            aria-labelledby="modalEditCustomerTitle" aria-hidden="true">
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
                            <div id="errNamaCostumer" class="text-danger mt-1">Silahkan isi nama costumer</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamatCustomerEdit" rows="3"></textarea>
                            <div id="errAlamatCostumer" class="text-danger mt-1">Silahkan isi alamat costumer</div>
                        </div>
                        <div class="mt-3">
                            <label for="noTelpon" class="form-label fw-bold">No. Telpon</label>
                            <input type="text" class="form-control" id="noTelponEdit" value="">
                            <div id="errNoTelpCostumer" class="text-danger mt-1">Silahkan isi no. telepon costumer
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="CategoryCustomerEdit" class="form-label fw-bold">Category</label>
                            <select class="form-control" id="CategoryCustomerEdit">
                                <option value="" selected disabled>Select Category Costumer</option>
                                <option value="Normal">Normal</option>
                                <option value="VIP">VIP</option>
                            </select>
                            <div id="errCategoryCostumer" class="text-danger mt-1">Silahkan pilih category costumer
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
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
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
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
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

        $(document).on('click', '#modalTambahCost', function(e) {
            $.ajax({
                type: "GET",
                url: "{{ route('generateMarking') }}",
                success: function(response) {
                    let valuemarking = response.new_marking
                    $('#markingCustomer').val(valuemarking);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        // Validasi input untuk nomor telepon
        $('#noTelpon, #noTelponEdit').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Fungsi untuk validasi input
        function validateInput(modal) {
            let isValid = true;

            // Nama Customer
            if ($(`#${modal} #namaCustomer, #${modal} #namaCustomerEdit`).val().trim() === '') {
                $(`#${modal} #errNamaCostumer`).show();
                isValid = false;
            } else {
                $(`#${modal} #errNamaCostumer`).hide();
            }

            // Alamat Customer
            if ($(`#${modal} #alamatCustomer, #${modal} #alamatCustomerEdit`).val().trim() === '') {
                $(`#${modal} #errAlamatCostumer`).show();
                isValid = false;
            } else {
                $(`#${modal} #errAlamatCostumer`).hide();
            }

            // No. Telpon
            if ($(`#${modal} #noTelpon, #${modal} #noTelponEdit`).val().trim() === '') {
                $(`#${modal} #errNoTelpCostumer`).show();
                isValid = false;
            } else {
                $(`#${modal} #errNoTelpCostumer`).hide();
            }

            // Category Customer
            if ($(`#${modal} #CategoryCustomer, #${modal} #CategoryCustomerEdit`).val() === null) {
                $(`#${modal} #errCategoryCostumer`).show();
                isValid = false;
            } else {
                $(`#${modal} #errCategoryCostumer`).hide();
            }

            return isValid;
        }

        validateInput('modalTambahCustomer');
        validateInput('modalEditCustomer');

        $('#namaCustomer, #alamatCustomer, #noTelpon, #CategoryCustomer').on('input change', function() {
            validateInput('modalTambahCustomer');
        });

        $('#namaCustomerEdit, #alamatCustomerEdit, #noTelponEdit, #CategoryCustomerEdit').on('input change', function() {
            validateInput('modalEditCustomer');
        });

        $('#saveCostumer').click(function() {
            $('#namaCustomer, #alamatCustomer, #noTelpon, #CategoryCustomer').data('touched', true);

            let markingCostmer = $('#markingCustomer').val();
            let namaCostmer = $('#namaCustomer').val();
            let alamatCustomer = $('#alamatCustomer').val();
            let noTelpon = $('#noTelpon').val();
            let CategoryCustomer = $('#CategoryCustomer').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (validateInput('modalTambahCustomer')) {
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
                        $.ajax({
                            type: "POST",
                            url: "{{ route('addCostumer') }}",
                            data: {
                                markingCostmer: markingCostmer,
                                namaCostmer: namaCostmer,
                                alamatCustomer: alamatCustomer,
                                noTelpon: noTelpon,
                                CategoryCustomer: CategoryCustomer,
                                _token: csrfToken
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    showMessage("success", "Data Berhasil Di Simpan");
                                    getListCustomer();
                                    $('#modalTambahCustomer').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Gagal Menambahkan Customer",
                                        icon: "error"
                                    });
                                }
                            }
                        });
                    }
                })
            } else {
                showMessage("error", "Mohon periksa input yang kosong");
            }
        });

        $('#saveEditCostumer').click(function() {
            $('#namaCustomerEdit, #alamatCustomerEdit, #noTelponEdit, #CategoryCustomerEdit').data('touched', true);

            let id = $('#customerIdEdit').val();
            let namaCostmer = $('#namaCustomerEdit').val();
            let alamatCustomer = $('#alamatCustomerEdit').val();
            let noTelpon = $('#noTelponEdit').val();
            let CategoryCustomer = $('#CategoryCustomerEdit').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (validateInput('modalEditCustomer')) {
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
                        $.ajax({
                            type: "POST",
                            url: "{{ route('updateCostumer') }}",
                            data: {
                                id: id,
                                namaCostmer: namaCostmer,
                                alamatCustomer: alamatCustomer,
                                noTelpon: noTelpon,
                                CategoryCustomer: CategoryCustomer,
                                _token: csrfToken
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    showMessage("success", "Data Berhasil Di Update");
                                    getListCustomer();
                                    $('#modalEditCustomer').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Gagal Mengubah Customer",
                                        icon: "error"
                                    });
                                }
                            }
                        });
                    }
                })
            } else {
                showMessage("error", "Mohon periksa input yang kosong");
            }
        });

        $('#modalTambahCustomer').on('hidden.bs.modal', function() {
            $('#namaCustomer, #alamatCustomer, #noTelpon, #CategoryCustomer').val('');
            validateInput('modalTambahCustomer');
        });

        $('#modalEditCustomer').on('hidden.bs.modal', function() {
            $('#namaCustomerEdit, #alamatCustomerEdit, #noTelponEdit, #CategoryCustomerEdit').val('');
            validateInput('modalEditCustomer');
        });

        $(document).on('click', '.btnUpdateCustomer', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            let noTelp = $(this).data('notelp');
            let alamat = $(this).data('alamat');
            let category = $(this).data('category');

            $('#namaCustomerEdit').val(nama);
            $('#noTelponEdit').val(noTelp);
            $('#alamatCustomerEdit').val(alamat);
            $('#CategoryCustomerEdit').val(category);
            $('#customerIdEdit').val(id);


            validateInput('modalEditCustomer');
            $('#modalEditCustomer').modal('show');
        });



        $(document).on('click', '.btnPointCostumer', function(e) {
            let point = $(this).data('poin');
            $('#pointValue').text(point !== null ? point : '-');
            $('#modalPointCostumer').modal('show');
        })

        $(document).on('click', '.btnDestroyCustomer', function(e) {
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
                        success: function(response) {
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
    </script>
@endsection
