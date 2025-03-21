@extends('layout.main')

@section('title', 'Sales')

@section('main')
    <style>
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }

        .select2-container--default .select2-selection--single {
            height: 42px;
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

        .select2-container {
            margin-right: 10px;
            /* Memberikan jarak antar elemen select */
        }
    </style>


    <div class="modal fade" id="modalFilterTanggal" tabindex="-1" role="dialog" aria-labelledby="modalFilterTanggalTitle"
        aria-hidden="true">
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
                                <label for="Tanggal" class="form-label fw-bold">Pilih Tanggal: </label>
                                <div class="d-flex align-items-center">
                                    <input type="input" id="startDate" class="form-control"
                                        placeholder="Pilih tanggal mulai" style="width: 200px;">
                                    <span class="mx-2">sampai</span>
                                    <input type="input" id="endDate" class="form-control"
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

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 px-4">Sales</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active" aria-current="page">Sales</li>
            </ol>
        </div>
        <div class="row mb-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            <button class="btn btn-primary mr-2" id="exportBtn">Export Excel</button>
                            <button class="btn btn-primary mr-2" id="btnExportSales">Export Pdf</button>
                        </div>
                        <div class="float-left d-flex">
                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                class="form-control rounded-3 mr-2" placeholder="Search">
                            <select class="form-control ml-2 select2" id="filterNoDO" style="width: 200px;">
                                <option value="" selected disabled>Pilih No Do</option>
                                @foreach ($listDo as $NoDo)
                                    <option value="{{ $NoDo->no_do }}">{{ $NoDo->no_do }}</option>
                                @endforeach
                            </select>
                            <select class="form-control ml-2 select2" id="filterCustomer" style="width: 200px;">
                                <option value="" selected disabled>Pilih Customer</option>
                                @foreach ($listCustomer as $Customer)
                                    <option value="{{ $Customer->nama_pembeli }}">{{ $Customer->nama_pembeli }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter</button>
                            <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">
                                Reset
                            </button>
                        </div>
                        <div id="containerOngoing" class="table-responsive px-2">
                            <table class="table align-items-center table-flush table-hover" id="tableSales">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No. Invoice</th>
                                        <th>Marking</th>
                                        <th>Tanggal Invoice</th>
                                        <th>No Resi</th>
                                        <th>Quantity</th>
                                        <th>No. DO</th>
                                        <th>Customer</th>
                                        <th>Pengiriman</th>
                                        <th>Status</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="9" style="text-align: right;">Grand Total:</th>
                                        <th id="grandTotal">Rp 0</th>
                                    </tr>
                                </tfoot>
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
        $(document).ready(function() {

            $('.select2').select2();

            let table = $('#tableSales').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('getListSales') }}",
                    type: "GET",
                    data: function(d) {
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                        d.no_do = $('#filterNoDO').val();
                        d.nama_pembeli = $('#filterCustomer').val();
                    },
                    dataSrc: function(json) {
                        $('#grandTotal').html(
                            `<strong>${parseFloat(json.total_sum).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}</strong>`
                        );
                        return json.data;
                    }
                },
                columns: [{
                        data: "no_invoice",
                        name: "no_invoice"
                    },
                    {
                        data: "marking",
                        name: "marking"
                    },
                    {
                        data: "tanggal_buat",
                        name: "tanggal_buat"
                    },
                    {
                        data: "no_resi",
                        name: "no_resi",
                        render: function(data) {
                            return data ? data.replace(/;/g, "<br>") : "-";
                        }
                    },
                    {
                        data: "berat_volume",
                        name: "berat_volume"
                    },
                    {
                        data: "no_do",
                        name: "no_do"
                    },
                    {
                        data: "customer",
                        name: "customer"
                    },
                    {
                        data: "metode_pengiriman",
                        name: "metode_pengiriman"
                    },
                    {
                        data: "status_transaksi",
                        name: "status_transaksi"
                    },
                    {
                        data: "total_harga",
                        name: "total_harga",
                        render: function(data) {
                            return `${parseFloat(data).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}`;
                        }
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

            $(document).on('click', '#filterTanggal', function(e) {
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
                    dateFormat: "d M Y",
                    onChange: function(selectedDates, dateStr, instance) {
                        let startDate = new Date($('#startDate').val());
                        let endDate = new Date(dateStr);
                        if (endDate < startDate) {
                            Swal.fire("Error",
                                "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.",
                                "error");
                            $('#endDate').val('');
                        }
                    }
                });

                $('#modalFilterTanggal').modal('show');
            });



            $('#saveFilterTanggal').click(function() {
                table.ajax.reload();
                $('#modalFilterTanggal').modal('hide');
            });


            $('#filterCustomer').change(function() {
                table.ajax.reload();
            });
            $('#filterNoDO').change(function() {
                table.ajax.reload();
            });
            $('#txSearch').keyup(function() {
                let searchValue = $(this).val();
                table.search(searchValue).draw();
            });

            $('#exportBtn').on('click', function() {
                let NoDo = $('#filterNoDO').val();
                let customer = $('#filterCustomer').val();
                let startDate = $('#startDate').val();
                let endDate = $('#endDate').val();

                let now = new Date();
                let day = String(now.getDate()).padStart(2, '0');
                let month = now.toLocaleString('default', {
                    month: 'long'
                });
                let year = now.getFullYear();
                let hours = String(now.getHours()).padStart(2, '0');
                let minutes = String(now.getMinutes()).padStart(2, '0');
                let seconds = String(now.getSeconds()).padStart(2, '0');

                let filename = `Sales_${day} ${month} ${year} ${hours}:${minutes}:${seconds}.xlsx`;

                $.ajax({
                    url: "{{ route('ExportSales') }}",
                    type: 'GET',
                    data: {
                        no_do: NoDo,
                        nama_pembeli: customer,
                        startDate: startDate,
                        endDate: endDate
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        let blob = new Blob([data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        });
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;
                        link.click();
                    },
                    error: function() {
                        Swal.fire({
                            title: "Export failed!",
                            icon: "error"
                        });
                    }
                });
            });

            $(document).on('click', '#btnExportSales', function(e) {
                e.preventDefault();

                let NoDo = $('#filterNoDO').val();
                let Customer = $('#filterCustomer').val();
                let startDate = $('#startDate').val();
                let endDate = $('#endDate').val();

                // Buat URL dengan semua parameter
                let url = "{{ route('exportSalesPdf') }}?no_do=" + encodeURIComponent(NoDo) +
                    "&nama_pembeli=" + encodeURIComponent(Customer) +
                    "&startDate=" + encodeURIComponent(startDate) +
                    "&endDate=" + encodeURIComponent(endDate);

                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    Swal.close();
                    window.open(url, '_blank'); // Buka file PDF langsung di tab baru
                }, 1000);
            });

        });
    </script>

@endsection
