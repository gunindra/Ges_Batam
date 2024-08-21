@extends('layout.main')

@section('title', 'Master Data | Driver')


@section('main')


    <div class="modal fade" id="modalDetailSim" tabindex="-1" role="dialog" aria-labelledby="modalDetailSimTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailSimTitle">Detail Driver</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="pembayaranStatus" class="form-label fw-bold">SIM Driver:</label>
                        <div class="containerFoto">
                            {{-- <img src="storage/app/bukti_pembayaran/1.jpg" alt=""> --}}
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    {{-- <button type="button" id="saveFileTransfer" class="btn btn-primary">Save</button> --}}
                </div>
            </div>
        </div>
    </div>

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">

        <!-- Modal Center -->
        <div class="modal fade" id="modalTambahDriver" tabindex="-1" role="dialog"
            aria-labelledby="modalTambahDriverTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahDriverTitle">Tambah Driver</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="namaDriver" class="form-label fw-bold">Nama Driver</label>
                            <input type="text" class="form-control" id="namaDriver" value="">
                            <div id="err-namaDriver" class="text-danger mt-1 d-none">Silahkan isi nama driver</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamatDriver" rows="3"></textarea>
                            <div id="err-alamatDriver" class="text-danger mt-1 d-none">Silahkan isi alamat driver</div>
                        </div>
                        <div class="mt-3">
                            <label for="noTelpon" class="form-label fw-bold">No. Telpon</label>
                            <input type="text" class="form-control" id="noTelponDriver" value="">
                            <div id="err-noTelponDriver" class="text-danger mt-1 d-none">Silahkan isi no. telp driver</div>
                        </div>
                        <div class="mt-3">
                            <label for="simDriver" class="form-label fw-bold">Sim</label>
                            <input type="file" class="form-control" id="simDriver" value="">
                            <div id="simDriverError" class="text-danger mt-1 d-none">Silahkan masukkan SIM</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveDriver" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Center -->
        <div class="modal fade" id="modalEditDriver" tabindex="-1" role="dialog" aria-labelledby="modalEditDriverTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditDriverTitle">Edit Driver</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="driverIdEdit">
                        <div class="mt-3">
                            <label for="namaDriver" class="form-label fw-bold">Nama Driver</label>
                            <input type="text" class="form-control" id="namaDriverEdit" value="">
                            <div id="err-namaDriverEdit" class="text-danger mt-1 d-none">Silahkan isi nama driver</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamatDriverEdit" rows="3"></textarea>
                            <div id="err-alamatDriverEdit" class="text-danger mt-1 d-none">Silahkan isi alamat driver
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="noTelpon" class="form-label fw-bold">No. Telpon</label>
                            <input type="text" class="form-control" id="noTelponDriverEdit" value="">
                            <div id="err-noTelponDriverEdit" class="text-danger mt-1 d-none">Silahkan isi no. telp driver
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="simDriverEdit" class="form-label fw-bold">Gambar</label>
                            <p class="">Nama Gambar : <span id="textSimEdit"></span></p>
                            <input type="file" class="form-control" id="simDriverEdit" value="">
                            <div id="simDriverEditError" class="text-danger mt-1 d-none">Silahkan isi Gambar
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveEditDriver" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Driver</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Master Data</li>
                <li class="breadcrumb-item active" aria-current="page">Driver</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahDriver" id="#modalCenter"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah Driver</button>
                        </div>
                        <div id="containerDriver" class="table-responsive px-3">
                            {{-- <table class="table align-items-center table-flush table-hover" id="tableDriver">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>No. Telp</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Ilham</td>
                                        <td>Jl.Central Legenda poin blok j No. 13 </td>
                                        <td>0893483478283</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tio</td>
                                        <td>Jl.Central Legenda poin blok j No. 12 </td>
                                        <td>08123476282221</td>
                                        <td>
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
        $(document).ready(function() {
            const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

            const getListDriver = () => {
                const txtSearch = $('#txSearch').val();

                $.ajax({
                        url: "{{ route('getlistDriver') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch
                        },
                        beforeSend: () => {
                            $('#containerDriver').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerDriver').html(res)
                        $('#tableDriver').DataTable({
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

            getListDriver();

            $('#noTelponDriver, #noTelponDriverEdit').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('#saveDriver').click(function() {
                // Ambil nilai input
                var namaDriver = $('#namaDriver').val();
                var alamatDriver = $('#alamatDriver').val();
                var noTelponDriver = $('#noTelponDriver').val().trim();
                var simDriver = $('#simDriver')[0].files[0];
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                var isValid = true;

                if (namaDriver === '') {
                    $('#err-namaDriver').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#err-namaDriver').addClass('d-none');
                }

                if (alamatDriver === '') {
                    $('#err-alamatDriver').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#err-alamatDriver').addClass('d-none');
                }

                if (noTelponDriver === '') {
                    $('#err-noTelponDriver').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#err-noTelponDriver').addClass('d-none');
                }


                if (!simDriver) {
                    $('#simDriverError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#simDriverError').addClass('d-none');
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
                            formData.append('namaDriver', namaDriver);
                            formData.append('alamatDriver', alamatDriver);
                            formData.append('noTelponDriver', noTelponDriver);
                            formData.append('simDriver', simDriver);
                            formData.append('_token', csrfToken);

                            Swal.fire({
                                title: 'Checking...',
                                html: 'Please wait while we check your credentials.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                type: "POST",
                                url: "{{ route('addDriver') }}",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {

                                    Swal.close();

                                    if (response.status === 'success') {
                                        showMessage("success",
                                            "Data Berhasil Disimpan");
                                        getListDriver();
                                        $('#modalTambahDriver').modal('hide');
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
                                    Swal.close();

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


            $(document).on('click', '.btnUpdateDriver', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let nama_supir = $(this).data('nama_supir');
                let alamat_supir = $(this).data('alamat_supir');
                let no_wa = $(this).data('no_wa');
                let sim = $(this).data('sim');

                $('#namaDriverEdit').val(nama_supir);
                $('#alamatDriverEdit').val(alamat_supir);
                $('#noTelponDriverEdit').val(no_wa);
                $('#textSimEdit').text(sim);
                $('#driverIdEdit').val(id);

                $(document).on('click', '#saveEditDriver', function(e) {

                    let id = $('#driverIdEdit').val();
                    let namaDriver = $('#namaDriverEdit').val();
                    let alamatDriver = $('#alamatDriverEdit').val();
                    let noTelponDriver = $('#noTelponDriverEdit').val();
                    let simDriverEdit = $('#simDriverEdit')[0].files[0];
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');

                    let isValid = true;

                    if (namaDriver === '') {
                        $('#err-namaDriverEdit').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#err-namaDriverEdit').addClass('d-none');
                    }

                    if (alamatDriver === '') {
                        $('#err-alamatDriverEdit').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#err-alamatDriverEdit').addClass('d-none');
                    }

                    if (noTelponDriver === '') {
                        $('#err-noTelponDriverEdit').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#err-noTelponDriverEdit').addClass('d-none');
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
                                formData.append('namaDriver', namaDriver);
                                formData.append('alamatDriver', alamatDriver);
                                formData.append('noTelponDriver', noTelponDriver);
                                formData.append('simDriverEdit', simDriverEdit);
                                formData.append('_token', csrfToken);

                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('updateDriver') }}",
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            showMessage("success",
                                                "Data Berhasil Diubah");
                                            getListDriver();
                                            $('#modalEditDriver').modal(
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

                // validateInformationsInput('modalEditInformations');
                $('#modalEditDriver').modal('show');
            });


            $('#modalTambahDriver').on('hidden.bs.modal', function() {
                $('#namaDriver,#alamatDriver,#noTelponDriver').val('');
            });


            $(document).on('click', '.btnDestroyDriver', function(e) {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Apakah Kamu Yakin Ingin Hapus Driver Ini?",
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
                            url: "{{ route('destroyDriver') }}",
                            data: {
                                id: id,
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    showMessage("success",
                                        "Berhasil menghapus Driver");
                                    getListDriver();
                                } else {
                                    showMessage("error", "Gagal menghapus Driver");
                                }
                            }
                        });
                    }
                })
            });

            $(document).on('click', '.btnDetailSim', function(e) {
                var id = $(this).data('id');
                var imageSim = $(this).data('bukti');
                var imageUrl = '/storage/sim/' + imageSim;
                $('.containerFoto').html('<img src="' + imageUrl + '" alt="SIM Driver" class="img-fluid">');
                $('#modalDetailSim').modal('show');
            });
        });
    </script>


@endsection
