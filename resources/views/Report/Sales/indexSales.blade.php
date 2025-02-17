@extends('layout.main')

@section('title', 'Sales')

@section('main')
    <style>
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }
    </style>

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
                                class="form-control rounded-3" placeholder="Search">
                            <select class="form-control ml-2" id="filterNoDO" style="width: 200px;">
                                <option value="" selected disabled>Pilih No Do</option>
                                @foreach ($listDo as $NoDo)
                                    <option value="{{ $NoDo->no_do }}">{{ $NoDo->no_do }}</option>
                                @endforeach
                            </select>
                            <select class="form-control ml-2" id="filterCustomer" style="width: 200px;">
                                <option value="" selected disabled>Pilih Customer</option>
                                @foreach ($listCustomer as $Customer)
                                    <option value="{{ $Customer->nama_pembeli }}">{{ $Customer->nama_pembeli }}</option>
                                @endforeach
                            </select>
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
                                        <th>Tanggal Invoice</th>
                                        <th>No. DO</th>
                                        <th>Customer</th>
                                        <th>Pengiriman</th>
                                        <th>Status</th>
                                        <th>Harga</th>
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

            var table = $('#tableSales').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('getListSales') }}",
                    type: "GET",
                    data: function (d) {
                        d.no_do = $('#filterNoDO').val();
                        d.nama_pembeli = $('#filterCustomer').val();
                    }
                },
                columns: [{
                    data: "no_invoice",
                    name: "no_invoice"
                },
                {
                    data: "tanggal_buat",
                    name: "tanggal_buat"
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
                    name: "metode_pengiriman",
                    render: function (data, type, row) {
                        return row.metode_pengiriman.includes("Delivery") || row
                            .metode_pengiriman.includes("Pickup") ?
                            row.metode_pengiriman :
                            "-"; // Hanya ambil Delivery dan Pickup
                    }
                },
                {
                    data: "status_transaksi",
                    name: "status_transaksi",
                    render: function (data, type, row) {
                        return `<span class="badge">${data}</span>`;
                    }
                },
                {
                    data: "total_harga",
                    name: "total_harga",
                    render: function (data, type, row) {
                        return `Rp ${parseFloat(data).toLocaleString('id-ID')}`;
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
            $('#filterCustomer').change(function () {
                table.ajax.reload();
            });
            $('#filterNoDO').change(function () {
                table.ajax.reload();
            });
            $('#txSearch').keyup(function () {
                var searchValue = $(this).val();
                table.search(searchValue).draw();
            });

            $('#exportBtn').on('click', function () {
                var NoDo = $('#filterNoDO').val();
                var customer = $('#filterCustomer').val();

                var now = new Date();
                var day = String(now.getDate()).padStart(2, '0');
                var month = now.toLocaleString('default', { month: 'long' });
                var year = now.getFullYear();
                var hours = String(now.getHours()).padStart(2, '0');
                var minutes = String(now.getMinutes()).padStart(2, '0');
                var seconds = String(now.getSeconds()).padStart(2, '0');

                var filename = `Sales_${day} ${month} ${year} ${hours}:${minutes}:${seconds}.xlsx`;

                $.ajax({
                    url: "{{ route('ExportSales') }}",
                    type: 'GET',
                    data: {
                        no_do: NoDo,
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
            $(document).on('click', '#btnExportSales', function (e) {
                let id = $(this).data('id');
                let NoDo = $('#filterNoDO').val();
                let Customer = $('#filterCustomer').val();

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
                    url: "{{ route('exportSalesPdf') }}",
                    data: {
                        id: id,
                        no_do: NoDo,
                        nama_pembeli: Customer
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

                        let errorMessage = 'Gagal Export Sales';
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