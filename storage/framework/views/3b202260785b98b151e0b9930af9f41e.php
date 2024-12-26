<?php $__env->startSection('title', ' Content | Information'); ?>

<?php $__env->startSection('main'); ?>

<div class="container-fluid" id="container-wrapper">
<div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Broadcast</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Broadcast</li>
        </ol>
    </div>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex mb-2 mr-3 float-right">
                    <a href="<?php echo e(route('wa.broadcast.new')); ?>" class="btn btn-primary">
                        <span class="pr-2"><i class="fas fa-plus"></i></span> Tambah Broadcast
                    </a>
                </div>
                <div class="row" style="clear: both">
                    <div class="col-12">
                        <table id="brodcastTable" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Created On</th>
                                    <th>Message</th>
                                    <th>Recipients</th>
                                    <th>Status</th>
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
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    $(document).ready(function() {
        var table = $('#brodcastTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/content/whatsapp/broadcast',
                type: 'GET',
                data: function(d) {}
            },
            columns: [
                { data: 'created_at' },
                { data: 'message' },
                { data: 'recipients', className: "text-center" },
                { data: 'status', className: "text-center" },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>', // Custom spinner saat processing
                info: "_START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                emptyTable: "No data available in table",
                loadingRecords: "Loading...",
                zeroRecords: "No matching records found"
            }
        });

    });

    $(document).on('click', '.btn-resend', function () {
        let broadcastId = $(this).data('id');

        if (confirm('Are you sure you want to resend this broadcast?')) {
            $.ajax({
                url: '<?php echo e(route('wa.broadcast.resend')); ?>',
                type: 'POST',
                data: {
                    id: broadcastId,
                    type: 'broadcast',
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
<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\content\broadcast\index.blade.php ENDPATH**/ ?>