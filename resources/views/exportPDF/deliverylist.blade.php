<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery PDF</title>
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
            padding: 10px;
        }

        .title {
            margin-bottom: 10px;
        }

        .title h5 {
            margin: 5px 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

            .layout-table td {
                width: 100%;
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

        .driver-info table {
            width: 100%;
            border-collapse: collapse;
            /* margin-bottom: 20px; */
            border: 1px solid transparent;
            /* Make table border transparent */
        }

        .driver-info h2,
        .driver-info h5 {
            margin: 0;
            /* Menghapus margin atas-bawah */
            padding: 0;
            /* Menghapus padding jika diperlukan */
        }

        .driver-info th,
        .driver-info td {
            border: 1px solid transparent;
            /* Make cell borders transparent */
            /* padding: 8px; */
            text-align: left;
        }

        .layout-table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .layout-table td {
            width: 50%;
            vertical-align: top;
            /* padding: 10px; */
        }

        .invoice-column {
            border: none;
        }

        .signature-section {
            border-collapse: collapse;
            margin-top: 30px;
            width: 100%;
            text-align: center;
            border: 1px solid transparent;
        }

        .signature-section td {
            width: 50%;
            vertical-align: top;
            padding-top: 80px;
            text-align: center;
            border: 1px solid transparent;
        }

        .signature-line {
            border-top: 1px dotted #000;
            width: 200px;
            margin: 0 auto;
        }

        .signature-label {
            margin-top: 5px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo-container">
                <?php
                $path = public_path('img/logo4.png');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                if (file_exists($path)) {
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                } else {
                    $base64 = '';
                }
                ?>
                <img src="<?php echo $base64; ?>" alt="logo" class="logo" />
            </div>
            <div class="company-info">
                <div class="company-name">PT. GES LOGISTIC</div>
                <div class="company-address">
                    42Q2+6PH, Unnamed Road, Batu Selicin, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau<br>
                    Telp: 0856-BATU-KECE (0856-2288-5323) | Email: Pt@batukerenrambut.com
                </div>
            </div>
        </div>

        <!-- Driver and Date Information -->
        <table class="driver-info">
            <tr>
                <td>
                    <h2>Nama Driver: {{ $pengantaran->nama_supir }}</h2>
                </td>
                <td style="text-align: right;">
                    <h5>Tanggal : {{ $pengantaran->tanggal_pengantaran }}</h5>
                </td>
            </tr>
        </table>

        <!-- Invoices Layout in 2 Columns -->

        <!-- Two-column layout using a transparent table -->
        <table class="layout-table">
            <tr>
                @php
                    $count = 0;
                @endphp

                @foreach ($invoices as $invoice)
                    <td class="invoice-column">
                        <table class="driver-info">
                            <tr>
                                <td>
                                    <div>
                                        <h5>Penerima: {{ $invoice->nama_pembeli }}</h5>
                                        <h5>Alamat: {{ $invoice->alamat }}</h5>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <h5 class="text-right">Invoice: {{ $invoice->no_invoice }}</h5>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        {{-- <div class="title">
                            <h5>Penerima: {{ $invoice->nama_pembeli }}</h5>
                            <h5>Alamat: {{ $invoice->alamat }}</h5>
                            <h5 class="text-right">Invoice: {{ $invoice->no_invoice }}</h5>
                        </div> --}}

                        <table>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No. DO</th>
                                    <th>No. Resi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                    $resiList = $invoiceResi->get($invoice->id) ?? collect();
                                @endphp

                                @foreach ($resiList as $resi)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $resi->no_do }}</td>
                                        <td>{{ $resi->no_resi }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>

                    @php
                        $count++;
                    @endphp

                    @if ($count % 2 == 0)
            </tr>
            <tr> <!-- Close and open new row after two invoices -->
                @endif
                @endforeach
            </tr>
        </table>
         <!-- Section Tanda Tangan -->
         <table class="signature-section">
            <tr>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-label">Admin</div>
                </td>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-label">Driver</div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
