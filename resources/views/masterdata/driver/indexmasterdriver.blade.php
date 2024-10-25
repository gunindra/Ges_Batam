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
                            <input type="text" class="form-control" id="namaDriver" value=""
                                placeholder="Masukkan nama driver">
                            <div id="err-namaDriver" class="text-danger mt-1 d-none">Silahkan isi nama driver</div>
                        </div>
                        <div class="mt-3">
                            <label for="emailDriver" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="emailDriver" value=""
                                placeholder="Masukkan email driver">
                            <div id="err-emailDriver" class="text-danger mt-1 d-none">Silahkan isi email driver</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamatDriver" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamatDriver" rows="3" placeholder="Masukkan alamat"></textarea>
                            <div id="err-alamatDriver" class="text-danger mt-1 d-none">Silahkan isi alamat driver</div>
                        </div>
                        <div class="mt-3">
                            <label for="noTelponDriver" class="form-label fw-bold">No. Telpon</label>
                            <div class="input-group">
                                <span class="input-group-text" id="nomor">+62</span>
                                <input type="text" class="form-control" id="noTelponDriver" value=""
                                    placeholder="8***********">
                            </div>
                            <div id="err-noTelponDriver" class="text-danger mt-1 d-none">Silahkan isi no. telp driver</div>
                        </div>
                        <div class="mt-3">
                            <label for="simDriver" class="form-label fw-bold">SIM</label>
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
        <div class="modal fade" id="modalEditDriver" tabindex="-1" role="dialog"
            aria-labelledby="modalEditDriverTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditDriverTitle">Edit Driver</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="namaDriver" class="form-label fw-bold">Nama Driver</label>
                            <input type="text" class="form-control" id="namaDriverEdit" value=""
                                placeholder="Masukkan nama driver">
                            <div id="err-namaDriverEdit" class="text-danger mt-1 d-none">Silahkan isi nama driver</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamatDriverEdit" rows="3" placeholder="Masukkan alamat"></textarea>
                            <div id="err-alamatDriverEdit" class="text-danger mt-1 d-none">Silahkan isi alamat driver
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="noTelpon" class="form-label fw-bold">No. Telpon</label>
                            <div class="input-group">
                                <span class="input-group-text" id="nomorEdit">+62</span>
                                <input type="text" class="form-control" id="noTelponDriverEdit" value=""
                                    placeholder="8***********">
                            </div>
                            <div id="err-noTelponDriverEdit" class="text-danger mt-1 d-none">Silahkan isi no. telp driver
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="simDriverEdit" class="form-label fw-bold">Sim</label>
                            <p class="">Nama Sim : <span id="textSimEdit"></span></p>
                            <input type="file" class="form-control" id="simDriverEdit" value="">
                            <div id="simDriverEditError" class="text-danger mt-1 d-none">Silahkan masukkan SIM
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
                        <div class="float-left">
                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                class="form-control rounded-3" placeholder="Search">
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
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tio</td>
                                    <td>Jl.Central Legenda poin blok j No. 12 </td>
                                    <td>08123476282221</td>
                                    <td>
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

            $('#txSearch').keyup(function(e) {
                var inputText = $(this).val();
                if (inputText.length >= 1 || inputText.length == 0) {
                    getListDriver();
                }
            })

            $('#noTelponDriver, #noTelponDriverEdit').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            $('#saveDriver').click(function() {
                // Ambil nilai input
                var namaDriver = $('#namaDriver').val();
                var emailDriver = $('#emailDriver').val();
                var alamatDriver = $('#alamatDriver').val();
                var noTelponDriver = $('#noTelponDriver').val().trim();
                var simDriver = $('#simDriver')[0].files[0];
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let nomors = $('#nomor').text();
                let clearplus = nomors.replace('+', '');
                let valueNotlep = clearplus + noTelponDriver;

                var isValid = true;

                // Validasi Nama Driver
                if (namaDriver === '') {
                    $('#err-namaDriver').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#err-namaDriver').addClass('d-none');
                }

                // Validasi Email
                if (emailDriver === '') {
                    $('#err-emailDriver').removeClass('d-none');
                    $('#err-emailDriver').text('Silahkan isi email driver');
                    isValid = false;
                } else if (!/\S+@\S+\.\S+/.test(emailDriver)) {
                    $('#err-emailDriver').removeClass('d-none');
                    $('#err-emailDriver').text('Format email tidak valid');
                    isValid = false;
                } else {
                    $('#err-emailDriver').addClass('d-none');
                }

                // Validasi Alamat
                if (alamatDriver === '') {
                    $('#err-alamatDriver').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#err-alamatDriver').addClass('d-none');
                }

                // Validasi No. Telepon
                if (noTelponDriver === '') {
                    $('#err-noTelponDriver').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#err-noTelponDriver').addClass('d-none');
                }

                // Validasi SIM
                if (simDriver) {
                    var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validExtensions.includes(simDriver.type)) {
                        $('#simDriverError').text('Hanya file JPG, JPEG atau PNG yang diperbolehkan')
                            .removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#simDriverError').addClass('d-none');
                    }
                } else {
                    $('#simDriverError').text('Silahkan masukkan SIM').removeClass('d-none');
                    isValid = false;
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
                            var formData = new FormData();
                            formData.append('namaDriver', namaDriver);
                            formData.append('emailDriver',
                            emailDriver); // Tambahkan email ke FormData
                            formData.append('alamatDriver', alamatDriver);
                            formData.append('noTelponDriver', valueNotlep);
                            formData.append('simDriver', simDriver);
                            formData.append('_token', csrfToken);

                            Swal.fire({
                                title: 'Checking...',
                                html: 'Please wait while we process your data driver.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: '/masterdata/driver/store',
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    Swal.close();
                                    if (response.success) {
                                        showMessage("success", "berhasil ditambahkan");
                                        $('#modalTambahDriver').modal('hide');
                                        getListDriver();
                                    }
                                },
                                error: function(response) {
                                    Swal.close();
                                    showMessage("error",
                                        "Terjadi kesalahan, coba lagi nanti");
                                }
                            });
                        }
                    });
                }
            });

            // Reset form ketika modal ditutup
            $('#modalTambahDriver').on('hidden.bs.modal', function() {
                $('#namaDriver, #emailDriver, #alamatDriver, #noTelponDriver, #simDriver').val('');
                $('.text-danger').addClass('d-none'); // Reset pesan error
            });


            $(document).on('click', '.btnUpdateDriver', function(e) {
                var driverid = $(this).data('id');
                $.ajax({
                    url: '/masterdata/driver/' + driverid,
                    method: 'GET',
                    success: function(response) {
                        $('#namaDriverEdit').val(response.nama_supir);
                        $('#alamatDriverEdit').val(response.alamat_supir);
                        let noWaWithoutCode = response.no_wa.slice(2);
                        $('#noTelponDriverEdit').val(noWaWithoutCode);
                        $('#textSimEdit').text(response.image_sim);
                        $('#modalEditDriver').modal('show');
                        $('#saveEditDriver').data('id', driverid);
                    },
                    error: function() {
                        showMessage("error", "Terjadi kesalahan saat mengambil data");
                    }
                });
            });

            $('#saveEditDriver').on('click', function() {
                var driverid = $(this).data('id');
                var namaDriver = $('#namaDriverEdit').val();
                var alamatDriver = $('#alamatDriverEdit').val();
                var noTelponDrivers = $('#noTelponDriverEdit').val();
                var noTelpon = '62' + noTelponDrivers;
                var simDriver = $('#simDriverEdit')[0].files[0];
                var currentSim = $('#currentSim').val();

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

                if (noTelponDrivers === '') {
                    $('#err-noTelponDriverEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#err-noTelponDriverEdit').addClass('d-none');
                }

                if (simDriver) {
                    var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validExtensions.includes(simDriver.type)) {
                        $('#simDriverEditError').text('Hanya file JPG, JPEG, atau PNG yang diizinkan')
                            .removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#simDriverEditError').addClass('d-none');
                    }
                } else if ($('#textSimEdit').text() === '') {
                    $('#simDriverEditError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#simDriverEditError').addClass('d-none');
                }

                if (isValid) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#5D87FF',
                        cancelButtonColor: '#49BEFF',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Loading...',
                                text: 'Please wait while we are updating the data.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            var formData = new FormData();
                            formData.append('namaDriver', namaDriver);
                            formData.append('alamatDriver', alamatDriver);
                            formData.append('noTelponDriver', noTelpon);
                            if (simDriver) {
                                formData.append('simDriver', simDriver);
                            }

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                }
                            });

                            $.ajax({
                                url: '/masterdata/driver/update/' + driverid,
                                method: 'POST',
                                processData: false,
                                contentType: false,
                                data: formData,
                                success: function(response) {
                                    Swal.close();
                                    if (response.success) {
                                        showMessage("success", response.message);
                                        $('#modalEditDriver').modal('hide');
                                        getlistDriver();
                                    }
                                },
                                error: function() {
                                    Swal.close();
                                    showMessage("error",
                                        "Terjadi kesalahan, coba lagi nanti");
                                }
                            });
                        }
                    });
                }
            });


            $('#modalEditDriver').on('hidden.bs.modal', function() {
                $('#namaDriverEdit, #alamatDriverEdit, #noTelponDriverEdit, #simDriverEdit').val('');
                if (!$('#err-namaDriverEdit').hasClass('d-none')) {
                    $('#err-namaDriverEdit').addClass('d-none');
                }
                if (!$('#err-alamatDriverEdit').hasClass('d-none')) {
                    $('#err-alamatDriverEdit').addClass('d-none');
                }
                if (!$('#err-noTelponDriverEdit').hasClass('d-none')) {
                    $('#err-noTelponDriverEdit').addClass('d-none');
                }
                if (!$('#simDriverEditError').hasClass('d-none')) {
                    $('#simDriverEditError').addClass('d-none');
                }
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
                        Swal.fire({
                            title: 'Checking...',
                            html: 'Please wait while we process delete your data driver.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            type: "DELETE",
                            url: '/masterdata/driver/destroy/' + id,
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                id: id,
                            },
                            success: function(response) {
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
