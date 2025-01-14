<table>
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="6">Asset Report</td>
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
        <tr></tr>
        <tr>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Date</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Asset Name</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Estimated Age</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Beginning Value</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal; "
                bgcolor="#b9bab8">Depreciation</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Ending Value</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($asset as $assets)
            <tr>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ \Carbon\Carbon::parse($assets->tanggal)->format('d M Y') }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $assets->asset_name }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $assets->estimated_age }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $assets->begining_value }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ number_format($assets->credit - $assets->begining_value, 2) }}
                </td>
                <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                    {{ $assets->ending_value }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>