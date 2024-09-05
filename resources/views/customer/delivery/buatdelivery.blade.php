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

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Tanggal Pickup -->
                                <label for="tanggal_pickup" class="form-label fw-bold">Tanggal Delivery</label>
                                <input type="text" class="form-control" id="tanggal_pickup" value=""
                                    placeholder="Pilih tanggal delivery">
                                <div id="tanggalPickupError" class="text-danger mt-1 d-none">Tanggal delivery tidak boleh
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
                                        <label class="form-check-label" for="list_batam_sortir">List Batam / Sortir</label>
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
                        <div class="row mt-3 d-none" id="datatable_resi_wrapper">
                            <div class="col-md-12">
                                <table id="datatable_resi" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select_all"></th>
                                            <th>No Resi</th>
                                            <th>Tanggal</th>
                                            <th>Customer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Sample Data -->
                                        <tr>
                                            <td><input type="checkbox" class="checkbox_resi"></td>
                                            <td>YT7479399533310</td>
                                            <td>28 September 2024</td>
                                            <td>Tandrio</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="checkbox_resi"></td>
                                            <td>YT7479499773944</td>
                                            <td>26 September 2024</td>
                                            <td>Rodrigues</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="checkbox_resi"></td>
                                            <td>YT7479495766539</td>
                                            <td>04 September 2024</td>
                                            <td>Tandrio</td>
                                        </tr>
                                        <tr>
                                            <td><input type="checkbox" class="checkbox_resi"></td>
                                            <td>YT8987049760157</td>
                                            <td>04 September 2024</td>
                                            <td>Ricard</td>
                                        </tr>
                                    </tbody>
                                </table>
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
        $('.select2singgle').select2({
            width: 'resolve'
        });


        var today = new Date();
        $('#tanggal_pickup').datepicker({
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



        $('#datatable_resi').DataTable({
            searching: false,
            lengthChange: false,
            "bSort": true,
            "aaSorting": [],
            pageLength: 7,
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
            var rows = $('#datatable_resi').DataTable().rows({
                'search': 'applied'
            }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
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
            var tanggalPickup = $('#tanggal_pickup').val();
            var driver = $('#driver').val();


            if (tanggalPickup === '') {
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

            $.ajax({
                url: "{{ route('buatDelivery') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    resi_list: noResi,
                    tanggal_pickup: tanggalPickup,
                    driver_id: driver
                },
                success: function(response) {
                    if (response.success) {
                        showMessage("success", "Delivery berhasil dibuat!").then(() => {
                                location.reload();
                            });
                    } else {
                        showMessage('error', 'Gagal membuat delivery: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    showMessage('error', 'Terjadi kesalahan: ' + error);
                }
            });
        });
    </script>
@endsection
