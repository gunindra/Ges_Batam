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
            <tr>
                <td style="text-align:left; font-size:11px; padding:6px;">Nama Customer:</td>
                <td style="text-align:left; font-size:11px; padding:6px; font-weight:bold;">
                    {{ $customer ?: '-' }}
                </td>
            </tr>
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
                @endif
                <th
                    style="text-align:center; font-size:11px; border:1px solid black; padding:10px; background-color:#b9bab8;">
                    Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $customerSaldo = [];
            @endphp

            @foreach ($topup as $topups)
                @php
                    $customerId = $topups->customer_id;
                    if (!isset($customerSaldo[$customerId])) {
                        $customerSaldo[$customerId] = 0;
                    }

                    if ($topups->type === 'topup') {
                        $customerSaldo[$customerId] += $topups->remaining_points;
                    } elseif ($topups->type === 'payment') {
                        $customerSaldo[$customerId] -= $topups->kuota;
                    }
                @endphp
                <tr>
                    <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                        {{ \Carbon\Carbon::parse($topups->date)->format('d M Y') }}
                    </td>
                    <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                        {{ $topups->marking ?? '-' }}
                    </td>
                    <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                        {{ $topups->type === 'topup' ? number_format($topups->remaining_points, 2) : 0 }}
                    </td>
                    <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                        {{ $topups->type === 'payment' ? number_format($topups->kuota, 2) : 0 }}
                    </td>
                    <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                        {{ number_format($customerSaldo[$customerId], 2) }}
                    </td>
                    @if (!$isCustomerRole)
                        <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                            @if ($topups->type === 'topup')
                                Rp. {{ number_format($topups->remaining_points * $topups->price_per_kg, 2) }}
                            @elseif ($topups->type === 'payment')
                                Rp. {{ number_format($topups->kuota * ($topups->amount / $topups->kuota), 2) }}
                            @else
                                Rp. 0
                            @endif
                        </td>
                    @endif
                    <td style="text-align:left; font-size:11px; border:1px solid black; padding:10px;">
                        {{ $topups->type === 'topup' ? 'IN' : 'OUT' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
