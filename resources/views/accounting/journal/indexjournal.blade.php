@extends('layout.main')

@section('title', 'Accounting | Journal')

@section('main')

    <style>
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }

        #menuBkk .table,
        #menuBkm .table {
            width: 100% !important;
        }

        footer {
            margin-top: 100px;
        }
    </style>

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Journal</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Accounting</li>
                <li class="breadcrumb-item active" aria-current="page">Journal</li>
            </ol>
        </div>

        <!-- Modal Filter Tanggal -->
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
                                    <label for="Tanggal" class="form-label fw-bold">Pilih Tanggal:</label>
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

        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-links nav-link active" aria-current="page" href="#" data-tab="jurnalUmum">Jurnal
                            Umum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-links nav-link" href="#" data-tab="menuBkk">Menu Jurnal BKK</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-links nav-link" href="#" data-tab="menuBkm">Menu Jurnal BKM</a>
                    </li>
                </ul>
                <div class="tab-content container-fluid mt-3" id="container-wrapper">
                    <!-- Jurnal Umum -->
                    <div id="jurnalUmum" class="tab-pane fade show active">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex mb-2 mr-3 float-right">
                                            <a class="btn btn-primary" href="{{ route('addjournal') }}?code_type=Jurnal">
                                                <span class="pr-2"><i class="fas fa-plus"></i></span>Buat Journal
                                            </a>
                                        </div>
                                        <div class="d-flex mb-4">
                                            <!-- Search -->
                                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                                class="form-control rounded-3" placeholder="Search">
                                            <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                                                <option value="" selected disabled>Pilih Filter</option>
                                                @foreach ($uniqueStatuses as $status)
                                                    <option value="{{ $status->status }}">{{ $status->status }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                                            <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                                onclick="window.location.reload()">Reset</button>
                                        </div>
                                        <div id="containerJournal" class="table-responsive px-3">
                                            <table class="table align-items-center table-flush table-hover"
                                                id="tableJournal">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>No. Journal</th>
                                                        <th>Deskripsi</th>
                                                        <th>Tanggal</th>
                                                        <th>Total</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Table Data -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jurnal BKK -->
                    <div id="menuBkk" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex mb-2 mr-3 float-right">
                                            <a class="btn btn-primary" href="{{ route('addjournal') }}?code_type=BKK"
                                                id="">
                                                <span class="pr-2"><i class="fas fa-plus"></i></span>Buat Journal
                                            </a>
                                        </div>
                                        <div class="d-flex mb-4">
                                            <!-- Search -->
                                            <input id="txSearch2" type="text" style="width: 250px; min-width: 250px;"
                                                class="form-control rounded-3" placeholder="Search">
                                            <select class="form-control ml-2" id="filterStatusBkk" style="width: 200px;">
                                                <option value="" selected disabled>Pilih Filter</option>
                                                @foreach ($uniqueStatuses as $status)
                                                    <option value="{{ $status->status }}">{{ $status->status }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter
                                                Tanggal</button>
                                            <button type="button" class="btn btn-outline-primary ml-2"
                                                id="btnResetDefault" onclick="window.location.reload()">Reset</button>
                                        </div>
                                        <div id="containerBKK" class="table-responsive px-3">
                                            <table class="table align-items-center table-flush table-hover"
                                                id="tableJournalBKK">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>No. Journal</th>
                                                        <th>Deskripsi</th>
                                                        <th>Tanggal</th>
                                                        <th>Total</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Table Data -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jurnal BKM -->
                    <div id="menuBkm" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="d-flex mb-2 mr-3 float-right">
                                            <a class="btn btn-primary" href="{{ route('addjournal') }}?code_type=BKM"
                                                id=""><span class="pr-2"><i
                                                        class="fas fa-plus"></i></span>Buat Journal</a>
                                        </div>
                                        <div class="d-flex mb-4">
                                            <!-- Search -->
                                            <input id="txSearch3" type="text" style="width: 250px; min-width: 250px;"
                                                class="form-control rounded-3" placeholder="Search">
                                            <select class="form-control ml-2" id="filterStatusBkm" style="width: 200px;">
                                                <option value="" selected disabled>Pilih Filter</option>
                                                @foreach ($uniqueStatuses as $status)
                                                    <option value="{{ $status->status }}">{{ $status->status }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter
                                                Tanggal</button>
                                            <button type="button" class="btn btn-outline-primary ml-2"
                                                id="btnResetDefault" onclick="window.location.reload()">Reset</button>
                                        </div>
                                        <div id="containerBKM" class="table-responsive px-3">
                                            <table class="table align-items-center table-flush table-hover"
                                                id="tableJournalBKM">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>No. Journal</th>
                                                        <th>Deskripsi</th>
                                                        <th>Tanggal</th>
                                                        <th>Total</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Table Data -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End of tab-content -->
            </div>
        </div>
    </div> <!-- End of container-fluid -->

@endsection
@section('script')

    <script>
        $('.nav-links').on('click', function(e) {
            e.preventDefault();
            var tab = $(this).data('tab');

            $('.tab-pane').removeClass('show active');
            $('#' + tab).addClass('show active');

            $('.nav-links').removeClass('active');
            $(this).addClass('active');
        });
    </script>
    <script>
        $(document).ready(function() {
            function initTableJournal(tipeKode, excludeTypes = []) {
                return $('#tableJournal' + (tipeKode || '')).DataTable({
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: "{{ route('journal.data') }}",
                        method: 'GET',
                        data: function(d) {
                            d.startDate = $('#startDate').val();
                            d.endDate = $('#endDate').val();
                            // Periksa status berdasarkan tab aktif
                            if ($('#jurnalUmum').hasClass('active')) {
                                d.status = $('#filterStatus').val();
                            } else if ($('#menuBkk').hasClass('active')) {
                                d.status = $('#filterStatusBkk').val();
                            } else if ($('#menuBkm').hasClass('active')) {
                                d.status = $('#filterStatusBkm').val();
                            }
                            d.tipe_kode = tipeKode;
                            if (excludeTypes.length > 0) {
                                d.excludeTypes = excludeTypes;
                            }
                        }
                    },
                    columns: [{
                            data: 'no_journal',
                            name: 'no_journal'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'tanggalFormat',
                            name: 'tanggalFormat'
                        },
                        {
                            data: 'totalcredit',
                            name: 'totalcredit'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    order: [],
                    lengthChange: false,
                    language: {
                        processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                        info: "_START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        emptyTable: "No data available in table",
                        loadingRecords: "Loading...",
                        zeroRecords: "No matching records found"
                    }
                });
            }

            // Fungsi debounce
            function debounce(func, delay) {
                let timer;
                return function(...args) {
                    const context = this;
                    clearTimeout(timer);
                    timer = setTimeout(() => func.apply(context, args), delay);
                };
            }

            let tableGeneral = initTableJournal('', ['BKK', 'BKM']);
            let tableBKK = initTableJournal('BKK');
            let tableBKM = initTableJournal('BKM');

            $('a[data-tab]').on('click', function(e) {
                e.preventDefault();
                let tab = $(this).data('tab');

                $('#jurnalUmum, #menuBkk, #menuBkm').removeClass('show active');
                $('#' + tab).addClass('show active');

                if (tab === 'jurnalUmum') {
                    tableGeneral.draw();
                } else if (tab === 'menuBkk') {
                    tableBKK.draw();
                } else if (tab === 'menuBkm') {
                    tableBKM.draw();
                }
            });

            $('#txSearch').on('input', debounce(function() {
                tableGeneral.search($(this).val()).draw();
            }, 1000));

            $('#txSearch2').on('input', debounce(function() {
                tableBKK.search($(this).val()).draw();
            }, 1000));

            $('#txSearch3').on('input', debounce(function() {
                tableBKM.search($(this).val()).draw();
            }, 1000));

            $('#saveFilterTanggal').on('click', function() {
                const activeTab = $('.tab-pane.active').attr('id');

                if (activeTab === 'jurnalUmum') {
                    tableGeneral.ajax.reload();
                } else if (activeTab === 'menuBkk') {
                    tableBKK.ajax.reload();
                } else if (activeTab === 'menuBkm') {
                    tableBKM.ajax.reload();
                }

                $('#modalFilterTanggal').modal('hide');
            });

            $(document).on('click', '#filterTanggal', function(e) {
                $('#modalFilterTanggal').modal('show');
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

            $('#filterStatus').change(function() {
                tableGeneral.ajax.reload();
            });
            $('#filterStatusBkk').change(function() {
                tableBKK.ajax.reload();
            });
            $('#filterStatusBkm').change(function() {
                tableBKM.ajax.reload();
            });



            $(document).on('click', '.btnUpdateJournal', function(e) {
                e.preventDefault();

                let id = $(this).data('id');
                var url = "{{ route('updatejournal', ':id') }}";

                url = url.replace(':id', id);

                window.location.href = url;
            });

            $(document).on('click', '.btnDestroyJournal', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Jurnal ini akan dihapus secara permanen!",
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
                            url: "{{ route('destroyJurnal', ':id') }}".replace(':id', id),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                showMessage("success", response.message)
                                    .then(
                                        () => {
                                            tableGeneral.ajax.reload(),
                                                tableBKK.ajax.reload(),
                                                tableBKM.ajax.reload();
                                        });
                            },
                            error: function(xhr, status, error) {
                                var errorMessage = xhr.responseJSON.error ||
                                    'Gagal menghapus jurnal.';
                                showMessage("error", errorMessage);
                            }
                        });
                    }
                });

            });
        });
    </script>
@endsection
