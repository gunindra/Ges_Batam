@extends('layout.main')

@section('title', 'Accounting | Journal')

@section('main')

<style>
    .select2-container--default .select2-selection--single {
        height: 40px;
        border: 1px solid #d1d3e2;
        border-radius: 0.25rem;
        padding: 6px 12px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 27px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }

    .select2-dropdown {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
</style>

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Asset</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Accounting</li>
            <li class="breadcrumb-item"><a href="{{ route('asset') }}">Asset</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat Asset</li>
        </ol>
    </div>
    <a class="btn btn-primary mb-3" href="{{ route('asset') }}">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display any general error messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('asset.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="col-12">
                                <div class="mt-3 col-6">
                                    <label for="assetCode" class="form-label fw-bold">Asset Code</label>
                                    <input type="text" class="form-control" id="assetCode" value="{{$assets->asset_code}}" readonly name="asset_code"
                                        placeholder="Masukkan Asset Code">
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="assetName" class="form-label fw-bold">Asset Name</label>
                                    <input required type="text" class="form-control" id="assetName" value="{{$assets->asset_name}}" readonly name="asset_name"
                                        placeholder="Masukkan Asset Name">
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="acquisitionDate" class="form-label fw-bold">Acquisition Date</label>
                                    <input type="text" class="form-control" id="acquisitionDate" value="{{ \Carbon\Carbon::parse($assets->acquisition_date)->format('d F Y') }}" readonly name="acquisition_date">
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="acquisitionPrice" class="form-label fw-bold">Acquisition Price</label>
                                    <input required type="text" class="form-control number-mask" id="acquisitionPrice" value="{{$assets->acquisition_price}}" readonly name="acquisition_price"
                                        placeholder="Masukkan Acquisition Price">
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="depreciationDate" class="form-label fw-bold">Depreciation Date</label>
                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($assets->depreciation_date)->format('d F Y') }}" readonly name="depreciation_date">
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="residueValue" class="form-label fw-bold">Residue Value</label>
                                    <input required type="text" class="form-control number-mask" id="residueValue" value="{{$assets->residue_value}}" readonly name="residue_value"
                                        placeholder="Masukkan Residue Value">
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="estimatedAge" class="form-label fw-bold">Estimated Age</label>
                                    <div class="input-group">
                                        <input required type="number" class="form-control" id="estimatedAge" value="{{$assets->estimated_age}}" readonly name="estimated_age"
                                            placeholder="Enter Estimated Age">
                                        <span class="input-group-text">Month</span>
                                    </div>
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="depreciationAccount" class="form-label fw-bold">Depreciation Account</label>
                                    <input required type="text" class="form-control" value="{{$depreciation->code_account_id}} - {{$depreciation->name}}" readonly name="depreciation_account"
                                            placeholder="Enter Depreciation Account">
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="accumulatedAccount" class="form-label fw-bold">Accumulated Depreciation Account</label>
                                    <input required type="text" class="form-control" value="{{$accumulated->code_account_id}} - {{$accumulated->name}}" readonly name="accumulated_account"
                                            placeholder="Enter Accumulated Depreciation Account">
                                </div>
                                
                            </div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(document).ready(function () {
        $('.number-mask').mask('000,000,000,000', {reverse: true});

        $('.select2').select2();
    });
</script>
@endsection