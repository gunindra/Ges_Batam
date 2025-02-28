<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->no_invoice }}</title>
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

        .signature-section {
            border-collapse: collapse;
            margin-top: 10px;
            width: 100%;
            text-align: center;
            border: 1px solid transparent;
        }

        .signature-section td {
            width: 50%;
            vertical-align: top;
            padding-top: 20px;
            text-align: right;
            border: 1px solid transparent;
        }

        .signature-line {
            border-top: 1px dotted #000;
            width: 200px;
            margin-left: auto;
            /* Geser ke kanan */
            margin-right: 0;
        }

        .signature-label {
            margin-top: 5px;
            font-weight: bold;
            padding-right: 45px;
        }

        .signature-section td:first-child {
            width: 30%;
        }

        .signature-section td:last-child {
            width: 70%;
        }
    </style>
</head>

<body>
    <?php
    $hargaIDR = ceil($hargaIDR / 1000) * 1000;
    ?>
    <div class="container">
        <div class="header">
            <div class="logo-container">
                <?php
                $path = public_path('img/logo4.png');
                $tipe1 = pathinfo($path, PATHINFO_EXTENSION);
                if (file_exists($path)) {
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $tipe1 . ';base64,' . base64_encode($data);
                } else {
                    $base64 = '';
                }
                ?>
                <img src="<?php echo $base64; ?>" alt="logo" class="logo" />
            </div>
            <div class="company-info">
                @if ($company)
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
                @else
                    <!-- Tampilkan pesan jika company tidak ditemukan -->
                    <div class="company-name">Company name not available</div>
                    <div class="company-address">No company address available</div>
                @endif
            </div>
        </div>
    </div>


    <div class="title">
        <h5>Pembeli: {{ $invoice->nama_pembeli }} ({{ $invoice->marking }}) </h5>
        <p>Alamat: {{ $invoice->alamat }}</p>

        <!-- Kondisi untuk menampilkan Invoice -->
        @if ($type === 'invoice')
            <h2>Invoice: {{ $invoice->no_invoice }}</h2>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Resi</th>
                <th>No. Do</th>
                <th>Berat/Dimensi</th>
                <th>Hitungan</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($resiData as $resi)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $resi->no_resi }}</td>
                    <td>{{ $resi->no_do }}</td>
                    @if ($resi->berat)
                        <td>Berat</td>
                    @else
                        <td>Dimensi</td>
                    @endif

                    @if ($resi->berat)
                        <td>
                            {{ $resi->berat ?? '0' }}
                            @if ($resi->priceperkg)
                                / {{ number_format($resi->priceperkg, 2) }} perkg
                            @endif
                        </td>
                    @else
                        <td>
                            @php
                                $panjang = $resi->panjang ?? 0;
                                $lebar = $resi->lebar ?? 0;
                                $tinggi = $resi->tinggi ?? 0;
                                $volume = ($panjang / 100) * ($lebar / 100) * ($tinggi / 100);
                            @endphp
                            {{ number_format($volume, 3) }} m³
                        </td>
                    @endif
                    <td>{{ number_format($resi->harga, 2) ?? '0' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>Total Harga:</strong></td>
                <td><strong>{{ number_format(ceil($hargaIDR / 1000) * 1000, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary" style="position: relative;">
        @if ($type === 'invoice')
            <?php
            $showTandaTangan = !empty($invoice->tanda_tangan);
            $pathLunas = public_path('img/lunas.png');
            $tipeLunas = pathinfo($pathLunas, PATHINFO_EXTENSION);

            // Cek apakah gambar lunas ada
            if (file_exists($pathLunas)) {
                $dataLunas = file_get_contents($pathLunas);
                $base64Lunas = 'data:image/' . $tipeLunas . ';base64,' . base64_encode($dataLunas);
            } else {
                $base64Lunas = '';
            }
            ?>
        @endif
    </div>

    <!-- Bagian tanda tangan -->
    <table class="signature-section" style="margin-left: auto; margin-right: 0; width: 50%;">
        <tr>
            <td></td> <!-- Kolom kiri tetap kosong -->
            <td style="position: relative; text-align: center;">
                @if ($showTandaTangan)
                    <?php
                    $ttdPath = storage_path('app/public/' . $invoice->tanda_tangan);
                    if (file_exists($ttdPath)) {
                        $ttdData = file_get_contents($ttdPath);
                        $ttdBase64 = 'data:image/png;base64,' . base64_encode($ttdData);
                    } else {
                        $ttdBase64 = null;
                    }
                    ?>

                    <!-- Jika ada tanda tangan, tampilkan -->
                    @if ($ttdBase64)
                        <img src="{{ $ttdBase64 }}" alt="Tanda Tangan Customer" style="width: 200px; height: auto; display: block; margin: 0 auto;">
                    @endif

                    <!-- Garis tanda tangan -->
                    <div class="signature-line" style="margin: 10px auto;"></div>
                    <div class="signature-label">Customer</div>

                    <!-- Stempel lunas tetap di atas tanda tangan -->
                    @if ($statusPembayaran === 'Lunas' && $base64Lunas)
                        <div class="paid-stamp" style="position: absolute; top: -30px; left: 50%; transform: translateX(-50%) rotate(-15deg) scale(1.4); opacity: 0.7;">
                            <img src="{{ $base64Lunas }}" alt="Stempel Lunas" style="width: 100px;">
                        </div>
                    @endif
                @else
                    <!-- Area tanda tangan kosong -->
                    <div class="signature-line"></div>
                    <div class="signature-label">Customer</div>
                @endif
            </td>
        </tr>
    </table>
</body>



</html>
