@extends('layout.main')

@section('title', 'Master Data | Rekening')

@section('main')

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">

        <!-- Modal Center -->
        <div class="modal fade" id="modalTambahRekening" tabindex="-1" role="dialog" aria-labelledby="modalTambahRekeningTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahRekeningTitle">Modal Tambah Rekening</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="namaRekening" class="form-label fw-bold">Pemilik</label>
                            <input type="text" class="form-control" id="namaRekening" value=""
                                placeholder="Masukkan Nama Pemilik">
                            <div id="err-NamaRekening" class="text-danger mt-1 d-none">Silahkan isi nama pemilik</div>
                        </div>
                        <div class="mt-3">
                            <label for="noRek" class="form-label fw-bold">No. Rekening</label>
                            <input type="text" class="form-control" id="noRekening" value=""
                                placeholder="Masukkan No. Rekening">
                            <div id="err-noRekening" class="text-danger mt-1 d-none">Silahkan isi no. Rekening</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Bank</label>
                            <select class="form-control" id="bankRekening" rows="3">
                                <option value="" selected disabled>Pilih Bank</option>
                                <option value="Mandiri">Mandiri</option>
                                <option value="BCA">BCA</option>
                                <option value="BRI">BRI</option>
                                <option value="BNI">BNI</option>
                            </select>
                            <div id="err-bankRekening" class="text-danger mt-1 d-none">Silahkan pilih Bank</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveRekening">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Center -->
        <div class="modal fade" id="modalEditRekening" tabindex="-1" role="dialog"
            aria-labelledby="modalEditRekeningTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditRekeningTitle">Modal Edit Rekening</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="rekeningIdEdit">
                        <div class="mt-3">
                            <label for="namaRekening" class="form-label fw-bold">Pemilik</label>
                            <input type="text" class="form-control" id="namaRekeningEdit" value="">
                            <div id="err-NamaRekening" class="text-danger mt-1">Silahkan isi nama pemilik</div>
                        </div>
                        <div class="mt-3">
                            <label for="noRek" class="form-label fw-bold">No. Rekening</label>
                            <input type="text" class="form-control" id="noRekeningEdit" value="">
                            <div id="err-noRekening" class="text-danger mt-1">Silahkan isi no. Rekening</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Bank</label>
                            <select class="form-control" id="bankRekeningEdit" rows="3">
                                <option value="" selected disabled>Pilih Bank</option>
                                <option value="Mandiri">Mandiri</option>
                                <option value="BCA">BCA</option>
                                <option value="BRI">BRI</option>
                                <option value="BNI">BNI</option>
                            </select>
                            <div id="err-bankRekening" class="text-danger mt-1">Silahkan pilih Bank</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveEditRekening">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Rekening</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Master Data</li>
                <li class="breadcrumb-item active" aria-current="page">Rekening</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahRekening" id="#modalCenter"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah Rekening</button>
                        </div>
                        <div id="containerRekening" class="table-responsive px-3 ">
                            {{-- <table class="table align-items-center table-flush table-hover" id="tableRekening">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Pemilik</th>
                                        <th>No. Rekening</th>
                                        <th>Bank</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Ilham</td>
                                        <td>291037292</td>
                                        <td>BCA</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tio</td>
                                        <td>1192837432</td>
                                        <td>Mandiri</td>
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

            const getListRekening = () => {
                const txtSearch = $('#txSearch').val();

                $.ajax({
                        url: "{{ route('getlistRekening') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch
                        },
                        beforeSend: () => {
                            $('#containerRekening').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerRekening').html(res)
                        $('#tableRekening').DataTable({
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

            getListRekening();

            $('#noRekening, #noRekeningEdit').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Fungsi untuk validasi input
            // function validateInput(modal) {
            //     let isValid = true;

            //     // Nama Rekening
            //     if ($(`#${modal} #namaRekening, #${modal} #namaRekeningEdit`).val().trim() === '') {
            //         $(`#${modal} #err-NamaRekening`).show();
            //         isValid = false;
            //     } else {
            //         $(`#${modal} #err-NamaRekening`).hide();
            //     }

            //     // No. Rekening
            //     if ($(`#${modal} #noRekening, #${modal} #noRekeningEdit`).val().trim() === '') {
            //         $(`#${modal} #err-noRekening`).show();
            //         isValid = false;
            //     } else {
            //         $(`#${modal} #err-noRekening`).hide();
            //     }

            //     // Bank
            //     if ($(`#${modal} #bankRekening, #${modal} #bankRekeningEdit`).val() === null) {
            //         $(`#${modal} #err-bankRekening`).show();
            //         isValid = false;
            //     } else {
            //         $(`#${modal} #err-bankRekening`).hide();
            //     }

            //     return isValid;
            // }

            // validateInput('modalTambahRekening');
            // validateInput('modalEditRekening');

            // $('#namaRekening, #noRekening, #bankRekening').on('input change', function() {
            //     validateInput('modalTambahRekening');
            // });

            // $('#namaRekeningEdit, #noRekeningEdit, #bankRekeningEdit').on('input change', function() {
            //     validateInput('modalEditRekening');
            // });


            $('#saveRekening').click(function() {
                // Ambil nilai input
                var namaRekening = $('#namaRekening').val().trim();
                var noRekening = $('#noRekening').val().trim();
                var bankRekening = $('#bankRekening').val();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');


                var isValid = true;

                // Validasi Nama Pemilik
                if (namaRekening === '') {
                    $('#err-NamaRekening').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#err-NamaRekening').addClass('d-none');
                }

                // Validasi No. Rekening
                if (noRekening === '') {
                    $('#err-noRekening').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#err-noRekening').addClass('d-none');
                }


                // Validasi Bank
                if ($('#bankRekening').val() === '' || $('#bankRekening').val() === null) {
                    $('#err-bankRekening').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#err-bankRekening').addClass('d-none');
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
                            $.ajax({
                                type: "POST",
                                url: "{{ route('addRekening') }}",
                                data: {
                                    namaRekening: namaRekening,
                                    noRekening: noRekening,
                                    bankRekening: bankRekening,
                                    _token: csrfToken
                                },
                                success: function(response) {
                                    if (response.status === 'success') {
                                        showMessage("success",
                                            "Data Berhasil Disimpan");
                                        getListRekening();
                                        $('#modalTambahRekening').modal('hide');
                                    } else {
                                        Swal.fire({
                                            title: "Gagal Menambahkan Rekening",
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

            $('#saveEditRekening').click(function() {
                $('#namaRekeningEdit, #noRekeningEdit, #bankRekeningEdit').data('touched', true);

                let id = $('#rekeningIdEdit').val();
                let namaRekening = $('#namaRekeningEdit').val();
                let noRekening = $('#noRekeningEdit').val();
                let bankRekening = $('#bankRekeningEdit').val();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                if (validateInput('modalEditRekening')) {
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
                                url: "{{ route('updateRekening') }}",
                                data: {
                                    id: id,
                                    namaRekening: namaRekening,
                                    noRekening: noRekening,
                                    bankRekening: bankRekening,
                                    _token: csrfToken
                                },
                                success: function(response) {
                                    if (response.status === 'success') {
                                        showMessage("success",
                                            "Data Berhasil Di Update");
                                        getListRekening();
                                        $('#modalEditRekening').modal('hide');
                                    } else {
                                        Swal.fire({
                                            title: "Gagal Mengubah Rekening",
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

            $('#modalTambahRekening').on('hidden.bs.modal', function() {
                $('#namaRekening, #noRekening, #bankRekening').val('');
                validateInput('modalTambahRekening');
            });

            $(document).on('click', '.btnUpdateRekening', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let pemilik = $(this).data('pemilik');
                let nomer_rekening = $(this).data('nomer_rekening');
                let nama_bank = $(this).data('nama_bank');

                $('#namaRekeningEdit').val(pemilik);
                $('#noRekeningEdit').val(nomer_rekening);
                $('#bankRekeningEdit').val(nama_bank);
                $('#rekeningIdEdit').val(id);

                validateInput('modalEditRekening');
                $('#modalEditRekening').modal('show');
            });

            $(document).on('click', '.btnDestroyRekening', function(e) {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Apakah Kamu Yakin Ingin Hapus Rekening Ini?",
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
                            url: "{{ route('destroyRekening') }}",
                            data: {
                                id: id,
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    showMessage("success",
                                        "Berhasil menghapus Rekening");
                                    getListRekening();
                                } else {
                                    showMessage("error", "Gagal menghapus Rekening");
                                }
                            }
                        });
                    }
                })
            });
        });
    </script>

@endsection
