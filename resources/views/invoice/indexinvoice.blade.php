@extends('layout.main')

@section('title', 'Invoice')

@section('main')


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
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Invoice</h1>
            {{-- <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
      </ol> --}}
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 justify-content-between align-items-center">
                            <div class="d-flex">
                                {{-- Search --}}
                                <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                    class="form-control rounded-3" placeholder="Search">
                                <button id="monthEvent" class="btn btn-light form-control ml-2"
                                    style="border: 1px solid #e9ecef;">
                                    <span id="calendarTitle" class="fs-4"></span>
                                </button>
                                <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                    onclick="window.location.reload()">
                                    Reset
                                </button>
                            </div>
                            <a class="btn btn-primary" href="{{ route('addinvoice') }}" id=""><span
                                    class="pr-2"><i class="fas fa-plus"></i></span>Buat Invoice</a>
                        </div>
                        <div id="containerInvoice" class="table-responsive px-3">
                            {{-- <table class="table align-items-center table-flush table-hover" id="tableInvoice">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No Resi</th>
                                        <th>Tanggal</th>
                                        <th>Costumer</th>
                                        <th>Pengiriman</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>B0230123</td>
                                        <td>24 Juli 2024</td>
                                        <td>Tandrio</td>
                                        <td>Delivery</td>
                                        <td>Rp. 10.000</td>
                                        <td><span class="badge badge-warning">Paid</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-print"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>B0234043</td>
                                        <td>28 Juli 2024</td>
                                        <td>Tandrio</td>
                                        <td>PickUp</td>
                                        <td>Rp. 12.000</td>
                                        <td><span class="badge badge-warning">Paid</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-print"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i
                                                    class="fas fa-trash"></i></a>
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

            let selectedMonth = getCurrentMonth();



            const getlistInvoice = () => {
                const txtSearch = $('#txSearch').val();

                $.ajax({
                        url: "{{ route('getlistInvoice') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch,
                            filter: selectedMonth
                        },
                        beforeSend: () => {
                            $('#containerInvoice').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerInvoice').html(res)
                        $('#tableInvoice').DataTable({
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

            getlistInvoice();

            $('#txSearch').keyup(function(e) {
                var inputText = $(this).val();
                if (inputText.length >= 1 || inputText.length == 0) {
                    getlistInvoice();
                }
            })

            function getCurrentMonth() {
                const months = [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ];

                const currentDate = new Date();
                const currentMonth = months[currentDate.getMonth()];
                const currentYear = currentDate.getFullYear();

                return `${currentMonth} ${currentYear}`;
            }

            $(document).ready(function() {
                $('#calendarTitle').text(selectedMonth);
            });

            const monthFilterInput = document.getElementById('monthEvent');

            const flatpickrInstance = flatpickr(monthFilterInput, {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true,
                        dateFormat: "M Y",
                        altFormat: "M Y",
                        theme: "light"
                    })
                ],
                onChange: function(selectedDates, dateStr, instance) {
                    const selectedDate = selectedDates[0];
                    selectedMonth = instance.formatDate(selectedDate, "M Y");
                    $('#calendarTitle').text(selectedMonth);
                    console.log("ini hasil dari filter bulan", selectedMonth);
                    getlistInvoice();
                }
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
                    url: "{{ route('exportPdf') }}",
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
                    url: "{{ route('detailBuktiPembayaran') }}",
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
                                        url: "{{ route('completePayment') }}",
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
                                url: "{{ route('completePayment') }}",
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
                            url: "{{ route('deleteInvoice') }}",
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
        });
    </script>
@endsection
