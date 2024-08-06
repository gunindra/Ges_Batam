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
            width: 85%;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }

        .row {
            margin-bottom: 10px;
        }

        .row-divider {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .col-full {
            width: 100%;
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

        .border {
            border: 2px solid #000;
            padding: 10px;
        }

        .total-bayar {
            font-size: 24px;
            font-weight: bold;
        }

        .border-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .border-section div {
            flex: 1;
            margin: 0 5px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 10px;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row row-divider">
            <table>
                <tr>
                    <td class="left">
                        <h4 class="font-weight-bold">PT. GES</h4>
                    </td>
                    <td class="right">
                        <h4 class="font-weight-bold">{{ $invoice->no_resi }}</h4>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row row-divider">
            <div class="col-full">
                <p><strong>Customer :</strong> {{ $invoice->pembeli }}, {{ $invoice->nohp }}<br>JL. BANGKA IX NO. 43B RT. 6
                    RW. 12 RAYA KEC. MAMPANG PRAPATAN JAKARTA SELATAN</p>
            </div>
        </div>
        <div class="row row-divider">
            <div class="col-full">
                <div class="border-section">
                    <div class="border font-weight-bold text-center">{{ $invoice->pengiriman }}</div>
                    @if($invoice->pengiriman === 'delivery')
                        <div class="border text-center">
                            <p><strong>Driver :</strong> {{ $additionalDetails['driverName'] }}, {{ $additionalDetails['driverPhone'] }}<br>{{ $additionalDetails['destinationAddress'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row row-divider">
            <table>
                <tr>
                    <td class="center" style="width: 50%;">
                        <div class="border-section">
                            <div class="border font-weight-bold text-center">{{ $invoice->tipe_pembayaran }}</div>
                            @if($invoice->tipe_pembayaran === 'Transfer')
                                <div class="border text-center">
                                    <p><strong>Berat:</strong> {{ $berat }} kg<br>
                                    <strong>Dimensions:</strong> {{ $panjang }} cm x {{ $lebar }} cm x {{ $tinggi }} cm<br>
                                    <strong>No Rek:</strong> {{ $paymentDetails['rekeningNumber'] }}<br>
                                    <strong>Pemilik:</strong> {{ $paymentDetails['accountHolder'] }}<br>
                                    <strong>Bank:</strong> {{ $paymentDetails['bankName'] }}</p>
                                </div>
                            @else
                                <div class="border text-center">
                                    <p><strong>Berat:</strong> {{ $berat }} kg<br>
                                    <strong>Dimensions:</strong> {{ $panjang }} cm x {{ $lebar }} cm x {{ $tinggi }} cm</p>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="center" style="width: 50%;">
                        <div class="border total-bayar text-center">
                            <h6>Total Bayar</h6>
                            {{ number_format($hargaIDR, 2) }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
