<?php $__env->startSection('title', 'Rate'); ?>

<?php $__env->startSection('main'); ?>


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
                        <input type="text" class="form-control" id="nilaiPembagi" value=""
                            placeholder="Silahkan isi Nilai">
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
    <div class="modal fade" id="modalTambahRate" tabindex="-1" role="dialog" aria-labelledby="modalTambahRateTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahRate">Tambah Rate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="nilaiRate" class="form-label fw-bold">Nilai</label>
                        <input type="text" class="form-control" id="nilaiRate" value=""
                            placeholder="Masukkan Nilai Rate">
                        <div id="nilaiRateError" class="text-danger mt-1 d-none">Silahkan isi nilai</div>
                    </div>
                    <div class="mt-3">
                        <label for="forRate" class="form-label fw-bold">For</label>
                        <select class="form-control" name="forRate" id="forRate">
                            <option value="" selected disabled>Pilih for</option>
                            <option value="Volume">Volume</option>
                            <option value="Berat">Berat</option>
                            <option value="Topup">Topup</option>
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
                            <option value="Topup">Topup</option>
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

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<script>
    $(document).ready(function () {
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

        const getlistPembagi = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                url: "<?php echo e(route('getlistPembagi')); ?>",
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

        $('#nilaiPembagi,#nilaiPembagiEdit').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        $('#savePembagi').click(function () {

            var nilaiPembagi = $('#nilaiPembagi').val().trim();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (nilaiPembagi === '') {
                $('#nilaiPembagiError').removeClass('d-none');
                isValid = false;
            } else {
                $('#nilaiPembagiError').addClass('d-none');
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
                            text: 'Please wait while we process your data pembagi.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            url: '/masterdata/pembagirate/store',
                            method: 'POST',
                            data: {
                                nilaiPembagi: nilaiPembagi,
                                _token: '<?php echo e(csrf_token()); ?>',
                            },
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success",
                                        "Berhasil ditambahkan");
                                    $('#modalTambahPembagi').modal('hide');
                                    getlistPembagi();
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

        $(document).on('click', '.btnUpdatePembagi', function (e) {
            var pembagiid = $(this).data('id');
            $.ajax({
                url: '/masterdata/pembagirate/' + pembagiid,
                method: 'GET',
                success: function (response) {
                    $('#nilaiPembagiEdit').val(response.nilai_pembagi);
                    $('#modalEditPembagi').modal('show');
                    $('#saveEditPembagi').data('id', pembagiid);
                },
                error: function () {
                    showMessage("error",
                        "Terjadi kesalahan saat mengambil data");
                }
            });
        });
        $('#saveEditPembagi').on('click', function () {
            var pembagiid = $(this).data('id');
            var nilaiPembagi = $('#nilaiPembagiEdit').val();

            let isValid = true;

            if (nilaiPembagi === '') {
                $('#nilaiPembagiErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#nilaiPembagiErrorEdit').addClass('d-none');
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
                        $.ajax({
                            url: '/masterdata/pembagirate/update/' + pembagiid,
                            method: 'PUT',
                            data: {
                                nilaiPembagi: nilaiPembagi,
                                _token: '<?php echo e(csrf_token()); ?>',
                            },
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success", response
                                        .message);
                                    $('#modalEditPembagi').modal('hide');
                                    getlistPembagi();
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
        $('#modalTambahPembagi').on('hidden.bs.modal', function () {
            $('#nilaiPembagi').val('');
            if (!$('#nilaiPembagiError').hasClass('d-none')) {
                $('#nilaiPembagiError').addClass('d-none');

            }

        });
        $('#modalEditPembagi').on('hidden.bs.modal', function () {
            $('#nilaiPembagiEdit').val('');
            if (!$('#nilaiPembagiErrorEdit').hasClass('d-none')) {
                $('#nilaiPembagiErrorEdit').addClass('d-none');

            }
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
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Please wait while we process delete your data pembagi.',
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
                url: "<?php echo e(route('getlistRate')); ?>",
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

        $('#nilaiRate,#nilaiRateEdit').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        $('#saveRate').click(function () {

            var nilaiRate = $('#nilaiRate').val();
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
                            text: 'Please wait while we process your data rate.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        $.ajax({
                            url: '/masterdata/rate/store',
                            method: 'POST',
                            data: {
                                nilaiRate: nilaiRate,
                                forRate: forRate,
                                _token: '<?php echo e(csrf_token()); ?>',
                            },
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success",
                                        "Berhasil ditambahkan");
                                    $('#modalTambahRate').modal('hide');
                                    getlistRate();
                                }
                            },
                            error: function (response) {
                                Swal.close();
                                 if (response.error) {
                                showMessage("error",
                                    "Terjadi kesalahan, coba lagi nanti");
                                 }
                            }
                        });
                    }
                });
            }
        });
        $('#modalTambahRate').on('hidden.bs.modal', function () {
            $('#nilaiRate,#forRate').val('');
            if (!$('#nilaiRateError').hasClass('d-none')) {
                $('#nilaiRateError').addClass('d-none');
            }
            if (!$('#forRateError').hasClass('d-none')) {
                $('#forRateError').addClass('d-none');
            }

        });

        $(document).on('click', '.btnUpdateRate', function (e) {
            var rateid = $(this).data('id');
            $.ajax({
                url: '/masterdata/rate/' + rateid,
                method: 'GET',
                success: function (response) {
                    $('#nilaiRateEdit').val(response.nilai_rate);
                    $('#forRateEdit').val(response.rate_for);
                    $('#modalEditRate').modal('show');
                    $('#saveEditRate').data('id', rateid);
                },
                error: function () {
                    showMessage("error",
                        "Terjadi kesalahan saat mengambil data");
                }
            });
        });
        $('#saveEditRate').on('click', function () {
            var rateid = $(this).data('id');
            var nilaiRate = $('#nilaiRateEdit').val().trim();
            var forRate = $('#forRateEdit').val();


            let isValid = true;

            if (nilaiRate === '') {
                $('#nilaiRateErrorEdit').removeClass('d-none');
                isValid = false;
            } else {
                $('#nilaiRateErrorEdit').addClass('d-none');
            }

            if (forRate === '') {
                $('#forRateEditError').removeClass('d-none');
                isValid = false;
            } else {
                $('#forRateEditError').addClass('d-none');
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

                        $.ajax({
                            url: '/masterdata/rate/update/' + rateid,
                            method: 'PUT',
                            data: {
                                nilaiRate: nilaiRate,
                                forRate: forRate,
                                _token: '<?php echo e(csrf_token()); ?>',
                            },
                            success: function (response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success", response
                                        .message);
                                    $('#modalEditRate').modal('hide');
                                    getlistRate();
                                }
                            },
                            error: function (response) {
                                Swal.close();
                                if (response.error) {
                                showMessage("error",
                                    "Terjadi kesalahan, coba lagi nanti");
                                 }
                            }
                        });
                    }
                });
            }
        });
        $('#modalEditRate').on('hidden.bs.modal', function () {
            $('#nilaiRateEdit').val('');
            if (!$('#nilaiRateErrorEdit').hasClass('d-none')) {
                $('#nilaiRateErrorEdit').addClass('d-none');
            }
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
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Please wait while we process delete your data rate.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: '/masterdata/rate/destroyrate/' + id,
                        data: {
                            id: id,
                            _token: $('meta[name="csrf-token"]').attr('content'),
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ilono\OneDrive\Desktop\Project SAC\pt-ges-project\resources\views\masterdata\pembagirate\indexpembagirate.blade.php ENDPATH**/ ?>