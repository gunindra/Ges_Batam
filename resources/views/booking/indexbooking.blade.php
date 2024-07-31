@extends('layout.main')

@section('title', 'Booking Confirmation')

@section('main')


    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">

        <!-- Modal tambah -->
        <div class="modal fade" id="modalTambahCustomer" tabindex="-1" role="dialog" aria-labelledby="modalTambahCustomerTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahCustomerTitle">Booking Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="namaCustomer" class="form-label fw-bold">Booking Code</label>
                            <input type="text" class="form-control" id="codeBooking" value="" disabled>
                        </div>
                        <div class="mt-3">
                            <label for="namaCustomer" class="form-label fw-bold">Booking Date</label>
                            <input type="date" class="form-control" id="namaCustomer" value="">
                            <div id="errNamaCostumer" class="text-danger mt-1">Silahkan isi tanggal booking</div>
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Costumer</label>
                            <select class="form-control" id="listCustomer" rows="3">
                                {{-- <option value="" selected disabled>Silahkan Pilih Costumer</option> --}}
                            </select>
                            <div id="errAlamatCostumer" class="text-danger mt-1">Silahkan pilihh costumer</div>
                        </div>
                        <div class="mt-3">
                            <label for="noTelpon" class="form-label fw-bold">Barang</label>
                            <select class="select2-multiple" name="states[]" multiple="multiple" style="width: 100%;">
                                <option value="Kacamata">Kacamata</option>
                                <option value="Sepatu">Sepatu</option>
                                <option value="Botol">Botol</option>
                                <option value="Piring">Piring</option>
                                <option value="Gorengan">Gorengan</option>
                            </select>

                            <div id="errNoTelpCostumer" class="text-danger mt-1">Silahkan pilih item</div>
                        </div>
                        {{-- <div class="mt-3">
                            <label for="noTelpon" class="form-label fw-bold">Harga</label>
                            <input  class="form-control" type="text" name="" id="">
                            <div id="errCategoryCostumer" class="text-danger mt-1">Silahkan masukkan harga</div>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveBook" class="btn btn-primary">Save Booking</button>
                    </div>
                </div>
            </div>
        </div>
        <!--End Modal Tambah -->

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Booking Confirmation</h1>
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
                                data-target="#modalTambahCustomer" id="modalTambahBook"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Booking</button>
                        </div>
                        <div id="containerBooking" class="table-responsive px-3">
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
        $(document).ready(function() {
            const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

            const getListBooking = () => {
                const txtSearch = $('#txSearch').val();

                $.ajax({
                        url: "{{ route('getlistBooking') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch
                        },
                        beforeSend: () => {
                            $('#containerBooking').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerBooking').html(res)
                        $('#tableBooking').DataTable({
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

            getListBooking();

            $(document).on('click', '#modalTambahBook', function(e) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('dataBookingForm') }}",
                    success: function(response) {
                        let valueCodeBooking = response.new_codebooking
                        $('#codeBooking').val(valueCodeBooking);

                        // let listPembeli = response.list_pembeli;
                        // $('#listCustomer').empty();
                        // $('#listCustomer').append(`<option value="" selected disabled>Pilih Pembeli</option>`);

                        // listPembeli.forEach(function(pembeli) {
                        //     $('#listCustomer').append(
                        //         `<option value="${pembeli.id}">${pembeli.nama_pembeli}</option>`
                        //     );
                        // });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2-multiple').select2({
                placeholder: "Pilih Barang",
                allowClear: true
            });
        });
    </script>

@endsection
