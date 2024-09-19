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

    <!-- New Card -->
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-body">
            <h4 class="card-title">User Points</h4>
                <p class="card-text">Here is an overview of your current points and other relevant details.</p>
                
                <div class="mb-3">
                    <div class="text-center">
                        <h1 id="pointValue" class="display-3 font-weight-bold text-primary" value="0">0</h1>
                        <p class="text-muted">Poin</p>
                    </div>
                </div>
               

                <div class="mb-3">
                    <h5>Points Earned</h5>
                    <ul>
                        <li>Purchase point: <span class="text-primary">200 points</span></li>
                        <li>Referral bonus: <span class="text-primary">300 points</span></li>
                        <li>Purchase bonus: <span class="text-primary">10 points</span></li>
                    </ul>
                </div>

                <div class="rewards-section">
            <h4>Penukaran Poin</h4>
            <div class="reward-item">
                <p>Diskon 10% Pengiriman (100 Poin)</p>
                <button class="btn-redeem btn-primary" data-points="100">Tukar</button>
            </div>
            <div class="reward-item">
                <p>Voucher Asuransi Pengiriman (200 Poin)</p>
                <button class="btn-redeem btn-primary" data-points="200">Tukar</button>
            </div>
            <div class="reward-item">
                <p>Merchandise Eksklusif (500 Poin)</p>
                <button class="btn-redeem btn-primary" data-points="500">Tukar</button>
            </div>
        </div>


                <!-- <div class="mb-3">
                    <h5>Recent Activities</h5>
                    <ul>
                        <li>September 10, 2024 - Survey completed</li>
                        <li>September 12, 2024 - Referral bonus awarded</li>
                    </ul>
                </div>  -->
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
