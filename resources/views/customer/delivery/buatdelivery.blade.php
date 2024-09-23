@extends('layout.main')

@section('title', 'Buat Delivery')

@section('main')

    <style>
        .select2-container--default .select2-selection--single {
            height: 40px;
            border: 1px solid #d1d3e2;
            border-radius: 0.25rem;
            padding: 6px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 27px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
    </style>

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Buat Delivery</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Costumer</li>
                <li class="breadcrumb-item"><a href="{{ route('delivery') }}">Delivery</a></li>
                <li class="breadcrumb-item active" aria-current="page">Buat Delivery</li>
            </ol>
        </div>

        <a class="btn btn-primary mb-3" href="{{ route('delivery') }}">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>


        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-links nav-link active" aria-current="page" href="#" data-tab="deliveryTab">Delivery</a>
            </li>
            <li class="nav-item">
                <a class="nav-links nav-link" href="#" data-tab="pickupTab">Pick Up</a>
            </li>
        </ul>
        <div id="deliveryTab" class="tab-pane fade show active">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Tanggal Delivery -->
                                    <label for="tanggal_delivery" class="form-label fw-bold">Tanggal Delivery</label>
                                    <input type="text" class="form-control" id="tanggal_delivery" value=""
                                        placeholder="Pilih tanggal delivery">
                                    <div id="tanggalPickupError" class="text-danger mt-1 d-none">Tanggal delivery tidak
                                        boleh
                                        kosong</div>
                                </div>

                                <div class="col-md-6">
                                    <!-- Nama Driver -->
                                    <label for="driver" class="form-label fw-bold">Nama Supir</label>
                                    <select class="form-control select2singgle" id="driver" style="width: 100%">
                                        <option value="" selected disabled>Pilih Driver</option>
                                        @foreach ($listSupir as $supir)
                                            <option value="{{ $supir->id }}">
                                                {{ $supir->nama_supir }} - {{ $supir->no_wa }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="driverError" class="text-danger mt-1 d-none">Silahkan pilih driver</div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <!-- Input Resi -->
                                    <label class="form-label fw-bold">Input Resi Menggunakan</label>
                                    <div class="d-flex">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="input_resi" id="scan_resi"
                                                value="scan" checked>
                                            <label class="form-check-label" for="scan_resi">Scan Resi</label>
                                        </div>
                                        <div class="form-check ml-3">
                                            <input class="form-check-input" type="radio" name="input_resi"
                                                id="list_batam_sortir" value="list">
                                            <label class="form-check-label" for="list_batam_sortir">List Batam /
                                                Sortir</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3" id="resi_container">
                                <div class="col-md-6">
                                    <!-- No. Resi -->
                                    <label for="no_resi" class="form-label fw-bold">No. Resi</label>
                                    <input type="text" class="form-control" id="no_resi"
                                        placeholder="Masukan atau Scan No. Resi">
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button class="btn btn-primary" id="tambah">Tambah</button>
                                </div>

                                <div class="col-md-12 mt-4" id="table_resi_container" style="display: none;">
                                    <h5 class="fw-bold">Daftar Nomor Resi yang Dimasukkan:</h5>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>No. Resi</th>
                                                <th>Nama Pembeli</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_resi_body">
                                            <!-- Data resi yang valid akan ditampilkan di sini -->
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button class="btn btn-primary px-5 py-2" id="buatDelivery">Buat Delivery</button>
                                    </div>
                                </div>
                            </div>
                            <!-- DataTable for List Batam / Sortir -->
                            <div class="row mt-3" id="datatable_resi_wrapper">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="filter_date">Filter by Date:</label>
                                            <input type="input" id="filter_date" class="form-control">
                                        </div>
                                    </div>
                                    <div id="containerBuatDelivery">
                                        {{-- <table id="datatable_resi" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="select_all"></th>
                                                    <th>No Resi</th>
                                                    <th>Tanggal</th>
                                                    <th>Customer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($listdataTable as $data)
                                                    <tr>
                                                        <td><input type="checkbox" class="checkbox_resi"
                                                                value="{{ $data->no_resi }}"></td>
                                                        <td>{{ $data->no_resi }}</td>
                                                        <td>{{ $data->tanggal_bayar }}</td>
                                                        <td>{{ $data->pembeli }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table> --}}
                                    </div>

                                    <div class="d-flex justify-content-end mt-3">
                                        <button class="btn btn-primary px-5 py-2" id="buatDeliveryTable">Buat
                                            Delivery</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="pickupTab" class="tab-pane fade">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="tanggal_pickup" class="form-label fw-bold">Tanggal Pickup</label>
                                    <input type="text" class="form-control" id="tanggal_pickup" value=""
                                        placeholder="Pilih tanggal pickup">
                                    <div id="tanggalPickupError" class="text-danger mt-1 d-none">Tanggal pickup tidak
                                        boleh kosong</div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">

                                    <label class="form-label fw-bold">Input Resi Menggunakan</label>
                                    <div class="d-flex">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="input_resi_pickup"
                                                id="scan_resi_pickup" value="scan" checked>
                                            <label class="form-check-label" for="scan_resi_pickup">Scan Resi</label>
                                        </div>
                                        <div class="form-check ml-3">
                                            <input class="form-check-input" type="radio" name="input_resi_pickup"
                                                id="list_batam_sortir_pickup" value="list">
                                            <label class="form-check-label" for="list_batam_sortir_pickup">List Batam /
                                                Sortir</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3" id="resi_container_pickup">
                                <div class="col-md-6">

                                    <label for="no_resi_pickup" class="form-label fw-bold">No. Resi</label>
                                    <input type="text" class="form-control" id="no_resi_pickup"
                                        placeholder="Masukan atau Scan No. Resi">
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button class="btn btn-primary" id="tambah_pickup">Tambah</button>
                                </div>

                                <div class="col-md-12 mt-4" id="table_resi_container_pickup" style="display: none;">
                                    <h5 class="fw-bold">Daftar Nomor Resi yang Dimasukkan:</h5>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>No. Resi</th>
                                                <th>Nama Pembeli</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_resi_body_pickup">

                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button class="btn btn-primary px-5 py-2" id="buatPickup">Buat Pickup</button>
                                    </div>
                                </div>
                            </div>

                            <!-- DataTable for List Batam / Sortir (Pickup) -->
                            <div class="row mt-3" id="datatable_resi_wrapper_pickup">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="filter_date_pickup">Filter by Date:</label>
                                            <input type="input" id="filter_date_pickup" class="form-control">
                                        </div>
                                    </div>
                                    <div id="containerBuatPickup">
                                        {{-- <table id="datatable_resi_pickup" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select_all_pickup"></th>
                        <th>No Resi</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listdataTable as $data)
                        <tr>
                            <td><input type="checkbox" class="checkbox_resi_pickup"
                                    value="{{ $data->no_resi }}"></td>
                            <td>{{ $data->no_resi }}</td>
                            <td>{{ $data->tanggal_bayar }}</td>
                            <td>{{ $data->pembeli }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table> --}}
                                    </div>

                                    <div class="d-flex justify-content-end mt-3">
                                        <button class="btn btn-primary px-5 py-2" id="buatPickupTable">Buat
                                            Pickup</button>
                                    </div>
                                </div>
                            </div>

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
            $('.nav-links').on('click', function(e) {
                e.preventDefault();
                var tab = $(this).data('tab');

                $('.tab-pane').removeClass('show active').addClass('d-none');

                $('#' + tab).removeClass('d-none').addClass('show active');

                $('.nav-links').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
    <script>
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

        const getlistTableBuatDelivery = () => {

            const filterDate = $('#filter_date').val();

            $.ajax({
                    url: "{{ route('getlistTableBuatDelivery') }}",
                    method: "GET",
                    data: {
                        filter_date: filterDate
                    },
                    beforeSend: () => {
                        $('#containerBuatDelivery').html(loadSpin)
                    }
                })
                .done(res => {
                    $('#containerBuatDelivery').html(res)
                    $('#datatable_resi').DataTable({
                        paging: false,
                        searching: false,
                        lengthChange: false,
                        info: false,
                        "bSort": true,
                        "aaSorting": [],
                        // pageLength: 7,
                        responsive: true,
                        language: {
                            search: ""
                        },
                        columnDefs: [{
                            targets: 0,
                            orderable: false
                        }]
                    });

                    $('#select_all').on('click', function() {

                        var rows = $('#datatable_resi tbody tr');

                        $('input[type="checkbox"]:visible', rows).prop('checked', this.checked);
                    });

                    $('#datatable_resi tbody').on('click', 'input[type="checkbox"]', function() {
                        var totalCheckboxes = $('#datatable_resi tbody input[type="checkbox"]:visible')
                            .length;
                        var checkedCheckboxes = $(
                            '#datatable_resi tbody input[type="checkbox"]:visible:checked').length;

                        $('#select_all').prop('checked', totalCheckboxes === checkedCheckboxes);
                    });
                })
        }
        getlistTableBuatDelivery();

        $('.select2singgle').select2({
            width: 'resolve'
        });

        var today = new Date();
        $('#tanggal_delivery, #filter_date').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        }).datepicker('setDate', today);


        $('input[name="input_resi"]').on('change', function() {
            if ($(this).val() === 'list') {
                $('#datatable_resi_wrapper').removeClass('d-none');
                $('#resi_container').addClass('d-none');
            } else {
                $('#datatable_resi_wrapper').addClass('d-none');
                $('#resi_container').removeClass('d-none');
            }
        });

        $('#datatable_resi tbody').on('change', 'input[type="checkbox"]', function() {
            if (!this.checked) {
                var el = $('#select_all').get(0);
                if (el && el.checked && ('indeterminate' in el)) {
                    el.indeterminate = true;
                }
            }
        });
        let enteredResis = [];

        $('#tambah').on('click', function(e) {
            e.preventDefault();

            var noResi = $('#no_resi').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (noResi === '') {
                showMessage('error', 'Mohon periksa input yang kosong');
                return;
            }
            if (enteredResis.includes(noResi)) {
                showMessage('error', 'Nomor resi sudah ada.');
                return;
            }

            $.ajax({
                url: "{{ route('cekResi') }}",
                method: 'POST',
                data: {
                    _token: csrfToken,
                    no_resi: noResi,
                },
                success: function(response) {
                    if (response.success) {
                        showMessage('success', response.message);
                        const data = response.data;
                        $('#table_resi_container').show();
                        var rowCount = $('#table_resi_body tr').length + 1;
                        var tableRow = `
                <tr>
                    <td>${rowCount}</td>
                    <td>${data.no_resi}</td>
                    <td>${data.nama_pembeli}</td>
                    <td>${data.status_name}</td>
                </tr>
            `;
                        $('#table_resi_body').append(tableRow);
                        enteredResis.push(noResi);
                        $('#no_resi').val('');



                    } else {
                        showMessage('error', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showMessage('error', 'Terjadi kesalahan: ' + error);
                }
            });
        });

        $('#buatDelivery').on('click', function(e) {
            e.preventDefault();

            var noResi = [];
            var tanggal_delivery = $('#tanggal_delivery').val();
            var driver = $('#driver').val();


            if (tanggal_delivery === '') {
                $('#tanggalPickupError').removeClass('d-none');
                showMessage('error', 'Tanggal delivery tidak boleh kosong.');
                return;
            } else {
                $('#tanggalPickupError').addClass('d-none');
            }

            if (driver === null) {
                $('#driverError').removeClass('d-none');
                showMessage('error', 'Silahkan pilih driver.');
                return;
            } else {
                $('#driverError').addClass('d-none');
            }

            $('#table_resi_body tr').each(function() {
                var noResiItem = $(this).find('td').eq(1).text();
                noResi.push(noResiItem);
            });


            if (noResi.length === 0) {
                showMessage('error', 'Tidak ada resi yang dimasukkan.');
                return;
            }

            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Harap menunggu hingga proses selesai',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('buatDelivery') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    resi_list: noResi,
                    tanggal: tanggal_delivery,
                    driver_id: driver
                },
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        showMessage("success", "Delivery berhasil dibuat!").then(() => {
                            location.reload();
                        });
                    } else {
                        showMessage('error', 'Gagal membuat delivery: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    showMessage('error', 'Terjadi kesalahan: ' + error);
                }
            });
        });


        $('#filter_date').on('change', function() {
            getlistTableBuatDelivery();
        });

        $('#filter_date').trigger('change');


        $('#buatDeliveryTable').on('click', function(e) {
            var selectedNoResi = [];

            // Ambil nilai dari checkbox yang dicentang
            $('input.checkbox_resi:checked').each(function() {
                selectedNoResi.push($(this).val());
            });

            var tanggal_delivery = $('#tanggal_delivery').val();
            var driver = $('#driver').val();


            if (tanggal_delivery === '') {
                $('#tanggalPickupError').removeClass('d-none');
                showMessage('error', 'Tanggal delivery tidak boleh kosong.');
                return;
            } else {
                $('#tanggalPickupError').addClass('d-none');
            }

            if (driver === null) {
                $('#driverError').removeClass('d-none');
                showMessage('error', 'Silahkan pilih driver.');
                return;
            } else {
                $('#driverError').addClass('d-none');
            }

            if (selectedNoResi.length === 0) {
                showMessage('error', 'Silakan pilih No Resi yang ingin di-delivery.');
                return;
            }


            Swal.fire({
                title: 'Apakah kamu yakin ingin Delivery invoice ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5D87FF',
                cancelButtonColor: '#49BEFF',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sedang memproses...',
                        text: 'Harap menunggu hingga proses selesai',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "{{ route('buatDelivery') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            resi_list: selectedNoResi,
                            tanggal: tanggal_delivery,
                            driver_id: driver
                        },
                        success: function(response) {
                            Swal.close();
                            if (response.success) {
                                showMessage("success", "Delivery berhasil dibuat!").then(() => {
                                    location.reload();
                                });
                            } else {
                                showMessage('error', 'Gagal membuat delivery: ' + response
                                    .message);
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.close();
                            showMessage('error', 'Terjadi kesalahan: ' + error);
                        }
                    });
                }
            });
        });
    </script>

    <script>
        const getlistTableBuatPickup = () => {
            const filterDate = $('#filter_date_pickup').val();

            $.ajax({
                    url: "{{ route('getlistTableBuatPickup') }}",
                    method: "GET",
                    data: {
                        filter_date: filterDate
                    },
                    beforeSend: () => {
                        $('#containerBuatPickup').html(loadSpin)
                    }
                })
                .done(res => {
                    $('#containerBuatPickup').html(res)
                    $('#datatable_resi_pickup').DataTable({
                        paging: false,
                        searching: false,
                        lengthChange: false,
                        info: false,
                        "bSort": true,
                        "aaSorting": [],
                        responsive: true,
                        language: {
                            search: ""
                        },
                        columnDefs: [{
                            targets: 0,
                            orderable: false
                        }]
                    });

                    $('#select_all_pickup').on('click', function() {
                        var rows = $('#datatable_resi_pickup tbody tr');
                        $('input[type="checkbox"]:visible', rows).prop('checked', this.checked);
                    });

                    $('#datatable_resi_pickup tbody').on('click', 'input[type="checkbox"]', function() {
                        var totalCheckboxes = $(
                            '#datatable_resi_pickup tbody input[type="checkbox"]:visible').length;
                        var checkedCheckboxes = $(
                                '#datatable_resi_pickup tbody input[type="checkbox"]:visible:checked')
                            .length;
                        $('#select_all_pickup').prop('checked', totalCheckboxes === checkedCheckboxes);
                    });
                })
        }


        var today = new Date();
        $('#tanggal_pickup, #filter_date_pickup').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        }).datepicker('setDate', today);

        $('#filter_date_pickup').on('change', function() {
            getlistTableBuatPickup();
        });

        $('#filter_date_pickup').trigger('change');


        $('input[name="input_resi_pickup"]').on('change', function() {
            if ($(this).val() === 'list') {
                $('#datatable_resi_wrapper_pickup').removeClass('d-none');
                $('#resi_container_pickup').addClass('d-none');
            } else {
                $('#datatable_resi_wrapper_pickup').addClass('d-none');
                $('#resi_container_pickup').removeClass('d-none');
            }
        });

        let enteredResisPickup = [];

        $('#tambah_pickup').on('click', function(e) {
            e.preventDefault();

            var noResi = $('#no_resi_pickup').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (noResi === '') {
                showMessage('error', 'Mohon periksa input yang kosong');
                return;
            }
            if (enteredResisPickup.includes(noResi)) {
                showMessage('error', 'Nomor resi sudah ada.');
                return;
            }

            $.ajax({
                url: "{{ route('cekResiPickup') }}",
                method: 'POST',
                data: {
                    _token: csrfToken,
                    no_resi: noResi,
                },
                success: function(response) {
                    if (response.success) {
                        showMessage('success', response.message);
                        const data = response.data;
                        $('#table_resi_container_pickup').show();
                        var rowCount = $('#table_resi_body_pickup tr').length + 1;
                        var tableRow = `
                <tr>
                    <td>${rowCount}</td>
                    <td>${data.no_resi}</td>
                    <td>${data.nama_pembeli}</td>
                    <td>${data.status_name}</td>
                </tr>
            `;
                        $('#table_resi_body_pickup').append(tableRow);
                        enteredResisPickup.push(noResi);
                        $('#no_resi_pickup').val('');

                    } else {
                        showMessage('error', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showMessage('error', 'Terjadi kesalahan: ' +
                        error);
                }
            });
        });

        $('#buatPickup').on('click', function(e) {
            e.preventDefault();

            var noResi = [];
            var tanggal_pickup = $('#tanggal_pickup').val();

            if (tanggal_pickup === '') {
                $('#tanggalPickupError').removeClass('d-none');
                showMessage('error', 'Tanggal delivery tidak boleh kosong.');
                return;
            } else {
                $('#tanggalPickupError').addClass('d-none');
            }

            $('#table_resi_body_pickup tr').each(function() {
                var noResiItem = $(this).find('td').eq(1).text();
                noResi.push(noResiItem);
            });


            if (noResi.length === 0) {
                showMessage('error', 'Tidak ada resi yang dimasukkan.');
                return;
            }

            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Harap menunggu hingga proses selesai',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('buatPickup') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    resi_list: noResi,
                    tanggal: tanggal_pickup,
                },
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        showMessage("success", "Pickup berhasil dibuat!").then(() => {
                            location.reload();
                        });
                    } else {
                        showMessage('error', 'Gagal membuat Pickup: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    showMessage('error', 'Terjadi kesalahan: ' + error);
                }
            });
        });


        $('#buatPickupTable').on('click', function(e) {
            var selectedNoResi = [];

            // Ambil nilai dari checkbox yang dicentang
            $('input.checkbox_resi_pickup:checked').each(function() {
                selectedNoResi.push($(this).val());
            });

            var tanggal_pickup = $('#tanggal_pickup').val();


            if (tanggal_pickup === '') {
                $('#tanggalPickupError').removeClass('d-none');
                showMessage('error', 'Tanggal Pickup tidak boleh kosong.');
                return;
            } else {
                $('#tanggalPickupError').addClass('d-none');
            }

            if (selectedNoResi.length === 0) {
                showMessage('error', 'Silakan pilih No Resi yang ingin di-Pickup.');
                return;
            }

            Swal.fire({
                title: 'Apakah kamu yakin ingin Pick Up invoice ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5D87FF',
                cancelButtonColor: '#49BEFF',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sedang memproses...',
                        text: 'Harap menunggu hingga proses selesai',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "{{ route('buatPickup') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            resi_list: selectedNoResi,
                            tanggal: tanggal_pickup,
                        },
                        success: function(response) {
                            Swal.close();
                            if (response.success) {
                                showMessage("success", "Pickup berhasil dibuat!").then(() => {
                                    location.reload();
                                });
                            } else {
                                showMessage('error', 'Gagal membuat Pickup: ' + response
                                    .message);
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.close();
                            showMessage('error', 'Terjadi kesalahan: ' + error);
                        }
                    });
                }
            });
        });
    </script>
@endsection
