<!DOCTYPE html>
<html>
<head>
    <title>Invoice PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            width: 800px;
            margin: 20px auto;
            border: 2px solid #000;
            padding: 20px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
            padding-bottom: 10px;
        }

        .row-divider {
            border-bottom: 3px solid #000;
        }

        .col-2 {
            flex: 0 0 16.6667%;
            max-width: 16.6667%;
            box-sizing: border-box;
            padding: 5px;
        }

        .col-3 {
            flex: 0 0 25%;
            max-width: 25%;
            box-sizing: border-box;
            padding: 5px;
        }

        .col-5 {
            flex: 0 0 41.6667%;
            max-width: 41.6667%;
            box-sizing: border-box;
            padding: 5px;
        }

        .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
            box-sizing: border-box;
            padding: 5px;
        }

        .col-7 {
            flex: 0 0 58.3333%;
            max-width: 58.3333%;
            box-sizing: border-box;
            padding: 5px;
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

        .p-2 {
            padding: 10px;
        }

        .py-2 {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .px-1 {
            padding-left: 5px;
            padding-right: 5px;
        }

    </style>
</head>
<body>
    <div class="container border p-4 my-4">
        <div class="row row-divider">
            <div class="col-7">
                <h4 class="font-weight-bold">PT. GES</h4>
            </div>
            <div class="col-5 text-right">
                <h4 class="font-weight-bold"> {{ $invoice[0]->no_resi }}</h4>
            </div>
        </div>
        <div class="row row-divider py-2">
            <div class="col-6">
                <p><strong>Driver :</strong> PIJE, 628******21<br>BATAM</p>
            </div>
            <div class="col-6">
                <p><strong>Customer :</strong> {{ $invoice[0]->pembeli }}, 6285714340762<br>JL. BANGKA IX NO. 43B RT. 6 RW. 12 RAYA KEC. MAMPANG PRAPATAN JAKARTA SELATAN</p>
            </div>
        </div>
        <div class="row row-divider py-2">
            <div class="col-2 text-center">
                <div class="border p-2 font-weight-bold">DELIVERY</div>
            </div>
            <div class="col-3">
                <div class="border p-2">
                    <div>GW: 0.8 kg / 0.8 kg</div>
                    <div>VW: 10/10/10 Cm / 0.1 kg</div>
                </div>
            </div>
            <div class="col-2 text-center">
                <div class="border py-2 px-1 font-weight-bold">TRANSFER</div>
            </div>
            <div class="col-5">

                <div class="border p-2 text-center total-bayar">
                    <h6>Total Bayar</h6>
                    {{ number_format($hargaIDR, 2) }}
                </div>
            </div>
        </div>
    </div>
</html>


