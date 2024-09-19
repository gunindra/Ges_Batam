@extends('layout.main')

@section('title', 'Tracking')

@section('main')
<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 px-2">Driver</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Driver</li>
        </ol>
    </div>

    <div class="row mb-3 px-3">
        <div class="col-xl-12 px-2">
            <div class="card ">
                <div class="card-body ">
                    <h6 class="m-0 font-weight-bold text-primary">Invoice</h6>
                    <div class="d-flex justify-content-center mb-2 mr-3 mt-3">

                        <select class="form-control" id="filterResi" style="width: 500px;">
                            <option value="" selected disabled>Pilih No.Invoice</option>
                            @foreach ($listInvoice as $Invoice)
                                <option value="{{ $Invoice->no_invoice }}">
                                    {{ $Invoice->no_invoice }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-center mt-3">
                        <h1 id="pointValue" class="display-3 font-weight-bold text-primary" value="0">0</h1>
                        <p class="text-muted">Jumlah Invoice</p>
                    </div>

                    <div id="containerResi" class="table-responsive px-3">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3 px-3">
        <div class="col-xl-12 px-2">
            <div class="card ">
                <div class="card-body">
                    <h6 class="m-0 font-weight-bold text-primary">Tanda Tangan</h6>              
                    <div class="preview mt-3" id="previewContainer" style="border:1px solid black; height: 400px; border-radius:10px;">
                    </div>
                    <div class="mt-3">
                        <label for="imageSupir" class="form-label fw-bold p-1">Image</label>
                        <input type="file" class="form-control" id="imageSupir" value="">
                        <div id="imageSupirError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
                        <p>Nama Gambar= <span id="imageName">{{ $aboutData->Image_AboutUs ?? ' -' }}</span></p>
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

</script>
@endsection