<table style="width: 100%; border-collapse: collapse; table-layout: auto;">
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="4">Ledger Report</td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">Start Date:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $startDate ?? '-' }}
            </td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">End Date:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $endDate ?? '-' }}
            </td>
        </tr>
        <tr>
            <td style="text-align:left;font-size:11px;padding: 14px;">Code Account:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $filterCode ?? '-' }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: nowrap; background-color: #b9bab8;">Date</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: nowrap; background-color: #b9bab8;">Description</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: nowrap; background-color: #b9bab8;">Total Debit</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: nowrap; background-color: #b9bab8;">Total Credit</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ledgerAccounts as $ledger)
            <tr>
                <td colspan="4" style="font-weight: bold; text-align: left; padding: 10px; border:1px solid black;">
                    {{ $ledger['account_name'] }} ({{ $ledger['code'] }})
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black; padding: 8px; text-align: center;">Beginning Balance</td>
                <td style="border:1px solid black; padding: 8px;"></td>
                <td style="border:1px solid black; padding: 8px; "></td>
                <td style="border:1px solid black; padding: 8px; text-align: right;">{{ number_format($ledger['beginning_balance'], 2) }}</td>
            </tr>
            @foreach($ledger['journal_entries'] as $entry)
                <tr>
                    <td style="border:1px solid black; padding: 8px; text-align: center; white-space: nowrap;">
                        {{ date('d/m/Y', strtotime($entry->tanggal)) }}
                    </td>
                    <td style="border:1px solid black; padding: 8px;">
                        {{ $entry->items_description }}
                    </td>
                    <td style="border:1px solid black; padding: 8px; text-align: right;">
                        {{ number_format($entry->debit, 2) }}
                    </td>
                    <td style="border:1px solid black; padding: 8px; text-align: right;">
                        {{ number_format($entry->credit, 2) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td style="border:1px solid black; padding: 8px; font-weight: bold;">Ending Balance</td>
                <td style="border:1px solid black; padding: 8px;"></td>
                <td style="border:1px solid black; padding: 8px;"></td>
                <td style="border:1px solid black; padding: 8px; text-align: right; font-weight: bold;">
                    {{ number_format($ledger['ending_balance'], 2) }}
                </td>
            </tr>
            <tr></tr> {{-- Baris Kosong Untuk Pemisah --}}
        @endforeach
    </tbody>
</table>
