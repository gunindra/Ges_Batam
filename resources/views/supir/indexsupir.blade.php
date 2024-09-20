@extends('layout.main')

@section('title', 'Driver')

@section('main')
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 px-2">Driver</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Driver</li>
            </ol>
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
                                    <option value="{{ $Invoice->invoice_id }}">{{ $Invoice->no_invoice }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-center mt-3">
                            <h1 id="pointValue" class="display-3 font-weight-bold text-primary" value="0">0</h1>
                            <p class="text-muted">Jumlah Resi</p>
                        </div>
                        <div id="containerResi" class="table-responsive px-3"></div>
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
        // Inisialisasi Select2
        $('#selectResi').select2({
            placeholder: 'Pilih No.Invoice',
            allowClear: true
        });
    </script>
    <script>
        $(document).ready(function() {
            // Inisialisasi SignaturePad
            let canvas = document.getElementById('signature-pad');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)'
            });

            // Resize canvas agar sesuai dengan ukuran layar
            function resizeCanvas() {
                var ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad.clear(); // Kosongkan canvas setelah resize
            }
            resizeCanvas();
            $(window).on('resize', resizeCanvas);

            // Clear canvas untuk hapus tanda tangan
            $('#clear').on('click', function() {
                signaturePad.clear();
                $('#photo').val(''); // Kosongkan input file jika ada
            });

            // Fungsi untuk menyimpan tanda tangan
            $('#save').on('click', function() {
                // Validasi tanda tangan kosong
                if (signaturePad.isEmpty()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tanda tangan kosong!',
                        text: 'Harap tanda tangan terlebih dahulu.'
                    });
                    return;
                }

                // Validasi foto yang diupload
                if ($('#photo').get(0).files.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Foto belum diunggah!',
                        text: 'Harap unggah foto terlebih dahulu.'
                    });
                    return;
                }

                // Validasi pilihan invoice
                if ($('#selectResi').val() === null || $('#selectResi').val().length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invoice belum dipilih!',
                        text: 'Harap pilih invoice terlebih dahulu.'
                    });
                    return;
                }

                // Konversi canvas menjadi Blob
                canvas.toBlob(function(blob) {
                    var formData = new FormData();
                    formData.append('signature', blob,
                    'signature.png'); // Tanda tangan dalam bentuk Blob
                    formData.append('photo', $('#photo')[0].files[0]); // Foto yang diupload
                    formData.append('selectedValues', $('#selectResi')
                .val()); // Invoice yang dipilih

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
                        url: '{{ route('tambahdata') }}', // Sesuaikan route sesuai server Anda
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
        });
    </script>
@endsection
