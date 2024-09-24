<?php $__env->startSection('title', 'Delivery'); ?>

<?php $__env->startSection('main'); ?>


    <div class="modal fade" id="modalConfirmasiPengantaran" tabindex="-1" role="dialog"
        aria-labelledby="modalConfirmasiPengantaranTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmasiPengantaranTitle">Confirmasi Pengantaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="pengantaranStatus" class="form-label fw-bold">Masukkan Bukti Pengantaran</label>
                        <input type="file" class="form-control" id="pengantaranStatus" value="">
                        <div id="err-pengantaranStatus" class="text-danger mt-1 d-none">Silakan masukkan file</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveFilePengantaran" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBuktiPengantaran" tabindex="-1" role="dialog"
        aria-labelledby="modalBuktiPengantaranTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBuktiPengantaranTitle">Bukti Pengantaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="pembayaranStatus" class="form-label fw-bold">Bukti Pengantaran :</label>
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

    <div class="modal fade" id="modalFilterTanggal" tabindex="-1" role="dialog" aria-labelledby="modalFilterTanggalTitle"
        aria-hidden="true">
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

    <!-- Modal Structure -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">Detail Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalContent">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Delivery / Pick Up</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Costumer</li>
                <li class="breadcrumb-item active" aria-current="page">Delivery</li>
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
                                <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                                    <option value="" selected disabled>Pilih Filter</option>
                                    <option value="Ready For Pickup">Ready For Pickup</option>
                                    <option value="Out For Delivery">Out For Delivery</option>
                                    <option value="Delivering">Delivering</option>
                                    <option value="Done">Done</option>
                                </select>
                                <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                                <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                    onclick="window.location.reload()">
                                    Reset
                                </button>
                            </div>
                            <a class="btn btn-primary" href="<?php echo e(route('addDelivery')); ?>" id=""><span
                                    class="pr-2"><i class="fas fa-plus"></i></span>Buat Pengantaran</a>
                        </div>
                        <div id="containerDelivery" class="table-responsive px-3">
                            
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
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

        const getlistDelivery = () => {
            const txtSearch = $('#txSearch').val();
            const filterStatus = $('#filterStatus').val();
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();

            $.ajax({
                    url: "<?php echo e(route('getlistDelivery')); ?>",
                    method: "GET",
                    data: {
                        txSearch: txtSearch,
                        startDate: startDate,
                        endDate: endDate,
                        status: filterStatus
                    },
                    beforeSend: () => {
                        $('#containerDelivery').html(loadSpin)
                    }
                })
                .done(res => {
                    $('#containerDelivery').html(res)
                    $('#tableDelivery').DataTable({
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

        getlistDelivery();

        $('#txSearch').keyup(function(e) {
            var inputText = $(this).val();
            if (inputText.length >= 1 || inputText.length == 0) {
                getlistDelivery();
            }
        })

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
                var startDate = new Date($('#startDate').val());
                var endDate = new Date(dateStr);
                if (endDate < startDate) {
                    showwMassage(error, "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                    $('#endDate').val('');
                }
            }
        });

        $(document).on('click', '#filterTanggal', function(e) {
            $('#modalFilterTanggal').modal('show');
        });



        $('#filterStatus').change(function() {
            getlistDelivery();
        });

        $('#saveFilterTanggal').click(function() {
            getlistDelivery();
            $('#modalFilterTanggal').modal('hide');
        });

        $(document).on('click', '.btnDetailPengantaran', function(e) {
            e.preventDefault();
            let namafoto = $(this).data('bukti');
            $.ajax({
                url: "<?php echo e(route('detailBuktiPengantaran')); ?>",
                method: 'GET',
                data: {
                    namafoto: namafoto,
                },
                success: function(response) {
                    if (response.status === 'success') {
                        let imageUrl = response.url;

                        console.log(imageUrl);

                        $('#modalBuktiPengantaran').find('.containerFoto').html(
                            '<img src="' + imageUrl + '" class="img-fluid">');
                    } else {
                        showMessage("error", "Gagal memuat bukti pembayaran");
                    }
                    $('#modalBuktiPengantaran').modal('show');
                },
                error: function(xhr) {
                    showMessage("error", "Terjadi kesalahan saat memuat bukti pembayaran");
                    $('#modalBuktiPengantaran').modal('show');
                }
            });
        });


        $(document).on('click', '.btnSelesaikanPickup', function(e) {
            let pengantaranId = $(this).data('id');

            Swal.fire({
                title: "Apakah Invoice ini Sudah di Pick Up?",
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
                        text: 'Please wait while we process your request.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        type: "GET",
                        url: "<?php echo e(route('updateStatus')); ?>",
                        data: {
                            id: pengantaranId,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.close();

                            if (response.status === 'error') {
                                showMessage("error", response.message);
                            } else {
                                showMessage("success", response.message);
                                getlistDelivery();
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
                }
            });
        });

        $(document).on('click', '.btnExportPDF', function() {
            let pengantaranId = $(this).data('id');

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
                url: "<?php echo e(route('exportPDFDelivery')); ?>",
                data: {
                    id: pengantaranId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

        $(document).on('click', '.show-invoice-modal', function() {
            var supirName = $(this).data('supir');
            var invoiceNumbers = $(this).data('invoices');
            var customerNames = $(this).data('customers');
            var addresses = $(this).data('alamat');


            if (typeof invoiceNumbers !== 'string') {
                invoiceNumbers = String(invoiceNumbers);
            }

            if (invoiceNumbers.indexOf(',') === -1) {
                invoiceNumbers = [invoiceNumbers];
            } else {
                invoiceNumbers = invoiceNumbers.split(', ');
            }

            customerNames = customerNames ? customerNames.split(', ') : [];
            addresses = addresses ? addresses.split(', ') : [];

            console.log('After split - Invoices:', invoiceNumbers);
            console.log('After split - Customers:', customerNames);
            console.log('After split - Addresses:', addresses);

            var modalContent = '<table id="invoiceTable" class="table table-striped table-bordered">';
            modalContent +=
                '<thead><tr><th>No. Invoice</th><th>Customer</th><th>Alamat</th><th>Action</th></tr></thead><tbody>';
            for (var i = 0; i < invoiceNumbers.length; i++) {
                modalContent += '<tr>';
                modalContent += '<td>' + invoiceNumbers[i] + '</td>';
                modalContent += '<td>' + customerNames[i] + '</td>';
                modalContent += '<td>' + addresses[i] + '</td>';
                modalContent +=
                    '<td><button type="button" class="btn btn-warning btnBuktiPengantaran" data-invoice="' +
                    invoiceNumbers[i] + '">Accept Pengantaran</button></td>';
                modalContent += '</tr>';
            }
            modalContent += '</tbody></table>';
            $('#modalContent').html(modalContent);
            $('#invoiceModal').modal('show');
            $('#invoiceTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthChange: false,
                pageLength: 5
            });
        });

        // $(document).on('click', '.btnBuktiPengantaran', function(e) {
        //     let id = $(this).data('id');

        //     Swal.fire({
        //         title: "Apakah Barang Dengan Resi Ini Siap Diatarkan?",
        //         icon: 'question',
        //         showCancelButton: true,
        //         confirmButtonColor: '#5D87FF',
        //         cancelButtonColor: '#49BEFF',
        //         confirmButtonText: 'Ya',
        //         cancelButtonText: 'Tidak',
        //         reverseButtons: true
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 type: "GET",
        //                 url: "<?php echo e(route('acceptPengantaran')); ?>",
        //                 data: {
        //                     id: id,
        //                 },
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 },
        //                 success: function(response) {
        //                     if (response.status === 'error') {
        //                         showMessage("error", response.message);
        //                     } else {
        //                         showMessage("success", response.message);
        //                         getlistDelivery();
        //                     }
        //                 },
        //                 error: function() {
        //                     showMessage("error", "Terjadi kesalahan pada server.");
        //                 }
        //             });
        //         }
        //     });
        // });



        $(document).on('click', '.btnBuktiPengantaran', function(e) {
            let id = $(this).data('id');

            function validatePengantaran() {
                let isValid = true;
                const fileInput = $('#pengantaranStatus');
                const file = fileInput[0].files[0];

                if (file) {
                    var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!validExtensions.includes(file.type)) {
                        $('#err-pengantaranStatus').text(
                                'Hanya file JPG , JPEG atau PNG yang diperbolehkan atau gambar tidak boleh kosong')
                            .removeClass('d-none');
                        isValid = false;
                    } else {
                        $('#err-pengantaranStatus').addClass('d-none');
                    }
                } else if (!file) {
                    $('#err-pengantaranStatus').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#err-pengantaranStatus').addClass('d-none');
                }
                return isValid;
            }

            $('#pengantaranStatus').on('input change', function() {
                validatePengantaran();
            });

            $(document).on('click', '#saveFilePengantaran', function(e) {
                e.preventDefault();

                if (validatePengantaran()) {
                    Swal.fire({
                        title: "Apakah Pengantaran ini Sudah di Selesaikan?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#5D87FF',
                        cancelButtonColor: '#49BEFF',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const fileInput = $('#pengantaranStatus')[0].files[0];
                            const formData = new FormData();
                            formData.append('id', id);
                            formData.append('file', fileInput);
                            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                            $.ajax({
                                type: "POST",
                                url: "<?php echo e(route('confirmasiPengantaran')); ?>",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    if (response.error) {
                                        showMessage("error", response.message);
                                    } else {
                                        showMessage("success", "Berhasil");
                                        getlistDelivery();
                                        $('#modalConfirmasiPengantaran').modal('hide');
                                    }
                                },
                                error: function() {
                                    showMessage("error",
                                        "Terjadi kesalahan pada server.");
                                }
                            });
                        }
                    });
                } else {
                    showMessage("error", "Mohon periksa input yang kosong.");
                }
            });

            $('#modalConfirmasiPengantaran').modal('show');
        });
        $('#modalConfirmasiPengantaran').on('hidden.bs.modal', function() {
            $('#pengantaranStatus').val('');
            if (!$('#err-pengantaranStatus').hasClass('d-none')) {
                $('#err-pengantaranStatus').addClass('d-none');

            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Asus\OneDrive\Desktop\SAC\GES-Project\resources\views\customer\delivery\indexdelivery.blade.php ENDPATH**/ ?>