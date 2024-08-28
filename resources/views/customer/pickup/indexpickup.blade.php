@extends('layout.main')

@section('title', 'Pickup')

@section('main')


    <div class="modal fade" id="modalFilterTanggal" tabindex="-1" role="dialog" aria-labelledby="modalFilterTanggalTitle"
        aria-hidden="true">
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

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pickup</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Costumer</li>
                <li class="breadcrumb-item active" aria-current="page">Pickup</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-3 justify-content-between align-items-center">
                            <div class="d-flex">
                                {{-- Search --}}
                                <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                    class="form-control rounded-3" placeholder="Search">
                                <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                                    <option value="" selected disabled>Pilih Filter</option>
                                    <option value="Pending Payment">Pending Payment</option>
                                    <option value="Ready For Pickup">Ready For Pickup</option>
                                    <option value="Done">Done</option>
                                </select>
                                <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                                <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                    onclick="window.location.reload()">
                                    Reset
                                </button>
                            </div>
                        </div>
                        <div id="containerPickup" class="table-responsive px-3">
                            <!-- <table class="table align-items-center table-flush table-hover" id="tablePickup"> -->
                            <!-- <thead class="thead-light">
                                        <tr>
                                            <th>No Resi</th>
                                            <th>Tanggal</th>
                                            <th>Costumer</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>B0230123</td>
                                            <td>24 Juli 2024</td>
                                            <td>Tandrio</td>
                                            <td><span class="badge badge-warning">Ready For Pickup</span></td>
                                            <td>
                                                <a class="btn btnPembayaran btn-sm btn-success text-white" data-id="' . $item->id . '" data-tipe="' . $item->tipe_pembayaran . '"><i class="fas fa-check"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>B0234043</td>
                                            <td>28 Juli 2024</td>
                                            <td>Tandrio</td>
                                            <td><span class="badge badge-warning">Ready For Pickup</span></td>
                                            <td>
                                                <a class="btn btnPembayaran btn-sm btn-success text-white" data-id="' . $item->id . '" data-tipe="' . $item->tipe_pembayaran . '"><i class="fas fa-check"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table> -->
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
        $(document).ready(function() {
            const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

            const getlistPickup = () => {
                const txtSearch = $('#txSearch').val();
                const filterStatus = $('#filterStatus').val();
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();

                $.ajax({
                        url: "{{ route('getlistPickup') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch,
                            status: filterStatus,
                            startDate: startDate,
                            endDate: endDate
                        },
                        beforeSend: () => {
                            $('#containerPickup').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerPickup').html(res)
                        $('#tablePickup').DataTable({
                            searching: false,
                            lengthChange: false,
                            "bSort": true,
                            "aaSorting": [],
                            pageLength: 7,
                            "lengthChange": false,
                            responsive: true,
                            language: {
                                search: ""
                            }
                        });
                    })
            }

            getlistPickup();

            $('#txSearch').keyup(function(e) {
                var inputText = $(this).val();
                if (inputText.length >= 1 || inputText.length == 0) {
                    getlistPickup();
                }
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
                        showwMassage(error,
                            "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                        $('#endDate').val('');
                    }
                }
            });

            $(document).on('click', '#filterTanggal', function(e) {
                $('#modalFilterTanggal').modal('show');
            });

            $('#filterStatus').change(function() {
                getlistPickup();
            });

            $('#saveFilterTanggal').click(function() {
                getlistPickup();
                $('#modalFilterTanggal').modal('hide');
            });


            $(document).on('click', '.btnPickup', function(e) {
                let id = $(this).data('id');

                Swal.fire({
                        title: "Apakah Barang Dengan Resi ini Sudah di Pickup?",
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
                                type: "POST",
                                url: "{{ route('acceptPickup') }}",
                                data: {
                                    id: id,
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(response) {
                                    if (response.error) {
                                        showMessage("error", response.message);
                                    } else {
                                        showMessage("success", "Berhasil");
                                        getlistPickup();
                                    }
                                },
                                error: function() {
                                    showMessage("error",
                                        "Terjadi kesalahan pada server.");
                                }
                            });
                        }
                    });
            })
        });
    </script>
@endsection
