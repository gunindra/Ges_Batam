<table>
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="7">
                Tracking Export
            </td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">Status Barang:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $status ?? '-' }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;"
                bgcolor="#b9bab8">No. Resi</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;"
                bgcolor="#b9bab8">No. DO</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;"
                bgcolor="#b9bab8">Status</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;"
                bgcolor="#b9bab8">Keterangan</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;"
                bgcolor="#b9bab8">Pembayaran</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;"
                bgcolor="#b9bab8">Quantitas</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;"
                bgcolor="#b9bab8">Barang Diterima</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($trackingData as $tracking)
            <tr>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">
                    {{ $tracking->no_resi }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">
                    {{ $tracking->no_do }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">
                    {{ $tracking->status }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">
                    {{ $tracking->keterangan }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">
                    {{ $tracking->status_bayar ?? '-' }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">
                    {{ $tracking->quantitas }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">
                    {{ $tracking->tanggal_penerimaan }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
