@extends('layout.main')

@section('title', 'Customer | Payment')

@section('main')

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payment</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Customer</li>
            <li class="breadcrumb-item active" aria-current="page">Payment</li>
        </ol>
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
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                        <a class="btn btn-primary" href="{{ route('addPayment') }}" id=""><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Buat Payment</a>
                    </div>
                    <div class="d-flex mb-4">
                        {{-- Search --}}
                        <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                            class="form-control rounded-3" placeholder="Search">
                        <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                            <option value="" selected disabled>Pilih Filter</option>

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
                                    <th>No. Invoice</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                    <th>Total Kurs</th>
                                    <th>Status</th>
                                    <th>Tanggal Payment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>B0230123</td>
                                    <td>001</td>
                                    <td>IDR 1</td>
                                    <td>IDR 100.000.000</td>
                                    <td>IDR 200.000.000</td>
                                    <td><span class="badge badge-success">Publish</span></td>
                                    <td>12 September 2024</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>B0230123</td>
                                    <td>001</td>
                                    <td>IDR 1</td>
                                    <td>IDR 100.000.000</td>
                                    <td>IDR 200.000.000</td>
                                    <td><span class="badge badge-success">Publish</span></td>
                                    <td>12 September 2024</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
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
        getlistJournal();
        $('#modalFilterTanggal').modal('hide');
    });



</script>
@endsection