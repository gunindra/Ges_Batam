@extends('layout.main')

@section('title', 'Invoice')

@section('main')

<style>
    .dataTables_length,
    .dataTables_filter {
        display: none;
    }
</style>
<div class="container-fluid" id="container-wrapper">

    <div class="modal fade" id="modalDetailInvoice" tabindex="-1" role="dialog"
        aria-labelledby="modalDetailInvoiceTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailInvoiceTitle">Detail Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Invoice Information Section -->
                    <h6>Invoice Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>No Voucher:</strong> <span id="invoiceNo"></span></p>
                            <p><strong>Tanggal:</strong> <span id="invoiceDate"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Vendor:</strong> <span id="vendorName"></span></p>
                        </div>
                    </div>

                    <!-- Detailed Items Section -->
                    <h6>Detail Invoice</h6>
                    <table class="table table-bordered" id="invoiceItems">
                        <thead>
                            <tr>
                                <th>Account</th>
                                <th>Description</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                {{-- <th>Memo</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Items will be appended here -->
                        </tbody>
                    </table>

                    <!-- Totals -->
                    <div class="row">
                        <div class="col-md-6 text-right">
                            <strong>Total Debit:</strong>
                        </div>
                        <div class="col-md-6">
                            <p id="totalDebit">0.00</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <strong>Total Credit:</strong>
                        </div>
                        <div class="col-md-6">
                            <p id="totalCredit">0.00</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



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



    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Vendor Invoice</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Vendor</li>
            <li class="breadcrumb-item active" aria-current="page">Supplier Invoice</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                        <a class="btn btn-primary" id="" href="{{ route('addSupplierInvoice') }}"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Tambah Invoice</a>
                    </div>
                    <div class="float-left">
                        <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                            class="form-control rounded-3" placeholder="Search">
                    </div>
                    <div class="float-left">
                        {{-- <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                            <option value="" selected disabled>Pilih Status</option>
                            @foreach ($listStatus as $status)
                            <option value="{{ $status->status_name }}">{{ $status->status_name }}</option>
                            @endforeach
                        </select> --}}
                    </div>
                    <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                    <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                        onclick="window.location.reload()">
                        Reset
                    </button>
                    <div id="containerSupplierInvoice" class="table-responsive px-3 ">
                        <table class="table align-items-center table-flush table-hover" id="supplierInvoiceTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>No. Voucher</th>
                                    <th>Vendor</th>
                                    <th>Tanggal</th>
                                    <th>Mata Uang</th>
                                    <th>Status</th>
                                    <th>Total Bayar</th>
                                    <th>Aksi</th>
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
<!---Container Fluid-->

@endsection
@section('script')
<script>
    $(document).ready(function () {
        let table = $('#supplierInvoiceTable').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('getlistSupplierInvoice') }}",
                type: 'GET',
                data: function (d) {
                    d.startDate = $('#startDate').val();
                    d.endDate = $('#endDate').val();
                    // d.status = $('#filterStatus').val();
                },
            },
            columns: [{
                data: 'invoice_no',
                name: 'invoice_no'
            },
            {
                data: 'vendor_name',
                name: 'vendor_name'
            },
            {
                data: 'tanggal',
                name: 'tanggal'
            },
            {
                data: 'singkatan_matauang',
                name: 'singkatan_matauang'
            },
            {
                data: 'status_bayar',
                name: 'status_bayar',
            },
            {
                data: 'total_harga',
                name: 'total_harga'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
            ],
            order: [[0, 'desc']],
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


        $('#txSearch').keyup(function () {
            var searchValue = $(this).val();
            table.search(searchValue).draw();
        });

        // $('#filterStatus').change(function() {
        //     getListSupplierInvoice();
        // });
        flatpickr("#startDate", {
            dateFormat: "d M Y",
            onChange: function (selectedDates, dateStr, instance) {

                $("#endDate").flatpickr({
                    dateFormat: "d M Y",
                    minDate: dateStr
                });
            }
        });

        flatpickr("#endDate", {
            dateFormat: "d MM Y",
            onChange: function (selectedDates, dateStr, instance) {
                var startDate = new Date($('#startDate').val());
                var endDate = new Date(dateStr);
                if (endDate < startDate) {
                    showwMassage(error,
                        "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                    $('#endDate').val('');
                }
            }
        });
        $(document).on('click', '#filterTanggal', function (e) {
            $('#modalFilterTanggal').modal('show');
        });
        $('#saveFilterTanggal').click(function () {
            table.ajax.reload();
            $('#modalFilterTanggal').modal('hide');
        });



        $(document).on('click', '.btnDetailInvoice', function (e) {
            var id = $(this).data('id');

            $.ajax({
                url: "{{ route('showDetail') }}",
                method: 'GET',
                data: {
                    id: id
                },
                success: function (response) {

                    // Update modal title and details
                    $('#modalDetailInvoiceTitle').text('Detail Invoice: ' + response.invoice_no);
                    $('#invoiceNo').text(response.invoice_no);
                    $('#invoiceDate').text(response.tanggal);
                    $('#vendorName').text(response.vendor_name); // Use vendor_name instead of vendor

                    // Clear previous item details
                    $('#invoiceItems tbody').empty();

                    // Initialize totals
                    var totalDebit = 0;
                    var totalCredit = 0;

                    // Create a formatter for IDR currency
                    var currencyFormatter = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 2
                    });

                    // Append each item to the table and calculate totals
                    response.items.forEach(function (item) {
                        var row = '<tr>' +
                            '<td>' + (item.coa ? item.coa.name : 'N/A') + '</td>' +
                            '<td>' + item.description + '</td>' +
                            '<td>' + currencyFormatter.format(parseFloat(item.debit)) + '</td>' +
                            '<td>' + currencyFormatter.format(parseFloat(item.credit)) + '</td>' +
                            '</tr>';

                        $('#invoiceItems tbody').append(row);

                        // Sum up debit and credit totals
                        totalDebit += parseFloat(item.debit);
                        totalCredit += parseFloat(item.credit);
                    });

                    // Update totals in the modal with currency format
                    $('#totalDebit').text(currencyFormatter.format(totalDebit));
                    $('#totalCredit').text(currencyFormatter.format(totalCredit));

                    // Show the modal
                    $('#modalDetailInvoice').modal('show');
                },
                error: function () {
                    showMessage("error", "Terjadi kesalahan saat mengambil data Invoice");
                }
            });
        });


    });
</script>
@endsection
