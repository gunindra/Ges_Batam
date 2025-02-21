@extends('layout.main')

@section('title', 'Driver')

@section('main')


    <style>
        @media (max-width: 576px) {

            /* Maksimal 576px (Mobile View) */
            .modal-dialog {
                max-width: 90%;
                /* Modal lebih kecil di HP */
            }

            .modal-content {
                font-size: 12px;
                /* Mengecilkan tulisan di dalam modal */
                padding: 10px;
                /* Kurangi padding agar lebih kompak */
            }

            .modal-title {
                font-size: 14px;
                /* Judul modal lebih kecil */
            }

            .table-responsive {
                overflow-x: auto;
                /* Supaya tabel bisa di-scroll */
                max-height: 300px;
                /* Batasi tinggi tabel agar tidak terlalu panjang */
            }

            .table th,
            .table td {
                font-size: 12px;
                /* Ukuran font dalam tabel lebih kecil */
                padding: 5px;
                /* Kurangi padding agar tabel lebih ringkas */
            }

            .btn-sm {
                font-size: 12px;
                /* Ukuran tombol lebih kecil */
                padding: 5px 10px;
            }

            .dataTables_wrapper .dataTables_filter {
                float: none;
                text-align: center;
                /* Tengah di layar HP */
                margin-bottom: 10px;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
                font-size: 12px;
                /* Ukuran font lebih kecil */
                padding: 5px;
            }

            .dataTables_wrapper .dataTables_paginate {
                display: flex;
                justify-content: center;
                /* Tengah di layar */
                font-size: 12px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 5px 8px;
                margin: 2px;
                font-size: 12px;
                /* Pagination lebih kecil */
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_info {
                font-size: 12px;
                /* Ukuran teks lebih kecil */
                text-align: center;
                /* Tengah di layar */
                margin-bottom: 10px;
            }


        }
    </style>

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 px-2">Driver</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Driver</li>
            </ol>
        </div>


        <!-- Modal Detail Invoice -->
        <div class="modal fade" id="detailInvoiceModal" tabindex="-1" aria-labelledby="detailInvoiceModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Gunakan modal-lg untuk desktop -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Invoice</h5>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="table-responsive">
                                <table id="tableDetailInvoice" class="table table-bordered table-striped text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No Resi</th>
                                            <th>No DO</th>
                                            <th>Quantity</th>
                                            <th>Harga</th>
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
                        <button type="button"  id="closeModal"class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>




        <!-- Modal Batal Kirim -->
        <div class="modal fade" id="batalModal" tabindex="-1" aria-labelledby="batalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="batalModalLabel">Pembatalan Kirim</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="batalForm">
                            <div class="mb-3">
                                <label for="alasanBatal" class="form-label">Masukkan alasan pembatalan (Optional)</label>
                                <textarea class="form-control" id="alasanBatal" rows="3" placeholder="Tuliskan alasan di sini..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-danger" id="submitBatal">Batalkan Kirim</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3 px-3">
            <div class="col-xl-12 px-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="m-0 font-weight-bold text-primary">Invoice</h6>
                        <div class="d-flex justify-content-center mb-2 mr-3 mt-3">
                            <select class="form-control" id="selectResi" style="width: 500px;" multiple="multiple">
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
                            <h4 class="font-weight-bold mt-2">Grand Total: <span id="grandTotal">Rp. 0</span></h4>
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
                        <h6 class="m-0 font-weight-bold text-primary">Bukti Pengantaran</h6>
                        <div class="my-3">
                            <label for="pengantaranStatus" class="form-label fw-bold">Masukkan Bukti Pengantaran</label>
                            <input type="file" class="form-control" id="photo" name="photo">
                            <div id="imageSupirError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
                        </div>

                        <label for="pengantaranStatus" class="form-label fw-bold">Tanda Tangan Bawah ini</label>
                        <div class="preview mt-3" id="previewContainer"
                            style="border:1px solid black; height: 250px; border-radius:10px;">
                            <canvas id="signature-pad" style="width: 100%; height: 100%;"></canvas>
                        </div>

                        <div class="mt-3">
                            <button id="clear" class="btn btn-danger">Hapus</button>
                            <button id="save" class="btn btn-success">Simpan</button>
                            <button id="batal" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#batalModal">Batal Kirim</button>
                            <input type="hidden" name="signature" id="signatureData" value="">
                            <div id="imageSupirError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
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
            $('#batal').on('click', function() {
                $('#batalModal').modal('show');
            });

            $('#selectResi').select2({
                placeholder: 'Pilih No.Invoice',
                allowClear: true
            });

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

                            console.log(response);

                            $('#pointValue').text(response.count);
                            $('#grandTotal').text('Rp. ' + response.total_harga);
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

                console.log(selectedInvoices);


                if (selectedInvoices && selectedInvoices.length > 0) {
                    $('#detailInvoiceModal').modal('show');

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
                            responsive: true, // Membuat tabel responsif di HP
                            lengthChange: false, // Nonaktifkan opsi jumlah baris
                            searching: true, // Aktifkan search bar
                            paging: true, // Aktifkan pagination
                            pageLength: 5, // Default tampilkan 5 baris per halaman
                            language: {
                                processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                                emptyTable: "Tidak ada data yang tersedia",
                                loadingRecords: "Sedang memuat...",
                                zeroRecords: "Tidak ditemukan data yang sesuai",
                                paginate: {
                                    first: "Pertama",
                                    last: "Terakhir",
                                    next: "→",
                                    previous: "←"
                                },
                                search: "Cari:",
                            },
                        });
                    } else {
                        // Refresh DataTable jika sudah diinisialisasi
                        $('#tableDetailInvoice').DataTable().ajax.reload(); // Memperbaiki elemen target
                    }
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Tidak ada invoice yang dipilih!',
                    });
                }
            });


            let canvas = document.getElementById('signature-pad');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)'
            });

            function resizeCanvas() {
                var ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad.clear();
            }
            resizeCanvas();
            $(window).on('resize', resizeCanvas);

            $('#clear').on('click', function() {
                signaturePad.clear();
                $('#photo').val('');
            });

            $('#save').on('click', function() {
                // Check if signature pad is empty
                var isSignatureEmpty = signaturePad.isEmpty();
                // Check if photo input has a file
                var photo = $('#photo').get(0).files[0];
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];

                // If both are empty, show a warning
                if (isSignatureEmpty && !photo) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tanda tangan atau foto wajib diisi!',
                        text: 'Harap isi minimal tanda tangan atau unggah foto.'
                    });
                    return;
                }

                // If photo is present, validate file type
                if (photo && !validExtensions.includes(photo.type)) {
                    $('#imageSupirError').text('Hanya file JPG, JPEG, atau PNG yang diizinkan.')
                        .removeClass('d-none');
                    return;
                } else {
                    $('#imageSupirError').addClass('d-none');
                }

                // Check if any invoice is selected
                if ($('#selectResi').val() === null || $('#selectResi').val().length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invoice belum dipilih!',
                        text: 'Harap pilih invoice terlebih dahulu.'
                    });
                    return;
                }

                // Prepare the data for submission
                canvas.toBlob(function(blob) {
                    var formData = new FormData();
                    if (!isSignatureEmpty) {
                        formData.append('signature', blob, 'signature.png');
                    }
                    if (photo) {
                        formData.append('photo', photo);
                    }
                    formData.append('selectedValues', $('#selectResi').val());

                    Swal.fire({
                        title: 'Sedang memproses...',
                        text: 'Harap menunggu hingga proses selesai',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('tambahdata') }}',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.close();
                            showMessage("success", "Data berhasil diupdate!").then(
                                () => {
                                    location.reload();
                                });
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


            $(document).on('click', '#closeModal', function() {
                $('#detailInvoiceModal').modal('hide');
                $('#tableDetailInvoice tbody').empty(); // Menghapus semua isi tabel
            });

            $('#submitBatal').on('click', function() {

                const alasanBatal = $('#alasanBatal').val();

                var formData = new FormData();

                formData.append('selectedValues', $('#selectResi').val());
                formData.append('alasan', alasanBatal);

                Swal.fire({
                    title: 'Sedang memproses...',
                    text: 'Harap menunggu hingga proses selesai',
                    icon: 'info',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '{{ route('batalAntar') }}',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.close();
                        showMessage("success", "Data berhasil diupdate!").then(() => {
                            location.reload();
                        });
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
            })
        });
    </script>
@endsection
