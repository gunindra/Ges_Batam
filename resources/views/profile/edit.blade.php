@extends('layout.main')

@section('title', 'Profile')

@section('main')
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Profile</h1>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="profileForm">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" class="form-control" type="text" name="name"
                                    value="{{ old('name', auth()->user()->name) }}" required autofocus>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" class="form-control" type="email" name="email"
                                    value="{{ old('email', auth()->user()->email) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="password">New Password (optional)</label>
                                <input id="password" class="form-control" type="password" name="password">
                                <span id="password-error" class="text-danger"></span>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input id="password_confirmation" class="form-control" type="password"
                                    name="password_confirmation">
                            </div>

                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#profileForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: "{{ route('profile.update') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#password-error').text('');
                        if (response.success) {
                            showMessage("success", response.message).then(() => {
                                location.reload();
                                $('#name').val(response.updatedData.name);
                                $('#email').val(response.updatedData.email);
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;

                            if (errors.password) {
                                $('#password-error').text(errors.password[
                                    0]);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong. Please try again later.',
                                showConfirmButton: true
                            });
                        }
                    }
                });

            });
        });
    </script>
@endsection
