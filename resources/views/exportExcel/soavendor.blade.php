<table>
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="6">Soa Vendor Report</td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">Start Date:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $startDate ? $startDate : '-' }}
            </td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">End Date:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $endDate ? $endDate : '-' }}
            </td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">Nama Customer:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $customer ? $customer : '-' }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Date</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">No Invoice</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Jumlah Tagihan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice as $invoices)
            <tr>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ \Carbon\Carbon::parse($invoices->tanggal )->format('d M Y')}}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $invoices->invoice_no }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $invoices->total_harga - $invoices->total_bayar }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>