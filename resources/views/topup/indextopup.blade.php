@extends('layout.main')

@section('title', 'Top Up')

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

        .dataTables_length,
        .dataTables_filter {
            display: none;
        }
    </style>

    {{-- <style>
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }
    </style> --}}


    <!-- Modal Konfirmasi Top-Up -->
    <div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="topupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="topupModalLabel">Top-Up Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Kolom kiri untuk pengguna dan akun -->
                        <div class="col-md-6">
                            <!-- Dropdown untuk memilih pengguna -->
                            <p><strong>Pilih Pengguna:</strong>
                                <select id="customerSelect" class="form-control select2" style="width: 100%">
                                    <option value="">Pilih Pengguna</option>
                                </select>
                                <span id="customerSelectError" class="text-danger"></span> <!-- Pesan error -->
                            </p>

                            <!-- Dropdown untuk memilih COA account -->
                            <p><strong>Pilih Akun Jurnal (COA):</strong>
                                <select id="accountSelect" class="form-control select2" style="width: 100%">
                                    <option value="">Pilih Akun Jurnal</option>
                                    @foreach ($coas as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->code_account_id }} -
                                            {{ $coa->name }}</option>
                                    @endforeach
                                </select>
                                <span id="accountSelectError" class="text-danger"></span> <!-- Pesan error -->
                            </p>
                        </div>

                        <!-- Kolom kanan untuk jumlah poin dan harga -->
                        <div class="col-md-6">
                            <!-- Input untuk jumlah poin -->
                            <p><strong>Jumlah Poin:</strong>
                                <input type="number" id="topupAmount" class="form-control"
                                    placeholder="Masukkan jumlah poin">
                                <span id="topupAmountError" class="text-danger"></span> <!-- Pesan error -->
                            </p>

                            <!-- Dropdown untuk memilih harga per poin atau tambah harga baru -->
                            <p><strong>Harga per 1kg poin:</strong>
                                <select id="pricePerKg" class="form-control">
                                    <option value="">Pilih harga</option>
                                    @foreach ($listRateVolume as $rate)
                                        @if ($rate->rate_for == 'Berat')
                                            <option value="{{ $rate->nilai_rate }}">
                                                {{ number_format($rate->nilai_rate, 0, ',', '.') }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <span id="pricePerKgError" class="text-danger"></span> <!-- Pesan error -->
                            </p>

                            <!-- Input untuk harga baru dan tanggal berlaku -->
                            {{-- <div id="newPriceSection" style="display: none;">
                                <p><strong>Harga Baru:</strong>
                                    <input type="number" id="newPrice" class="form-control"
                                        placeholder="Masukkan harga baru">
                                    <span id="newPriceError" class="text-danger"></span> <!-- Pesan error -->
                                </p>
                                <p><strong>Tanggal Berlaku:</strong>
                                    <input type="date" id="effectiveDate" class="form-control">
                                    <span id="effectiveDateError" class="text-danger"></span> <!-- Pesan error -->
                                </p>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Baris untuk menampilkan total -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <h4><strong>Total yang Harus Dibayar:</strong></h4>
                            <h1 class="display-4 text-primary" id="totalCost">Rp 0</h1>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmTopUp">Konfirmasi Top-Up</button>
                </div>
            </div>
        </div>
    </div>




    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Top-up</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Top-up</li>
                {{-- <li class="breadcrumb-item active" aria-current="page">Top-up</li> --}}
            </ol>
        </div>
        <div class="modal fade" id="modalFilterTanggal" tabindex="-1" role="dialog"
            aria-labelledby="modalFilterTanggalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFilterTanggalTitle">Filter Tanggal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mt-3">
                                    <label for="Tanggal" class="form-label fw-bold">Pilih Tanggal:</label>
                                    <div class="d-flex align-items-center">
                                        <input type="date" id="startDate" class="form-control"
                                            placeholder="Pilih tanggal mulai" style="width: 200px;">
                                        <span class="mx-2">sampai</span>
                                        <input type="date" id="endDate" class="form-control"
                                            placeholder="Pilih tanggal akhir" style="width: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveFilterTanggal" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-primary mr-2" id="exportBtn">Export Excel</button> --}}
                            <button class="btn btn-primary" data-toggle="modal" data-target="#topupModal">
                                <span class="pr-2"><i class="fas fa-plus"></i></span>Buat Top-up
                            </button>
                        </div>
                        <div class="d-flex mb-4">
                            {{-- Search --}}
                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                class="form-control rounded-3" placeholder="Search">
                            {{-- <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                                <option value="" selected disabled>Pilih Filter</option>

                            </select> --}}
                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                            <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">
                                Reset
                            </button>
                        </div>
                        <div id="containerPurchaseTop-up" class="px-3">
                            <table class="table align-items-center table-flush table-hover" id="tableTopup">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Marking</th>
                                        <th>Nama Costumer</th>
                                        <th>Nominal Top Up</th>
                                        <th>Poin Top Up</th>
                                        <th>Harga (1kg)</th>
                                        <th>Nama Akun Jurnal</th>
                                        {{-- <th>Status</th> --}}
                                        <th>Tanggal</th>
                                        {{-- <th>Poin</th> <!-- Kolom untuk penanda poin --> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- <tr>
                                        <td>001</td>
                                        <td>John Doe</td>
                                        <td>100.000</td>
                                        <td>50.000</td>
                                        <td>Jurnal A</td>
                                        <td><span class="text-success"><i class="fas fa-check-circle"></i> Lunas</span>
                                        </td>
                                        <td>2024-10-23</td>
                                        <td><span class="badge text-white bg-success">Masuk</span></td>
                                        <!-- Penanda poin masuk -->
                                    </tr>
                                    <tr>
                                        <td>002</td>
                                        <td>Jane Smith</td>
                                        <td>50.000</td>
                                        <td>25.000</td>
                                        <td>Jurnal B</td>
                                        <td><span class="text-danger"><i class="fas fa-exclamation-circle"></i> Belum
                                                Lunas</span></td>
                                        <td>2024-10-22</td>
                                        <td><span class="badge text-white bg-danger">Keluar</span></td>
                                        <!-- Penanda poin keluar -->
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
@section('script')

    <script>
        $(document).ready(function() {
            let table = $('#tableTopup').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('topup.data') }}",
                    type: 'GET',
                    data: function(d) {
                        // d.status = $('#filterStatus').val();
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                    },
                    error: function(xhr, error, thrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load top-up data. Please try again!',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                columns: [{
                        data: 'customer_id',
                        name: 'customer_id'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'remaining_points',
                        name: 'remaining_points',
                        render: function(data) {
                            if (data % 1 === 0) {
                                return parseInt(data);
                            } else {
                                return parseFloat(data).toFixed(2);
                            }
                        }
                    },
                    {
                        data: 'topup_amount',
                        name: 'topup_amount',
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        data: 'price_per_kg',
                        name: 'price_per_kg',
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        data: 'account.name',
                        name: 'account.name'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    }
                ],
                order: [],
                lengthChange: false,
                pageLength: 10,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>', // Custom spinner saat processing
                    info: "_START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    emptyTable: "No data available in table",
                    loadingRecords: "Loading...",
                    zeroRecords: "No matching records found"
                }
            });


            $('#txSearch').keyup(function() {
                var searchValue = $(this).val();
                table.search(searchValue).draw();
            });


            $('#saveFilterTanggal').on('click', function() {
                table.ajax.reload();
                $('#modalFilterTanggal').modal('hide');
            });

            $(document).on('click', '#filterTanggal', function(e) {
                $('#modalFilterTanggal').modal('show');
            });

            flatpickr("#startDate", {
                dateFormat: "d M Y",
                onChange: function(selectedDates, dateStr, instance) {

                    $("#endDate").flatpickr({
                        dateFormat: "d M Y",
                        minDate: dateStr
                    });
                }
            });

            flatpickr("#endDate", {
                dateFormat: "d MM Y",
                onChange: function(selectedDates, dateStr, instance) {
                    var startDate = new Date($('#startDate').val());
                    var endDate = new Date(dateStr);
                    if (endDate < startDate) {
                        showMessage(error,
                            "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                        $('#endDate').val('');
                    }
                }
            });

            // $('#filterStatus').change(function() {
            //     table.ajax.reload();
            // });






            const pricePerKgDropdown = $('#pricePerKg');
            const customerSelect = $('#customerSelect');
            const coaSelect = $('#accountSelect');
            const totalCostDisplay = $('#totalCost');
            const remainingPointsInput = $('#topupAmount'); // Ubah id untuk representasi jumlah poin
            const newPriceSection = $('#newPriceSection');
            const newPriceInput = $('#newPrice');
            const effectiveDateInput = $('#effectiveDate');
            let selectedPrice = 0;

            // Inisialisasi Select2
            $('.select2').select2();

            // Reset pesan error
            function resetErrorMessages() {
                $('.text-danger').text(''); // Menghapus semua pesan error
            }

            // Ambil daftar harga per kg dan daftar pengguna saat modal dibuka
            $('#topupModal').on('show.bs.modal', function() {
                resetErrorMessages();

                // Ambil daftar pengguna
                $.ajax({
                    url: "{{ route('get-customers') }}",
                    method: 'GET',
                    success: function(response) {
                        customerSelect.empty();
                        customerSelect.append('<option value="">Pilih Pengguna</option>');
                        $.each(response, function(index, customer) {
                            customerSelect.append(
                                `<option value="${customer.id}">${customer.nama_pembeli} (Marking: ${customer.marking})</option>`
                            );
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal mengambil data pengguna.',
                            confirmButtonText: 'OK'
                        });
                    }
                });

                // Ambil daftar harga
                // $.ajax({
                //     url: "{{ route('get-price-points') }}",
                //     method: 'GET',
                //     success: function(response) {
                //         pricePerKgDropdown.empty();
                //         pricePerKgDropdown.append('<option value="">Pilih Harga</option>');
                //         pricePerKgDropdown.append(
                //             '<option value="new">Tambah Harga Baru</option>');
                //         $.each(response, function(index, price) {
                //             pricePerKgDropdown.append(
                //                 `<option value="${price.price_per_kg}" data-id="${price.id}">Rp ${price.price_per_kg} - Berlaku Sejak: ${price.effective_date}</option>`
                //             );
                //         });
                //     },
                //     error: function() {
                //         Swal.fire({
                //             icon: 'error',
                //             title: 'Error!',
                //             text: 'Gagal mengambil data harga.',
                //             confirmButtonText: 'OK'
                //         });
                //     }
                // });
            });

            pricePerKgDropdown.on('change', function() {
                if ($(this).val() === 'new') {
                    newPriceSection.show();
                    selectedPrice = 0;
                } else {
                    newPriceSection.hide();
                    selectedPrice = $(this).val();
                }
                calculateTotal();
            });

            remainingPointsInput.on('input', function() {
                calculateTotal();
            });

            newPriceInput.on('input', function() {
                selectedPrice = $(this).val();
                calculateTotal();
            });

            function calculateTotal() {
                const remainingPoints = remainingPointsInput.val();
                const topupAmount = remainingPoints * selectedPrice;
                totalCostDisplay.text(topupAmount ? `Rp ${topupAmount.toLocaleString()}` : 'Rp 0');
            }

            $('#confirmTopUp').on('click', function() {
                resetErrorMessages();

                const customerId = customerSelect.val();
                const coaId = coaSelect.val();
                const remainingPoints = remainingPointsInput.val();
                const priceId = pricePerKgDropdown.val();
                const topupAmount = remainingPoints * selectedPrice;

                let hasError = false;

                if (!customerId) {
                    $('#customerSelectError').text('Pengguna harus dipilih.');
                    hasError = true;
                }

                if (!coaId) {
                    $('#accountSelectError').text('Akun jurnal harus dipilih.');
                    hasError = true;
                }

                if (!remainingPoints || remainingPoints <= 0) {
                    $('#topupAmountError').text('Silahkan masukkan jumlah poin.');
                    hasError = true;
                }

                if (!priceId) {
                    $('#pricePerKgError').text('Silahkan pilih harga.');
                    hasError = true;
                }

                if (hasError) {
                    return;
                }

                $.ajax({
                    url: "{{ route('topup-points') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        customer_id: customerId,
                        remaining_points: remainingPoints,
                        price_per_kg: priceId || null,
                        coa_id: coaId
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: 'Top-up berhasil!',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#topupModal').modal('hide');
                            table.ajax.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal melakukan top-up.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endsection
