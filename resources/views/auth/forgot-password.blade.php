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
                                        <h1 class="h4 text-gray-900 mb-4">Forgot Password</h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    @if (session('status'))
                                                        <div class="alert alert-success">
                                                            {{ session('status') }}
                                                        </div>
                                                    @endif
                                                    <form method="POST" action="{{ route('password.email') }}">
                                                        @csrf

                                                        <div class="form-group">
                                                            <label for="email">Email Address</label>
                                                            <input id="email" class="form-control" type="email" name="email" required autofocus>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
                                                    </form>

                                                    <div class="form-group text-center mt-3">
                                                        <a href="{{ route('login') }}" class="btn btn-secondary btn-block">Back to
                                                            login</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
