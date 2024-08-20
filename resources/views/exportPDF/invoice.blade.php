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
            width: 87%;
            height: 50%;
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
            border: 1px solid #ddd;
        }

        .table-content td:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .table-content td:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .table-content tr:nth-child(even) {
            background-color: #f9f9f9;
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

        .cut-line {
            border-top: 2px dashed #333;
            margin: 20px 0;
            text-align: center;
            position: relative;
        }

        .cut-line::after {
            content: "Potong di sini";
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            padding: 0 10px;
            font-size: 12px;
            color: #666;
        }

        .resi-section {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #333;
            background-color: #fafafa;
            border-radius: 10px;
        }

        .table-section {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
        }

        .table-section td {
            padding: 8px 12px;
            text-align: left;
            border: none;
            background-color: #f4f4f4;
            border-radius: 8px;
        }

        .table-section td:nth-child(2) {
            text-align: right;
        }

        .total-bayar {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Bagian Invoice Utama (Untuk Ditempel) -->
        <div class="row-divider">
            <table class="table-head">
                <tr>
                    <td style="width: 60%;">
                        <h4 class="font-weight-bold">PT. GES</h4>
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
            <p>
                <strong>Customer :</strong> {{ $invoice->pembeli }}, {{ $invoice->nohp }}<br>
                @if(empty($additionalDetails['destinationAddress']))
                    <strong>Alamat Pickup :</strong> <br>Unnamed Road, Batu Selicin, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau
                @else
                    <strong>Alamat Tujuan :</strong> <br>{{ $additionalDetails['destinationAddress'] }}
                @endif
            </p>
        </div>

        <div class="row-divider">
            <table class="table-content">
                <tr>
                    <td>
                        <p><strong>{{ $invoice->pengiriman }}</strong></p>
                    </td>
                    <td class="text-center">
                        @if($invoice->pengiriman === 'Delivery')
                            <p><strong>Driver: </strong>{{ $additionalDetails['driverName'] ?? 'N/A' }}</p>
                        @else
                            <p><strong>Penanggung Jawab: </strong>Admin Gudang</p>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- Garis Potong -->
        <div class="cut-line"></div>

        <!-- Bagian Bawah (Untuk Dibuang) -->
        <div class="resi-section">
            <table class="table-section">
                <tr>
                    <td>Metode Pembayaran:</td>
                    <td>{{ $invoice->tipe_pembayaran }}</td>
                </tr>
                @if ($invoice->tipe_pembayaran === 'Transfer')
                <tr>
                    <td>No Rek:</td>
                    <td>{{ $paymentDetails['rekeningNumber'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Pemilik:</td>
                    <td>{{ $paymentDetails['accountHolder'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Bank:</td>
                    <td>{{ $paymentDetails['bankName'] ?? 'N/A' }}</td>
                </tr>
                @endif
                <tr>
                    <td>Total Bayar:</td>
                    <td class="total-bayar">{{ number_format($hargaIDR, 2) }}</td>
                </tr>
            </table>
        </div>

        <footer>
            <p>PT. GES, Jl. Unnamed Road, Batu Selicin, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau</p>
            <p>Telp: 021-12345678 | Email: info@ptges.com | Website: www.ptges.com</p>
        </footer>
    </div>
</body>

</html>
