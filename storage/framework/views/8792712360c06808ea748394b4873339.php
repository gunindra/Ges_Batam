<?php $__env->startSection('title', 'Content | Contact'); ?>

<?php $__env->startSection('main'); ?>

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Broadcast Whatsapp</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Broadcast Whatsapp</li>
        </ol>
    </div>
    <div class="d-flex justify-content-between">
        <a class="btn btn-primary mb-3" href="<?php echo e(route('wa.broadcast')); ?>">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
    <div class="row mb-3 d-flex">
        <div class="col-xl-12">
            <form action="<?php echo e(route('wa.broadcast.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info">
                            Note: Check "Send to all customer" to broadcast message to all current active customer.
                        </div>
                        <div class="mt-3">
                            <label for="messageWa" name="media" class="form-label fw-bold">Media (Optional, Max 4
                                MB)</label>
                            <input type="file" class="form-control" />
                            <div id="mediaError" class="text-danger mt-1 d-none">Silahkan pilih Gambar</div>
                        </div>
                        <div class="mt-3">
                            <label for="messageWa" class="form-label fw-bold">Pesan Broadcast</label>
                            <textarea class="form-control" id="message" name="message" rows="5"
                                placeholder="Masukkan Pesan WhatsApp"><?php echo e(isset($waData->Message_wa) ? $waData->Message_wa : ''); ?></textarea>
                            <div id="messageWaError" class="text-danger mt-1 d-none">Silahkan isi Pesan</div>
                        </div>
                        <div class="mt-3">
                            <div>
                                Recipients
                                <button type="button" class="btn btn-sm btn-secondary float-right ml-3"
                                    data-toggle="modal" data-target="#mdlSearchCustomer">Search From Customer</button>
                                <button type="button" onclick="addRow()" class="btn btn-sm btn-info float-right">Add
                                    Recipient</button>
                            </div>
                            <input type="checkbox" name="send_to_all_customer" />Send to all customer
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                </tbody>
                            </table>
                            <div id="tableError" class="text-danger mt-2 d-none">    Silakan isi table dengan lengkap dan pastikan nomor telepon terdiri dari 13 digit. Mohon periksa kembali nomor yang Anda masukkan serta pilih customer.
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">
                            <span class="pr-3"><i class="fas fa-save"></i></span> Send Broadcast
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Find Customer Modal-->
<div class="modal fade" id="mdlSearchCustomer">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Find Customer</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table id="customerTable" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Marking</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    addRow();
    function addRow(recipient = '', phone = '62') {
        var newRow = `
            <tr>
                <td>
                    <input type="text" class="form-control" name="name[]" placeholder="Recipient Name" value="${recipient}" >
                </td>
                <td>
                    <input type="tel" class="form-control" name="phone[]" value="${phone}" placeholder="62" >
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button>
                </td>
            </tr>
            `;
        $('#items-container').append(newRow);
    }
    $(document).on('click', '.removeItemButton', function () {
        $(this).closest('tr').remove();
    });

    $(document).ready(function () {
        var table = $('#customerTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/masterdata/costumer/listbyname',
                type: 'GET',
                data: function (d) {

                }
            },
            columns: [
                { data: 'marking' },
                { data: 'nama_pembeli' },
                { data: 'no_wa' },
                {
                    data: 'id',
                    render: function (data, type, row) {
                        return '<button class="btn btn-success" onclick="addRow(\'' + row.nama_pembeli + '\', \'' + row.no_wa + '\')">Select</button>';
                    }
                }
            ]
        });

        $('#searchCustomerBtn').on('click', function () {
            table.draw();
        });

        $('form').on('submit', function (e) {
            let isValid = true;
            const message = $('#message').val().trim();

            // Validate message field
            if (!message) {
                $('#messageWaError').removeClass('d-none');
                isValid = false;
            } else {
                $('#messageWaError').addClass('d-none');
            }

            // Validate media (file input)
            const media = $('input[type="file"]').val();
            if (!media) {
                $('#mediaError').removeClass('d-none');
                isValid = false;
            } else {
                $('#mediaError').addClass('d-none');
            }

            // Table validation for name, phone, and phone length
            let isTableValid = true;
            $('#items-container tr').each(function () {
                const name = $(this).find('input[name="name[]"]').val();
                const phone = $(this).find('input[name="phone[]"]').val();

                // Check if name or phone is missing
                if (!name || !phone) {
                    isTableValid = false;
                }

                // Validate phone number length
                if (phone && phone.length !== 13) {
                    isTableValid = false;
                    // Display phone length error
                    $('#tableError').removeClass('d-none');
                } else {
                    $('#tableError').addClass('d-none');
                }
            });

            // Show table validation error if any row is invalid
            if (!isTableValid) {
                $('#tableError').removeClass('d-none');  // Show table error
                isValid = false;  // Prevent form submission
            } else {
                $('#tableError').addClass('d-none');  // Hide table error
            }

            // Prevent form submission if any validation fails
            if (!isValid) {
                e.preventDefault();  // Prevent the form from submitting
            }
        });

    });

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\content\broadcast\new.blade.php ENDPATH**/ ?>