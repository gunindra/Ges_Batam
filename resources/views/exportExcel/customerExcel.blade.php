<table>
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="12">Export Customer Data</td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">Status:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $status ? $status : 'Semua Status' }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;" bgcolor="#b9bab8">Marking</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;" bgcolor="#b9bab8">Nama Pembeli</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;" bgcolor="#b9bab8">Alamat</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;" bgcolor="#b9bab8">Jumlah Alamat</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;" bgcolor="#b9bab8">No WA</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;" bgcolor="#b9bab8">Sisa Poin</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;" bgcolor="#b9bab8">Metode Pengiriman</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;" bgcolor="#b9bab8">Tanggal Bayar</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;" bgcolor="#b9bab8">Status</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;" bgcolor="#b9bab8">Kategori</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 10px; white-space: normal;" bgcolor="#b9bab8">Email</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
            <tr>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">{{ $customer->marking }}</td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">{{ $customer->nama_pembeli }}</td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">{{ $customer->alamat }}</td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">{{ $customer->alamat_count }}</td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">{{ $customer->no_wa }}</td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">{{ $customer->sisa_poin }}</td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">{{ $customer->metode_pengiriman }}</td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">{{ $customer->tanggal_bayar }}</td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">{{ $customer->status == 1 ? 'Active' : 'Non Active' }}</td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">{{ $customer->category_name }}</td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 10px">{{ $customer->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
