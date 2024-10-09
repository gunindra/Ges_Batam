@extends('layout.main')

@section('title', 'Master Data | Rekening')

@section('main')

<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">

    <!-- Modal Center -->
    <div class="modal fade" id="modalTambahRekening" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahRekeningTitle" aria-hidden="true">
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
                        <input type="text" class="form-control" id="bankRekening" value=""
                            placeholder="Masukkan nama bank">
                        <div id="err-bankRekening" class="text-danger mt-1 d-none">Silahkan masukkan nama bank</div>
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
    <div class="modal fade" id="modalEditRekening" tabindex="-1" role="dialog" aria-labelledby="modalEditRekeningTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditRekeningTitle">Modal Edit Rekening</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="namaRekening" class="form-label fw-bold">Pemilik</label>
                        <input type="text" class="form-control" id="namaRekeningEdit" value=""
                            placeholder="Masukkan Nama Pemilik">
                        <div id="err-NamaRekeningEdit" class="text-danger mt-1 d-none">Silahkan isi nama pemilik</div>
                    </div>
                    <div class="mt-3">
                        <label for="noRek" class="form-label fw-bold">No. Rekening</label>
                        <input type="text" class="form-control" id="noRekeningEdit" value=""
                            placeholder="Masukkan No. Rekening">
                        <div id="err-noRekeningEdit" class="text-danger mt-1 d-none">Silahkan isi no. Rekening</div>
                    </div>
                    <div class="mt-3">
                        <label for="alamat" class="form-label fw-bold">Bank</label>
                        <input type="text" class="form-control" id="bankRekeningEdit" value=""
                            placeholder="Masukkan nama bank">
                        <div id="err-bankRekeningEdit" class="text-danger mt-1 d-none">Silahkan masukkan nama bank</div>
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
                    <div class="float-left">
                        <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                            class="form-control rounded-3" placeholder="Search">
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
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tio</td>
                                    <td>1192837432</td>
                                    <td>Mandiri</td>
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
    $(document).ready(function () {
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

        $('#txSearch').keyup(function (e) {
            var inputText = $(this).val();
            if (inputText.length >= 1 || inputText.length == 0) {
                getListRekening();
            }
        })


        $('#noRekening, #noRekeningEdit').on('input', function () {
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


        $('#saveRekening').click(function () {
            var namaRekening = $('#namaRekening').val().trim();
            var noRekening = $('#noRekening').val().trim();
            var bankRekening = $('#bankRekening').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (namaRekening === '') {
                $('#err-NamaRekening').removeClass('d-none');
                isValid = false;
            } else {
                $('#err-NamaRekening').addClass('d-none');
            }

            if (noRekening === '') {
                $('#err-noRekening').removeClass('d-none');
                isValid = false;
            } else {
                $('#err-noRekening').addClass('d-none');
            }

            if ($('#bankRekening').val() === '' || $('#bankRekening').val() === null) {
                $('#err-bankRekening').removeClass('d-none');
                isValid = false;
            } else {
                $('#err-bankRekening').addClass('d-none');
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
                        Swal.fire({
                            title: 'Loading...',
                            text: 'Please wait while we process your rekening.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            url: '/masterdata/rekening/store',
                            method: 'POST',
                            data: {
                                namaRekening: namaRekening,
                                noRekening: noRekening,
                                bankRekening: bankRekening,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success", "berhasil ditambahkan")
                                        .then(
                                            () => {
                                                location.reload();
                                            });
                                }
                            },
                            error: function (response) {
                                Swal.close();
                                showMessage("error",
                                    "Terjadi kesalahan, coba lagi nanti");
                            }
                        });
                    }
                });
            }
        });

        $('#modalTambahRekening').on('hidden.bs.modal', function () {
            $('#namaRekening, #noRekening, #bankRekening').val('');
            if (!$('#err-NamaRekening').hasClass('d-none')) {
                $('#err-NamaRekening').addClass('d-none');

            }
            if (!$('#err-noRekening').hasClass('d-none')) {
                $('#err-noRekening').addClass('d-none');

            }
            if (!$('#err-bankRekening').hasClass('d-none')) {
                $('#err-bankRekening').addClass('d-none');

            }
        });

        $(document).on('click', '.btnUpdateRekening', function (e) {
            var RekeningId = $(this).data('id');
            $.ajax({
                url: '/masterdata/rekening/' + RekeningId,
                method: 'GET',
                success: function (response) {
                    $('#namaRekeningEdit').val(response.pemilik);
                    $('#noRekeningEdit').val(response.nomer_rekening);
                    $('#bankRekeningEdit').val(response.nama_bank);
                    $('#modalEditRekening').modal('show');
                    $('#saveEditRekening').data('id', RekeningId);
                },
                error: function () {
                    showMessage("error", "Terjadi kesalahan saat mengambil data");
                }
            });
        });

        $('#saveEditRekening').on('click', function () {
            var RekeningId = $(this).data('id');
            var namaRekening = $('#namaRekeningEdit').val();
            var noRekening = $('#noRekeningEdit').val();
            var bankRekening = $('#bankRekeningEdit').val();

            let isValid = true;

            if (namaRekening === '') {
                $('#err-NamaRekeningEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#err-NamaRekeningEdit').addClass('d-none');
            }

            if (noRekening === '') {
                $('#err-noRekeningEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#err-noRekeningEdit').addClass('d-none');
            }

            if (bankRekening === '') {
                $('#err-bankRekeningEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#err-bankRekeningEdit').addClass('d-none');
            }
            if (isValid) {
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Anda akan memperbarui ini",
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
                            title: 'Memproses...',
                            text: 'Harap tunggu, sedang memperbarui data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: '/masterdata/rekening/update/' + RekeningId,
                            method: 'PUT',
                            data: {
                                namaRekening: namaRekening,
                                noRekening: noRekening,
                                bankRekening: bankRekening,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success", "berhasil diperbarui")
                                        .then(
                                            () => {
                                                location.reload();
                                            });
                                }
                            },
                            error: function (response) {
                                Swal.close();
                                showMessage("error",
                                    "Terjadi kesalahan, coba lagi nanti");
                            }
                        });
                    }
                });
            }
        });
        $('#modalEditRekening').on('hidden.bs.modal', function () {
            $('#namaRekeningEdit, #noRekeningEdit, #bankRekeningEdit').val('');
            if (!$('#err-NamaRekeningEdit').hasClass('d-none')) {
                $('#err-NamaRekeningEdit').addClass('d-none');

            }
            if (!$('#err-noRekeningEdit').hasClass('d-none')) {
                $('#err-noRekeningEdit').addClass('d-none');

            }
            if (!$('#err-bankRekeningEdit').hasClass('d-none')) {
                $('#err-bankRekeningEdit').addClass('d-none');

            }
        });


        $(document).on('click', '.btnDestroyRekening', function (e) {
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
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Please wait while we process delete your rekening.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: '/masterdata/pembagirate/destroy/' + id,
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: id,
                        },
                        success: function (response) {
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