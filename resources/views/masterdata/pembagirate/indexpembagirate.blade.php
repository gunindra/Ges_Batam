@extends('layout.main')

@section('title', 'Rate')

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
                        <input type="text" class="form-control" id="nilaiRate" value="" placeholder="Masukkan Nilai Rate">
                        <div id="nilaiRateError" class="text-danger mt-1 d-none">Silahkan isi nilai</div>
                    </div>
                    <div class="mt-3">
                        <label for="forRate" class="form-label fw-bold">For</label>
                        <select class="form-control" name="forRate" id="forRate">
                            <option value="" selected disabled>Pilih for</option>
                            <option value="Volume">Volume</option>
                            <option value="Berat">Berat</option>
                        </select>
                        <div id="forRateError" class="text-danger mt-1 d-none">Silahkan isi nilai</div>
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
                    <div class="mt-3">
                        <label for="forRateEdit" class="form-label fw-bold">For</label>
                        <select class="form-control" name="forRate" id="forRateEdit">
                            <option value="" selected disabled>Pilih for</option>
                            <option value="Volume">Volume</option>
                            <option value="Berat">Berat</option>
                        </select>
                        <div id="forRateEditError" class="text-danger mt-1 d-none">Silahkan isi nilai</div>
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
        <h1 class="h3 mb-0 text-gray-800 px-2">Rate</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active" aria-current="page">Rate</li>
        </ol>
    </div>
    <div class="row mb-3 px-3">
        <div class="col-xl-6 px-2">
            <div class="card ">
                <div class="card-body ">
                    <h6 class="m-0 font-weight-bold text-primary">Pembagi Volume</h6>
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

            var nilaiPembagi = $('#nilaiPembagi').val().trim();

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            // Validasi Nilai Pembagi
            if (nilaiPembagi === '') {
                $('#nilaiPembagiError').removeClass('d-none');
                isValid = false;
            } else {
                $('#nilaiPembagiError').addClass('d-none');
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
                        formData.append('nilaiPembagi', nilaiPembagi);
                        formData.append('_token', csrfToken);

                        $.ajax({
                            type: "POST",
                            url: "{{ route('addPembagi') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                if (response.status === 'success') {
                                    showMessage("success", "Data Berhasil Disimpan");
                                    getlistPembagi();
                                    $('#modalTambahPembagi').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Gagal Menambahkan Data",
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

        $(document).on('click', '.btnUpdatePembagi', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let nilai_pembagi = $(this).data('nilai_pembagi');

            $('#nilaiPembagiEdit').val(nilai_pembagi);
            $('#pembagiIdEdit').val(id);

            $(document).on('click', '#saveEditPembagi', function(e) {
                e.preventDefault();

                let id = $('#pembagiIdEdit').val();
                let nilaiPembagi = $('#nilaiPembagiEdit').val();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                let isValid = true;

                // Validasi Nilai Pembagi
                if (nilaiPembagi === '') {
                    $('#nilaiPembagiErrorEdit').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#nilaiPembagiErrorEdit').addClass('d-none');
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
                            formData.append('nilaiPembagi', nilaiPembagi);
                            formData.append('_token', csrfToken);

                            $.ajax({
                                type: "POST",
                                url: "{{ route('updatePembagi') }}",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    if (response.status === 'success') {
                                        showMessage("success", "Data Berhasil Diubah");
                                        getlistPembagi();
                                        $('#modalEditPembagi').modal('hide');
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

            $('#modalEditPembagi').modal('show');
        });

        $('#modalTambahPembagi').on('hidden.bs.modal', function() {
            $('#nilaiPembagi').val('');
        });

        $(document).on('click', '.btnDestroyPembagi', function (e) {
            let id = $(this).data('id');

            Swal.fire({
                title: "Apakah Kamu Yakin Ingin Hapus Pembagi Ini?",
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

        var nilaiRate = $('#nilaiRate').val().trim();
        var forRate = $('#forRate').val();

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        var isValid = true;


        if (nilaiRate === '') {
            $('#nilaiRateError').removeClass('d-none');
            isValid = false;
        } else {
            $('#nilaiRateError').addClass('d-none');
        }
        if (!forRate) {
            $('#forRateError').removeClass('d-none');
            isValid = false;
        } else {
            $('#forRateError').addClass('d-none');
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
                    formData.append('nilaiRate', nilaiRate);
                    formData.append('forRate', forRate);
                    formData.append('_token', csrfToken);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('addRate') }}",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if (response.status === 'success') {
                                showMessage("success", "Data Berhasil Disimpan");
                                getlistRate();
                                $('#modalTambahRate').modal('hide');
                            } else {
                                Swal.fire({
                                    title: "Gagal Menambahkan Data",
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

        $(document).on('click', '.btnUpdateRate', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let nilai_rate = $(this).data('nilai_rate');
        let rate_for = $(this).data('rate_for');

        $('#nilaiRateEdit').val(nilai_rate);
        $('#forRateEdit').val(rate_for);
        $('#rateIdEdit').val(id);

        $(document).on('click', '#saveEditRate', function(e) {
            e.preventDefault();

            let id = $('#rateIdEdit').val();
            let nilaiRate = $('#nilaiRateEdit').val();
            let rateFor = $('#forRateEdit').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            let isValid = true;

            // Validasi Nilai Pembagi
            if (nilaiRate === '') {
                $('#nilaiRateErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#nilaiRateErrorEdit').addClass('d-none');
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
                        formData.append('nilaiRate', nilaiRate);
                        formData.append('rateFor', rateFor);
                        formData.append('_token', csrfToken);

                        $.ajax({
                            type: "POST",
                            url: "{{ route('updateRate') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                if (response.status === 'success') {
                                    showMessage("success", "Data Berhasil Diubah");
                                    getlistRate();
                                    $('#modalEditRate').modal('hide');
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

        $('#modalEditRate').modal('show');
        });

        $('#modalTambahRate').on('hidden.bs.modal', function() {
        $('#nilaiRate').val('');
        });


        $(document).on('click', '.btnDestroyRate', function (e) {
            let id = $(this).data('id');

            Swal.fire({
                title: "Apakah Kamu Yakin Ingin Hapus Rate Ini?",
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



    });


    </script>

@endsection
