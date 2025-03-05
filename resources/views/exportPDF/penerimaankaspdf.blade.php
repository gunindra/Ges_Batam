<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Kas</title>
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
            /* padding-left: 30px; */
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
        <?php
$activeCompanyId = session('active_company_id');
$company = \App\Models\Company::find($activeCompanyId);
        ?>
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
                <div class="company-name">{{ $company->name }}</div>
                <div class="company-address">
                    {{ $company->alamat }}<br>
                    @if ($company->hp && $company->email)
                        Telp: {{ $company->hp }} | Email: {{ $company->email }}
                    @elseif($company->hp)
                        Telp: {{ $company->hp }}
                    @elseif($company->email)
                        Email: {{ $company->email }}
                    @endif
                </div>
            </div>
        </div>

        <div class="title">
            <h5>StartDate: {{ $startDate ? $startDate : '-' }}</h5>
            <h5>EndDate: {{ $endDate ? $endDate : '-' }}</h5>
            <h5>Customer: {{ $customer ? $customer : '-' }}</h5>
            <h5>Metode Pembayaran: {{ $account ? $account : '-' }}</h5>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No Kode</th>
                    <th>Date</th>
                    <th>Transfer Date</th>
                    <th>Marking</th>
                    <th>Method</th>
                    <th>No Invoice</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $grandTotal = 0;
                @endphp
                @foreach ($payments as $kas)
                                @php
                                    $total = $kas->total_amount - $kas->discount;
                                    $grandTotal += $total; 
                                @endphp
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td> {{ $kas->kode_pembayaran }}</td>
                                    <td> {{ \Carbon\Carbon::parse($kas->created_date)->format('d M Y')}}</td>
                                    <td>{{ \Carbon\Carbon::parse($kas->payment_date)->format('d M Y H:i')}}</td>
                                    <td>{{ $kas->marking }}</td>
                                    <td>{{ $kas->payment_method }}</td>
                                    <td>{{ $kas->no_invoice_with_amount }}</td>
                                    <td>{{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" style="text-align: right; font-weight: bold;">Grand Total:</td>
                    <td style="font-weight: bold;">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

    </div>
</body>

</html>