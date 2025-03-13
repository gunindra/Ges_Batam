<table>
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="6">Export Ongoing Invoice</td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">No Do:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $NoDo ? $NoDo : '-' }}
            </td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">Marking:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $customer ? $customer : '-' }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">No. Invoice</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Open Invoice</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Marking</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">No. DO</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Nama Supir</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Tanggal Pengantaran</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($OngoingInvoice as $OngoingInvoices)
            <tr>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $OngoingInvoices->no_invoice }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{\Carbon\Carbon::parse( $OngoingInvoices->tanggal_buat) ->format('d M Y')}}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $OngoingInvoices->marking }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $OngoingInvoices->no_do }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $OngoingInvoices->nama_supir }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ \Carbon\Carbon::parse( $OngoingInvoices->tanggal_pengantaran )->format('d M Y')}}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $OngoingInvoices->status_transaksi }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
