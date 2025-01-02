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
                                    <input type="text" class="form-control" id="assetCode" value="" name="asset_code"
                                        placeholder="Masukkan Asset Code">
                                    <div id="assetError" class="text-danger mt-1 d-none">Silahkan Masukkan Asset
                                        terlebih dahulu</div>
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="assetName" class="form-label fw-bold">Asset Name</label>
                                    <input  type="text" class="form-control" id="assetName" value="" name="asset_name"
                                        placeholder="Masukkan Asset Name">
                                    <div id="assetnameError" class="text-danger mt-1 d-none">Silahkan Masukkan Nama Asset
                                        terlebih dahulu</div>
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="acquisitionDate" class="form-label fw-bold">Acquisition Date</label>
                                    <input type="text" class="form-control" id="acquisitionDate" value="" name="acquisition_date">
                                    <div id="assetdateError" class="text-danger mt-1 d-none">Silahkan Masukkan Date
                                    terlebih dahulu</div>
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="acquisitionPrice" class="form-label fw-bold">Acquisition Price</label>
                                    <input  type="text" class="form-control number-mask" id="acquisitionPrice" value="" name="acquisition_price"
                                        placeholder="Masukkan Acquisition Price">
                                    <div id="acquisitionError" class="text-danger mt-1 d-none">Silahkan Masukkan Price
                                    terlebih dahulu</div>
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="depreciationDate" class="form-label fw-bold">Depreciation Date</label>
                                    <input type="text" class="form-control" id="depreciationDate" value="" name="depreciation_date">
                                    <div id="depreciationError" class="text-danger mt-1 d-none">Silahkan Masukkan Date
                                    terlebih dahulu</div>
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="residueValue" class="form-label fw-bold">Residue Value</label>
                                    <input  type="text" class="form-control number-mask" id="residueValue" value="0" name="residue_value"
                                        placeholder="Masukkan Residue Value">
                                    <div id="ResidueError" class="text-danger mt-1 d-none">Silahkan Masukkan Residue
                                        terlebih dahulu</div>
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="estimatedAge" class="form-label fw-bold">Estimated Age</label>
                                    <div class="input-group">
                                        <input  type="number" class="form-control" id="estimatedAge" value="1" name="estimated_age"
                                            placeholder="Enter Estimated Age">
                                        <span class="input-group-text">Month</span>
                                    </div>
                                    <div id="AgeError" class="text-danger mt-1 d-none">Silahkan Masukkan Age
                                        terlebih dahulu</div>
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="depreciationAccount" class="form-label fw-bold">Asset Account</label>
                                    <select class="form-control select2" name="asset_account">
                                        <option value="">Pilih Akun</option>
                                        @foreach ($account as $coa)
                                            <option value="{{ $coa->id }}">
                                                {{ $coa->code_account_id }} - {{ $coa->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="AccountError" class="text-danger mt-1 d-none">Silahkan Pilih Account
                                    terlebih dahulu</div>
                                </div>

                                <div class="mt-3 col-6">
                                    <label for="depreciationAccount" class="form-label fw-bold">Expense Account</label>
                                    <select class="form-control select2" name="expense_account">
                                        <option value="">Pilih Akun</option>
                                        @foreach ($account as $coa)
                                            <option value="{{ $coa->id }}">
                                                {{ $coa->code_account_id }} - {{ $coa->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="depreciationAccountError" class="text-danger mt-1 d-none">Silahkan Pilih Account
                                    terlebih dahulu</div>
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="depreciationAccount" class="form-label fw-bold">Depreciation Account</label>
                                    <select class="form-control select2" name="depreciation_account">
                                        <option value="">Pilih Akun</option>
                                        @foreach ($account as $coa)
                                            <option value="{{ $coa->id }}">
                                                {{ $coa->code_account_id }} - {{ $coa->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="depreciationAccount2Error" class="text-danger mt-1 d-none">Silahkan Pilih Account
                                    terlebih dahulu</div>
                                </div>
                                <div class="mt-3 col-6">
                                    <label for="accumulatedAccount" class="form-label fw-bold">Accumulated Depreciation Account</label>
                                    <select class="form-control select2" name="accumulated_account">
                                        <option value="">Pilih Akun</option>
                                        @foreach ($account as $coa)
                                            <option value="{{ $coa->id }}">
                                                {{ $coa->code_account_id }} - {{ $coa->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="accumulatedAccountError" class="text-danger mt-1 d-none">Silahkan Pilih Account
                                    terlebih dahulu</div>
                                </div>
                                <div class="col-12 mt-4">
                                    <div class="col-4 float-right">
                                        <button class="btn btn-success p-3 float-right mt-3"
                                            style="width: 80%;" id="submit">Submit Depreciation</button>
                                    </div>
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
        var today = new Date();
        $('#acquisitionDate').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        }).datepicker('setDate', today);

        $('#depreciationDate').datepicker({
            format: 'dd MM yyyy',
            todayBtn: 'linked',
            todayHighlight: true,
            autoclose: true,
        }).datepicker('setDate', today);
        
        $('.number-mask').mask('000,000,000,000', {reverse: true});

        $('.select2').select2();

        $('form').on('submit', function (e) {
            var isValid = true;

            // Clear previous error messages
            $('.text-danger').addClass('d-none');

            // Validate fields
            if ($('#assetCode').val() === '') {
                $('#assetError').removeClass('d-none');
                isValid = false;
            }

            if ($('#assetName').val() === '') {
                $('#assetnameError').removeClass('d-none');
                isValid = false;
            }

            if ($('#acquisitionDate').val() === '') {
                $('#assetdateError').removeClass('d-none');
                isValid = false;
            }

            if ($('#acquisitionPrice').val() === '') {
                $('#acquisitionError').removeClass('d-none');
                isValid = false;
            }

            if ($('#depreciationDate').val() === '') {
                $('#depreciationError').removeClass('d-none');
                isValid = false;
            }

            if ($('#residueValue').val() === '') {
                $('#ResidueError').removeClass('d-none');
                isValid = false;
            }

            if ($('#estimatedAge').val() === '') {
                $('#AgeError').removeClass('d-none');
                isValid = false;
            }

            if ($('select[name="asset_account"]').val() === '') {
                $('#AccountError').removeClass('d-none');
                isValid = false;
            }

            if ($('select[name="expense_account"]').val() === '') {
                $('#depreciationAccountError').removeClass('d-none');
                isValid = false;
            }

            if ($('select[name="depreciation_account"]').val() === '') {
                $('#depreciationAccount2Error').removeClass('d-none');
                isValid = false;
            }

            if ($('select[name="accumulated_account"]').val() === '') {
                $('#accumulatedAccountError').removeClass('d-none');
                isValid = false;
            }

            // If any field is empty, prevent form submission
            if (!isValid) {
                e.preventDefault(); // Prevent form submission
            }
        });
    });
</script>
@endsection