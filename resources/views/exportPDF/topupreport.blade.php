<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piutang Report</title>
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
        </div>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Topup Date</th>
                    <th>Customer</th>
                    <th>In (kg)</th>
                    <th>Out (kg)</th>
                    <th>Saldo (kg)</th>
                    <th>Value (Rp.)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>


                @php
                    $no = 1;
                @endphp
                @foreach ($combined as $topups)
                    @php
                        $customerId = $topups->customer_id;
                        // Initialize saldo for the customer if it doesn't exist
if (!isset($customerSaldo[$customerId])) {
    $customerSaldo[$customerId] = 0;
}

// Determine transaction type (topup or payment)
if ($topups->type === 'topup') {
    // Add points to saldo
    $customerSaldo[$customerId] += $topups->remaining_points;
} elseif ($topups->type === 'payment') {
    // Subtract points from saldo
    $customerSaldo[$customerId] -= $topups->kuota;
}

// Define the status based on transaction type
$status = $topups->type === 'topup' ? 'IN' : 'OUT';
                    @endphp
                    <tr>
                        <td style="text-align:center;">{{ $no++ }}</td>
                        <td style="text-align:center;">{{ \Carbon\Carbon::parse($topups->date)->format('d M Y') }}</td>
                        <td style="text-align:center;">
                            {{ $topups->customer_name ?? $topups->payment->pembeli->nama_pembeli }}
                        </td>
                        <td style="text-align:center;">
                            @if ($topups->type === 'topup')
                                {{ number_format($topups->remaining_points, 2) }}
                            @else
                                0
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if ($topups->type === 'payment')
                                {{ number_format($topups->kuota, 2) }}
                            @else
                                0
                            @endif
                        </td>
                        <td style="text-align:center;">{{ number_format($customerSaldo[$customerId], 2) }}</td>
                        <td style="text-align:center;">
                            {{ isset($topups->value) ? 'Rp. ' . number_format($topups->value, 2) : 'Rp. 0.00' }}
                        </td>
                        <td style="text-align:center;">{{ $status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
