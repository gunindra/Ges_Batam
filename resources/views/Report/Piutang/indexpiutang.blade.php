@extends('layout.main')

@section('title', 'Report | Piutang')

@section('main')
<style>
    .dataTables_length,
    .dataTables_filter {
        display: none;
    }

    .select2-container--default .select2-selection--single {
        height: 40px;
        border: 1px solid #d1d3e2;
        border-radius: 0.25rem;
        padding: 6px 12px;
        width: 470px;
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

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 px-4">Piutang</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Report</li>
            <li class="breadcrumb-item active" aria-current="page">Piutang</li>
        </ol>
    </div>

    <div class="modal fade" id="modalFilterTanggal" tabindex="-1" role="dialog"
        aria-labelledby="modalFilterTanggalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilterTanggalTitle">Filter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mt-3">
                                <label for="Tanggal" class="form-label fw-bold">Customer:</label>
                                <div></div>
                                <select class="form-control select2" id="customer">
                                    <option value="" selected>Pilih Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" data-bell="{{ $customer->bell_color }}">
                                            {{ $customer->marking }} - {{ $customer->nama_pembeli }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3">
                                <label for="Tanggal" class="form-label fw-bold">Pilih Tanggal: ( Kosongkan jika ingin
                                    munculkan data bulan ini )</label>
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
    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        <button class="btn btn-primary mr-2" id="exportBtn">Export Excel</button>
                        <button class="btn btn-primary mr-2" id="btnExportPiutang">Export Pdf</button>
                    </div>
                    <div class="float-left d-flex">
                        <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                            class="form-control rounded-3" placeholder="Search">
                        <button class="btn btn-primary ml-2" id="filterTanggal">Filter</button>
                        <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                            onclick="window.location.reload()">
                            Reset
                        </button>
                    </div>
                    <div id="containerPiutang" class="table-responsive px-2">
                        <div class="d-flex">
                            <p class="m-2"><i class="fas fa-bell text-success"></i> : <span id="hijauCount">-</span></p>
                            <p class="m-2"><i class="fas fa-bell text-warning"></i> : <span id="kuningCount">-</span>
                            </p>
                            <p class="m-2"><i class="fas fa-bell text-danger"></i> : <span id="merahCount">-</span></p>
                        </div>
                        <table class="table align-items-center table-flush table-hover" id="tablePiutang">
                            <thead class="thead-light">
                                <tr>
                                    <th>No. Invoice</th>
                                    <th>Customer</th>
                                    <th>Tanggal</th>
                                    <th>Umur</th>
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
@endsection
@section('script')
<script>
    $(document).ready(function () {
        var table = $('#tablePiutang').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('getlistPiutang') }}",
                type: 'GET',
                data: function (d) {
                    d.startDate = $('#startDate').val();
                    d.endDate = $('#endDate').val();
                    d.customer = $('#customer').val();
                },
                error: function (xhr, error, thrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load debit note data. Please try again!',
                        confirmButtonText: 'OK'
                    });
                }
            },
            columns: [
                {
                    data: 'no_invoice',
                    name: 'no_invoice',
                    render: function (data, type, row) {
                        var bellIcon = '';
                        if (row.bell_color === 'yellow') {
                            bellIcon = ' <i class="fas fa-bell text-warning ml-1"></i>';
                        } else if (row.bell_color === 'red') {
                            bellIcon = ' <i class="fas fa-bell text-danger ml-1"></i>';
                        } else if (row.bell_color === 'green') {
                            bellIcon = ' <i class="fas fa-bell text-success ml-1"></i>';
                        }
                        return data + bellIcon;
                    }
                },
                { data: 'nama_pembeli', name: 'nama_pembeli' },
                { data: 'tanggal_buat', name: 'tanggal_buat' },
                { data: 'umur', name: 'umur' },
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
            },
            drawCallback: function (settings) {
                // Hitung jumlah kategori
                var hijauCount = 0;
                var kuningCount = 0;
                var merahCount = 0;

                table.rows().every(function () {
                    var row = this.data();
                    if (row.bell_color === 'green') hijauCount++;
                    if (row.bell_color === 'yellow') kuningCount++;
                    if (row.bell_color === 'red') merahCount++;
                });

                $('#hijauCount').text(hijauCount);
                $('#kuningCount').text(kuningCount);
                $('#merahCount').text(merahCount);
            }
        });
        $('#txSearch').keyup(function () {
            var searchValue = $(this).val();
            table.search(searchValue).draw();
        });
        $(document).on('click', '#filterTanggal', function (e) {
            $('#modalFilterTanggal').modal('show');
        });

        $('#saveFilterTanggal').click(function () {
            table.ajax.reload();
            $('#modalFilterTanggal').modal('hide');
        });

        $('#startDate').val('2025-01-01');

        flatpickr("#startDate", {
            dateFormat: "d M Y",
            defaultDate: "01 Jan 2025", // Default start date
            onChange: function (selectedDates, dateStr, instance) {
                $("#endDate").flatpickr({
                    dateFormat: "d M Y",
                    minDate: dateStr
                });
            }
        });

        flatpickr("#endDate", {
            dateFormat: "d M Y",
            onChange: function (selectedDates, dateStr, instance) {
                var startDate = new Date($('#startDate').val());
                var endDate = new Date(dateStr);
                if (endDate < startDate) {
                    Swal.fire("Error", "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.", "error");
                    $('#endDate').val('');
                }
            }
        });
        $('#customer').select2({
            dropdownParent: $('#modalFilterTanggal'),
            templateResult: function (data) {
                if (!data.id) return data.text;

                // Get the bell color from the data-bell attribute
                var bellColor = $(data.element).data('bell');
                var bellIcon = '';

                // Prioritize the bell colors based on the given conditions
                if (bellColor === 'red') {
                    bellIcon = '<i class="fas fa-bell text-danger mr-2"></i>';
                } else if (bellColor === 'yellow') {
                    bellIcon = '<i class="fas fa-bell text-warning mr-2"></i>';
                } else if (bellColor === 'green') {
                    bellIcon = '<i class="fas fa-bell text-success mr-2"></i>';
                }

                return $('<span>' + bellIcon + data.text + '</span>');
            }
        });
        $('#exportBtn').on('click', function () {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var customer = $('#customer').val();

            var now = new Date();
            var day = String(now.getDate()).padStart(2, '0');
            var month = now.toLocaleString('default', { month: 'long' });
            var year = now.getFullYear();
            var hours = String(now.getHours()).padStart(2, '0');
            var minutes = String(now.getMinutes()).padStart(2, '0');
            var seconds = String(now.getSeconds()).padStart(2, '0');

            var filename = `Piutang_${day} ${month} ${year} ${hours}:${minutes}:${seconds}.xlsx`;

            $.ajax({
                url: "{{ route('exportPiutangReport') }}",
                type: 'GET',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    nama_pembeli: customer
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (data) {
                    var blob = new Blob([data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    link.click();
                },
                error: function () {
                    Swal.fire({
                        title: "Export failed!",
                        icon: "error"
                    });
                }
            });
        });
        $(document).on('click', '#btnExportPiutang', function (e) {
            let id = $(this).data('id');
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();
            let customer = $('#customer').val();

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
                url: "{{ route('exportPiutangPdf') }}",
                data: {
                    id: id,
                    startDate: startDate,
                    endDate: endDate,
                    nama_pembeli: customer
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
                },
                error: function (xhr) {
                    Swal.close();

                    let errorMessage = 'Gagal Export Piutang';
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

    });
</script>
@endsection
