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
            padding-right: 60px;
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

    $activeCompanyId = session('active_company_id');
    $company = \App\Models\Company::find($activeCompanyId);
    ?>
    <div class="container">
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
            <h5>Pembeli: {{ $invoice->pembeli }} ({{ $invoice->marking }}) </h5>
            <p>Tanggal : {{ $invoice->tanggal_bayar }}</p>
            <p>Alamat : {{ $invoice->alamat ?? '-' }}</p>
            <h2>Invoice: {{ $invoice->no_invoice }}</h2>
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
                    $totalHarga = 0;
                    $totalCreditNote = 0;
                    $totalRetur = 0;
                @endphp

                {{-- Data Resi --}}
                @foreach ($resiData as $resi)
                    @php
                        $totalHarga += $resi->harga;
                    @endphp
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
                                {{ $resi->berat ?? '0' }} Kg
                            </td>
                        @else
                            <td>
                                @php
                                    $panjang = $resi->panjang ?? 0;
                                    $lebar = $resi->lebar ?? 0;
                                    $tinggi = $resi->tinggi ?? 0;
                                    $volume = ($panjang / 100) * ($lebar / 100) * ($tinggi / 100); // hasil dalam m3
                                @endphp
                                {{ number_format($volume, 3) }} m³
                            </td>
                        @endif
                        <td>{{ number_format($resi->harga, 2) ?? '0' }}</td>
                    </tr>
                @endforeach

                {{-- Data Credit Note --}}
                @if (!empty($creditNoteItems) && count($creditNoteItems) > 0)
                    @foreach ($creditNoteItems as $creditNote)
                        @php
                            $totalCreditNote += $creditNote->harga;
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $creditNote->no_resi }}</td>
                            <td colspan="3" class="text-center"><strong>Credit Note</strong></td>
                            <td>-{{ number_format($creditNote->harga, 2) }}</td>
                        </tr>
                    @endforeach
                @endif

                {{-- Data Retur --}}
                @if (!empty($returItems) && count($returItems) > 0)
                    @foreach ($returItems as $retur)
                        @php
                            $totalRetur += $retur->harga;
                        @endphp
                        <tr>
                            <td>{{ $no++ }}</td>
                        <td>{{ $retur->no_resi }}</td>
                        <td>Retur</td>
                        @if ($retur->berat)
                            <td>Berat</td>
                        @else
                            <td>Dimensi</td>
                        @endif

                        @if ($retur->berat)
                            <td>
                                {{ $retur->berat ?? '0' }} Kg
                            </td>
                        @else
                            <td>
                                @php
                                    $panjang = $retur->panjang ?? 0;
                                    $lebar = $retur->lebar ?? 0;
                                    $tinggi = $retur->tinggi ?? 0;
                                    $volume = ($panjang / 100) * ($lebar / 100) * ($tinggi / 100); // hasil dalam m3
                                @endphp
                                {{ number_format($volume, 3) }} m³
                            </td>
                        @endif
                        <td>-{{ number_format($retur->harga, 2) ?? '0' }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>

            {{-- Total Harga --}}
            @php
                $finalTotalHarga = ceil(($totalHarga - $totalCreditNote - $totalRetur) / 1000) * 1000;
            @endphp
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right"><strong>Total Harga:</strong></td>
                    <td><strong>{{ number_format($finalTotalHarga, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <table class="signature-section">
            <tr>
                <td></td> <!-- Kolom kiri dibiarkan kosong -->
                <td>
                    @if (!empty($invoice->tanda_tangan))
                        <?php
                        $ttdPath = storage_path('app/public/' . $invoice->tanda_tangan);
                        if (file_exists($ttdPath)) {
                            $ttdData = file_get_contents($ttdPath);
                            $ttdBase64 = 'data:image/png;base64,' . base64_encode($ttdData);
                        } else {
                            $ttdBase64 = null;
                        }
                        ?>
                        @if ($ttdBase64)
                            <img src="{{ $ttdBase64 }}" alt="Tanda Tangan Customer"
                                style="width: 200px; height: auto;">
                            <div class="signature-line"></div>
                            <div class="signature-label">Customer</div>
                        @endif
                    @endif

                    @if (!empty($invoice->metode_pengiriman))
                        <div style="margin-top: 20px" class="signature-label">
                            @if ($invoice->metode_pengiriman == 'Delivery')
                                Yang Bertanggung jawab : {{ $invoice->nama_supir ?? '-' }}
                            @elseif ($invoice->metode_pengiriman == 'Pickup')
                                Yang Bertanggung jawab : {{ $invoice->createby ?? '-' }}
                            @endif
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
