<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?php echo e($invoice->no_invoice); ?></title>
    <style>
        @page {
            size: A4;
            margin: 15mm 10mm 15mm 10mm;

            @bottom-right {
                content: "Halaman " counter(page) " dari " counter(pages);
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .title {
            margin-bottom: 10px;
        }

        .title h2 {
            margin: 0;
            color: #444;
        }

        .title h5 {
            margin: 5px 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            margin-top: 20px;
        }

        .summary p {
            margin: 5px 0;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
            }

            .container {
                width: 100%;
                max-width: none;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            button {
                display: none;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        @media screen {
            .container {
                max-width: 900px;
            }
        }

        /* kop */
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .logo-container {
            flex: 0 0 23%;
            padding-right: 15px;
            padding-left: 30px;
        }

        .logo {
            width: 100%;
            max-width: 120px;
            height: auto;
        }

        .company-info {
            flex: 0 0 75%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 90px;
        }

        .company-name {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 10pt;
            line-height: 1.3;
        }

        .document-title {
            font-size: 16pt;
            font-weight: bold;
            margin-top: 15px;
            text-align: center;
            clear: both;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo-container">
                
            </div>
            <div class="company-info">
                <div class="company-name">PT. GES LOGISTIC</div>
                <div class="company-address">
                    42Q2+6PH, Unnamed Road,
                    Batu Selicin, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau<br>
                    Telp: 0856-BATU-KECE (0856-2288-5323) | Email: Pt@batukerenrambut.com
                </div>
            </div>
        </div>

        <div class="title">
            <h5>Tanggal: <?php echo e($invoice->tanggal_bayar); ?></h5>
            <h5>Pembeli: <?php echo e($invoice->pembeli); ?></h5>
            <h2>Invoice: <?php echo e($invoice->no_invoice); ?></h2>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. Resi</th>
                    <th>No. Do</th>
                    <th>Berat/Dimensi</th>
                    <th>Hitungan</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $no = 1;
                ?>
                <?php $__currentLoopData = $resiData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($no++); ?></td>
                        <td><?php echo e($resi->no_resi); ?></td>
                        <td><?php echo e($resi->no_do); ?></td>
                        <?php if($resi->berat): ?>
                            <td>Berat</td>
                        <?php else: ?>
                            <td>Dimensi</td>
                        <?php endif; ?>

                        <?php if($resi->berat): ?>
                            <td><?php echo e($resi->berat); ?></td>
                        <?php else: ?>
                            <td><?php echo e($resi->panjang ?? '0'); ?> x <?php echo e($resi->lebar ?? '0'); ?> x <?php echo e($resi->tinggi ?? '0'); ?> cm
                            </td>
                        <?php endif; ?>
                        <td><?php echo e(number_format($resi->harga, 2) ?? '0'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <div class="summary">
            <p class="text-right">Total Harga: <strong><?php echo e(number_format($hargaIDR, 2)); ?></strong></p>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\Users\Asus\OneDrive\Desktop\SAC\GES-Project\resources\views\exportPDF\invoice.blade.php ENDPATH**/ ?>