@extends('layout.main')

@section('title', 'Buat Payment')

@section('main')

<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Payment</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Costumer</li>
            <li class="breadcrumb-item"><a href="{{ route('payment') }}">Payment</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Payment</li>
        </ol>
    </div>

    <a class="btn btn-primary mb-3" href="{{ route('payment') }}">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex mb-2 justify-content-between align-items-center">
                        <div class="d-flex">
                            <!-- Search -->
                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                class="form-control rounded-3" placeholder="Search">
                            <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                                <option value="" selected disabled>Pilih Status</option>
                            </select>
                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                            <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">Reset</button>
                        </div>
                    </div>
                    <div id="containerInvoice" class="table-responsive px-3">
                        <table class="table align-items-center table-flush table-hover" id="tableInvoice">
                            <thead class="thead-light">
                                <tr>
                                    <th>
                                        <input type="checkbox" class="largerCheckbox" id="chkAll" />
                                    </th>
                                    <th>No Resi</th>
                                    <th>Tanggal</th>
                                    <th>Costumer</th>
                                    <th>Pengiriman</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox" class="largerCheckbox tblChk" data-id="B0230123" /></td>
                                    <td>B0230123</td>
                                    <td>24 Juli 2024</td>
                                    <td>Tandrio</td>
                                    <td>Delivery</td>
                                    <td>Rp. 10.000</td>
                                    <td><span class="badge badge-warning">Paid</span></td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="largerCheckbox tblChk" data-id="B0234043" /></td>
                                    <td>B0234043</td>
                                    <td>28 Juli 2024</td>
                                    <td>Tandrio</td>
                                    <td>PickUp</td>
                                    <td>Rp. 12.000</td>
                                    <td><span class="badge badge-warning">Paid</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-6">
                            <div class="mt-3">
                                <label for="idPembayaran" class="form-label fw-bold">Id Pembayaran</label>
                                <select class="form-control col-8" name="" id="idPembayaran">
                                    <option value="" selected disabled>Pilih Id Pembayaran</option>

                                </select>
                                <div id="idPembayaranError" class="text-danger mt-1 d-none">Silahkan Pilih Id Pembayaran
                                    terlebih dahulu</div>
                            </div>
                            <div class="mt-3">
                                <label for="tipePembayaran" class="form-label fw-bold">Tipe Pembayaran</label>
                                <select class="form-control col-8" name="" id="tipePembayaran">
                                    <option value="" selected disabled>Pilih Tipe Pembayaran</option>

                                </select>
                                <div id="tipePembayaranError" class="text-danger mt-1 d-none">Silahkan Pilih Tipe
                                    Pembayaran
                                    terlebih dahulu</div>
                            </div>
                            <div class="mt-3">
                                <label for="matauangIdPembayaran" class="form-label fw-bold">Id Mata Uang</label>
                                <select class="form-control col-8" id="matauangIdPembayaran" name="">
                                    <option value="" selected disabled>Pilih Id Mata Uang</option>

                                </select>
                                <div id="matauangIdPembayaranError" class="text-danger mt-1 d-none">Silahkan Pilih Id
                                    Mata Uang
                                    terlebih dahulu
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="imageRateBuktiPembayaran" class="form-label fw-bold p-1">Rate Bukti Pembayaran</label>
                                <input type="file" class="form-control" id="imageRateBuktiPembayaran" value="" style="width:66.6%;">
                                <div id="imageRateBuktiPembayaranError" class="text-danger mt-1 d-none">Silahkan Upload Bukti Pembayaran</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mt-3">
                                <label for="hargaPembayaran" class="form-label fw-bold">Harga</label>
                                <input type="text" class="form-control col-8" id="hargaPembayaran" value=""
                                    placeholder="Masukkan harga">
                                <div id="hargaPembayaranError" class="text-danger mt-1 d-none">harga tidak boleh kosong
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="rekeningIdPembayaran" class="form-label fw-bold">Id Rekening</label>
                                <select class="form-control col-8" name="" id="rekeningIdPembayaran">
                                    <option value="" selected disabled>Pilih Id Rekening</option>

                                </select>
                                <div id="rekeningIdPembayaranError" class="text-danger mt-1 d-none">Silahkan Pilih Id
                                    Rekening
                                    terlebih dahulu</div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-4 d-none" id="rowDimensi">
                            <div class="col-2 offset-8 me-1">
                                <p class="mb-0">Dimensi</p>
                                <div class="box bg-light text-dark p-3 mt-2"
                                    style="border: 1px solid; border-radius: 8px; font-size: 1.5rem;">
                                    <span id="dimensiValue" style="font-weight: bold; color: #555;">0</span><img
                                        class="pb-1" style="width: 35px; height: 35px;" src="/img/m3_icon.png" alt="m3">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="col-4 float-right">
                            <p class="mb-0">Total Harga</p>
                            <div class="box bg-light text-dark p-3 mt-2"
                                style="border: 1px solid; border-radius: 8px; font-size: 1.5rem;">
                                <span id="total-harga" style="font-weight: bold; color: #555;">Rp. 0</span>
                            </div>
                            <input type="hidden" name="" id="totalHargaValue">
                            <button id="buatPayment" class="btn btn-primary p-3 float-right mt-3"
                                style="width: 100%;">Buat
                                Payment</button>
                        </div>
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
        loadGridData();

        // Event listener for select all checkbox
        $('#chkAll').change(function () {
            $('.tblChk').prop('checked', this.checked);
        });

        // Event listener for individual checkboxes
        $('#tableInvoice').on('change', '.tblChk', function () {
            if ($('.tblChk:checked').length === $('.tblChk').length) {
                $('#chkAll').prop('checked', true);
            } else {
                $('#chkAll').prop('checked', false);
            }
        });

        // Tombol hapus data yang tidak dicentang
        $('#deleteUnselected').click(function () {
            $('.tblChk:not(:checked)').closest('tr').remove();
        });
    });

    function loadGridData() {
        $.ajax({
            type: "GET",
            url: "https://jsonplaceholder.typicode.com/users",
            beforeSend: function () {
                $("#trLoader").show();
            },
            success: function (results) {
                $("#trLoader").remove();
                results.forEach(function (element, index) {
                    let dynamicTR = `
                        <tr>
                            <td>
                                <input type='checkbox' class='largerCheckbox tblChk chk${index}' />
                            </td>
                            <td>${element.name}</td>
                            <td>${element.username}</td>
                            <td>${element.email}</td>
                            <td>${element.phone}</td>
                            <td>${element.website}</td>
                        </tr>`;
                    $("#tableInvoice tbody").append(dynamicTR);
                });
            }
        });
    }

</script>
@endsection