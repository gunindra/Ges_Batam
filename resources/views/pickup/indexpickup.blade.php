@extends('layout.main')

@section('title', 'Pickup')

@section('main')

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
                                <div class="preview mt-3" id="previewContainer1"
                                    style="border:1px solid black; height: 250px; border-radius:10px;">
                                    <canvas id="signature-pad-1" style="width: 100%; height: 100%;"></canvas>
                                </div>
                                <div class="mt-2 text-center">
                                    <label for="signature1" class="form-label fw-bold">Admin</label>
                                </div>
                            </div>

                            <!-- Signature 2 -->
                            <div class="col-md-6">
                                <div class="preview mt-3" id="previewContainer2"
                                    style="border:1px solid black; height: 250px; border-radius:10px;">
                                    <canvas id="signature-pad-2" style="width: 100%; height: 100%;"></canvas>
                                </div>
                                <div class="mt-2 text-center">
                                    <label for="signature2" class="form-label fw-bold">Customer</label>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button Centered -->
                        <div class="mt-4 text-center">
                            <button id="save" class="btn btn-success mt-3 w-50" >Submit</button>
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

            $('#selectResi').select2({
                placeholder: 'Pilih No.Invoice',
                allowClear: true
            });
            // Setup for selectResi
            $('#selectResi').on('change', function() {
                var selectedInvoices = $(this).val();
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
                signaturePad.clear();
            }

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
