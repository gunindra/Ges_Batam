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
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: nowrap; background-color: #b9bab8;">Payment Date</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: nowrap; background-color: #b9bab8;">No. DO</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: nowrap; background-color: #b9bab8;">No Voucher</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: nowrap; background-color: #b9bab8;">Description</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: nowrap; background-color: #b9bab8;">Total Debit</th>
            <th style="text-align:center;font-size:11px;border:1px solid black; font-weight: bold; padding: 20px; white-space: nowrap; background-color: #b9bab8;">Total Credit</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ledgerAccounts as $ledger)
            <tr>
                <td colspan="7" style="font-weight: bold; text-align: left; padding: 10px; border:1px solid black;">
                    {{ $ledger['account_name'] }} ({{ $ledger['code'] }})
                </td>
            </tr>
            <tr>
                <td colspan="5" style="border:1px solid black; padding: 8px; font-weight: bold;">Beginning Balance</td>
                <td colspan="2" style="border:1px solid black; padding: 8px; text-align: right;">{{ $ledger['beginning_balance'] }}</td>
            </tr>
            @foreach($ledger['journal_entries'] as $entry)
                <tr>
                    <td style="border:1px solid black; padding: 8px;">
                        {{ date('d/m/Y', strtotime($entry->tanggal)) }}
                    </td>
                    <td style="border:1px solid black; padding: 8px;">
                        {{ $entry->tanggal_payment ? date('d/m/Y H:i:s', strtotime($entry->tanggal_payment)) : '-' }}
                    </td>
                    <td style="border:1px solid black; padding: 8px;">
                        {{ $entry->resi_no_do }}
                    </td>
                    <td style="border:1px solid black; padding: 8px;">
                        {{ $entry->no_journal }}
                        {!! (!empty($entry->pembeli_invoice) || !empty($entry->pembeli_payment)
                        ? ' - ' . (!empty($entry->pembeli_invoice) ? $entry->pembeli_invoice : $entry->pembeli_payment)
                        : '') !!}
                    </td>
                    <td style="border:1px solid black; padding: 8px;">
                        {{ $entry->items_description }}
                    </td>
                    <td style="border:1px solid black; padding: 8px; text-align: right;">
                        {{ $entry->debit }}
                    </td>
                    <td style="border:1px solid black; padding: 8px; text-align: right;">
                        {{ $entry->credit }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" style="border:1px solid black; padding: 8px; font-weight: bold;">Ending Balance</td>
                <td colspan="2" style="border:1px solid black; padding: 8px; text-align: right; font-weight: bold;">
                    {{ $ledger['ending_balance'] }}
                </td>
            </tr>
            <tr></tr> {{-- Baris Kosong Untuk Pemisah --}}
        @endforeach
    </tbody>
</table>
