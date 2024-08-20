<!DOCTYPE html>
<html>

<head>
    <title>Invoice PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100vh;
            height: 100vh;
            margin: 0;
            padding: 10px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .row-divider {
            padding-bottom: 10px;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
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
            table-layout: fixed;
        }

        .table-head td {
            vertical-align: top;
            padding: 10px;
            font-size: 20px;
        }

        .table-head h4 {
            margin: 0;
            font-size: 24px;
        }

        .table-content {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .table-content td {
            padding: 20px;
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
            background-color: #ffffff;
        }

        footer {
            margin-top: auto;
            padding-top: 20px;
            text-align: center;
            font-size: 14px;
            /* color: #666; */
            border-top: 1px solid #ddd;
            background-color: #ffffff;
        }

        footer p {
            margin: 5px 0;
            line-height: 1.5;
        }

        h4,
        h6 {
            margin: 10px 0;
            font-size: 22px;
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
            content: "Cut Here";
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            padding: 0 10px;
            font-size: 16px;
            color: #666;
        }

        .resi-section {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #333;
            background-color: #ffffff;
            border-radius: 10px;
        }

        .table-section {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .table-section td {
            padding: 15px 20px;
            text-align: left;
            border: none;
            /* background-color: #f4f4f4; */
            border-radius: 8px;
        }

        .table-section td:nth-child(2) {
            text-align: right;
        }

        .total-bayar {
            font-size: 26px;
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
                @if (empty($additionalDetails['destinationAddress']))
                    <strong>Alamat Pickup :</strong> <br>Unnamed Road, Batu Selicin, Kec. Lubuk Baja, Kota Batam,
                    Kepulauan Riau
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
                        @if ($invoice->pengiriman === 'Delivery')
                            <p><strong>Driver: </strong>{{ $additionalDetails['driverName'] ?? 'N/A' }}</p>
                        @else
                            <p><strong>Penanggung Jawab: </strong>Admin Gudang</p>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="row-divider">
            <p class="text-center">
                <strong>Berat :</strong> {{ $berat }} kg<br>
                <strong>Dimensi :</strong> {{ $invoice->panjang }} x {{ $invoice->lebar }} x {{ $invoice->tinggi }} cm
            </p>
        </div>


        <!-- Garis Potong -->
        <div class="cut-line"></div>

        <!-- Bagian Bawah (Untuk Dibuang) -->
        <div class="resi-section">
            <table class="table-section">
                <tr>
                    <td>Metode Pembayaran:</td>
                    <td><strong>{{ $invoice->tipe_pembayaran }}</strong></td>
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
