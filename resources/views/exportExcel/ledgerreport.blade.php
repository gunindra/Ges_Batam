<table>
    <thead>
        <tr>
            <td style="text-align:center;font-size:14px; font-weight: bold; padding: 14px" colspan="6">Ledger Report</td>
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
            <td style="text-align:left;font-size:11px;padding: 14px;">Code Account:</td>
            <td style="text-align:left;font-size:11px;padding: 14px;font-weight: bold;">
                {{ $filterCode ? implode(', ', $filterCode) : '-' }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Account Code</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Account Name</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Date</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Description</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Debit</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Credit</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Beginning Balance</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: normal;"
                bgcolor="#b9bab8">Ending Balance</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ledgerAccounts as $account)
            @foreach ($account['journal_entries'] as $entry)
                <tr>
                    <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                        {{ $entry->items_id }}
                    </td>
                    <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                        {{ $account['account_name'] }}
                    </td>
                    <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
                        {{ $entry->tanggal }}
                    </td>
                    <td style="text-align:left;font-size:11px;border:1px solid black; padding: 20px">
    
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
