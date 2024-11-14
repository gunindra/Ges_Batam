@extends('layout.main')

@section('title', 'Report | ProfitLoss')

@section('main')
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Profit/Loss</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active" aria-current="page">Profit / Loss</li>
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
                                <div class="mt-3">
                                    <button type="button" id="comparison" class="btn btn-info">Comparison</button>
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
                        <div class="d-flex mb-2 mr-3">
                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                            <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">
                                Reset
                            </button>
                        </div>
                        <div id="containerProfitLoss" class="table-responsive px-3">

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
        </div>`;

        const getProfitOrLoss = () => {
            const txtSearch = $('#txSearch').val();
            const filterStatus = $('#filterStatus').val();
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            const compareStart = $('#compareStart').val();
            const compareEnd = $('#compareEnd').val();

            // Validate if comparison dates are shown and not filled (only if comparison is active)
            if ($('#compareStart').is(':visible') && (!compareStart || !compareEnd)) {
                alert("Both comparison start and end dates are required.");
                return; // Prevent the request if validation fails
            }

            $.ajax({
                url: "{{ route('getProfitOrLoss') }}",
                method: "GET",
                data: {
                    txSearch: txtSearch,
                    status: filterStatus,
                    startDate: startDate,
                    endDate: endDate,
                    compareStart: compareStart,
                    compareEnd: compareEnd,
                },
                beforeSend: () => {
                    $('#containerProfitLoss').html(loadSpin);
                }
            })
            .done(res => {
                $('#containerProfitLoss').html(res);
            });
        }

        getProfitOrLoss();

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
                    showwMassage(error, "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                    $('#endDate').val('');
                }
            }
        });

        $(document).on('click', '#filterTanggal', function(e) {
            $('#modalFilterTanggal').modal('show');
        });

        $('#comparison').click(function() {
            // Hide the "Comparison" button
            this.style.display = "none";

            // Create a new div element for the additional date range
            const newDateRange = document.createElement("div");
            newDateRange.classList.add("mt-3");
            newDateRange.setAttribute("id", "extraDateRange"); // Assign ID for easy removal

            // Add label and date range inputs for the new date range
            newDateRange.innerHTML = `
                <label for="Tanggal" class="form-label fw-bold">Tanggal Comparison:</label>
                <div class="d-flex align-items-center">
                    <input type="date" id="compareStart" class="form-control" placeholder="Pilih tanggal mulai" style="width: 200px;" required>
                    <span class="mx-2">sampai</span>
                    <input type="date" id="compareEnd" class="form-control" placeholder="Pilih tanggal akhir" style="width: 200px;" required>
                </div>
                <div class="mt-3">
                    <button type="button" id="cancelComparison" class="btn btn-danger">Cancel Comparison</button>
                </div>
            `;

            // Append the new date range and "Cancel Comparison" button to the modal body
            document.querySelector("#modalFilterTanggal .modal-body .col-12").appendChild(newDateRange);

            // Add event listener for the "Cancel Comparison" button
            document.getElementById("cancelComparison").addEventListener("click", function() {
                // Remove the additional date range
                document.getElementById("extraDateRange").remove();

                // Show the "Comparison" button again
                document.getElementById("comparison").style.display = "inline-block";
            });

            flatpickr("#compareStart", {
                dateFormat: "d M Y",
                onChange: function(selectedDates, compareStr, instance) {
                    $("#compareEnd").flatpickr({
                        dateFormat: "d M Y",
                        minDate: compareStr
                    });
                }
            });

            flatpickr("#compareEnd", {
                dateFormat: "d MM Y",
                onChange: function(selectedDates, compareStr, instance) {
                    var compareStart = new Date($('#compareStart').val());
                    var compareEnd = new Date(compareStr);
                    if (compareEnd < compareStart) {
                        showwMassage(error, "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                        $('#compareEnd').val('');
                    }
                }
            });
        });

        // Validation before saving
        $('#saveFilterTanggal').click(function() {
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            const compareStart = $('#compareStart').val();
            const compareEnd = $('#compareEnd').val();

            if(!startDate || !endDate){
                alert("Silahkan Masukkan Semua Kolom Tanggal");
                return;
            }
            // Validate comparison start and end only if comparison is active (fields are visible)
            if ($('#compareStart').is(':visible') && (!startDate || !endDate)) {
                alert("Silahkan Masukkan Semua Kolom Tanggal");
                return; // Prevent saving if validation fails
            }

            // Proceed with saving and closing the modal
            getProfitOrLoss();
            $('#modalFilterTanggal').modal('hide');
        });

        $('#Print').on('click', function(e) {
            e.preventDefault();
            window.location.href = '{{ route('profitLoss.pdf') }}';
        });
    });

    </script>
@endsection
