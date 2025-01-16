@extends('layout.main')

@section('title', 'Master Data | Company')

@section('main')
    <style>
        /* Pembungkus Switch */
        .switch-wrapper {
            display: flex;
            /* align-items: center; */
            /* gap: 10px; */
            font-family: Arial, sans-serif;
        }

        /* Container Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 70px;
            /* Lebar Switch */
            height: 35px;
            /* Tinggi Switch */
        }

        /* Input Checkbox */
        .switch-input {
            display: none;
        }

        /* Label Switch */
        .switch-label {
            display: block;
            border-radius: 50px;
            background-color: #ccc;
            /* position: relative; */
            transition: background-color 0.3s ease-in-out;
            height: 100%;
            cursor: pointer;
        }

        /* Knob Switch */
        .switch-label:before {
            content: "";
            position: absolute;
            top: 3px;
            left: 3px;
            width: 29px;
            height: 29px;
            background-color: white;
            border-radius: 50%;
            transition: transform 0.3s ease-in-out;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* On/Off Text */
        .switch-label .text-on,
        .switch-label .text-off {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
            pointer-events: none;
        }

        .switch-label .text-on {
            left: 10px;
            /* Geser teks "On" ke kiri */
            color: white;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .switch-label .text-off {
            right: 10px;
            /* Geser teks "Off" ke kanan */
            color: white;
            opacity: 1;
            transition: opacity 0.3s;
        }

        /* Aktifkan (On) */
        .switch-input:checked+.switch-label {
            background-color: #4caf50;
        }

        .switch-input:checked+.switch-label:before {
            transform: translateX(35px);
            /* Gerakkan knob ke kanan */
        }

        .switch-input:checked+.switch-label .text-on {
            opacity: 1;
            /* Tampilkan teks "On" */
        }

        .switch-input:checked+.switch-label .text-off {
            opacity: 0;
            /* Sembunyikan teks "Off" */
        }
    </style>
    <div class="container-fluid" id="container-wrapper">

        <!-- Modal tambah -->
        <div class="modal fade" id="modalTambahCompany" tabindex="-1" role="dialog" aria-labelledby="modalTambahCompanyTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahCompany">Tambah Company</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="namaCompany" class="form-label fw-bold">Nama</label>
                            <input type="text" class="form-control" id="namaCompany" value=""
                                placeholder="Masukkan Nama">
                            <div id="namaCompanyError" class="text-danger mt-1 d-none">Silahkan isi Name</div>
                        </div>
                        <div class="mt-3">
                            <label for="logoCompany" class="form-label fw-bold">Logo</label>
                            <input type="file" class="form-control" id="logoCompany" value="">
                            <div id="logoCompanyError" class="text-danger mt-1 d-none">Silahkan Masukkan Logo</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamatCompany" class="form-label fw-bold">Alamat</label>
                            {{-- <input type="text" class="form-control" id="alamatCompany" value=""
                                placeholder="Masukkan Alamat"> --}}
                            <textarea class="form-control" name="alamatCompany" id="alamatCompany" placeholder="Masukkan Alamat"></textarea>
                            <div id="alamatCompanyError" class="text-danger mt-1 d-none">Silahkan isi Alamat</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                            <button type="button" id="saveCompany" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="modalEditCompany" tabindex="-1" role="dialog" aria-labelledby="modalEditCompanyTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditCompanyTitle">Edit Company</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- Tambahkan modal-body untuk padding -->
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="namaCompanyEdit" class="form-label fw-bold">Nama</label>
                            <input type="text" class="form-control" id="namaCompanyEdit" placeholder="Masukkan Nama">
                            <div id="namaCompanyErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Nama</div>
                        </div>
                        <div class="mt-3">
                            <label for="logoCompanyEdit" class="form-label fw-bold">Logo</label>
                            <input type="file" class="form-control" id="logoCompanyEdit">
                            <div id="logoCompanyErrorEdit" class="text-danger mt-1 d-none">Silahkan Masukkan Logo</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamatCompanyEdit" class="form-label fw-bold">Alamat</label>
                            {{-- <input type="text" class="form-control" id="alamatCompanyEdit" placeholder="Masukkan Alamat"> --}}
                            <textarea class="form-control" name="alamatCompanyEdit" id="alamatCompanyEdit"></textarea>
                            <div id="alamatCompanyErrorEdit" class="text-danger mt-1 d-none">Silahkan isi Alamat</div>
                        </div>
                    </div>
                    <!-- Footer untuk tombol aksi -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveEditCompany" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 px-4">Company</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Master Data</li>
                <li class="breadcrumb-item active" aria-current="page">Company</li>
            </ol>
        </div>
        <div class="row mb-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahCompany" id="#modalCenter"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah Company</button>
                        </div>

                        <div class="float-left">
                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                class="form-control rounded-3" placeholder="Search">
                        </div>

                        <div id="containerCompany" class="table-responsive px-2">
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


            const getlistCompany = () => {
                const txtSearch = $('#txSearch').val();
                // const filterStatus = $('#filterStatus').val();

                $.ajax({
                        url: "{{ route('getlistCompany') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch,
                            // status: filterStatus
                        },
                        beforeSend: () => {
                            $('#containerCompany').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerCompany').html(res)
                        $('#tableCompany').DataTable({
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

            getlistCompany();


            const validateSwitches = () => {
                const activeSwitches = $('.switch-input:checked');
                if (activeSwitches.length === 0) {
                    showMessage("error", "Harus ada salah satu company yang active");
                    return false;
                }
                return true;
            };

            $(document).on('change', '.switch-input', function() {
                const switchId = $(this).data('id');
                const isChecked = $(this).is(':checked');

                if (!validateSwitches()) {
                    $(this).prop('checked', !isChecked);
                    return;
                }

                if (isChecked) {
                    $('.switch-input').not(this).prop('checked', false);
                }

                $.ajax({
                    url: '/set-active-company',
                    method: "POST",
                    data: {
                        id: switchId,
                        is_active: isChecked ? 1 : 0,
                        _token: "{{ csrf_token() }}"
                    },
                    success: (res) => {
                        console.log(res.message);
                    },
                    error: (err) => {
                        console.error(err);
                        alert("Terjadi kesalahan. Silakan coba lagi.");
                        $(this).prop('checked', !isChecked);
                    }
                });
            });


            $('#saveCompany').click(function() {
                var namaCompany = $('#namaCompany').val().trim();
                var logoCompany = $('#logoCompany')[0].files[0];
                var alamatCompany = $('#alamatCompany').val().trim();

                var isValid = true;

                if (namaCompany === '') {
                    $('#namaCompanyError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#namaCompanyError').addClass('d-none');
                }
                if (logoCompany) {
                    var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validExtensions.includes(logoCompany.type)) {
                        $('#logoCompanyError').text('Hanya file JPG, JPEG, atau PNG yang diizinkan.')
                            .removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#logoCompanyError').addClass('d-none');
                    }
                } else {
                    $('#logoCompanyError').removeClass('d-none');
                    isValid = false;
                }
                if (alamatCompany === '') {
                    $('#alamatCompanyError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#alamatCompanyError').addClass('d-none');
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
                                text: 'Please wait while we are saving the data.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            var formData = new FormData();
                            formData.append('namaCompany', namaCompany);
                            formData.append('logoCompany', logoCompany);
                            formData.append('alamatCompany', alamatCompany);
                            formData.append('_token', '{{ csrf_token() }}');

                            $.ajax({
                                url: "{{ route('tambahCompany') }}",
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    Swal.close();
                                    if (response.success) {
                                        showMessage("success",
                                            "Company Berhasil ditambahkan");
                                        $('#modalTambahCompany').modal('hide');
                                        getlistCompany();
                                    }
                                },
                                error: function(xhr) {
                                    Swal.close();
                                    if (xhr.status === 422) {
                                        // Ambil pesan error dari respon
                                        var errors = xhr.responseJSON.errors;

                                        if (errors.namaCompany) {
                                            $('#namaCompanyError')
                                                .text(errors.namaCompany[0])
                                                .removeClass('d-none');
                                        }

                                        if (errors.alamatCompany) {
                                            $('#alamatCompanyError')
                                                .text(errors.alamatCompany[0])
                                                .removeClass('d-none');
                                        }

                                        if (errors.logoCompany) {
                                            $('#logoCompanyError')
                                                .text(errors.logoCompany[0])
                                                .removeClass('d-none');
                                        }
                                    } else {
                                        Swal.fire('error',
                                            'Terjadi kesalahan saat menyimpan data.'
                                        );
                                    }
                                }
                            });
                        }
                    });
                }
            });


            // $(document).on('click', '.btnDestroyCompany', function(e) {
            //     let id = $(this).data('id');

            //     Swal.fire({
            //         title: "Apakah Anda yakin ingin menghapus ini?",
            //         icon: 'question',
            //         showCancelButton: true,
            //         confirmButtonColor: '#5D87FF',
            //         cancelButtonColor: '#49BEFF',
            //         confirmButtonText: 'Ya',
            //         cancelButtonText: 'Tidak',
            //         reverseButtons: true
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             Swal.fire({
            //                 title: 'Loading...',
            //                 text: 'Please wait while we process Delete your data.',
            //                 allowOutsideClick: false,
            //                 didOpen: () => {
            //                     Swal.showLoading();
            //                 }
            //             });

            //             $.ajax({
            //                 type: "POST",
            //                 url: "{{ route('deleteCompany') }}",
            //                 data: {
            //                     _token: $('meta[name="csrf-token"]').attr('content'),
            //                     id: id,
            //                 },
            //                 success: function(response) {
            //                     Swal.close();
            //                     if (response.status === 'success') {
            //                         showMessage("success", response.message ||
            //                             "Berhasil dihapus");
            //                         getlistCompany();
            //                     } else {
            //                         showMessage("error", response.message ||
            //                             "Gagal menghapus");
            //                     }
            //                 },
            //                 error: function(xhr) {
            //                     Swal.close();
            //                     Swal.fire({
            //                         icon: 'error',
            //                         title: 'Error',
            //                         text: xhr.responseJSON?.message ||
            //                             'Terjadi kesalahan.',
            //                     });
            //                 }
            //             });
            //         }
            //     });
            // });

            $(document).on('click', '.btnUpdateCompany', function(e) {
                var companyid = $(this).data('id');
                $.ajax({
                    url: "{{ route('getDataCompany') }}",
                    method: 'GET',
                    data: {
                        id: companyid,
                    },
                    success: function(response) {
                        $('#namaCompanyEdit').val(response.data.name);
                        $('#textNamaEdit').text(response.data.logo);
                        $('#alamatCompanyEdit').val(response.data.alamat);
                        $('#modalEditCompany').modal('show');
                        $('#saveEditCompany').data('id', companyid);
                    },
                    error: function() {
                        showMessage("error",
                            "Terjadi kesalahan saat mengambil data HeroPage");
                    }
                });
            });

            $('#saveEditCompany').on('click', function() {
                var companyid = $(this).data('id');
                var namaCompany = $('#namaCompanyEdit').val();
                var logoCompany = $('#logoCompanyEdit')[0].files[0];
                var alamatCompany = $('#alamatCompanyEdit').val();

                let isValid = true;

                if (namaCompany === '') {
                    $('#namaCompanyErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#namaCompanyErrorEdit').addClass('d-none');
                }

                if (logoCompany) {
                    var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validExtensions.includes(logoCompany.type)) {
                        $('#logoCompanyErrorEdit').text(
                            'Hanya file JPG, JPEG, atau PNG yang diizinkan.').removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#logoCompanyErrorEdit').addClass('d-none');
                    }
                } else if ($('#textNamaEdit').text() === '') {
                    $('#logoCompanyErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#logoCompanyErrorEdit').addClass('d-none');
                }

                if (alamatCompany === '') {
                    $('#alamatCompanyErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#alamatCompanyErrorEdit').addClass('d-none');
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
                            formData.append('namaCompany', namaCompany);
                            formData.append('id', companyid);
                            if (logoCompany) {
                                formData.append('logoCompany', logoCompany);
                            }
                            formData.append('alamatCompany', alamatCompany);
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                }
                            });

                            $.ajax({
                                url: "{{ route('updateCompany') }}",
                                method: 'POST',
                                processData: false,
                                contentType: false,
                                data: formData,
                                success: function(response) {
                                    Swal.close();
                                    if (response.success) {
                                        showMessage("success", response
                                            .message);
                                        $('#modalEditCompany').modal('hide');
                                        getlistCompany();
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

            $('#modalTambahCompany').on('hidden.bs.modal', function() {
                $('#namaCompany,#logoCompany,#alamatCompany').val('');
                if (!$('#namaCompanyError').hasClass('d-none')) {
                    $('#namaCompanyError').addClass('d-none');

                }
                if (!$('#logoCompanyError').hasClass('d-none')) {
                    $('#logoCompanyError').addClass('d-none');

                }
                if (!$('#alamatCompanyError').hasClass('d-none')) {
                    $('#alamatCompanyError').addClass('d-none');

                }
            });
            $('#modalEditCompany').on('hidden.bs.modal', function() {
                $('#namaCompanyEdit,#logoCompanyEdit,#alamatCompanyEdit').val('');
                if (!$('#namaCompanyErrorEdit').hasClass('d-none')) {
                    $('#namaCompanyErrorEdit').addClass('d-none');

                }
                if (!$('#logoCompanyErrorEdit').hasClass('d-none')) {
                    $('#logoCompanyErrorEdit').addClass('d-none');

                }
                if (!$('#alamatCompanyErrorEdit').hasClass('d-none')) {
                    $('#alamatCompanyErrorEdit').addClass('d-none');

                }
            });

        });
    </script>
@endsection
