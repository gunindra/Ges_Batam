@extends('layout.main')

@section('title', 'Report | Equity')

@section('main')

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Equity</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Report</li>
            <li class="breadcrumb-item active" aria-current="page">Equity</li>
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
                        <a class="btn btn-success mr-1" style="color:white;" id="Print"><span class="pr-2"><i
                                    class="fas fa-solid fa-print mr-1"></i></span>Print</a>
                    </div>
                    <div class="d-flex mb-2 mr-3 mb-4">
                        <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                        <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                            onclick="window.location.reload()">
                            Reset
                        </button>
                    </div>
                    <div id="containerequity" class="table-responsive px-3">
                        <table width="100%" class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th width="30%">Description</th>
                                    <th width="30%"></th>
                                    <th width="20%" class="text-right"></th>
                                    <th width="20%" class="text-right">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Opening Balance of Owner's Equity</td>
                                    <td></td>
                                    <td class="text-right"></td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr>
                                    <td>Additional Capital</td>
                                    <td></td>
                                    <td class="text-right"></td>
                                    <td class="text-right">0.00</td>
                                </tr>
                                <tr>
                                    <td>Retained Earning</td>
                                    <td></td>
                                    <td class="text-right">0.00</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td>Current Year Earning</td>
                                    <td></td>
                                    <td class="text-right">0.00</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td>Prive or Dividend</td>
                                    <td></td>
                                    <td class="text-right"></td>
                                    <td class="text-right" style="border-bottom:3px solid black;">0.00</td>
                                </tr>
                                <tr>
                                    <td><b>Prive or Dividend</b></td>
                                    <td></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"><b>0.00</b></td>
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
                showwMassage(error, "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                $('#endDate').val('');
            }
        }
    });

    $(document).on('click', '#filterTanggal', function (e) {
        $('#modalFilterTanggal').modal('show');
    });


</script>
@endsection