<table>
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="10">Export Sales</td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">No Do:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $NoDo ? $NoDo : '-' }}
            </td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">Nama Customer:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $customer ? $customer : '-' }}
            </td>
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
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;" bgcolor="#b9bab8">No. Invoice</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;" bgcolor="#b9bab8">Marking</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;" bgcolor="#b9bab8">Tanggal Invoice</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;" bgcolor="#b9bab8">No Resi</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;" bgcolor="#b9bab8">Quantity</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;" bgcolor="#b9bab8">No. DO</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;" bgcolor="#b9bab8">Customer</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;" bgcolor="#b9bab8">Pengiriman</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;" bgcolor="#b9bab8">Status</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;" bgcolor="#b9bab8">Harga</th>
        </tr>
    </thead>
    <tbody>
        @php
            $grandTotal = 0;
            $i = 0;
        @endphp
        @foreach ($Sales as $Sale)
            @php
                $grandTotal += $Sale->total_harga;
            @endphp
            <tr>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $Sale->no_invoice }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $Sale->marking }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ \Carbon\Carbon::parse($Sale->tanggal_buat)->format('d M Y') }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $Sale->no_resi }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $Sale->berat_volume }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $Sale->no_do }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $Sale->customer }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $Sale->metode_pengiriman }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $Sale->status_transaksi }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $Sale->total_harga }}
                </td>
            </tr>
            @php
                $i++;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="9" class="text-right grand-total" style="font-size:11px;border:1px solid black; padding: 20px">
                Grand Total
            </td>
            <td class="text-right grand-total" style="font-size:11px;border:1px solid black; padding: 20px">
                {{ $grandTotal }}
            </td>
        </tr>
    </tfoot>
</table>
