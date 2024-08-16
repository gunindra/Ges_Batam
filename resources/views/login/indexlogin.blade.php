@extends('layout.app')

@section('title', 'Login')

@push('styles')
    <style>
        body {
            background-image: url('{{ asset('img/LogisticBackground.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
        }
    </style>
@endpush

@section('content')
    <!-- Login Content -->
    <div class="container-login">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-12 col-md-9">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Login</h1>
                                    </div>
                                    <form class="user">
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="exampleInputEmail"
                                                aria-describedby="emailHelp" placeholder="Enter Email Address">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="exampleInputPassword"
                                                placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember Me</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-block">Login</a>
                                        </div>
                                        <div class="form-group text-center">
                                            <a href="{{ route('PTGes') }}" class="btn btn-secondary btn-block">Back to Home</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Content -->
@endsection

@section('script')
@endsection


