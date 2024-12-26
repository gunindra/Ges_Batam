<?php $__env->startSection('title', '| Acoounting | COA'); ?>

<?php $__env->startSection('main'); ?>


    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">

        <!-- Modal tambah COA -->
        <div class="modal fade" id="modalTambahCOA" tabindex="-1" role="dialog" aria-labelledby="modalTambahCOATitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahCOATitle">Add New Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="mt-3">
                            <label for="groupAccount" class="form-label fw-bold">Group Account</label>
                            <select class="form-control" id="groupAccount" required>
                                <option value="" selected>Select Group Account</option>
                                <?php $__currentLoopData = $groupAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>"><?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div id="errGroupAccount" class="text-danger mt-1 d-none">Silahkan pilih group account</div>
                        </div>
                        <div class="mt-3">
                            <label for="codeAccountID" class="form-label fw-bold">Code Account ID*</label>
                            <input type="text" class="form-control" id="codeAccountID" placeholder="Input Account ID"
                                required>
                            <div id="errCodeAccountID" class="text-danger mt-1 d-none">
                            Silahkan Masukkan Code Account
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="nameAccount" class="form-label fw-bold">Nama*</label>
                            <input type="text" class="form-control" id="nameAccount" placeholder="Input Name" required>
                            <div id="errNameAccount" class="text-danger mt-1 d-none">  Silahkan Masukkan Nama</div>
                        </div>
                        <div class="mt-3">
                            <label for="descriptionAccount" class="form-label fw-bold">Description</label>
                            <input type="text" class="form-control" id="descriptionAccount"
                                placeholder="Input Description">
                        </div>
                        <div class="mt-3">
                            <label for="setGroup" class="form-label fw-bold">Set as Group</label>
                            <div>
                                <input type="checkbox" id="setGroup" name="setGroup"> Yes
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="defaultPosisi" class="form-label fw-bold">Default Posisi*</label>
                            <select class="form-control" id="defaultPosisi" required>
                                <option value="" disabled selected>Select Default Position</option>
                                <option value="Debit">Debit</option>
                                <option value="Credit">Credit</option>
                            </select>
                            <div id="errDefaultPosisi" class="text-danger mt-1 d-none">Silahkan Pilih Posisi</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveCOA" class="btn btn-primary">Save COA</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal tambah COA -->


        <!-- Modal Update COA -->
        <div class="modal fade" id="modalUpdateCOA" tabindex="-1" role="dialog" aria-labelledby="modalUpdateCOATitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalUpdateCOATitle">Update Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="editGroupAccount" class="form-label fw-bold">Group Account</label>
                            <select class="form-control" id="editGroupAccount" required>
                                <option value="" selected>Select Group Account</option>
                                <?php $__currentLoopData = $groupAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($coa->id); ?>"><?php echo e($coa->code_account_id); ?> - <?php echo e($coa->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div id="errEditGroupAccount" class="text-danger mt-1 d-none">Please select a group account
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="editCodeAccountID" class="form-label fw-bold">Code Account ID*</label>
                            <input type="text" class="form-control" id="editCodeAccountID"
                                placeholder="Input Account ID" required>
                            <div id="errEditCodeAccountID" class="text-danger mt-1 d-none">Silahkan Masukkan Code Account</div>
                        </div>
                        <div class="mt-3">
                            <label for="editNameAccount" class="form-label fw-bold">Name*</label>
                            <input type="text" class="form-control" id="editNameAccount" placeholder="Input Name"
                                required>
                            <div id="errEditNameAccount" class="text-danger mt-1 d-none">Silahkan Masukkan Nama</div>
                        </div>
                        <div class="mt-3">
                            <label for="editDescriptionAccount" class="form-label fw-bold">Description</label>
                            <input type="text" class="form-control" id="editDescriptionAccount"
                                placeholder="Input Description">
                        </div>
                        <div class="mt-3">
                            <label for="editSetGroup" class="form-label fw-bold">Set as Group</label>
                            <div>
                                <input type="checkbox" id="editSetGroup" name="editSetGroup"> Yes
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="editDefaultPosisi" class="form-label fw-bold">Default Posisi*</label>
                            <select class="form-control" id="editDefaultPosisi" required>
                                <option value="" disabled selected>Select Default Position</option>
                                <option value="Debit">Debit</option>
                                <option value="Credit">Credit</option>
                            </select>
                            <div id="errEditDefaultPosisi" class="text-danger mt-1 d-none">This field is required</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" id="updateCOA" class="btn btn-primary">Update COA</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Update COA -->


        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">COA</h1>
            
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahCOA" id="modalTambahBook"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah</button>
                        </div>

                        <h4 class="pt-5 ml-3">Daftar Account</h4>
                        <div class="ml-3 table-responsive" id="containerListCOA">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!---Container Fluid-->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $(document).ready(function() {
            // Load daftar COA dengan spinner saat data di-load
            function loadCOAList() {
                const loadSpin = `
            <div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        `;

                $('#containerListCOA').html(loadSpin);

                $.ajax({
                    url: "<?php echo e(route('getlistcoa')); ?>",
                    method: 'GET',
                    success: function(response) {
                        $('#containerListCOA').html(response.html);
                        // Initialize DataTable
                        $('#tableCOA').DataTable({
                            // You can customize options here
                            "paging": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "lengthChange": true
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading COA list:", error);
                        $('#containerListCOA').html('<p>Gagal memuat data. Silakan coba lagi.</p>');
                    }
                });
            }

            loadCOAList();

            $('#groupAccount').on('change', function() {
                const selectedSubChildId = $(this).val();

                $.ajax({
                    url: "<?php echo e(route('getNextAccountCode')); ?>",
                    method: 'GET',
                    data: {
                        accountId: selectedSubChildId
                    },
                    success: function(response) {
                        $('#codeAccountID').val(response.next_account_code);
                    },
                    error: function(error) {
                        console.log('Error fetching account code:', error);
                    }
                });

            });



            // Tambah COA
            $('#saveCOA').on('click', function() {
                var codeAccountID = $('#codeAccountID').val();
                var groupAccount = $('#groupAccount').val();
                var nameAccount = $('#nameAccount').val();
                var descriptionAccount = $('#descriptionAccount').val();
                var setGroup = $('#setGroup').is(':checked') ? 1 : 0;
                var defaultPosisi = $('#defaultPosisi').val();

                // Validasi input
                if (!codeAccountID || !nameAccount || !defaultPosisi) {
                    if (!codeAccountID) $('#errCodeAccountID').removeClass('d-none');
                    if (!nameAccount) $('#errNameAccount').removeClass('d-none');
                    if (!defaultPosisi) $('#errDefaultPosisi').removeClass('d-none');
                    return;
                }

                // Tampilkan konfirmasi sebelum eksekusi
                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Anda akan menambahkan COA baru",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5D87FF',
                    cancelButtonColor: '#49BEFF',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Harap tunggu, sedang menyimpan data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Kirim data dengan AJAX
                        $.ajax({
                            url: '/coa/store',
                            method: 'POST',
                            data: {
                                code_account_id: codeAccountID,
                                group_account: groupAccount,
                                name: nameAccount,
                                description: descriptionAccount,
                                set_group: setGroup,
                                default_position: defaultPosisi,
                                _token: '<?php echo e(csrf_token()); ?>'
                            },
                            success: function(response) {
                                Swal.close();
                                if (response.success) {
                                    showMessage("success", "COA Berhasil ditambahkan")
                                        .then(
                                            () => {
                                                location.reload();
                                            });
                                }
                            },
                            error: function(response) {
                                Swal.close();
                                showMessage("error",
                                    "Terjadi kesalahan, coba lagi nanti");
                            }
                        });
                    }
                });
            });

            // Edit COA
            $(document).on('click', '.editCOA', function(e) {
                var coaId = $(this).data('id');
                $.ajax({
                    url: '/coa/' + coaId,
                    method: 'GET',
                    success: function(response) {
                        $('#editCodeAccountID').val(response.code_account_id);
                        $('#editGroupAccount').val(response.parent_id);
                        $('#editNameAccount').val(response.name);
                        $('#editDescriptionAccount').val(response.description);
                        $('#editSetGroup').prop('checked', response.set_as_group);
                        $('#editDefaultPosisi').val(response.default_posisi);
                        $('#modalUpdateCOA').modal('show');
                        $('#updateCOA').data('id', coaId);
                    },
                    error: function() {
                        showMessage("error", "Terjadi kesalahan saat mengambil data COA");
                    }
                });
            });

            // Konfirmasi untuk Update COA
            $('#updateCOA').on('click', function() {
                var coaId = $(this).data('id');
                var codeAccountID = $('#editCodeAccountID').val();
                var groupAccount = $('#editGroupAccount').val();
                var nameAccount = $('#editNameAccount').val();
                var descriptionAccount = $('#editDescriptionAccount').val();
                var setGroup = $('#editSetGroup').is(':checked') ? 1 : 0;
                var defaultPosisi = $('#editDefaultPosisi').val();

                // Validasi sederhana di frontend
                if (!codeAccountID || !nameAccount || !defaultPosisi) {
                    if (!codeAccountID) $('#errEditCodeAccountID').removeClass('d-none');
                    if (!nameAccount) $('#errEditNameAccount').removeClass('d-none');
                    if (!defaultPosisi) $('#errEditDefaultPosisi').removeClass('d-none');
                    return;
                }

                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Anda akan memperbarui COA ini",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5D87FF',
                    cancelButtonColor: '#49BEFF',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan SweetAlert2 loading spinner
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Harap tunggu, sedang memperbarui data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Kirim data dengan AJAX
                        $.ajax({
                            url: '/coa/update/' + coaId,
                            method: 'PUT',
                            data: {
                                code_account_id: codeAccountID,
                                group_account: groupAccount,
                                name: nameAccount,
                                description: descriptionAccount,
                                set_as_group: setGroup,
                                default_posisi: defaultPosisi,
                                _token: '<?php echo e(csrf_token()); ?>'
                            },
                            success: function(response) {
                                Swal.close(); // Tutup spinner
                                if (response.success) {
                                    showMessage("success", "COA berhasil diperbarui")
                                        .then(
                                            () => {
                                                location.reload();
                                            });
                                }
                            },
                            error: function(response) {
                                Swal.close();
                                showMessage("error",
                                    "Terjadi kesalahan, coba lagi nanti");
                            }
                        });
                    }
                });
            });

            // Konfirmasi untuk Hapus COA
            $(document).on('click', '.btndeleteCOA', function(e) {
                e.preventDefault(); // Mencegah event bawaan
                var coaId = $(this).data('id');

                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5D87FF',
                    cancelButtonColor: '#49BEFF',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan SweetAlert2 loading spinner
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Harap tunggu, sedang menghapus data.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Kirim data dengan AJAX
                        $.ajax({
                            url: '/coa/delete/' + coaId,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.close(); // Tutup spinner
                                if (response.success) {
                                    showMessage("success", "COA berhasil dihapus").then(
                                        () => {
                                            location.reload();
                                        });
                                } else {
                                    showMessage("error", "Gagal menghapus COA");
                                }
                            },
                            error: function() {
                                Swal.close();
                                showMessage("error",
                                    "Terjadi kesalahan saat menghapus COA");
                            }
                        });
                    }
                });
            });
            $('#modalTambahCOA').on('hidden.bs.modal', function() {
                $('#groupAccount,#codeAccountID,#nameAccount,#descriptionAccount,#defaultPosisi').val('');
                $('#setGroup').prop('checked', false);

                if (!$('#errGroupAccount').hasClass('d-none')) {
                    $('#errGroupAccount').addClass('d-none');
                }
                if (!$('#errCodeAccountID').hasClass('d-none')) {
                    $('#errCodeAccountID').addClass('d-none');
                }
                if (!$('#errNameAccount').hasClass('d-none')) {
                    $('#errNameAccount').addClass('d-none');
                }
                if (!$('#errDefaultPosisi').hasClass('d-none')) {
                    $('#errDefaultPosisi').addClass('d-none');
                }
            });
            $('#modalUpdateCOA').on('hidden.bs.modal', function() {
                $('#editCodeAccountID,#editNameAccount,#editDescriptionAccount,#editDefaultPosisi').val('');
                if (!$('#errEditGroupAccount').hasClass('d-none')) {
                    $('#errEditGroupAccount').addClass('d-none');
                }
                if (!$('#errEditCodeAccountID').hasClass('d-none')) {
                    $('#errEditCodeAccountID').addClass('d-none');
                }
                if (!$('#errEditNameAccount').hasClass('d-none')) {
                    $('#errEditNameAccount').addClass('d-none');
                }
                if (!$('#errEditDefaultPosisi').hasClass('d-none')) {
                    $('#errEditDefaultPosisi').addClass('d-none');
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\accounting\coa\indexcoa.blade.php ENDPATH**/ ?>