@extends('layout.main')

@section('title', 'Master Data | Role')

@section('main')

<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">
    <div class="modal fade" id="modalRoleMaster" tabindex="-1" role="dialog" aria-labelledby="modalEditIklanTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRoleMasterTitle">Role Master</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="roleMasterId">
                    <div class="CheckAll mt-1">
                        <input type="checkbox" class="largerCheckbox" id="chkAll" />
                        <label for="selectAll" class="form-label fw-bold">Select All</label>
                    </div>
                     <!-- dashboard -->
                     <div class="dashboard mt-1 mx-3">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="dashboard" class="form-label fw-bold">Dashboard</label>
                    </div>
                    <!-- customer -->
                    <div class="customer mt-1 mx-3">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="customer" class="form-label fw-bold">Customer</label>
                    </div>
                    <div class="check1 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="invoice" class="form-label fw-bold">Invoice</label>
                    </div>
                    <div class="check1 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="delivery" class="form-label fw-bold">Delivery</label>
                    </div>
                    <div class="check1 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="pickup" class="form-label fw-bold">Pickup</label>
                    </div>
                    <div class="check1 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="payment" class="form-label fw-bold">Payment</label>
                    </div>
                    <!-- vendor -->
                    <div class="mt-1 mx-3">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="vendor" class="form-label fw-bold">Vendor</label>
                    </div>
                    <!-- content -->
                    <div class="content mt-1 mx-3">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="content" class="form-label fw-bold">Content</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="about" class="form-label fw-bold">About</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="why" class="form-label fw-bold">Why</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="service" class="form-label fw-bold">Service</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="information" class="form-label fw-bold">Information</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="carousel" class="form-label fw-bold">Carousel</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="iklan" class="form-label fw-bold">Iklan</label>
                    </div>
                    <div class="check2 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="popup" class="form-label fw-bold">Popup</label>
                    </div>
                    <!-- Tracking -->
                    <div class="mt-1 mx-3">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="tracking" class="form-label fw-bold">Tracking</label>
                    </div>
                    <!-- master data -->
                    <div class="masterData mt-1 mx-3">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="masterData" class="form-label fw-bold">Master Data</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="costumers" class="form-label fw-bold">Costumers</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="driver" class="form-label fw-bold">Driver</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="rekening" class="form-label fw-bold">Rekening</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="rate" class="form-label fw-bold">Rate</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="category" class="form-label fw-bold">Category</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="role" class="form-label fw-bold">Role</label>
                    </div>
                    <div class="check3 mt-1 mx-5">
                        <input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" />
                        <label for="user" class="form-label fw-bold">User</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveRoleMaster" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Role</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active" aria-current="page">Role</li>
        </ol>
    </div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-links nav-link active" aria-current="page" href="#" data-tab="roleTab">Role Master</a>
        </li>
        <li class="nav-item">
            <a class="nav-links nav-link" href="#" data-tab="aksesTab">Menu Akses</a>
        </li>
    </ul>
    <div class="tab-content mt-3">
        <!-- Role Content -->
        <div id="roleTab" class="tab-pane fade show active">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="float-left">
                                <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                    class="form-control rounded-3" placeholder="Search">
                            </div>
                            <div id="containerRole" class="table-responsive px-3 ">
                                <table class="table align-items-center table-flush table-hover" id="tableRole">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Pemilik</th>
                                            <th>No. Rekening</th>
                                            <th>Bank</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Ilham</td>
                                            <td>291037292</td>
                                            <td>BCA</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-secondary"><i
                                                        class="fas fa-edit"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tio</td>
                                            <td>1192837432</td>
                                            <td>Mandiri</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-secondary"><i
                                                        class="fas fa-edit"></i></a>
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

        <!-- Akses Content -->
        <div id="aksesTab" class="tab-pane fade">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="float-left">
                                <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                    class="form-control rounded-3" placeholder="Search">
                            </div>
                            <div id="containerAkses" class="table-responsive px-3 ">
                                <table class="table align-items-center table-flush table-hover" id="tableAkses">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>nama</th>
                                            <th>halo</th>
                                            <th>Bcuk</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Ilhamd</td>
                                            <td>291037292sadasdada</td>
                                            <td>BCAdwadaw</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-secondary"><i
                                                        class="fas fa-edit"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tiodwadaw</td>
                                            <td>1192837432dwadaw</td>
                                            <td>awdawdaw</td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-secondary"><i
                                                        class="fas fa-edit"></i></a>
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
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('.nav-links').on('click', function (e) {
            e.preventDefault();
            var tab = $(this).data('tab');

            $('.tab-pane').removeClass('show active');
            $('#' + tab).addClass('show active');

            $('.nav-links').removeClass('active');
            $(this).addClass('active');
        });
        // Select All functionality
        $('#chkAll').click(function () {
            $('input.tblChk').prop('checked', this.checked);
        });

        // Function to update Select All status
        function updateSelectAllStatus() {
            var allChecked = $('input.tblChk').length === $('input.tblChk:checked').length;
            $('#chkAll').prop('checked', allChecked);
        }

        // Customer checkboxes group
        $('.customer input[type="checkbox"]').click(function () {
            var isChecked = $(this).is(':checked');
            // Check/uncheck all checkboxes in customer and check1 group
            $('.customer input[type="checkbox"], .check1 input[type="checkbox"]').prop('checked', isChecked);
            // Update Select All status
            updateSelectAllStatus();
        });

        $('.check1 input[type="checkbox"]').click(function () {
            var allChecked = $('.check1 input[type="checkbox"]').length === $('.check1 input[type="checkbox"]:checked').length;
            if (allChecked) {
                $('.customer input[type="checkbox"]').prop('checked', true);
            } else {
                $('.customer input[type="checkbox"]').prop('checked', false);
            }
            // Update Select All status
            updateSelectAllStatus();
        });

        // Content checkboxes group
        $('.content input[type="checkbox"]').click(function () {
            var isChecked = $(this).is(':checked');
            $('.content input[type="checkbox"], .check2 input[type="checkbox"]').prop('checked', isChecked);
            updateSelectAllStatus();
        });

        $('.check2 input[type="checkbox"]').click(function () {
            var allChecked = $('.check2 input[type="checkbox"]').length === $('.check2 input[type="checkbox"]:checked').length;
            if (allChecked) {
                $('.content input[type="checkbox"]').prop('checked', true);
            } else {
                $('.content input[type="checkbox"]').prop('checked', false);
            }
            updateSelectAllStatus();
        });

        // Master Data checkboxes group
        $('.masterData input[type="checkbox"]').click(function () {
            var isChecked = $(this).is(':checked');
            $('.masterData input[type="checkbox"], .check3 input[type="checkbox"]').prop('checked', isChecked);
            updateSelectAllStatus();
        });

        $('.check3 input[type="checkbox"]').click(function () {
            var allChecked = $('.check3 input[type="checkbox"]').length === $('.check3 input[type="checkbox"]:checked').length;
            if (allChecked) {
                $('.masterData input[type="checkbox"]').prop('checked', true);
            } else {
                $('.masterData input[type="checkbox"]').prop('checked', false);
            }
            updateSelectAllStatus();
        });

        // When any individual checkbox in tblChk is clicked, update Select All status
        $('input.tblChk').click(function () {
            updateSelectAllStatus();
        });
    });
</script>
@endsection