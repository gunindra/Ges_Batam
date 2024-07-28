@extends('layout.main')

@section('title', 'Master Data | Costumer')

@section('main')

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Costumer</h1>
            {{-- <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
      </ol> --}}
        </div>

        <div class="table-responsive p-3">
            <table class="table align-items-center table-flush table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. Telp</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Ilham</td>
                        <td>Jl.Central Legenda poin blok j No. 13 </td>
                        <td>0893483478283</td>
                        <td><span class="badge badge-primary">VIP</span></td>
                        <td><a href="#" class="btn btn-sm btn-primary">Show point</a></td>
                    </tr>
                    <tr>
                        <td>Tio</td>
                        <td>Jl.Central Legenda poin blok j No. 12 </td>
                        <td>Nasi Padang</td>
                        <td>Normal</td>
                        <td><a href="#" class="btn btn-sm btn-primary">Show point</a></td>
                    </tr>
                    {{-- <tr>
                        <td><a href="#">RA5324</a></td>
                        <td>Jaenab Bajigur</td>
                        <td>Gundam 90' Edition</td>
                        <td><span class="badge badge-warning">Shipping</span></td>
                        <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                    </tr>
                    <tr>
                        <td><a href="#">RA8568</a></td>
                        <td>Rivat Mahesa</td>
                        <td>Oblong T-Shirt</td>
                        <td><span class="badge badge-danger">Pending</span></td>
                        <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                    </tr>
                    <tr>
                        <td><a href="#">RA1453</a></td>
                        <td>Indri Junanda</td>
                        <td>Hat Rounded</td>
                        <td><span class="badge badge-info">Processing</span></td>
                        <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                    </tr>
                    <tr>
                        <td><a href="#">RA1998</a></td>
                        <td>Udin Cilok</td>
                        <td>Baby Powder</td>
                        <td><span class="badge badge-success">Delivered</span></td>
                        <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                    </tr> --}}
                </tbody>
            </table>
        </div>


    </div>
    <!---Container Fluid-->





@endsection


@section('script')

<script>
    
</script>

@endsection
