@extends('layout.main')

@section('title', 'Pembagi Dan Rate')

@section('main')


<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">


    <!-- Modal tambah -->
    <div class="modal fade" id="modalTambahPembagi" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahPembagiTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahPembagi">Tambah Pembagi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="nilaiPembagi" class="form-label fw-bold">Nilai</label>
                        <input type="text" class="form-control" id="nilaiPembagi" value="">
                        <div id="nilaiPembagiError" class="text-danger mt-1 d-none">Silahkan isi nilai</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="savePembagi" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditPembagi" tabindex="-1" role="dialog" aria-labelledby="modalEditPembagiTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditPembagiTitle">Edit Pembagi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="pembagiIdEdit">
                    <div class="mt-3">
                        <label for="nilaiPembagi" class="form-label fw-bold">Nilai</label>
                        <input type="text" class="form-control" id="nilaiPembagiEdit" value="">
                        <div id="nilaiPembagiErrorEdit" class="text-danger mt-1 d-none">Silahkan isi nilai</div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditPembagi" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


      <!-- Modal tambah -->
      <div class="modal fade" id="modalTambahRate" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahRateTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahRate">Tambah Pembagi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="nilaiRate" class="form-label fw-bold">Nilai</label>
                        <input type="text" class="form-control" id="nilaiRate" value="">
                        <div id="nilaiRateError" class="text-danger mt-1 d-none">Silahkan isi nilai</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveRate" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditRate" tabindex="-1" role="dialog" aria-labelledby="modalEditRateTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditRateTitle">Edit Rate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="rateIdEdit">
                    <div class="mt-3">
                        <label for="nilaiRate" class="form-label fw-bold">Nilai</label>
                        <input type="text" class="form-control" id="nilaiRateEdit" value="">
                        <div id="nilaiRateErrorEdit" class="text-danger mt-1 d-none">Silahkan isi nilai</div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditRate" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 px-2">Pembagi Dan Rate</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active" aria-current="page">Pembagi dan Rate</li>
        </ol>
    </div>
    <div class="row mb-3 px-3">
        <div class="col-xl-6 px-2">
            <div class="card ">
                <div class="card-body ">
                    <h6 class="m-0 font-weight-bold text-primary">Pembagi</h6>
                    <div class="d-flex mb-2 mr-3 float-right">
                        {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahPembagi" id="modalTambahPembagi"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Tambah</button>
                    </div>
                    <div id="containerPembagi" class="table-responsive px-3">
                        <!-- <table class="table align-items-center table-flush table-hover" id="tablePembagi">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nilai</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>1.000.000</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>6.000</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 px-2">
            <div class="card ">
                <div class="card-body">
                    <h6 class="m-0 font-weight-bold text-primary">Rate Harga</h6>
                    <div class="d-flex mb-2 mr-3 float-right">
                        {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahRate"
                            id="modalTambahRate"><span class="pr-2"><i class="fas fa-plus"></i></span>Tambah</button>
                    </div>
                    <div id="containerRate" class="table-responsive px-3">
                        <!-- <table class="table align-items-center table-flush table-hover" id="tableRate">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Rate Harga</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Rp. 200.000</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Rp. 300.000</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
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
<!---Container Fluid-->

@endsection
@section('script')

<script>
    $(document).ready(function () {
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

        const getlistPembagi = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "{{ route('getlistPembagi') }}",
                method: "GET",
                data: {
                    txSearch: txtSearch
                },
                beforeSend: () => {
                    $('#containerPembagi').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerPembagi').html(res)
                    $('#tablePembagi').DataTable({
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

        getlistPembagi();

        $('#nilaiPembagi,#nilaiPembagiEdit').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });


        $('#savePembagi').click(function () {
            $('#nilaiPembagi').data('touched', true);

            let nilaiPembagi = $('#nilaiPembagi').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (('modalTambahPembagi')) {
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
                            url: "{{ route('addPembagi') }}",
                            data: {
                                nilaiPembagi: nilaiPembagi,
                                _token: csrfToken
                            },
                            success: function (response) {
                                if (response.status === 'success') {
                                    showMessage("success", "Data Berhasil Disimpan");
                                    getlistPembagi();
                                    $('#modalTambahPembagi').modal('hide');
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
        $('#modalTambahPembagi').on('hidden.bs.modal', function () {
            $('#nilaiPembagi').val('');
            // validatePembagiInput('modalTambahPembagi');
        });

        $(document).on('click', '.btnUpdatePembagi', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let nilai_pembagi = $(this).data('nilai_pembagi');

            $('#nilaiPembagiEdit').val(nilai_pembagi);
            $('#pembagiIdEdit').val(id);

            $(document).on('click', '#saveEditPembagi', function (e) {

                let id = $('#pembagiIdEdit').val();
                let nilaiPembagi = $('#nilaiPembagiEdit').val();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

               
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
                        formData.append('nilaiPembagi', nilaiPembagi);
                        formData.append('_token', csrfToken);

                        $.ajax({
                            type: "POST",
                            url: "{{ route('updatePembagi') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                               
                                if (response.status === 'success') {
                                    showMessage("success",
                                        "Data Berhasil Diubah");
                                    getlistPembagi();
                                    $('#modalEditPembagi').modal(
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
            });

            // validateInformationsInput('modalEditInformations');
            $('#modalEditPembagi').modal('show');
        });

        $(document).on('click', '.btnDestroyPembagi', function (e) {
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
                        url: "{{ route('destroyPembagi') }}",
                        data: {
                            id: id,
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                showMessage("success",
                                    "Berhasil menghapus");
                                getlistPembagi();
                            } else {
                                showMessage("error", "Gagal menghapus");
                            }
                        }
                    });
                }
            })
        });
        $('#savePembagi').click(function() {
            const nilaiPembagi = $('#nilaiPembagi').val().trim();

            let isValid = true;

                if (nilaiPembagi === '') {
                    $('#nilaiPembagiError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#nilaiPembagiError').addClass('d-none');
                }

       
                if (!isValid) {
                    Swal.fire({
                        title: "Periksa input yang masih kosong.",
                        icon: "error"
                    });
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('addPembagi') }}",
                    data: {
                        nilaiPembagi: nilaiPembagi,
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showMessage("success", "Pembagi berhasil dibuat").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal membuat Pembagi",
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "Gagal membuat Pembagi",
                            text: "Terjadi kesalahan. Mohon coba lagi.",
                            icon: "error"
                        });
                    }
                });
            });

            $('#saveEditPembagi').click(function() {
                const nilaiPembagiEdit = $('#nilaiPembagiEdit').val().trim();

                let isValid = true;

                if (nilaiPembagiEdit === '') {
                    $('#nilaiPembagiErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#nilaiPembagiErrorEdit').addClass('d-none');
                }

                if (!isValid) {
                    Swal.fire({
                        title: "Periksa input yang masih kosong.",
                        icon: "error"
                    });
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('updatePembagi') }}",
                    data: {
                        nilaiPembagiEdit: nilaiPembagiEdit,
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: "Berhasil mengubah pembagi",
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal mengubah pembagi",
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "Gagal mengubah pembagi",
                            text: "Terjadi kesalahan. Mohon coba lagi.",
                            icon: "error"
                        });
                    }
                });
            });
    });

 </script>
  <script>
         $(document).ready(function () {
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

        const getlistRate = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "{{ route('getlistRate') }}",
                method: "GET",
                data: {
                    txSearch: txtSearch
                },
                beforeSend: () => {
                    $('#containerRate').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerRate').html(res)
                    $('#tableRate').DataTable({
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

        getlistRate();

        $('#nilaiRate,#nilaiRateEdit').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

        $('#saveRate').click(function () {
            $('#nilaiRate').data('touched', true);

            let nilaiRate = $('#nilaiRate').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (('modalTambahRate')) {
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
                            url: "{{ route('addRate') }}",
                            data: {
                                nilaiRate: nilaiRate,
                                _token: csrfToken
                            },
                            success: function (response) {
                                if (response.status === 'success') {
                                    showMessage("success", "Data Berhasil Disimpan");
                                    getlistRate();
                                    $('#modalTambahRate').modal('hide');
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
        $('#modalTambahRate').on('hidden.bs.modal', function () {
            $('#nilaiRate').val('');
            // validateRateInput('modalTambahRate');
        });

        $(document).on('click', '.btnUpdateRate', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let nilai_rate = $(this).data('nilai_rate');

            $('#nilaiRateEdit').val(nilai_rate);
            $('#RateIdEdit').val(id);

            $(document).on('click', '#saveEditRate', function (e) {

                let id = $('#rateIdEdit').val();
                let nilaiRate = $('#nilaiRateEdit').val();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

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
                        formData.append('nilaiRate', nilaiRate);
                        formData.append('_token', csrfToken);

                        $.ajax({
                            type: "POST",
                            url: "{{ route('updateRate') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                if (response.status === 'success') {
                                    showMessage("success",
                                        "Data Berhasil Diubah");
                                    getlistRate();
                                    $('#modalEditRate').modal(
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
            });


            // validateRateInput('modalEditRate');
            $('#modalEditRate').modal('show');
        });

        $(document).on('click', '.btnDestroyRate', function (e) {
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
                        url: "{{ route('destroyRate') }}",
                        data: {
                            id: id,
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                showMessage("success",
                                    "Berhasil menghapus");
                                getlistRate();
                            } else {
                                showMessage("error", "Gagal menghapus");
                            }
                        }
                    });
                }
            })
        });
        $('#saveRate').click(function() {
            const nilaiRate = $('#nilaiRate').val().trim();

            let isValid = true;

                if (nilaiRate === '') {
                    $('#nilaiRateError').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#nilaiRateError').addClass('d-none');
                }

       
                if (!isValid) {
                    Swal.fire({
                        title: "Periksa input yang masih kosong.",
                        icon: "error"
                    });
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('addRate') }}",
                    data: {
                        nilaiRate: nilaiRate,
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showMessage("success", "Rate berhasil dibuat").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal membuat Rate",
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "Gagal membuat Rate",
                            text: "Terjadi kesalahan. Mohon coba lagi.",
                            icon: "error"
                        });
                    }
                });
            });

            $('#saveEditRate').click(function() {
                const nilaiRateEdit = $('#nilaiRateEdit').val().trim();

                let isValid = true;

                if (nilaiRateEdit === '') {
                    $('#nilaiRateErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#nilaiRateErrorEdit').addClass('d-none');
                }

                if (!isValid) {
                    Swal.fire({
                        title: "Periksa input yang masih kosong.",
                        icon: "error"
                    });
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('updateRate') }}",
                    data: {
                        nilaiRateEdit: nilaiRateEdit,
                        _token: csrfToken
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: "Berhasil mengubah Rate",
                                icon: "success"
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Gagal mengubah Rate",
                                icon: "error"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: "Gagal mengubah Rate",
                            text: "Terjadi kesalahan. Mohon coba lagi.",
                            icon: "error"
                        });
                    }
                });
            });
    });
        

    </script>

@endsection
