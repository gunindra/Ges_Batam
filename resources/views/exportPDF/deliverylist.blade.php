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
                {{-- <img src="{{asset('/img/logo4.png')}}" alt="logo" class="logo"> --}}
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

        <div class="tableheaddelivery">
            <h2>Nama Driver : {{ $pengantaran->nama_supir }}</h2>
            <h5>Tanggal Pengantaran: {{ $pengantaran->tanggal_pengantaran }}</h5>
        </div>

        @foreach ($invoices as $invoice)
            <div class="title">
                <h5>Invoice : {{ $invoice->no_invoice }}</h5>
                <h5>Alamat : {{ $invoice->alamat }}</h5>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Resi</th>
                        <th>No. DO</th>
                        <th>Berat/Dimensi</th>
                        <th>Hitungan</th>
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
                            <td>{{ $resi->no_resi }}</td>
                            <td>{{ $resi->no_do }}</td>
                            @if ($resi->berat)
                                <td>Berat</td>
                                <td>{{ $resi->berat }} kg</td>
                            @else
                                <td>Dimensi</td>
                                <td>{{ $resi->panjang ?? '0' }} x {{ $resi->lebar ?? '0' }} x
                                    {{ $resi->tinggi ?? '0' }} cm
                                </td>
                            @endif
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @endforeach
    </div>
</body>



</html>
