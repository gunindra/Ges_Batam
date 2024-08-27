@extends('layout.main')

@section('title', 'Invoice Cicilan')

@section('main')


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

    <!-- Modal -->
    <div class="modal fade" id="modalBayarCicilan" tabindex="-1" role="dialog" aria-labelledby="modalBayarCicilanTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBayarCicilanTitle">Detail Pembayaran Cicilan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="jumlahPembayaran" class="form-label fw-bold">Jumlah Pembayaran</label>
                        <input type="text" class="form-control" id="jumlahPembayaran" value=""
                            placeholder="Masukkan jumlah pembayaran">
                        <div id="err-jumlahPembayaran" class="text-danger mt-1 d-none">Silahkan isi jumlah pembayaran yang
                            valid (hanya angka)</div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label fw-bold">Metode Pembayaran</label>
                        <div>
                            <input type="radio" name="metodePembayaran" value="Tunai" id="radioTunai" checked> Tunai
                            <input type="radio" name="metodePembayaran" value="Transfer" id="radioTransfer"
                                class="ml-3"> Transfer
                        </div>
                    </div>
                    <div class="mt-3 d-none" id="inputBuktiPembayaran">
                        <label for="buktiPembayaran" class="form-label fw-bold">Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="buktiPembayaran">
                        <div id="err-buktiPembayaran" class="text-danger mt-1 d-none">Silahkan unggah bukti pembayaran
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveCicilan" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Invoice Cicilan</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Costumer</li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('invoice') }}">Invoice</a></li>
                <li class="breadcrumb-item active" aria-current="page">Invoice Cicilan</li>
            </ol>
        </div>

        <a class="btn btn-primary mb-3" href="{{ route('invoice') }}">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>


        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header"
                        style="border-bottom: 2px solid #dee2e6; padding: 1rem; background-color: #f8f9fa;">
                        <div id="containerHeadCicilan" class="d-flex align-items-center">
                            {{-- <table style="width: 50%; border-collapse: collapse;">
                                <tr>
                                    <td style="width: 50%; padding: 0.5rem;"><strong>No Resi</strong></td>
                                    <td style="padding: 0.5rem;">: GES342324</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; padding: 0.5rem;"><strong>Costumer</strong></td>
                                    <td style="padding: 0.5rem;">: IlhamNur</td>
                                </tr>
                            </table>
                            <table style="width: 50%; border-collapse: collapse;">
                                <tr>
                                    <td style="width: 50%; padding: 0.5rem;"><strong>Status Pembayaran</strong></td>
                                    <td style="padding: 0.5rem;" id="statusPembayaran">: Lunas</td>
                                </tr>
                                <tr>
                                    <td style="width: 50%; padding: 0.5rem;"><strong>Sisa Tagihan</strong></td>
                                    <td style="padding: 0.5rem;" id="sisaTagihan">: Rp.100.000</td>
                                </tr>
                            </table> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex mb-2 justify-content-between align-items-center">
                            <div class="d-flex">
                                <!-- Search -->
                                <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                    class="form-control rounded-3" placeholder="Search">
                                <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                                <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                    onclick="window.location.reload()">
                                    Reset
                                </button>
                            </div>
                            <button class="btn btn-primary" id="btnBuatPembayaran"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Buat Pembayaran</button>
                        </div>
                        <div id="containerCicilan" class="table-responsive px-3">
                            <!-- Tabel isi -->
                            {{-- <table class="table align-items-center table-flush table-hover" id="tableCicilan">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Admin</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Jumlah Pembayaran</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Bukti Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Tandrio</td>
                                        <td>24 Juli 2024</td>
                                        <td>Rp. 10.000</td>
                                        <td>Cash</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>IlhamNur</td>
                                        <td>28 Juli 2024</td>
                                        <td>Rp. 12.000</td>
                                        <td>Transfer</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-eye"></i></a>
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
        var url = window.location.href;
        var segments = url.split('/');
        var id = segments.pop() || segments.pop();

        function formatRupiah(angka) {
            return 'Rp.' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function getlistHeadCicilan() {
            $.ajax({
                url: '{{ route('getlistHeadCicilan') }}',
                method: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    // Mengganti konten dalam containerHeadCicilan
                    $('#containerHeadCicilan').html(`
                <table style="width: 50%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; padding: 0.5rem;"><strong>No Resi</strong></td>
                        <td style="padding: 0.5rem;">: ${response.no_resi}</td>
                    </tr>
                    <tr>
                        <td style="width: 50%; padding: 0.5rem;"><strong>Customer</strong></td>
                        <td style="padding: 0.5rem;">: ${response.nama_pembeli}</td>
                    </tr>
                </table>
                <table style="width: 50%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; padding: 0.5rem;"><strong>Status Pembayaran</strong></td>
                        <td style="padding: 0.5rem;">: ${response.status_pembayaran}</td>
                    </tr>
                    <tr>
                        <td style="width: 50%; padding: 0.5rem;"><strong>Sisa Tagihan</strong></td>
                         <td style="padding: 0.5rem;">: ${formatRupiah(response.cicilan)}</td>
                    </tr>
                </table>
            `);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }



        function getlistCicilan() {
            const txtSearch = $('#txSearch').val();
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();

            const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
            <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
        </div>`;

            $.ajax({
                    url: "{{ route('getlistCicilan') }}",
                    method: "GET",
                    data: {
                        id: id,
                        txSearch: txtSearch,
                        startDate: startDate,
                        endDate: endDate
                    },
                    beforeSend: () => {
                        $('#containerCicilan').html(loadSpin);
                    }
                })
                .done(res => {
                    $('#containerCicilan').html(res);
                    $('#tableCicilan').DataTable({
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
                });
        }

        $(document).ready(function() {
            getlistCicilan();
            getlistHeadCicilan();

            $('#txSearch').keyup(function(e) {
                var inputText = $(this).val();
                if (inputText.length >= 1 || inputText.length == 0) {
                    getlistCicilan();
                }
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
                    var startDate = new Date($('#startDate').val());
                    var endDate = new Date(dateStr);
                    if (endDate < startDate) {
                        showMessage("error",
                            "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                        $('#endDate').val('');
                    }
                }
            });

            $(document).on('click', '#filterTanggal', function(e) {
                $('#modalFilterTanggal').modal('show');
            });

            $('#saveFilterTanggal').click(function() {
                getlistCicilan();
                $('#modalFilterTanggal').modal('hide');
            });

        });

        $(document).on('click', '#btnBuatPembayaran', function() {

            $('#modalBayarCicilan').modal('show');

            $('#jumlahPembayaran').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            let metodePembayaran = $('input[name="metodePembayaran"]:checked').val();

            $('input[name="metodePembayaran"]').on('change', function() {
                metodePembayaran = $('input[name="metodePembayaran"]:checked').val();

                if (metodePembayaran === 'Tunai') {
                    $('#inputBuktiPembayaran').addClass('d-none');
                } else {
                    $('#inputBuktiPembayaran').removeClass('d-none');
                }

                console.log('Metode pembayaran yang dipilih: ' + metodePembayaran);
            });

            $(document).on('click', '#saveCicilan', function() {
                let valid = true;

                if ($('#jumlahPembayaran').val() === '') {
                    $('#err-jumlahPembayaran').removeClass('d-none');
                    valid = false;
                } else {
                    $('#err-jumlahPembayaran').addClass('d-none');
                }

                if (metodePembayaran === 'transfer' && $('#buktiPembayaran').val() === '') {
                    $('#err-buktiPembayaran').removeClass('d-none');
                    valid = false;
                } else {
                    $('#err-buktiPembayaran').addClass('d-none');
                }

                if (valid) {
                    let jumlahPembayaran = $('#jumlahPembayaran').val();
                    let buktiPembayaran = $('#buktiPembayaran')[0].files[0];
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
                            formData.append('jumlahPembayaran', jumlahPembayaran);
                            formData.append('buktiPembayaran', buktiPembayaran);
                            formData.append('metodePembayaran', metodePembayaran);
                            formData.append('_token', csrfToken);

                            $.ajax({
                                type: "POST",
                                url: "{{ route('bayarTagihan') }}",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    if (response.status === 'success') {
                                        showMessage("success", response.message);
                                        getlistCicilan();
                                        getlistHeadCicilan();
                                        $('#modalBayarCicilan').modal('hide');
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

            function formatRupiah(angka) {
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }


        });
    </script>

@endsection
