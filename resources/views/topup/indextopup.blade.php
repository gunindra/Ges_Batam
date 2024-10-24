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
    </style>

    {{-- <style>
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }
    </style> --}}


    <!-- Modal Konfirmasi Top-Up -->
    <div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="topupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="topupModalLabel">Top-Up Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Dropdown untuk memilih pengguna -->
                    <p><strong>Pilih Pengguna:</strong>
                        <select id="customerSelect" class="form-control select2" style="width: 100%">
                            <option value="">Pilih Pengguna</option>
                        </select>
                    </p>

                    <!-- Input untuk jumlah poin -->
                    <p><strong>Jumlah Poin:</strong> <input type="number" id="topupAmount" class="form-control"
                            placeholder="Masukkan jumlah poin"></p>

                    <!-- Dropdown untuk memilih harga per poin atau tambah harga baru -->
                    <p><strong>Harga per 1kg poin:</strong>
                        <select id="pricePerKg" class="form-control">
                            <option value="">Pilih Harga</option>
                            <option value="new">Tambah Harga Baru</option>
                        </select>
                    </p>

                    <!-- Input untuk harga baru dan tanggal berlaku -->
                    <div id="newPriceSection" style="display: none;">
                        <p><strong>Harga Baru:</strong> <input type="number" id="newPrice" class="form-control"
                                placeholder="Masukkan harga baru"></p>
                        <p><strong>Tanggal Berlaku:</strong> <input type="date" id="effectiveDate" class="form-control">
                        </p>
                    </div>

                    <p><strong>Total yang Harus Dibayar:</strong> Rp <span id="totalCost">0</span></p>
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
                            <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                                <option value="" selected disabled>Pilih Filter</option>

                            </select>
                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                            <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">
                                Reset
                            </button>
                        </div>
                        <div id="containerPurchaseTop-up" class="px-3">
                            <table class="table table-striped" id="tableTopup">
                                <thead>
                                    <tr>
                                        <th>Costumer ID</th>
                                        <th>Nama Costumer</th>
                                        <th>Nominal Top Up</th>
                                        <th>Harga (1kg)</th>
                                        <th>Nama Akun Jurnal</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Poin</th> <!-- Kolom untuk penanda poin -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
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
                                    </tr>
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
            const pricePerKgDropdown = $('#pricePerKg');
            const customerSelect = $('#customerSelect');
            const totalCostDisplay = $('#totalCost');
            const topupAmountInput = $('#topupAmount');
            const newPriceSection = $('#newPriceSection');
            const newPriceInput = $('#newPrice');
            const effectiveDateInput = $('#effectiveDate');
            let selectedPrice = 0;

            $('.select2').select2();


            // Ambil daftar pengguna dan daftar harga ketika modal dibuka
            $('#topupModal').on('show.bs.modal', function() {
                // Ambil daftar pengguna
                $.ajax({
                    url: "{{ route('get-customers') }}",
                    method: 'GET',
                    success: function(response) {
                        customerSelect.empty(); // Kosongkan dropdown pengguna
                        customerSelect.append('<option value="">Pilih Pengguna</option>');

                        // Tambahkan pengguna ke dropdown
                        $.each(response, function(index, customer) {
                            customerSelect.append(
                                `<option value="${customer.id}">${customer.nama_pembeli} (Marking: ${customer.marking})</option>`
                                );
                        });
                    },
                    error: function() {
                        alert('Gagal mengambil data pengguna.');
                    }
                });

                // Ambil daftar harga
                $.ajax({
                    url: "{{ route('get-price-points') }}",
                    method: 'GET',
                    success: function(response) {
                        pricePerKgDropdown.empty(); // Kosongkan dropdown harga
                        pricePerKgDropdown.append('<option value="">Pilih Harga</option>');
                        pricePerKgDropdown.append(
                            '<option value="new">Tambah Harga Baru</option>'
                            ); // Opsi untuk menambah harga baru

                        // Tambahkan harga ke dropdown
                        $.each(response, function(index, price) {
                            pricePerKgDropdown.append(
                                `<option value="${price.price_per_kg}" data-id="${price.id}">Rp ${price.price_per_kg} - Berlaku Sejak: ${price.effective_date}</option>`
                                );
                        });
                    },
                    error: function() {
                        alert('Gagal mengambil data harga.');
                    }
                });
            });

            // Tampilkan input harga baru jika admin memilih "Tambah Harga Baru"
            pricePerKgDropdown.on('change', function() {
                if ($(this).val() === 'new') {
                    newPriceSection.show();
                    selectedPrice = 0; // Reset harga jika memilih tambah harga baru
                } else {
                    newPriceSection.hide();
                    selectedPrice = $(this).val();
                    calculateTotal();
                }
            });

            // Kalkulasi total biaya ketika jumlah poin, harga, atau harga baru dimasukkan
            topupAmountInput.on('input', function() {
                calculateTotal();
            });

            newPriceInput.on('input', function() {
                // Ketika harga baru dimasukkan, gunakan harga tersebut untuk kalkulasi
                selectedPrice = $(this).val();
                calculateTotal();
            });

            function calculateTotal() {
                const amount = topupAmountInput.val();
                const totalCost = amount * selectedPrice;
                totalCostDisplay.text(totalCost ? totalCost.toLocaleString() : 0); // Tampilkan total biaya
            }

            // Handle Top-Up Confirmation
            $('#confirmTopUp').on('click', function() {
                let amount = topupAmountInput.val();
                let selectedCustomer = customerSelect.val();
                let selectedPriceId = pricePerKgDropdown.find('option:selected').data('id');
                let newPrice = newPriceInput.val();
                let effectiveDate = effectiveDateInput.val();

                if (selectedCustomer && amount > 0) {
                    // Jika admin memilih untuk menambah harga baru
                    if (pricePerKgDropdown.val() === 'new') {
                        if (newPrice > 0 && effectiveDate) {
                            // Simpan harga baru dan lakukan top-up
                            saveNewPriceAndTopUp(selectedCustomer, amount, newPrice, effectiveDate);
                        } else {
                            alert('Masukkan harga baru dan tanggal berlaku.');
                        }
                    } else if (selectedPriceId) {
                        // Jika admin memilih harga yang sudah ada, lakukan top-up dengan harga tersebut
                        saveTopUp(selectedCustomer, amount, selectedPriceId);
                    } else {
                        alert('Pilih harga yang valid.');
                    }
                } else {
                    alert('Masukkan jumlah poin dan pilih pengguna.');
                }
            });

            // Simpan harga baru dan lakukan top-up
            function saveNewPriceAndTopUp(customerId, amount, newPrice, effectiveDate) {
                $.ajax({
                    url: "{{ route('topup-points') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        customer_id: customerId,
                        topup_amount: amount,
                        new_price: newPrice,
                        effective_date: effectiveDate
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Top-up berhasil disimpan dengan harga baru.');
                            $('#topupModal').modal('hide');
                        }
                    },
                    error: function() {
                        alert('Gagal menyimpan top-up.');
                    }
                });
            }

            // Simpan top-up dengan harga yang sudah ada
            function saveTopUp(customerId, amount, priceId) {
                $.ajax({
                    url: "{{ route('topup-points') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        customer_id: customerId,
                        topup_amount: amount,
                        price_per_kg_id: priceId
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Top-up berhasil disimpan.');
                            $('#topupModal').modal('hide');
                        }
                    },
                    error: function() {
                        alert('Gagal menyimpan top-up.');
                    }
                });
            }
        });
    </script>
    {{-- <script>
        var table = $('#tablePurchaseTop-up').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('Top-up.data') }}",
                method: 'GET',
                data: function(d) {
                    d.startDate = $('#startDate').val();
                    d.endDate = $('#endDate').val();
                    d.status = $('#filterStatus').val();
                },
                error: function(xhr, error, thrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load Top-up data. Please try again!',
                        confirmButtonText: 'OK'
                    });
                }
            },
            columns: [{
                    data: 'kode_pembayaran',
                    name: 'a.kode_pembayaran'
                },
                {
                    data: 'no_invoice',
                    name: 'b.no_invoice'
                },
                {
                    data: 'amount',
                    name: 'a.amount',
                    render: function(data, type, row) {
                        return parseFloat(data).toLocaleString('id-ID', {
                            style: 'decimal',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });
                    }
                },
                {
                    data: 'Top-up_method',
                    name: 'c.name'
                },
                {
                    data: 'status_bayar',
                    name: 'b.status_bayar'
                },
                {
                    data: 'tanggal_bayar',
                    name: 'tanggal_bayar',
                    searchable: false
                }
            ],
            lengthChange: false,
            pageLength: 7,
            language: {
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

        $('#filterStatus').change(function() {
            table.ajax.reload();
        });



        $('#exportBtn').on('click', function() {
            var status = $('#filterStatus').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            $.ajax({
                url: "{{ route('exportTop-up') }}",
                type: 'GET',
                data: {
                    status: status,
                    startDate: startDate,
                    endDate: endDate
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data) {
                    var blob = new Blob([data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Top-up Customers.xlsx";
                    link.click();
                },
                error: function() {
                    Swal.fire({
                        title: "Export failed!",
                        icon: "error"
                    });
                }
            });
        });
    </script> --}}
@endsection
