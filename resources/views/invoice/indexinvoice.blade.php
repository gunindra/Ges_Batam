@extends('layout.main')

@section('title', 'Invoice')

@section('main')


    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Invoice</h1>
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
                            <a class="btn btn-primary" href="{{ route('addinvoice') }}" id=""><span
                                    class="pr-2"><i class="fas fa-plus"></i></span>Buat Invoice</a>
                        </div>
                        <div id="containerInvoice" class="table-responsive px-3">
                            {{-- <table class="table align-items-center table-flush table-hover" id="tableInvoice">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No Resi</th>
                                        <th>Tanggal</th>
                                        <th>Costumer</th>
                                        <th>Pengiriman</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>B0230123</td>
                                        <td>24 Juli 2024</td>
                                        <td>Tandrio</td>
                                        <td>Delivery</td>
                                        <td>Rp. 10.000</td>
                                        <td><span class="badge badge-warning">Paid</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-print"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>B0234043</td>
                                        <td>28 Juli 2024</td>
                                        <td>Tandrio</td>
                                        <td>PickUp</td>
                                        <td>Rp. 12.000</td>
                                        <td><span class="badge badge-warning">Paid</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-print"></i></a>
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

            const getlistInvoice = () => {
                const txtSearch = $('#txSearch').val();

                $.ajax({
                        url: "{{ route('getlistInvoice') }}",
                        method: "GET",
                        data: {
                            txSearch: txtSearch
                        },
                        beforeSend: () => {
                            $('#containerInvoice').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerInvoice').html(res)
                        $('#tableInvoice').DataTable({
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

            getlistInvoice();



            $(document).on('click', '.btnExportInvoice', function(e) {
                let id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    url: "{{ route('exportPdf') }}",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.url) {
                            showMessage("success", "Berhasil Export Invoice");
                            window.open(response.url, '_blank');
                        } else if (response.error) {
                            showMessage("error", response.error);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal Export Invoice';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        showMessage("error", errorMessage);
                    }
                });
            });



        });
    </script>
@endsection
