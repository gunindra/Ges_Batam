@extends('layout.main')

@section('title', 'COA')

@section('main')


    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">

        <!-- Modal tambah COA -->
        <div class="modal fade" id="modalTambahCOA" tabindex="-1" role="dialog" aria-labelledby="modalTambahCOATitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahCOATitle">Add New Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="codeAccountID" class="form-label fw-bold">Code Account ID*</label>
                            <input type="text" class="form-control" id="codeAccountID" placeholder="Input Account ID"
                                required>
                            <div id="errCodeAccountID" class="text-danger mt-1">This field is required</div>
                        </div>
                        <div class="mt-3">
                            <label for="groupAccount" class="form-label fw-bold">Group Account</label>
                            <select class="form-control" id="groupAccount" required>
                                <option value="" disabled selected>Select Group Account</option>
                                <!-- Add options dynamically or manually here -->
                            </select>
                            <div id="errGroupAccount" class="text-danger mt-1">Please select a group account</div>
                        </div>
                        <div class="mt-3">
                            <label for="nameAccount" class="form-label fw-bold">Name*</label>
                            <input type="text" class="form-control" id="nameAccount" placeholder="Input Name" required>
                            <div id="errNameAccount" class="text-danger mt-1">This field is required</div>
                        </div>
                        <div class="mt-3">
                            <label for="descriptionAccount" class="form-label fw-bold">Description</label>
                            <input type="text" class="form-control" id="descriptionAccount"
                                placeholder="Input Description">
                        </div>
                        <div class="mt-3">
                            <label for="setGroup" class="form-label fw-bold">Set as Group</label>
                            <div>
                                <input type="checkbox" id="setGroup" name="setGroup"> Yes
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="defaultPosisi" class="form-label fw-bold">Default Posisi*</label>
                            <input type="text" class="form-control" id="defaultPosisi" placeholder="Input Default Posisi"
                                required>
                            <div id="errDefaultPosisi" class="text-danger mt-1">This field is required</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveCOA" class="btn btn-primary">Save COA</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal tambah COA -->

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">COA</h1>
            {{-- <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
      </ol> --}}
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahCOA" id="modalTambahBook"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah</button>
                        </div>
                        <div id="containerBooking" class="table-responsive px-3">
                            <ul>
                                <li>
                                    <a href="">1.0.00 - ASET</a>
                                    <button class="btn-danger" style="font-size: 12px;" onclick="removeItem(this)"><i
                                            class="fas fa-trash"></i></button>
                                    <ul>
                                        <li>
                                            <a href="">1.1.00 - Sub Bab A</a>
                                            <button class="btn-danger" style="font-size: 12px;"
                                                onclick="removeItem(this)"><i class="fas fa-trash"></i></button>
                                            <ul>
                                                <li>
                                                    <a href="">1.1.1 - Sub Sub Bab A.1</a>
                                                    <button class="btn-danger" style="font-size: 12px;"
                                                        onclick="removeItem(this)"><i class="fas fa-trash"></i></button>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="">1.2.00 - Sub Bab B</a>
                                            <button class="btn-danger" style="font-size: 12px;"
                                                onclick="removeItem(this)"><i class="fas fa-trash"></i></button>
                                            <ul>
                                                <li>
                                                    <a href="">1.2.1 - Sub Sub Bab B.1</a>
                                                    <button class="btn-danger" style="font-size: 12px;"
                                                        onclick="removeItem(this)"><i class="fas fa-trash"></i></button>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>

                            {{-- <table class="table align-items-center table-flush table-hover" id="tableBooking">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Booking Code</th>
                                        <th>Booking Date</th>
                                        <th>Costumer</th>
                                        <th>Nama Barang</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>B0230123</td>
                                        <td>24 Juli 2024</td>
                                        <td>Tandrio</td>
                                        <td>Botol, Pelastik, Gorengan</td>
                                        <td><span class="badge badge-warning">Booking</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>B0234043</td>
                                        <td>28 Juli 2024</td>
                                        <td>Tandrio</td>
                                        <td>Kacamata, Tas, Sepatu</td>
                                        <td><span class="badge badge-warning">Booking</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i
                                                    class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> --}}
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
        function removeItem(button) {
            // Menghapus elemen li dari DOM
            var listItem = button.parentElement;
            listItem.remove();
        }
    </script>
@endsection
