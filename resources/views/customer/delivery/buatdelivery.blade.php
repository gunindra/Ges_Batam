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


<!-- Modal -->
<div class="modal fade" id="inputResiModal" tabindex="-1" aria-labelledby="inputResiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inputResiModalLabel">Pilih dari List Resi Delivery</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Search Input -->
                <input type="text" id="txSearch" class="form-control mb-3"
                    placeholder="Cari No. Resi atau Nama Pembeli">

                <div class="d-flex align-items-center">
                    <input type="text" class="form-control" id="filter_date" placeholder="Pilih tanggal">
                    <select class="form-control ml-2" id="filterMarking" style="width: 200px;">
                        <option value="" selected disabled>Pilih Marking</option>
                        @foreach ($listMarking as $marking)
                            <option value="{{ $marking->marking }}">{{ $marking->marking }}</option>
                        @endforeach
                    </select>
                    <select class="form-control ml-2" id="filterNoDo" style="width: 200px;">
                        <option value="" selected disabled>Pilih NoDo</option>
                        @foreach ($listNoDo as $nodo)
                            <option value="{{ $nodo->no_do }}">{{ $nodo->no_do }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                        onclick="resetModalFilters()">
                        Reset
                    </button>
                </div>
                <div id="containerBuatDelivery" class="ml-2">

                </div>

                <!-- Table with Checkboxes -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmSelection">Pilih</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- Modal -->
<div class="modal fade" id="inputResiPickupModal" tabindex="-1" aria-labelledby="inputResiPickupModalLabel"
    aria-hidden="true">
    <div class="modal-dialog  modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inputResiPickupModalLabel">Pilih dari List Resi Pick up</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Search Input -->
                <input type="text" id="txSearchPickup" class="form-control mb-3"
                    placeholder="Cari No. Resi atau Nama Pembeli">
                <div class="d-flex align-items-center">
                    <input type="text" class="form-control" id="filter_date_pickup" placeholder="Pilih tanggal">
                    <select class="form-control ml-2" id="filterMarkingPickup" style="width: 200px;">
                        <option value="" selected disabled>Pilih Marking</option>
                        @foreach ($listMarkingPickup as $marking)
                            <option value="{{ $marking->marking }}">{{ $marking->marking }}</option>
                        @endforeach
                    </select>
                    <select class="form-control ml-2" id="filterNoDoPickup" style="width: 200px;">
                        <option value="" selected disabled>Pilih NoDo</option>
                        @foreach ($listNoDoPickup as $nodo)
                            <option value="{{ $nodo->no_do }}">{{ $nodo->no_do }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                        onclick="resetModalFiltersPickup()">
                        Reset
                    </button>
                </div>
                    <div id="containerBuatPickup" class="">

                    </div>
                    <!-- Table with Checkboxes -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="confirmSelectionPickup">Pilih</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Buat Delivery</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Customer</li>
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
                                    <input type="text" class="form-control" id="tanggal_delivery"
                                        placeholder="Pilih tanggal delivery">
                                    <div id="tanggalPickupError" class="text-danger mt-1 d-none">Tanggal delivery tidak
                                        boleh kosong</div>
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

                            <!-- Button to Open Modal -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Input Invoice Menggunakan</label>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#inputResiModal">
                                        Pilih dari List
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-3" id="resi_container">
                                <div class="col-md-6">
                                    <!-- No. Resi -->
                                    <label for="no_resi" class="form-label fw-bold">No. Invoice</label>
                                    <input type="text" class="form-control" id="no_resi"
                                        placeholder="Masukan No. Invoice">
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button class="btn btn-primary" id="tambah">Tambah</button>
                                </div>

                                <div class="col-md-12 mt-4" id="table_resi_container" style="display: none;">
                                    <h5 class="fw-bold">Daftar Nomor Invoice yang Dimasukkan:</h5>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>No. invoice</th>
                                                <th>Nama Pembeli</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_resi_body">
                                            <!-- Data resi yang valid akan ditampilkan di sini -->
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button class="btn btn-primary px-5 py-2" id="buatDelivery">Buat
                                            Delivery</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="pickupTab" class="tab-pane d-none">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Tanggal Pickup -->
                                    <label for="tanggal_pickup" class="form-label fw-bold">Tanggal Pickup</label>
                                    <input type="text" class="form-control" id="tanggal_pickup"
                                        placeholder="Pilih tanggal pickup">
                                    <div id="tanggalPickupError" class="text-danger mt-1 d-none">Tanggal pickup tidak
                                        boleh kosong</div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Nama Driver (removed for pickup) -->
                                </div>
                            </div>

                            <!-- Button to Open Modal -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Input Invoice Menggunakan</label>
                                    <button class="btn btn-primary" data-toggle="modal"
                                        data-target="#inputResiPickupModal">
                                        Pilih dari List
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-3" id="resi_container">
                                <div class="col-md-6">
                                    <!-- No. Resi -->
                                    <label for="no_resi" class="form-label fw-bold">No. Invoice</label>
                                    <input type="text" class="form-control" id="no_resi_pickup"
                                        placeholder="Masukan No. Invoice">
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button class="btn btn-primary" id="tambah_pickup">Tambah</button>
                                </div>

                                <div class="col-md-12 mt-4" id="table_resi_container_pickup" style="display: none;">
                                    <h5 class="fw-bold">Daftar Nomor Invoice yang Dimasukkan:</h5>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>No. invoice</th>
                                                <th>Nama Pembeli</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_resi_body_pickup">
                                            <!-- Data resi yang valid akan ditampilkan di sini -->
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button class="btn btn-primary px-5 py-2" id="buatPickup">Buat Pickup</button>
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
        $(document).ready(function () {
            $('.nav-links').on('click', function (e) {
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
            const txtSearch = $('#txSearch').val();
            const filterMarking = $('#filterMarking').val();
            const filterNoDo = $('#filterNoDo').val();

            $.ajax({
                url: "{{ route('getlistTableBuatDelivery') }}",
                method: "GET",
                data: {
                    txSearch: txtSearch,
                    filter_date: filterDate,
                    marking: filterMarking,
                    no_do: filterNoDo
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

                    $('#select_all').on('click', function () {

                        var rows = $('#datatable_resi tbody tr');

                        $('input[type="checkbox"]:visible', rows).prop('checked', this.checked);
                    });

                    $('#datatable_resi tbody').on('click', 'input[type="checkbox"]', function () {
                        var totalCheckboxes = $('#datatable_resi tbody input[type="checkbox"]:visible')
                            .length;
                        var checkedCheckboxes = $(
                            '#datatable_resi tbody input[type="checkbox"]:visible:checked').length;

                        $('#select_all').prop('checked', totalCheckboxes === checkedCheckboxes);
                    });
                })
        }
        getlistTableBuatDelivery();

        $('#filterMarking').change(function () {
            getlistTableBuatDelivery();
        });
        $('#filterNoDo').change(function () {
            getlistTableBuatDelivery();
        });

        $('.select2singgle').select2({
            width: 'resolve'
        });
        function resetModalFilters() {
            $('#txSearch').val('');

            $('#filterMarking').prop('selectedIndex', 0);

            $('#filterNoDo').prop('selectedIndex', 0);
            getlistTableBuatDelivery();
        }

        var today = new Date();
        $('#tanggal_delivery').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        }).datepicker('setDate', today);

        $('input[id="filter_date"]').daterangepicker({
            locale: {
                format: 'DD MMMM YYYY' // Format: 'dd MM yyyy'
            },

            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
            opens: 'left'
        });

        $('#txSearch').keyup(function (e) {
            var inputText = $(this).val();
            if (inputText.length >= 1 || inputText.length == 0) {
                getlistTableBuatDelivery();
            }
        })


        $('input[name="input_resi"]').on('change', function () {
            if ($(this).val() === 'list') {
                $('#datatable_resi_wrapper').removeClass('d-none');
                $('#resi_container').addClass('d-none');
            } else {
                $('#datatable_resi_wrapper').addClass('d-none');
                $('#resi_container').removeClass('d-none');
            }
        });

        $('#datatable_resi tbody').on('change', 'input[type="checkbox"]', function () {
            if (!this.checked) {
                var el = $('#select_all').get(0);
                if (el && el.checked && ('indeterminate' in el)) {
                    el.indeterminate = true;
                }
            }
        });
        let enteredResis = [];

        function updateRowNumbers() {
            $('#table_resi_body tr').each(function (index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        $('#tambah').on('click', function (e) {
            e.preventDefault();

            var noInvoice = $('#no_resi').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (noInvoice === '') {
                showMessage('error', 'Mohon periksa input yang kosong');
                return;
            }
            if (enteredResis.includes(noInvoice)) {
                showMessage('error', 'Nomor invoice sudah ada.');
                return;
            }

            $.ajax({
                url: "{{ route('cekResi') }}",
                method: 'POST',
                data: {
                    _token: csrfToken,
                    no_invoice: noInvoice,
                },
                success: function (response) {
                    if (response.success) {
                        showMessage('success', response.message);
                        const data = response.data;
                        $('#table_resi_container').show();
                        var tableRow = `
                    <tr>
                        <td></td> <!-- Tempat untuk nomor urut -->
                        <td>${data.no_invoice}</td>
                        <td>${data.nama_pembeli}</td>
                        <td>${data.status_name}</td>
                        <td>
                            <button class="btn btn-danger btn-sm remove-row" data-invoice="${noInvoice}">Remove</button>
                        </td>
                    </tr>
                `;
                        $('#table_resi_body').append(tableRow);
                        enteredResis.push(String(noInvoice));
                        $('#no_resi').val('');

                        // Update nomor urut setelah menambah baris baru
                        updateRowNumbers();
                    } else {
                        showMessage('error', response.message);
                    }
                },
                error: function (xhr, status, error) {
                    showMessage('error', 'Terjadi kesalahan: ' + error);
                }
            });
        });

        $('#confirmSelection').on('click', function () {
            var selectedInvoices = [];
            $('#datatable_resi tbody input[type="checkbox"]:checked').each(function () {
                selectedInvoices.push($(this).val());
            });

            if (selectedInvoices.length === 0) {
                showMessage('error', 'Tidak ada nomor invoice yang dipilih.');
                return;
            }

            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('cekResiBulk') }}",
                method: 'POST',
                data: {
                    _token: csrfToken,
                    no_invoices: selectedInvoices
                },
                success: function (response) {
                    if (response.success) {
                        showMessage('success', response.message);

                        const invoices = response.data;
                        $('#table_resi_container').show();

                        invoices.forEach(data => {
                            if (enteredResis.includes(data.no_invoice)) {
                                showMessage('error', 'Nomor invoice ' + data.no_invoice +
                                    ' sudah ada.');
                                return;
                            }

                            var tableRow = `
                        <tr>
                            <td></td> <!-- Tempat untuk nomor urut -->
                            <td>${data.no_invoice}</td>
                            <td>${data.data.nama_pembeli}</td>
                            <td>${data.data.status_name}</td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-row" data-invoice="${data.no_invoice}">Remove</button>
                            </td>
                        </tr>
                    `;

                            $('#table_resi_body').append(tableRow);
                            enteredResis.push(String(data
                                .no_invoice)); // Add the invoice number to the array
                        });

                        updateRowNumbers(); // Update nomor urut
                    } else {
                        showMessage('error', response.message);
                    }
                },
                error: function (xhr, status, error) {
                    showMessage('error', 'Terjadi kesalahan: ' + error);
                }
            });

            $('#inputResiModal').modal('hide');
        });



        // Event listener untuk tombol "Remove"
        $(document).on('click', '.remove-row', function () {
            var noInvoice = String($(this).data('invoice')); // Konversi ke string
            var rowIndex = enteredResis.indexOf(noInvoice);

            if (rowIndex !== -1) {
                enteredResis.splice(rowIndex, 1);
            }

            $(this).closest('tr').remove();
            updateRowNumbers();
            console.log(enteredResis);
        });

        $('#buatDelivery').on('click', function (e) {
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

            $('#table_resi_body tr').each(function () {
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
                success: function (response) {
                    Swal.close();
                    if (response.success) {
                        showMessage("success", "Delivery berhasil dibuat!").then(() => {
                            location.reload();
                        });
                    } else {
                        showMessage('error', 'Gagal membuat delivery: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    Swal.close();
                    showMessage('error', 'Terjadi kesalahan: ' + error);
                }
            });
        });



        $('#filter_date').on('change', function () {
            getlistTableBuatDelivery();
        });

        $('#filter_date').trigger('change');


        $('#buatDeliveryTable').on('click', function (e) {
            var selectedNoResi = [];

            // Ambil nilai dari checkbox yang dicentang
            $('input.checkbox_resi:checked').each(function () {
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
                        success: function (response) {
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
                        error: function (xhr, status, error) {
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

            const filterDatePickup = $('#filter_date_pickup').val();
            const txtSearchPickup = $('#txSearchPickup').val();
            const filterMarkingPickup = $('#filterMarkingPickup').val();
            const filterNoDoPickup = $('#filterNoDoPickup').val();

            $.ajax({
                url: "{{ route('getlistTableBuatPickup') }}",
                method: "GET",
                data: {
                    txSearch: txtSearchPickup,
                    filter_date: filterDatePickup,
                    marking: filterMarkingPickup,
                    no_do: filterNoDoPickup
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

                    $('#select_all_pickup').on('click', function () {
                        var rows = $('#datatable_resi_pickup tbody tr');

                        $('input[type="checkbox"]:visible', rows).prop('checked', this.checked);
                    });

                    $('#datatable_resi_pickup tbody').on('click', 'input[type="checkbox"]', function () {
                        var totalCheckboxes = $(
                            '#datatable_resi_pickup tbody input[type="checkbox"]:visible').length;
                        var checkedCheckboxes = $(
                            '#datatable_resi_pickup tbody input[type="checkbox"]:visible:checked')
                            .length;

                        $('#select_all_pickup').prop('checked', totalCheckboxes === checkedCheckboxes);
                    });
                })
        }

        getlistTableBuatPickup();

        $('#tanggal_pickup').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        }).datepicker('setDate', today);

        $('#filter_date_pickup').daterangepicker({
            locale: {
                format: 'DD MMMM YYYY'
            },
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
            opens: 'left'
        });

        $('#filterMarkingPickup').change(function () {
            getlistTableBuatPickup();
        });
        $('#filterNoDoPickup').change(function () {
            getlistTableBuatPickup();
        });

        function resetModalFiltersPickup() {
            $('#txSearch').val('');

            $('#filterMarkingPickup').prop('selectedIndex', 0);

            $('#filterNoDoPickup').prop('selectedIndex', 0);
            getlistTableBuatPickup();
        }

        $('#txSearchPickup').keyup(function (e) {
            var inputText = $(this).val();
            if (inputText.length >= 1 || inputText.length == 0) {
                getlistTableBuatPickup();
            }
        });

        $('#filter_date_pickup').on('change', function () {
            getlistTableBuatPickup();
        });
        $('#filter_date_pickup').trigger('change');

        let enteredResisPickup = [];

        function updateRowNumbersPickup() {
            $('#table_resi_body_pickup tr').each(function (index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        $('#tambah_pickup').on('click', function (e) {
            e.preventDefault();

            var noInvoicePickup = $('#no_resi_pickup').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (noInvoicePickup === '') {
                showMessage('error', 'Mohon periksa input yang kosong');
                return;
            }
            if (enteredResisPickup.includes(noInvoicePickup)) {
                showMessage('error', 'Nomor invoice sudah ada.');
                return;
            }

            $.ajax({
                url: "{{ route('cekResiPickup') }}",
                method: 'POST',
                data: {
                    _token: csrfToken,
                    no_invoice: noInvoicePickup,
                },
                success: function (response) {
                    if (response.success) {
                        showMessage('success', response.message);
                        const data = response.data;
                        $('#table_resi_container_pickup').show();
                        var tableRow = `
                        <tr>
                            <td></td> <!-- Tempat untuk nomor urut -->
                            <td>${data.no_invoice}</td>
                            <td>${data.nama_pembeli}</td>
                            <td>${data.status_name}</td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-row-pickup" data-invoice="${noInvoicePickup}">Remove</button>
                            </td>
                        </tr>
                    `;
                        $('#table_resi_body_pickup').append(tableRow);
                        enteredResisPickup.push(String(noInvoicePickup));
                        $('#no_resi_pickup').val('');

                        updateRowNumbersPickup();
                    } else {
                        showMessage('error', response.message);
                    }
                },
                error: function (xhr, status, error) {
                    showMessage('error', 'Terjadi kesalahan: ' + error);
                }
            });
        });

        $('#confirmSelectionPickup').on('click', function () {
            var selectedInvoicesPickup = [];
            $('#datatable_resi_pickup tbody input[type="checkbox"]:checked').each(function () {
                selectedInvoicesPickup.push($(this).val());
            });

            if (selectedInvoicesPickup.length === 0) {
                showMessage('error', 'Tidak ada nomor invoice yang dipilih.');
                return;
            }
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ route('cekResiBulkPickup') }}",
                method: 'POST',
                data: {
                    _token: csrfToken,
                    no_invoices: selectedInvoicesPickup
                },
                success: function (response) {
                    if (response.success) {
                        showMessage('success', response.message);
                        const invoices = response.data;
                        $('#table_resi_container_pickup').show();
                        invoices.forEach(data => {
                            if (enteredResisPickup.includes(data.no_invoice)) {
                                showMessage('error', 'Nomor invoice ' + data.no_invoice +
                                    ' sudah ada.');
                                return;
                            }
                            var tableRow = `
                            <tr>
                                <td></td> <!-- Tempat untuk nomor urut -->
                                <td>${data.no_invoice}</td>
                                <td>${data.data.nama_pembeli}</td>
                                <td>${data.data.status_name}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm remove-row-pickup" data-invoice="${data.no_invoice}">Remove</button>
                                </td>
                            </tr>
                        `;
                            $('#table_resi_body_pickup').append(tableRow);
                            enteredResisPickup.push(String(data.no_invoice));
                        });

                        updateRowNumbersPickup();
                    } else {
                        showMessage('error', response.message);
                    }
                },
                error: function (xhr, status, error) {
                    showMessage('error', 'Terjadi kesalahan: ' + error);
                }
            });

            $('#inputResiPickupModal').modal('hide');
        });

        $(document).on('click', '.remove-row-pickup', function () {
            var noInvoicePickup = String($(this).data('invoice'));
            var rowIndex = enteredResisPickup.indexOf(noInvoicePickup);

            if (rowIndex !== -1) {
                enteredResisPickup.splice(rowIndex, 1);
            }

            $(this).closest('tr').remove();
            updateRowNumbersPickup();
        });

        $('#buatPickup').on('click', function (e) {
            e.preventDefault();

            var noResiPickup = [];
            var tanggalPickup = $('#tanggal_pickup').val();

            if (tanggalPickup === '') {
                $('#tanggalPickupError').removeClass('d-none');
                showMessage('error', 'Tanggal pickup tidak boleh kosong.');
                return;
            } else {
                $('#tanggalPickupError').addClass('d-none');
            }

            $('#table_resi_body_pickup tr').each(function () {
                var noResiItem = $(this).find('td').eq(1).text();
                noResiPickup.push(noResiItem);
            });

            if (noResiPickup.length === 0) {
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
                    resi_list: noResiPickup,
                    tanggal: tanggalPickup
                },
                success: function (response) {
                    Swal.close();
                    if (response.success) {
                        showMessage("success", "Pickup berhasil dibuat!").then(() => {
                            location.reload();
                        });
                    } else {
                        showMessage('error', 'Gagal membuat pickup: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    Swal.close();
                    showMessage('error', 'Terjadi kesalahan: ' + error);
                }
            });
        });
    </script>

    @endsection
