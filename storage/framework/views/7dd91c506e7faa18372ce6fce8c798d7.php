<table>
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="6">TopUp Report</td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">Start Date:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                <?php echo e($startDate ? $startDate : '-'); ?>

            </td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">End Date:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                <?php echo e($endDate ? $endDate : '-'); ?>

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
                bgcolor="#b9bab8">Topup Date</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Customer</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">In (kg)</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Out (kg)</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Saldo (kg)</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $topup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topups): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e(\Carbon\Carbon::parse( $topups->date)->format('d M Y')); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($topups->customer_name); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($topups->remaining_points); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($topups->remaining_points - $topups->balance); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($topups->balance); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($topups->status); ?>

                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\exportExcel\topupreport.blade.php ENDPATH**/ ?>