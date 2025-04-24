<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
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
            padding: 3px;
            /* Ubah dari 5px ke 3px */
            font-size: 8px;
            /* Ubah dari 10px ke 8px */
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
            <h5>No. Do: {{ $NoDo ? $NoDo : '-' }}</h5>
            <h5>Customer: {{ $Customer ? $Customer : '-' }} </h5>
            <h5>StartDate:  {{ $startDate ? $startDate : '-' }}</h5>
            <h5>EndDate:  {{ $endDate ? $endDate : '-' }}</h5>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. Invoice</th>
                    <th>Marking</th>
                    <th>Tanggal Pembukuan</th>
                    <th>Tanggal Invoice</th>
                    <th>No. Resi</th>
                    <th>Quantity</th>
                    <th>No. DO</th>
                    <th>Customer</th>
                    <th>Pengiriman</th>
                    <th>Status</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $grandTotal = 0; // Inisialisasi grand total
                @endphp
                @foreach ($salesdata as $sales)
                    @php
                        // Hitung grand total
                        $grandTotal += $sales->total_harga;
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $sales->no_invoice }}</td>
                        <td>{{ $sales->marking }}</td>
                        <td>{{ $sales->tanggal_pembukuan }}</td>
                        <td>{{ $sales->tanggal_buat }}</td>
                        <td>{{ $sales->no_resi }}</td>
                        <td>{{ $sales->berat_volume }}</td>
                        <td>{{ $sales->no_do }}</td>
                        <td>{{ $sales->customer }}</td>
                        <td>{{ $sales->metode_pengiriman }}</td>
                        <td>{{ $sales->status_transaksi }}</td>
                        <td class="text-right">
                            Rp {{ number_format($sales->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="11" class="text-right grand-total">Grand Total</td>
                    <td class="text-right grand-total">
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </td>
                </tr>
                @php
                    $selisih = $journalTotal - $grandTotal;
                @endphp
                 @if($selisih != 0 && is_null($txSearch))
                    <tr>
                        <td colspan="11" class="text-right grand-total">Selisih Terhadap Ledger</td>
                        <td class="text-right grand-total">
                            Rp {{ number_format($selisih, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="11" class="text-right grand-total">Total Setelah Selisih</td>
                        <td class="text-right grand-total">
                            Rp {{ number_format($journalTotal, 0, ',', '.') }}
                        </td>
                    </tr>
                @endif
            </tfoot>
        </table>
    </div>
</body>

</html>
