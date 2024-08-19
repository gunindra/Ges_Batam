<!DOCTYPE html>
<html>

<head>
    <title>Invoice PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 85%;
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .row-divider {
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .table-head {
            width: 100%;
            margin-bottom: 20px;
        }

        .table-head td {
            vertical-align: top;
            padding: 5px;
        }

        .table-content {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        .table-content td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd; /* Border halus untuk membatasi cell */
        }

        /* Menghilangkan inner-border agar tidak terlihat terpisah */
        .table-content td:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .table-content td:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .table-content tr:first-child td:first-child {
            border-top-left-radius: 8px;
        }

        .table-content tr:first-child td:last-child {
            border-top-right-radius: 8px;
        }

        .table-content tr:last-child td:first-child {
            border-bottom-left-radius: 8px;
        }

        .table-content tr:last-child td:last-child {
            border-bottom-right-radius: 8px;
        }

        .table-content tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .total-bayar {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        footer p {
            margin: 0;
        }

        .logo {
            display: inline-block;
            margin-right: 10px;
        }

        h4,
        h6 {
            margin: 0;
        }

        .container p {
            color: #333;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row-divider">
            <table class="table-head">
                <tr>
                    <td style="width: 60%;">
                        {{-- <svg class="logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="50" height="50">
                            <circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red" />
                        </svg> --}}
                        <h4 class="font-weight-bold">PT. GES</h4>
                        {{-- <p>Jl. Example No. 123, Jakarta, Indonesia</p> --}}
                    </td>
                    <td class="text-right" style="width: 40%;">
                        <h4 class="font-weight-bold">INVOICE</h4>
                    </td>
                </tr>
                <tr>
                    <td style="width: 60%;">
                        <h4 class="font-weight-bold">{{ $tanggal }}</h4>
                    </td>
                    <td class="text-right" style="width: 40%;">
                        <h4 class="font-weight-bold">{{ $invoice->no_resi }}</h4>
                    </td>
                </tr>
            </table>
        </div>

        <div class="row-divider">
            <p><strong>Customer :</strong> {{ $invoice->pembeli }}, {{ $invoice->nohp }}<br>
                <strong>Alamat Tujuan :</strong> <br>{{ $additionalDetails['destinationAddress'] ?? 'Unnamed Road, Batu Selicin, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau' }}
            </p>
        </div>

        <div class="row-divider">
            <table class="table-content">
                <tr>
                    <td colspan="{{ $invoice->pengiriman === 'Delivery' ? 1 : 2 }}">{{ $invoice->pengiriman }}</td>
                    @if ($invoice->pengiriman === 'Delivery')
                    <td>
                        <p><strong>Driver :</strong> {{ $additionalDetails['driverName'] ?? 'N/A' }},
                            {{ $additionalDetails['driverPhone'] ?? 'N/A' }}</p>
                    </td>
                    @endif
                </tr>
                <tr>
                    <td colspan="{{ $invoice->tipe_pembayaran === 'Transfer' ? 1 : 2 }}">{{ $invoice->tipe_pembayaran }}</td>
                    @if ($invoice->tipe_pembayaran === 'Transfer')
                    <td>
                        <p><strong>No Rek:</strong> {{ $paymentDetails['rekeningNumber'] ?? 'N/A' }}<br>
                            <strong>Pemilik:</strong> {{ $paymentDetails['accountHolder'] ?? 'N/A' }}<br>
                            <strong>Bank:</strong> {{ $paymentDetails['bankName'] ?? 'N/A' }}
                        </p>
                    </td>
                    @endif
                </tr>
                <tr>
                    <td>
                        <p><strong>Berat: <br> </strong> {{ $berat }} kg</p>
                        <p><strong>Dimensions: <br> </strong> {{ $panjang }} cm x {{ $lebar }} cm x
                            {{ $tinggi }} cm</p>
                    </td>
                    <td>
                        <div class="total-bayar">
                            <h6>Total Bayar</h6>
                            {{ number_format($hargaIDR, 2) }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <footer>
            <p>PT. GES, Jl. Example No. 123, Jakarta, Indonesia</p>
            <p>Telp: 021-12345678 | Email: info@ptges.com | Website: www.ptges.com</p>
        </footer>
    </div>
</body>

</html>
