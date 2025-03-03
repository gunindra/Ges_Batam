<table>
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="6">Export Sales</td>
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
        <tr></tr>
        <tr>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">No. Invoice</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Marking</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Tanggal Invoice</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">No Resi</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Quantity</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">No. DO</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Customer</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Pengiriman</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Status</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Harga</th>
        </tr>
    </thead>
    <tbody>
        @php
            $grandTotal = 0;
        @endphp
        @foreach ($Sales as $Sale)
                @php
                    $grandTotal += $Sale->total_harga;
                    $harga_resi_list = explode('; ', $Sale->harga_resi);
                    $no_resi_list = explode('; ', $Sale->no_resi);
                    $berat_volume_list = explode('; ', $Sale->berat_volume);

                    $max_rows = max(count($no_resi_list), count($berat_volume_list), count($harga_resi_list));
                @endphp
                @for ($i = 0; $i < $max_rows; $i++)
                    <tr>
                        <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                            {{ $Sale->no_invoice }}
                        </td>
                        <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                            {{ $Sale->marking }}
                        </td>
                        <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                            {{\Carbon\Carbon::parse($Sale->tanggal_buat)->format('d M Y')}}
                        </td>
                        <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                            {{  $no_resi_list[$i] ?? '' }}
                        </td>
                        <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                            {{ $berat_volume_list[$i] ?? '' }}
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
                            Rp {{ number_format((int) ($harga_resi_list[$i] ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                @endfor
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="9" class="text-right grand-total" style="font-size:11px;border:1px solid black; padding: 20px">
                Grand Total</td>
            <td class="text-right grand-total" style="font-size:11px;border:1px solid black; padding: 20px">
                {{ $grandTotal }}
            </td>
        </tr>
    </tfoot>
</table>