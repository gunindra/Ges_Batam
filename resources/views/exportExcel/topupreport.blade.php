<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #b9bab8;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td colspan="{{ !$isCustomerRole ? '7' : '6' }}"
                    style="text-align:center; font-size:14px; font-weight:bold; padding:10px;">
                    TopUp Report
                </td>
            </tr>
            <tr>
                <td style="text-align:left; font-size:11px; padding:6px;">Start Date:</td>
                <td style="text-align:left; font-size:11px; padding:6px; font-weight:bold;">
                    {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td style="text-align:left; font-size:11px; padding:6px;">End Date:</td>
                <td style="text-align:left; font-size:11px; padding:6px; font-weight:bold;">
                    {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : '-' }}
                </td>
            </tr>
            @if (!$isCustomerRole)
                <tr>
                    <td style="text-align:left; font-size:11px; padding:6px;">Marking Customer:</td>
                    <td style="text-align:left; font-size:11px; padding:6px; font-weight:bold;">
                        {{ $marking ?: '-' }}
                    </td>
                </tr>
            @endif
            <tr></tr>
            <tr>
                <th
                    style="text-align:center; font-size:11px; border:1px solid black; padding:10px; background-color:#b9bab8;">
                    Topup Date</th>
                <th
                    style="text-align:center; font-size:11px; border:1px solid black; padding:10px; background-color:#b9bab8;">
                    Customer</th>
                <th
                    style="text-align:center; font-size:11px; border:1px solid black; padding:10px; background-color:#b9bab8;">
                    In (kg)</th>
                <th
                    style="text-align:center; font-size:11px; border:1px solid black; padding:10px; background-color:#b9bab8;">
                    Out (kg)</th>
                <th
                    style="text-align:center; font-size:11px; border:1px solid black; padding:10px; background-color:#b9bab8;">
                    Saldo (kg)</th>
                @if (!$isCustomerRole)
                    <th
                        style="text-align:center; font-size:11px; border:1px solid black; padding:10px; background-color:#b9bab8;">
                        Value (Rp)</th>
                    <th
                        style="text-align:center; font-size:11px; border:1px solid black; padding:10px; background-color:#b9bab8;">
                        Saldo Value (Rp)</th>
                @endif
                <th
                    style="text-align:center; font-size:11px; border:1px solid black; padding:10px; background-color:#b9bab8;">
                    Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topup as $data)
                <tr>
                    <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                        {{ \Carbon\Carbon::parse($data->date)->format('d M Y') }}
                    </td>
                    <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                        {{ $data->marking }}
                    </td>
                    <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                        {{ number_format($data->in_points, 2) }}
                    </td>
                    <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                        {{ number_format($data->out_points, 2) }}
                    </td>
                    <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                        {{ number_format($data->saldo, 2) }}
                    </td>
                    @if (!$isCustomerRole)
                        <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                            {{ number_format($data->value, 2) }}
                        </td>
                        <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                            {{ number_format($data->saldo_value, 2) }}
                        </td>
                    @endif
                    <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                        {{ strtoupper($data->status) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
