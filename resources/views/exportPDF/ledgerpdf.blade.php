<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ledger Report</title>
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
            border: 1px solid #000;
            padding: 8px;
            text-align: right;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            border-top: 2px solid #000;
        }

        td:first-child,
        th:first-child {
            text-align: center;
        }

        .account-header {
            background: #ddd;
            font-weight: bold;
            text-align: left;
            border: 2px solid #000;
            padding: 10px;
        }

        .summary-row {
            font-weight: bold;
            background: #eee;
            border-top: 2px solid #000;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
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
            <h5>Account: {{ $filterCode ? $filterCode : '-' }}</h5>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Total Debit</th>
                    <th>Total Credit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ledgerAccounts as $ledger)
                    <tr>
                        <td colspan="4" class="account-header">
                            {{ $ledger['account_name'] }} ({{ $ledger['code'] }})
                        </td>
                    </tr>
                    <tr class="summary-row">
                        <td>Beginning Balance</td>
                        <td></td>
                        <td></td>
                        <td>{{ number_format($ledger['beginning_balance'], 2) }}</td>
                    </tr>
                    @foreach ($ledger['journal_entries'] as $entry)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($entry->tanggal)) }}</td>
                            <td class="text-left">{{ $entry->items_description }}</td>
                            <td>{{ number_format($entry->debit, 2) }}</td>
                            <td>{{ number_format($entry->credit, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="summary-row">
                        <td>Ending Balance</td>
                        <td></td>
                        <td></td>
                        <td>{{ number_format($ledger['ending_balance'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
