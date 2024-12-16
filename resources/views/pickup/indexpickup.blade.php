@extends('layout.main')

@section('title', 'Pickup')

@section('main')

    <style>
        /* Style responsif untuk modal dan tabel di perangkat tablet */
        @media (max-width: 768px) {
            .modal-dialog {
                max-width: 90%;
                /* Mengatur modal agar lebih lebar di perangkat kecil */
            }

            .table-responsive {
                overflow-x: auto;
                /* Membuat tabel bisa scroll horizontal */
            }

            .modal-content {
                padding: 10px;
                /* Menambah padding di dalam modal untuk responsivitas */
            }

            #tableDetailInvoice {
                font-size: 14px;
                /* Menyesuaikan ukuran font tabel agar tidak terlalu besar */
            }

            .modal-header {
                padding: 10px 15px;
                /* Memastikan header modal tetap rapat */
            }

            .modal-footer {
                padding: 10px 15px;
                /* Memastikan footer modal tetap rapat */
            }
        }

        /* Agar modal tetap responsif pada layar lebih besar dari tablet */
        @media (min-width: 768px) {
            .modal-dialog {
                max-width: 80%;
            }
        }
    </style>


    <!-- Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Admin Verification</h5>
                </div>
                <div class="modal-body">
                    <form id="passwordForm">
                        <div class="form-group">
                            <label for="adminUsername">Enter Admin Username</label>
                            <input type="text" class="form-control" id="adminUsername" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <label for="adminPassword">Enter Admin Password</label>
                            <input type="password" class="form-control" id="adminPassword" placeholder="Password" required>
                            <small id="error-message" class="text-danger mt-2 d-none">Password Yang dimasukkan salah</small>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submitPassword" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Invoice -->
    <div class="modal fade" id="detailInvoiceModal" tabindex="-1" aria-labelledby="detailInvoiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailInvoiceModalLabel">Detail Invoice</h5>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="table-responsive">
                            <table id="tableDetailInvoice" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No Resi</th>
                                        <th>No DO</th>
                                        <th>Harga</th>
                                        <th>Quantity</th> <!-- Kolom baru untuk Quantity -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan dimasukkan secara dinamis -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pickup</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Costumer</li>
                <li class="breadcrumb-item active" aria-current="page">Pickup</li>
            </ol>
        </div>
        <div class="row mb-3 px-3">
            <div class="col-xl-12 px-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="m-0 font-weight-bold text-primary">Invoice</h6>
                        <div class="d-flex justify-content-center mb-2 mr-3 mt-3">
                            <select class="form-control" id="selectResi" style="width: 800px;" multiple="multiple">
                                <option value="" disabled>Pilih No.Invoice</option>
                                @foreach ($listInvoice as $Invoice)
                                    <option value="{{ $Invoice->invoice_id }}">{{ $Invoice->no_invoice }}
                                        ({{ $Invoice->marking }} - {{ $Invoice->nama_pembeli }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center mt-3">
                            <h1 id="pointValue" class="display-3 font-weight-bold text-primary" value="0">0</h1>
                            <p class="text-muted">Jumlah Resi</p>
                            <button class="btn btn-primary" id="detailInvoice">Detail Invoice</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3 px-3">
            <div class="col-xl-12 px-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="m-0 font-weight-bold text-primary">Tanda Tangan</h6>
                        <div class="row">
                            <!-- Signature 1 -->
                            <div class="col-md-6">
                                <label for="signature1" class="form-label fw-bold text-center w-100 mt-2">Admin</label>
                                <div class="preview mt-1" id="previewContainer1"
                                    style="border:1px solid black; height: 250px; border-radius:10px;">
                                    <canvas id="signature-pad-1" style="width: 100%; height: 100%;"></canvas>
                                </div>
                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                    <button id="clear" class="btn btn-danger btn-sm">Hapus</button>
                                </div>
                            </div>

                            <!-- Signature 2 -->
                            <div class="col-md-6">
                                <label for="signature2" class="form-label fw-bold text-center w-100 mt-2">Customer</label>
                                <div class="preview mt-1" id="previewContainer2"
                                    style="border:1px solid black; height: 250px; border-radius:10px;">
                                    <canvas id="signature-pad-2" style="width: 100%; height: 100%;"></canvas>
                                </div>
                                <div class="mt-2 d-flex justify-content-between align-items-center">
                                    <button id="clear1" class="btn btn-danger btn-sm">Hapus</button>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button Centered -->
                        <div class="mt-4 text-center">
                            <button id="save" class="btn btn-success mt-3 w-50">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')|

    <script>
        $(document).ready(function() {
            $('#passwordModal').modal({
                backdrop: 'static',
                keyboard: false
            });


            $('#adminPassword').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#submitPassword').click();
                }
            });

            $('#submitPassword').on('click', function(e) {
                e.preventDefault();

                const enteredUsername = $('#adminUsername').val();
                const enteredPassword = $('#adminPassword').val();

                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '{{ route('cekPassPickup') }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        username: enteredUsername,
                        password: enteredPassword
                    },
                    success: function(response) {
                        Swal.close();
                        if (response.valid) {
                            $('#passwordModal').modal('hide');

                            // Menyimpan username untuk pengiriman berikutnya
                            localStorage.setItem('verifiedUsername', enteredUsername);

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Password valid, proses berhasil.',
                            });
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gagal',
                                text: response.message,
                            });
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan',
                            text: 'Tidak dapat memproses permintaan. Silakan coba lagi nanti.',
                        });
                    }
                });
            });



        });
    </script>
    <script>
        $(document).ready(function() {


            $('#selectResi').select2({
                placeholder: 'Pilih No.Invoice',
                allowClear: true,
                width: 'resolve',
                closeOnSelect: false
            });
            // Setup for selectResi
            $('#selectResi').on('change', function() {
                var selectedInvoices = $(this).val();
                $('#detailInvoice').data('selected-invoices', selectedInvoices);
                if (selectedInvoices.length > 0) {
                    $.ajax({
                        url: '{{ route('jumlahresi') }}',
                        type: 'GET',
                        data: {
                            invoice_ids: selectedInvoices
                        },
                        success: function(response) {
                            $('#pointValue').text(response.count);
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    });
                } else {
                    $('#pointValue').text(0);
                }
            });

            $(document).on('click', '#detailInvoice', function() {
                var selectedInvoices = $(this).data('selected-invoices');

                if (selectedInvoices && selectedInvoices.length > 0) {


                    $('#detailInvoiceModal').modal('show');
                    // Inisialisasi DataTable jika belum ada
                    if (!$.fn.DataTable.isDataTable('#tableDetailInvoice')) {
                        $('#tableDetailInvoice').DataTable({
                            serverSide: true,
                            processing: true,
                            ajax: {
                                url: "{{ route('getDetailInvoice') }}", // URL endpoint ke backend
                                method: 'GET',
                                data: function(d) {
                                    // Pastikan `selectedInvoices` adalah array
                                    d.invoice_ids = selectedInvoices || [];
                                }
                            },
                            columns: [{
                                    data: 'no_resi',
                                    name: 'no_resi'
                                }, // Kolom nomor resi
                                {
                                    data: 'no_do',
                                    name: 'no_do'
                                }, // Kolom nomor DO
                                {
                                    data: 'berat_or_volume',
                                    name: 'berat_or_volume'
                                }, // Berat/Volume
                                {
                                    data: 'harga',
                                    name: 'harga',
                                    render: function(data, type, row) {
                                        // Format angka ke dalam format mata uang
                                        return new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR'
                                        }).format(data);
                                    }
                                },
                            ],
                            order: [], // Tidak ada pengurutan default
                            lengthChange: false, // Nonaktifkan opsi untuk mengubah jumlah baris yang ditampilkan
                            language: {
                                processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                                // infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                                emptyTable: "Tidak ada data yang tersedia",
                                loadingRecords: "Sedang memuat...",
                                zeroRecords: "Tidak ditemukan data yang sesuai",
                                paginate: {
                                    first: "Pertama",
                                    last: "Terakhir",
                                    next: "Berikutnya",
                                    previous: "Sebelumnya"
                                }
                            }
                        });
                    } else {
                        // Refresh DataTable jika sudah diinisialisasi
                        $('#tableDetailInvoice').DataTable().ajax.reload(); // Memperbaiki elemen target
                    }
                } else {
                    // Menampilkan pesan SweetAlert jika tidak ada invoice yang dipilih
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Tidak ada invoice yang dipilih!',
                    });
                }
            });

            $(document).on('click', '#closeModal', function() {
                $('#detailInvoiceModal').modal('hide');
            });




            // Initialize signature pads for admin and customer
            let canvas1 = document.getElementById('signature-pad-1');
            let canvas2 = document.getElementById('signature-pad-2');


            const signaturePad1 = new SignaturePad(canvas1, {
                backgroundColor: 'rgb(255, 255, 255)'
            });
            const signaturePad2 = new SignaturePad(canvas2, {
                backgroundColor: 'rgb(255, 255, 255)'
            });

            function resizeCanvas(canvas, signaturePad) {
                var ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad1.clear();
                signaturePad2.clear();
            }

            $('#clear').on('click', function() {
                signaturePad1.clear();
            });

            $('#clear1').on('click', function() {
                signaturePad2.clear();
            });

            resizeCanvas(canvas1, signaturePad1);
            resizeCanvas(canvas2, signaturePad2);

            $(window).on('resize', function() {
                resizeCanvas(canvas1, signaturePad1);
                resizeCanvas(canvas2, signaturePad2);
            });

            $('#save').on('click', function() {
                var selectedInvoices = $('#selectResi').val();
                if (!selectedInvoices || selectedInvoices.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invoice belum dipilih!',
                        text: 'Harap pilih invoice terlebih dahulu.'
                    });
                    return;
                }

                var isSignature1Empty = signaturePad1.isEmpty();
                var isSignature2Empty = signaturePad2.isEmpty();
                if (isSignature1Empty && isSignature2Empty) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tanda tangan wajib diisi!',
                        text: 'Harap isi tanda tangan admin dan/atau customer.'
                    });
                    return;
                }

                var formData = new FormData();
                formData.append('selectedValues', selectedInvoices);

                // Ambil username yang telah diverifikasi sebelumnya
                const verifiedUsername = localStorage.getItem('verifiedUsername');
                if (verifiedUsername) {
                    formData.append('verified_username', verifiedUsername);
                }


                // Show loading Swal before AJAX call
                Swal.fire({
                    title: 'Sedang memproses...',
                    text: 'Harap menunggu hingga proses selesai',
                    icon: 'info',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Process blobs and submit only after both are ready
                let promises = [];

                if (!isSignature1Empty) {
                    promises.push(new Promise(resolve => {
                        canvas1.toBlob(function(blob) {
                            formData.append('admin_signature', blob,
                                'admin_signature.png');
                            resolve();
                        });
                    }));
                }

                if (!isSignature2Empty) {
                    promises.push(new Promise(resolve => {
                        canvas2.toBlob(function(blob) {
                            formData.append('customer_signature', blob,
                                'customer_signature.png');
                            resolve();
                        });
                    }));
                }

                Promise.all(promises).then(() => {
                    // Send AJAX only after all blobs are ready
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('updateStatus') }}',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Data berhasil diupdate!'
                            }).then(() => location.reload());
                        },
                        error: function(xhr, status, error) {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat menyimpan data.'
                            });
                        }
                    });
                });
            });
        });
    </script>
@endsection
