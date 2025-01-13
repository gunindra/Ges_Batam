<table>
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="6">Penerimaan Kas Report</td>
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
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">Metode Pembayaran:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $account ? $account : '-' }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">No</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Date</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Transfer Date</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Customer</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Method</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">No Invoice</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $kas)
            <tr>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $kas->kode_pembayaran }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ \Carbon\Carbon::parse( $kas->created_date)->format('d M Y')}}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ \Carbon\Carbon::parse($kas->payment_date )->format('d M Y H:m')}}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $kas->customer_name }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $kas->payment_method }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $kas->no_invoice_with_amount }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $kas->total_amount - $kas->discount }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>