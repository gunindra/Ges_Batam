<?php $__env->startSection('title', 'Invoice'); ?>

<?php $__env->startSection('main'); ?>

    <div class="modal fade" id="modalFilter" tabindex="-1" aria-labelledby="modalFilterLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilterLabel">Filter Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Existing filters -->
                    <div class="form-group">
                        <label for="filterStatus">Pilih Status</label>
                        <select class="form-control" id="filterStatus">
                            <option value="" selected disabled>Pilih Status</option>
                            <?php $__currentLoopData = $listStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status->status_name); ?>"><?php echo e($status->status_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filterNoDO">Pilih No DO</label>
                        <select class="form-control" id="filterNoDO">
                            <option value="" selected disabled>Pilih No DO</option>
                            <?php $__currentLoopData = $listDo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $NoDo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($NoDo->no_do); ?>"><?php echo e($NoDo->no_do); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <!-- New filter for payment status -->
                    <div class="form-group">
                        <label for="filterPaymentStatus">Status Pembayaran</label>
                        <select class="form-control" id="filterPaymentStatus">
                            <option value="" selected disabled>Pilih Status Pembayaran</option>
                            <option value="Lunas">Lunas</option>
                            <option value="Belum Lunas">Belum Lunas</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveFilter">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalConfirmasiPembayaran" tabindex="-1" role="dialog"
        aria-labelledby="modalConfirmasiPembayaranTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmasiPembayaranTitle">Confirmasi Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="pembayaranStatus" class="form-label fw-bold">Masukkan Bukti Transfer</label>
                        <input type="file" class="form-control" id="pembayaranStatus" value="">
                        <div id="err-pembayaranStatus" class="text-danger mt-1">Silakan masukkan file</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveFileTransfer" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBuktiPembayaran" tabindex="-1" role="dialog"
        aria-labelledby="modalBuktiPembayaranTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBuktiPembayaranTitle">Bukti Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="pembayaranStatus" class="form-label fw-bold">Bukti Pembayaran :</label>
                        <div class="containerFoto">
                            
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFilterTanggal" tabindex="-1" role="dialog"
        aria-labelledby="modalFilterTanggalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilterTanggalTitle">Filter Tanggal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mt-3">
                                <label for="pembayaranStatus" class="form-label fw-bold">Pilih Tanggal:</label>
                                <div class="d-flex align-items-center">
                                    <input type="date" id="startDate" class="form-control"
                                        placeholder="Pilih tanggal mulai" style="width: 200px;">
                                    <span class="mx-2">sampai</span>
                                    <input type="date" id="endDate" class="form-control"
                                        placeholder="Pilih tanggal akhir" style="width: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveFilterTanggal" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Invoice</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Costumer</li>
                <li class="breadcrumb-item active" aria-current="page">Invoice</li>
            </ol>
        </div>

<div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 justify-content-between align-items-center">
                            <div class="d-flex">
                                
                                <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                    class="form-control rounded-3" placeholder="Search">
                                    <button class="btn btn-primary ml-2" id="filterModalButton">Filter</button>
                                <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                                <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                    onclick="window.location.reload()">
                                    Reset
                                </button>
                            </div>
                            <div class="d-flex mb-2 mr-3 float-right">


                                <?php if(Auth::user()->role === 'supervisor'): ?>
                                    <a class="btn btn-secondary mr-1" style="color:white;" id="kirimNot"><span
                                            class="pr-2"><i class="fas fa-paper-plane"
                                                style="color: #ffffff;"></i></span>Kirim
                                        Notifikasi</a>
                                    <a class="btn btn-success mr-1" style="color:white;" id="kirimInvoice"><span
                                            class="pr-2"><i class="fab fa-whatsapp"
                                                style="color: #ffffff;"></i></span>Kirim Invoice</a>
                                <?php endif; ?>

                                <!-- <button class="btn btn-success mr-1" id="isNotif"><span class="pr-2"><i
                                                                                                    class="fas fa-bell"></i></span>Notifikasi</button> -->
                                <a class="btn btn-primary" href="<?php echo e(route('addinvoice')); ?>" id=""><span
                                        class="pr-2"><i class="fas fa-plus"></i></span>Buat Invoice</a>

                            </div>
                        </div>
                        
                        
                        

                        <div id="containerInvoice">
                            <div class="table-responsive">
                                <table id="tableInvoice" class="table align-items-center table-flush table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <?php if(Auth::user()->role === 'supervisor'): ?>
                                                <th><input type="checkbox" class="selectAll" id="selectAll"></th>
                                            <?php endif; ?>
                                            <th>No Invoice</th>
                                            <th>Tanggal</th>
                                            <th>No Do</th>
                                            <th>Customer</th>
                                            <th>Pengiriman</th>
                                            <th>Alamat</th>
                                            <th>Status Pembayaran</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                            <th>Created by</th>
                                            <th>Updated by</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
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
        $(document).ready(function() {
            let tableColumns = [];
            if ($('#selectAll').length) {
                tableColumns.push({
                    data: 'checkbox',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<input type="checkbox" class="selectItem" data-id="' + row.id +
                            '" value="' + row.id + '">';
                    }
                });
            }
            tableColumns = tableColumns.concat([{
                    data: 'no_invoice',
                    name: 'no_invoice',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                }, {
                    data: 'tanggal_bayar',
                    name: 'tanggal_bayar',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'no_do',
                    name: 'no_do',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'pembeli',
                    name: 'pembeli',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'metode_pengiriman',
                    name: 'metode_pengiriman',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'alamat',
                    name: 'alamat',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'status_bayar',
                    name: 'status_bayar',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'converted_harga',
                    name: 'converted_harga',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'status_badge',
                    name: 'status_badge',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data, type, row) {
                        if (data) {
                            return row.user ?
                                `${data} (${row.user})` :
                                `${data}`;
                        }
                        return '-';
                    }
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                    render: function(data, type, row) {
                        if (data) {
                            return row.user_update ?
                                `${data} (${row.user_update})` :
                                `${data}`;
                        }
                        return '-';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]);

            let table = $('#tableInvoice').DataTable({
                serverSide: true,
                processing: true,
                searching: false,
                ajax: {
                    url: "<?php echo e(route('getlistInvoice')); ?>",
                    method: 'GET',
                    data: function(d) {
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                        d.status = $('#filterStatus').val();
                        d.txSearch = $('#txSearch').val();
                        d.no_do = $('#filterNoDO').val();
                        d.payment_status = $('#filterPaymentStatus').val();
                    },
                    error: function(xhr, error, thrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load payment data. Please try again!',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                columns: tableColumns,
                order: [],
                lengthChange: false,
                pageLength: 7,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                    info: "_START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    emptyTable: "No data available in table",
                    loadingRecords: "Loading...",
                    zeroRecords: "No matching records found"
                }
            });



            const handleSelectItems = () => {
                $('#tableInvoice').on('change', '.selectItem', function() {
                    const allChecked = $('.selectItem').length === $('.selectItem:checked').length;
                    $('#selectAll').prop('checked', allChecked);
                });

                $('#tableInvoice').on('change', '#selectAll', function() {
                    $('.selectItem').prop('checked', this.checked);
                });
            };

            table.on('draw', function() {
                handleSelectItems();
            });

            $(document).ready(function() {
                handleSelectItems();

                $(document).on('click', '#kirimNot', function(e) {
                    e.preventDefault();

                    let selectedItems = [];
                    $('.selectItem:checked').each(function() {
                        selectedItems.push($(this).data('id'));
                    });

                    if (selectedItems.length === 0) {
                        showMessage("error", "Tidak ada invoice yang dipilih");
                        return;
                    }

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
                                title: 'Mengirim notifikasi...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                type: "GET",
                                url: "<?php echo e(route('kirimPesanWaPembeli')); ?>",
                                data: {
                                    id: selectedItems,
                                    type: 'listbarang',
                                },
                                success: function(response) {
                                    Swal.close();
                                    if (response.success) {
                                        showMessage("success",
                                            "Berhasil mengirim notifikasi");
                                        // $('#kirimNot').hide();
                                        table.ajax.reload();
                                    } else {
                                        showMessage("error", response.message ||
                                            "Gagal mengirim notifikasi");
                                    }
                                },
                                error: function() {
                                    Swal.close();
                                    showMessage("error",
                                        "Terjadi kesalahan saat mengirim notifikasi"
                                    );

                                }
                            });
                        }
                    });
                });


                $(document).on('click', '#kirimInvoice', function(e) {
                    e.preventDefault();

                    let selectedItems = [];
                    $('.selectItem:checked').each(function() {
                        selectedItems.push($(this).data('id'));
                    });

                    if (selectedItems.length === 0) {
                        showMessage("error", "Tidak ada invoice yang dipilih");
                        return;
                    }


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
                                title: 'Mengirim notifikasi...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                type: "GET",
                                url: "<?php echo e(route('kirimPesanWaPembeli')); ?>",
                                data: {
                                    id: selectedItems,
                                    type: 'invoice',
                                },
                                success: function(response) {
                                    Swal.close();
                                    if (response.success) {
                                        showMessage("success",
                                            "Berhasil mengirim notifikasi");
                                        table.ajax.reload();
                                    } else {
                                        showMessage("error", response.message ||
                                            "Gagal mengirim notifikasi");
                                    }
                                },
                                error: function() {
                                    Swal.close();
                                    showMessage("error",
                                        "Terjadi kesalahan saat mengirim notifikasi"
                                    );

                                }
                            });
                        }
                    });

                });



            });


            $('#txSearch').keyup(function() {
                table.draw();
            });

            flatpickr("#startDate", {
                dateFormat: "d M Y",
                onChange: function(selectedDates, dateStr, instance) {

                    $("#endDate").flatpickr({
                        dateFormat: "d M Y",
                        minDate: dateStr
                    });
                }
            });

            flatpickr("#endDate", {
                dateFormat: "d MM Y",
                onChange: function(selectedDates, dateStr, instance) {
                    let startDate = new Date($('#startDate').val());
                    let endDate = new Date(dateStr);
                    if (endDate < startDate) {
                        showMessage(error,
                            "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                        $('#endDate').val('');
                    }
                }
            });
            $(document).on('click', '#filterModalButton', function() {
                $('#modalFilter').modal('show');
            });
            $(document).on('click', '#filterTanggal', function(e) {
                $('#modalFilterTanggal').modal('show');
            });
            $('#saveFilter').click(function() {
                table.ajax.reload();
                $('#modalFilter').modal('hide');
            });
            $('#saveFilterTanggal').click(function() {
                table.ajax.reload();
                $('#modalFilterTanggal').modal('hide');
            });




            $(document).on('click', '.btnExportInvoice', function(e) {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: "GET",
                    url: "<?php echo e(route('exportPdf')); ?>",
                    data: {
                        id: id
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
                    },
                    error: function(xhr) {
                        Swal.close();

                        let errorMessage = 'Gagal Export Invoice';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    }
                });
            });

            $(document).on('click', '.btnDetailPembayaran', function(e) {
                e.preventDefault();
                let namafoto = $(this).data('bukti');
                $.ajax({
                    url: "<?php echo e(route('detailBuktiPembayaran')); ?>",
                    method: 'GET',
                    data: {
                        namafoto: namafoto,
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            let imageUrl = response.url;

                            console.log(imageUrl);

                            $('#modalBuktiPembayaran').find('.containerFoto').html(
                                '<img src="' + imageUrl + '" class="img-fluid">');
                        } else {
                            showMessage("error", "Gagal memuat bukti pembayaran");
                        }
                        $('#modalBuktiPembayaran').modal('show');
                    },
                    error: function(xhr) {
                        showMessage("error", "Terjadi kesalahan saat memuat bukti pembayaran");
                        $('#modalBuktiPembayaran').modal('show');
                    }
                });
            });


            $(document).on('click', '.btnPembayaran', function(e) {
                let id = $(this).data('id');
                let tipePembayaran = $(this).data('tipe');

                if (tipePembayaran === 'Transfer') {
                    function validatePembayaran() {
                        let isValid = true;
                        const fileInput = $('#pembayaranStatus');
                        const file = fileInput[0].files[0];

                        if (!file) {
                            $('#err-pembayaranStatus').show();
                            isValid = false;
                        } else {
                            $('#err-pembayaranStatus').hide();
                        }

                        return isValid;
                    }

                    $('#pembayaranStatus').on('input change', function() {
                        validatePembayaran();
                    });

                    $(document).on('click', '#saveFileTransfer', function(e) {
                        if (validatePembayaran()) {
                            Swal.fire({
                                title: "Apakah Pembayaran Invoice ini Sudah di Selesaikan?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#5D87FF',
                                cancelButtonColor: '#49BEFF',
                                confirmButtonText: 'Ya',
                                cancelButtonText: 'Tidak',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {

                                    const fileInput = $('#pembayaranStatus')[0].files[0];
                                    const formData = new FormData();
                                    formData.append('id', id);
                                    formData.append('file', fileInput);
                                    formData.append('_token', $('meta[name="csrf-token"]')
                                        .attr('content'));

                                    $.ajax({
                                        type: "POST",
                                        url: "<?php echo e(route('completePayment')); ?>",
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        success: function(response) {
                                            if (response.error) {
                                                showMessage("error", response
                                                    .message);
                                            } else {
                                                showMessage("success",
                                                    "Berhasil");
                                                getlistInvoice();
                                                $('#modalConfirmasiPembayaran')
                                                    .modal('hide');
                                            }
                                        },
                                        error: function() {
                                            showMessage("error",
                                                "Terjadi kesalahan pada server."
                                            );
                                        }
                                    });
                                }
                            });
                        } else {
                            showMessage("error", "Mohon periksa input yang kosong.");
                        }
                    });

                    $('#modalConfirmasiPembayaran').modal('show');
                } else {
                    Swal.fire({
                        title: "Apakah Pembayaran Invoice ini Sudah di Selesaikan?",
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
                                url: "<?php echo e(route('completePayment')); ?>",
                                data: {
                                    id: id,
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(response) {
                                    if (response.error) {
                                        showMessage("error", response.message);
                                    } else {
                                        showMessage("success", "Berhasil");
                                        getlistInvoice();
                                    }
                                },
                                error: function() {
                                    showMessage("error",
                                        "Terjadi kesalahan pada server.");
                                }
                            });
                        }
                    });
                }
            });

            $('#modalConfirmasiPembayaran').on('hidden.bs.modal', function() {
                $('#pembayaranStatus').val('');
                validatePembayaran('modalConfirmasiPembayaran');
            });



            $(document).on('click', '.btnDeleteInvoice', function(e) {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Apakah Kamu Yakin Ingin Hapus Invoice Ini?",
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
                            url: "<?php echo e(route('deleteInvoice')); ?>",
                            data: {
                                id: id,
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    showMessage("success",
                                        "Berhasil menghapus Invoice");
                                    getlistInvoice();
                                } else {
                                    showMessage("error", "Gagal menghapus Invoice");
                                }
                            }
                        });
                    }
                })
            });

            $(document).on('click', '.btnEditInvoice', function(e) {
                let id = $(this).data('id');
                let url = "<?php echo e(route('deleteoreditinvoice', ':id')); ?>";
                url = url.replace(':id', id);
                window.location.href = url;
            });

            $(document).on('click', '.btnCicilan', function(e) {
                let id = $(this).data('id');
                let url = "<?php echo e(route('cicilanInvoice', ':id')); ?>";
                url = url.replace(':id', id);
                window.location.href = url;
            });
            $(document).on('click', '.btnChangeMethod', function(e) {
                let id = $(this).data('id');
                let method = $(this).data('method');

                Swal.fire({
                    title: "Apakah Anda ingin mengubah pengiriman invoice ini menjadi Delivery?",
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
                            url: "<?php echo e(route('changeMethod')); ?>",
                            data: {
                                id: id,
                                method: method,
                            },
                            success: function(response) {
                                if (response
                                    .success) {
                                    showMessage("success", response
                                        .message);
                                    table.ajax.reload();
                                } else {
                                    showMessage("error", response.message ||
                                        "Gagal mengubah invoice");
                                }
                            },
                            error: function() {
                                showMessage("error",
                                    "Terjadi kesalahan dalam mengubah invoice");
                            }
                        });
                    }
                })
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\customer\invoice\indexinvoice.blade.php ENDPATH**/ ?>