@extends('layout.main')

@section('title', 'Pickup')

@section('main')

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
                                    <option value="Out For Delivery">Out For Delivery</option>
                                    <option value="Delivering">Delivering</option>
                                    <option value="Done">Done</option>
                                </select>
                                <button id="monthEvent" class="btn btn-light form-control ml-2"
                                    style="border: 1px solid #e9ecef;">
                                    <span id="calendarTitle" class="fs-4"></span>
                                </button>
                                <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                    onclick="window.location.reload()">
                                    Reset
                                </button>
                            </div>
                        </div>
                        <div id="containerPickup" class="table-responsive px-3">
                            <table class="table align-items-center table-flush table-hover" id="tablePickup">
                                <thead class="thead-light">
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---Container Fluid-->


@endsection
