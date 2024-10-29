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

            <!-- Updated User Points Section with clearer Top-Up and current price -->
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">User Points</h4>
                        <p class="card-text">Here is an overview of your current points and other relevant details.</p>

                        <div class="mb-3">
                            <div class="text-center">
                                <h1 id="pointValue" class="display-3 font-weight-bold text-primary">
                                    {{ $listPoin !== null ? (floor($listPoin) == $listPoin ? number_format($listPoin, 0) : rtrim(rtrim(number_format($listPoin, 2), '0'), '.')) : '0' }}
                                    poin</h1>
                                <p class="text-muted">Poin</p>
                            </div>
                        </div>

                        <!-- Indikator Harga Berlaku -->
                        <div class="mb-3">
                            <p class="text-muted">Harga per 1kg poin saat ini: <strong>Rp 50.000</strong></p>
                        </div>

                        <!-- Progress Bar to Show Poin Usage -->
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="70"
                                aria-valuemin="0" aria-valuemax="100">100% Used</div>
                        </div>

                        <!-- Top-Up Points Section with prominent button -->
                        <div class="rewards-section">
                            <h4>Top-Up Points</h4>
                            <div class="reward-item d-flex align-items-center">
                                <p class="pt-2">Ingin Mengisi Poin Untuk User Ini?</p>
                                <button class="btn-topUp ml-3 btn btn-lg btn-primary" data-toggle="modal"
                                    data-target="#topupModal">Top Up</button>
                            </div>
                        </div>

                        <!-- Riwayat Penggunaan Poin Terakhir -->
                        <div class="mb-3">
                            <h5>Riwayat Penggunaan Terakhir</h5>
                            <ul>
                                <li>10 Poin digunakan pada 2024-10-20</li>
                                <li>5 Poin digunakan pada 2024-10-18</li>
                                <li>15 Poin digunakan pada 2024-10-15</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Updated History Points Section -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">History Poin</h4>
                        <table id="pointsHistoryTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer Name</th>
                                    <th>Marking</th>
                                    <th>Points</th>
                                    <th>Price per Kg</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Konfirmasi Top-Up -->
    <div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="topupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="topupModalLabel">Konfirmasi Top-Up</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda akan mengisi ulang poin untuk pengguna ini.</p>
                    <p><strong>Jumlah Poin:</strong> <input type="number" id="topupAmount" class="form-control"
                            placeholder="Masukkan jumlah poin"></p>

                    <p><strong>Harga per 1kg poin:</strong>
                        <select id="pricePerKg" class="form-control">
                            <option value="">Pilih Harga</option>
                        </select>
                    </p>

                    <p><strong>Total yang Harus Dibayar:</strong> Rp <span id="totalCost">0</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmTopUp">Konfirmasi Top-Up</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')

    <script>
        $(document).ready(function() {
            $('#pointsHistoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('getPointsHistory') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'marking',
                        name: 'marking'
                    },
                    {
                        data: 'points',
                        name: 'points'
                    },
                    {
                        data: 'price_per_kg',
                        name: 'price_per_kg'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'type_badge',
                        name: 'type_badge',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [{
                        targets: [0, 6],
                        className: 'text-center'
                    },
                    {
                        targets: 6,
                        className: 'text-center'
                    } // Centering the 'Type' badge
                ],
                lengthChange: false,
                pageLength: 7,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                    info: "_START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    emptyTable: "No data available in table",
                    loadingRecords: "Loading...",
                    zeroRecords: "No matching records found"
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            const pricePerKg = 50000; // Harga per kg poin

            // Saat jumlah poin diubah, hitung total biaya
            $('#topupAmount').on('input', function() {
                let amount = $(this).val();
                let totalCost = amount * pricePerKg;
                $('#totalCost').text(totalCost.toLocaleString()); // Format angka agar lebih mudah dibaca
            });

            // Handle Top-Up Confirmation
            $('#confirmTopUp').on('click', function() {
                let amount = $('#topupAmount').val();
                if (amount > 0) {
                    // Logic to handle the top-up (could be an AJAX call)
                    alert('Top-up berhasil untuk ' + amount + ' poin.');
                    $('#topupModal').modal('hide');
                } else {
                    alert('Masukkan jumlah poin yang valid.');
                }
            });

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
                                $('#password-error').text(errors.password[0]);
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
