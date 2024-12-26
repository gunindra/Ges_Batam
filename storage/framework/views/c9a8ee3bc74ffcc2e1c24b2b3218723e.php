<table>
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="6">Asset Report</td>
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
        <tr></tr>
        <tr>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Date</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Asset Name</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Estimated Age</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Beginning Value</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Ending Value</th>

        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $asset; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assets): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e(\Carbon\Carbon::parse($assets->acquisition_date)->format('d M Y')); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($assets->asset_name); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($assets->estimated_age); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($assets->acquisition_price); ?>

                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    <?php echo e($assets->beginning_balance); ?>

                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table><?php /**PATH C:\xampp\htdocs\GES\GES-Project\resources\views\exportExcel\assetreport.blade.php ENDPATH**/ ?>