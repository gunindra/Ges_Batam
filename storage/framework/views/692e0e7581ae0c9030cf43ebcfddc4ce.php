<?php $__env->startSection('title', 'Driver'); ?>

<?php $__env->startSection('main'); ?>
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 px-2">Driver</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Driver</li>
            </ol>
        </div>


        <!-- Modal Batal Kirim -->
        <div class="modal fade" id="batalModal" tabindex="-1" aria-labelledby="batalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="batalModalLabel">Alasan Pembatalan Kirim</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="batalForm">
                            <div class="mb-3">
                                <label for="alasanBatal" class="form-label">Masukkan alasan pembatalan:</label>
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
                                <?php $__currentLoopData = $listInvoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($Invoice->invoice_id); ?>"><?php echo e($Invoice->no_invoice); ?>

                                        (<?php echo e($Invoice->marking); ?> - <?php echo e($Invoice->nama_pembeli); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
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
                if (selectedInvoices.length > 0) {
                    $.ajax({
                        url: '<?php echo e(route('jumlahresi')); ?>',
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
                        url: '<?php echo e(route('tambahdata')); ?>',
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
                    url: '<?php echo e(route('batalAntar')); ?>',
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\ilono\OneDrive\Desktop\Project SAC\pt-ges-project\resources\views\supir\indexsupir.blade.php ENDPATH**/ ?>