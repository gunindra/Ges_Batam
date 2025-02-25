<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <h1>Statement of Account </h1>
        </div>
        <div class="title">
            <h5>Tanggal: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</h5>
            <h5>Customer: {{ $customer->marking }} - {{ $customer->nama_pembeli }}</h5>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">No DO</th>
                    <th class="text-center">No Invoice</th>
                    <th class="text-center">Metode Pembayaran</th>
                    <th class="text-right">Jumlah Tagihan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_belum_bayar = 0;
                @endphp
                @foreach($invoice as $data)
                    @php
                        $belum_bayar = $data->total_harga - $data->total_bayar;
                        $total_belum_bayar += $belum_bayar;
                    @endphp
                    <tr>
                        <td class="text-center">{{ \Carbon\Carbon::parse($data->tanggal_invoice)->format('d-m-Y') }}</td>
                        <td class="text-center">{{ $data->no_do }}</td>
                        <td class="text-center">{{ $data->no_invoice }}</td>
                        <td class="text-center">{{ $data->payment_type ?? '-' }}</td>
                        <td class="text-right">{{ number_format($belum_bayar, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Grand Total:</strong></td>
                    <td class="text-right"><strong>{{ number_format($total_belum_bayar, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
