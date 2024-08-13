@extends('layout.main')

@section('title', 'Delivery')

@section('main')


    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Delivery</h1>
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
                            {{-- <a class="btn btn-primary" href="" id=""><span
                                    class="pr-2"><i class="fas fa-plus"></i></span>Buat Invoice</a> --}}
                        </div>
                        <div id="containerDelivery" class="table-responsive px-3">
                            {{-- <table class="table align-items-center table-flush table-hover" id="tableDelivery">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No Resi</th>
                                        <th>Tanggal</th>
                                        <th>Driver</th>
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
                                        <td><span class="badge badge-success">Done</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-eye"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i
                                                    class="fas fa-file-upload"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>B0234043</td>
                                        <td>28 Juli 2024</td>
                                        <td>Tandrio</td>
                                        <td>Delivery</td>
                                        <td>Rp. 12.000</td>
                                        <td><span class="badge badge-info">Delivery</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a>
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
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

        // let selectedMonth = getCurrentMonth();

        const getlistDelivery = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                    url: "{{ route('getlistDelivery') }}",
                    method: "GET",
                    data: {
                        txSearch: txtSearch,
                        // filter: selectedMonth
                    },
                    beforeSend: () => {
                        $('#containerDelivery').html(loadSpin)
                    }
                })
                .done(res => {
                    $('#containerDelivery').html(res)
                    $('#tableDelivery').DataTable({
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

        getlistDelivery();


        $(document).on('click', '.btnAcceptPengantaran', function(e) {
            let id = $(this).data('id');

            Swal.fire({
                title: "Apakah Barang Dengan Resi Ini Siap Diatarkan?",
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
                        type: "GET",
                        url: "{{ route('acceptPengantaran') }}",
                        data: {
                            id: id,
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status === 'error') {
                                showMessage("error", response.message);
                            } else {
                                showMessage("success", response.message);
                                getlistDelivery();
                            }
                        },
                        error: function() {
                            showMessage("error", "Terjadi kesalahan pada server.");
                        }
                    });
                }
            });
        });
    </script>
@endsection
