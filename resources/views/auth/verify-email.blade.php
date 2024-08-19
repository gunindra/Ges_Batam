@extends('layout.main')

@section('title', 'Verify Email')

@section('main')
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Email Verification</h1>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        @if ($verified)
                            <div class="alert alert-success col-6">
                                Your email is already verified.
                            </div>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                        @else
                            <div class="alert alert-danger col-6">
                                Your email is not verified yet. Please check your email for the verification link.
                            </div>
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">Resend Verification Email</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
