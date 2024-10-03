@extends('layout.main')

@section('title', 'Customer | Payment')

@section('main')

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payment</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Customer</li>
            <li class="breadcrumb-item active" aria-current="page">Payment</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                        <a class="btn btn-primary" href="{{ route('addPayment') }}" id=""><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Buat Payment</a>
                    </div>
                    <div id="containerPurchasePayment" class="table-responsive px-3">
                        <table class="table align-items-center table-flush table-hover" id="tablePurchasePayment">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>No. Invoice</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                    <th>Total Kurs</th>
                                    <th>Status</th>
                                    <th>Tanggal Payment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>B0230123</td>
                                    <td>001</td>
                                    <td>IDR 1</td>
                                    <td>IDR 100.000.000</td>
                                    <td>IDR 200.000.000</td>
                                    <td><span class="badge badge-success">Publish</span></td>
                                    <td>12 September 2024</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>B0230123</td>
                                    <td>001</td>
                                    <td>IDR 1</td>
                                    <td>IDR 100.000.000</td>
                                    <td>IDR 200.000.000</td>
                                    <td><span class="badge badge-success">Publish</span></td>
                                    <td>12 September 2024</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
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

@endsection
@section('script')
<script>



</script>
@endsection