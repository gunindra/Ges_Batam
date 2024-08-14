@extends('layout.main')

@section('title', 'Delivery')

@section('main')


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
                        <div id="err-pengantaranStatus" class="text-danger mt-1">Silakan masukkan file</div>
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
                            {{-- <img src="storage/app/bukti_pembayaran/1.jpg" alt=""> --}}
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    {{-- <button type="button" id="saveFilePengantaran" class="btn btn-primary">Save</button> --}}
                </div>
            </div>
        </div>
    </div>


    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Delivery</h1>
            {{-- <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
      </ol> --}}
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                            {{-- <a class="btn btn-primary" href="" id=""><span
                                    class="pr-2"><i class="fas fa-plus"></i></span>Buat Invoice</a> --}}
                        </div>
                        <div id="containerDelivery" class="table-responsive px-3">
                            {{-- <table class="table align-items-center table-flush table-hover" id="tableDelivery">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No Resi</th>
                                        <th>Tanggal</th>
                                        <th>Driver</th>
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
                                        <td><span class="badge badge-success">Done</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-eye"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i
                                                    class="fas fa-file-upload"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>B0234043</td>
                                        <td>28 Juli 2024</td>
                                        <td>Tandrio</td>
                                        <td>Delivery</td>
                                        <td>Rp. 12.000</td>
                                        <td><span class="badge badge-info">Delivery</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
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
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

        // let selectedMonth = getCurrentMonth();

        const getlistDelivery = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                    url: "{{ route('getlistDelivery') }}",
                    method: "GET",
                    data: {
                        txSearch: txtSearch,
                        // filter: selectedMonth
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


        $(document).on('click', '.btnAcceptPengantaran', function(e) {
            let id = $(this).data('id');

            Swal.fire({
                title: "Apakah Barang Dengan Resi Ini Siap Diatarkan?",
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
                        url: "{{ route('acceptPengantaran') }}",
                        data: {
                            id: id,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status === 'error') {
                                showMessage("error", response.message);
                            } else {
                                showMessage("success", response.message);
                                getlistDelivery();
                            }
                        },
                        error: function() {
                            showMessage("error", "Terjadi kesalahan pada server.");
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btnBuktiPengantaran', function(e) {
            let id = $(this).data('id');

            function validatePengantaran() {
                let isValid = true;
                const fileInput = $('#pengantaranStatus');
                const file = fileInput[0].files[0];

                if (!file) {
                    $('#err-pengantaranStatus').show();
                    isValid = false;
                } else {
                    $('#err-pengantaranStatus').hide();
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
                                url: "{{ route('confirmasiPengantaran') }}",
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

        $(document).on('click', '.btnDetailPengantaran', function(e) {
                e.preventDefault();
                let namafoto = $(this).data('bukti');
                $.ajax({
                    url: "{{ route('detailBuktiPengantaran') }}",
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

    </script>
@endsection
