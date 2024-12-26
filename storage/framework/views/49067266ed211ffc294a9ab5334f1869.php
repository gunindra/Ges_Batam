<?php $__env->startSection('title', 'Content | Edit Broadcast'); ?>

<?php $__env->startSection('main'); ?>

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Broadcast Whatsapp</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Edit Broadcast Whatsapp</li>
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
            <form action="<?php echo e(route('wa.broadcast.update', $broadcast->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="card">
                    <div class="card-body">
                        <div class="mt-3">
                            <?php if($broadcast->media_path): ?>
                                <p class="mt-2">
                                    Current Media: 
                                    <a href="<?php echo e(asset($broadcast->media_path)); ?>" target="_blank">View Media</a>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="mt-3">
                            <label for="message" class="form-label fw-bold">Pesan Broadcast</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required readonly><?php echo e($broadcast->message); ?></textarea>
                        </div>
                        <div class="mt-3">
                            <div>Recipients</div>
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                    <?php $__currentLoopData = $broadcast->recipients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($recipient->recipient); ?></td>
                                            <td><?php echo e($recipient->phone); ?></td>
                                            <td><?php echo e($recipient->status); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary btn-resend mt-1" data-id="<?php echo e($recipient->id); ?>">Resend</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    $(document).on('click', '.btn-resend', function () {
        let broadcastId = $(this).data('id');

        if (confirm('Are you sure you want to resend this broadcast?')) {
            $.ajax({
                url: '<?php echo e(route('wa.broadcast.resend')); ?>',
                type: 'POST',
                data: {
                    id: broadcastId,
                    type: 'detail',
                    _token: $('meta[name="csrf-token"]').attr('content'), // Ensure CSRF token is included
                },
                success: function (response) {
                    alert(response.message || 'Broadcast resent successfully.');
                    // Optionally reload the DataTable
                    $('#exampleTable').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message || 'Failed to resend broadcast.');
                },
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\content\broadcast\edit.blade.php ENDPATH**/ ?>