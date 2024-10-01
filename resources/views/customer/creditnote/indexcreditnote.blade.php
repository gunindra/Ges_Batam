@extends('layout.main')

@section('title', 'Customer | Credit Note')

@section('main')

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Credit Note</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Customer</li>
            <li class="breadcrumb-item active" aria-current="page">Credit Note</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                        <a class="btn btn-primary" href="{{ route('addCreditNote') }}" id=""><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Buat Credit Note</a>
                    </div>
                    <div id="containerCreditNote" class="table-responsive px-3">
                        <table class="table align-items-center table-flush table-hover" id="tableCreditNote">
                            <thead class="thead-light">
                                <tr>
                                    <th>No. Credit Note </th>
                                    <th>No. Invoice</th>
                                    <th>Company</th>
                                    <th>Supplier</th>
                                    <th>Publisher</th>
                                    <th>Paid Status</th>
                                    <th>Tanggal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>B0230123</td>
                                    <td>001</td>
                                    <td>PT GES</td>
                                    <td>Tandrio</td>
                                    <td><span class="badge badge-success">Publish</span></td>
                                    <td><span class="badge badge-warning">Unpaid</span></td>
                                    <td>12 September 2024</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>B0230123</td>
                                    <td>001</td>
                                    <td>PT GES</td>
                                    <td>Tandrio</td>
                                    <td><span class="badge badge-success">Publish</span></td>
                                    <td><span class="badge badge-warning">Unpaid</span></td>
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