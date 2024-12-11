@extends('layout.main')

@section('title', 'Report | Penerimaan Kas Report')

@section('main')

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Penerimaan Kas Report</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active" aria-current="page">Penerimaan Kas Report</li>
            </ol>
        </div>
        @if ($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
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
                                    <select class="form-control select2" id="customer">
                                        <option value="" selected disabled>Pilih Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->marking }} - {{$customer->nama_pembeli}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="payment" class="form-label fw-bold">Payment:</label>
                                    <select class="form-control select2" id="payment">
                                        <option value="" selected disabled>Pilih Payment</option>
                                        @foreach ($payment as $account)
                                            <option value="{{ $account->id }}">{{$account->code_account_id}} - {{$account->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="Tanggal" class="form-label fw-bold">Pilih Tanggal: ( Kosongkan jika ingin munculkan data bulan ini )</label>
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
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                        <button class="btn btn-primary mr-2" id="exportBtn">Export Excel</button>
                            <a class="btn btn-success mr-1" style="color:white;" id="print"><span class="pr-2"><i class="fas fa-print"></i></span>Print</a>
                        </div>
                        <div class="d-flex mb-2 mr-3 mb-4">
                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter</button>
                            <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">
                                Reset
                            </button>
                        </div>
                        <div id="containerPenerimaaKas" class="table-responsive px-3">

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
            const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
            <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
        </div> `;

            const getPenerimaanKas = () => {
                const txtSearch = $('#txSearch').val();
                const filterStatus = $('#filterStatus').val();
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();
                const customer = $('#customer').val();
                const payment = $('#payment').val();
                
                $.ajax({
                        url: "{{ route('getPenerimaanKas') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch,
                            status: filterStatus,
                            startDate: startDate,
                            endDate: endDate,
                            customer: customer,
                            payment : payment,
                        },
                        beforeSend: () => {
                            $('#containerPenerimaaKas').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerPenerimaaKas').html(res)

                    })
            }

            getPenerimaanKas();

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
                        showwMassage(error,
                        "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                        $('#endDate').val('');
                    }
                }
            });
            
            $(document).on('click', '#filterTanggal', function(e) {
                $('#modalFilterTanggal').modal('show');
            });

            $('#saveFilterTanggal').click(function() {
                getPenerimaanKas();
                $('#modalFilterTanggal').modal('hide');
            });
            $('#print').on('click', function(e) {
                e.preventDefault
                window.location.href = '{{ route('penerimaanKas.pdf') }}';
            });
            $('#exportBtn').on('click', function () {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var customer = $('#customer').val();
            var account = $('#payment').val();

            var now = new Date();
            var day = String(now.getDate()).padStart(2, '0');
            var month = now.toLocaleString('default', { month: 'long' });
            var year = now.getFullYear();
            var hours = String(now.getHours()).padStart(2, '0');
            var minutes = String(now.getMinutes()).padStart(2, '0');
            var seconds = String(now.getSeconds()).padStart(2, '0');

            var filename = `Penerimaan Kas_${day} ${month} ${year} ${hours}:${minutes}:${seconds}.xlsx`;

            $.ajax({
                url: "{{ route('exportKasReport') }}",
                type: 'GET',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    nama_pembeli: customer,
                    name: account,
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

        });
        // Store the sorting order for each column
        let sortOrders = {};

        function sortTable(columnIndex) {
            console.log(`Sorting column: ${columnIndex}`);

            const table = document.getElementById("penerimaanKasTable");
            const rows = Array.from(table.rows).slice(1); // Skip the header row

            // Initialize or toggle the sorting order for this column
            if (sortOrders[columnIndex] === undefined) {
                sortOrders[columnIndex] = true; // Default to ascending on first click
            } else {
                sortOrders[columnIndex] = !sortOrders[columnIndex];
            }

            const ascending = sortOrders[columnIndex];

            rows.sort((rowA, rowB) => {
                const cellA = rowA.cells[columnIndex].innerText.trim();
                const cellB = rowB.cells[columnIndex].innerText.trim();

                if (!isNaN(cellA) && !isNaN(cellB)) {
                    // Numeric sorting
                    return ascending ? cellA - cellB : cellB - cellA;
                } else {
                    // String sorting (case-insensitive)
                    return ascending
                        ? cellA.localeCompare(cellB, undefined, { numeric: true, sensitivity: 'base' })
                        : cellB.localeCompare(cellA, undefined, { numeric: true, sensitivity: 'base' });
                }
            });

            // Rebuild the table
            rows.forEach(row => table.appendChild(row));
        }


    </script>
@endsection
