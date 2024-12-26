<table>
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="6">Export Ongoing Invoice</td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">No Do:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                <?php echo e($NoDo ? $NoDo : '-'); ?>

            </td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">Nama Customer:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                <?php echo e($customer ? $customer : '-'); ?>

            </td>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">No. Invoice</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Open Invoice</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Customer</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">No. DO</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Nama Supir</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Tanggal Pengantaran</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $OngoingInvoice; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $OngoingInvoices): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($OngoingInvoices->no_invoice); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e(\Carbon\Carbon::parse( $OngoingInvoices->tanggal_buat) ->format('d M Y')); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($OngoingInvoices->nama_pembeli); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($OngoingInvoices->no_do); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($OngoingInvoices->nama_supir); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e(\Carbon\Carbon::parse( $OngoingInvoices->tanggal_pengantaran )->format('d M Y')); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($OngoingInvoices->status_transaksi); ?>

                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\exportExcel\ongoinginvoice.blade.php ENDPATH**/ ?>