@extends('layout.main')

@section('title', 'Vendor | Payment')

@section('main')

<style>
    .dataTables_length,
    .dataTables_filter {
        display: none;
    }
</style>

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

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payment</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Vendor</li>
            <li class="breadcrumb-item active" aria-current="page">Payment</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        <button class="btn btn-primary mr-2" id="exportBtn">Export Excel</button>
                        <a class="btn btn-primary" href="{{ route('addPurchasePayment') }}" id=""><span
                                class="pr-2"><i class="fas fa-plus"></i></span>Buat Payment</a>
                    </div>
                    <div class="d-flex mb-4">
                        {{-- Search --}}
                        <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                            class="form-control rounded-3" placeholder="Search">
                        <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                            <option value="" selected disabled>Pilih Filter</option>
                            <option value="Lunas" >Lunas</option>
                            <option value="Belum Lunas">Belum Lunas</option>
                        </select>
                        <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                        <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                            onclick="window.location.reload()">
                            Reset
                        </button>
                    </div>
                    <div id="containerPurchasePayment" class="table-responsive px-3">
                        <table class="table align-items-center table-flush table-hover" id="tablePurchasePayment">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>No. Voucher</th>
                                    <th>Tanggal Payment</th>
                                    <th>Total Harga</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
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
    let table = $('#tablePurchasePayment').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('getPaymentSupData') }}",
            type: 'GET',
            data: function(d) {
                d.status = $('#filterStatus').val();
                d.startDate = $('#startDate').val();
                d.endDate = $('#endDate').val();
            },
            error: function(xhr, error, thrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load payment data. Please try again!',
                        confirmButtonText: 'OK'
                    });
                }
        },
        columns: [
            { data: 'kode_pembayaran', name: 'kode_pembayaran' },
            { data: 'invoice_no', name: 'invoice_no' },
            { data: 'tanggal_bayar', name: 'tanggal_bayar' },
            { data: 'amount', name: 'amount', render: $.fn.dataTable.render.number(',', '.', 2) },
            { data: 'payment_method', name: 'payment_method' },
            { data: 'status_bayar', name: 'status_bayar' },
        ],
        lengthChange: false,
            pageLength: 7,
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>', // Custom processing spinner
                info: "_START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                emptyTable: "No data available in table",
                loadingRecords: "Loading...",
                zeroRecords: "No matching records found"
            }
    });

    $('#txSearch').keyup(function() {
            var searchValue = $(this).val();
            table.search(searchValue).draw();
        });


        $('#saveFilterTanggal').on('click', function() {
            table.ajax.reload();
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
                    showMessage(error,
                        "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                    $('#endDate').val('');
                }
            }
        });

        $('#filterStatus').change(function() {
            table.ajax.reload();
        });


        $('#exportBtn').on('click', function() {
            var status = $('#filterStatus').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            $.ajax({
                url: "{{ route('getSupInvoiceExport') }}",
                type: 'GET',
                data: {
                    status: status,
                    startDate: startDate,
                    endDate: endDate
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data) {
                    var blob = new Blob([data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Payment Customers.xlsx";
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


});



</script>
@endsection
