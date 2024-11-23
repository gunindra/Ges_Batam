<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LedgerExport implements FromArray, WithHeadings
{
    protected $ledgerAccounts;

    public function __construct(array $ledgerAccounts)
    {
        $this->ledgerAccounts = $ledgerAccounts;
    }

    public function array(): array
    {
        $data = [];
        foreach ($this->ledgerAccounts as $account) {
            $data[] = [
                'Account Code' => $account['code'],
                'Account Name' => $account['account_name'],
                'Beginning Balance' => $account['beginning_balance'],
                'Ending Balance' => $account['ending_balance'],
            ];

            foreach ($account['journal_entries'] as $entry) {
                $data[] = [
                    'Account Code' => '',
                    'Account Name' => '',
                    'Date' => $entry->tanggal,
                    'Description' => $entry->items_description,
                    'Debit' => $entry->debit,
                    'Credit' => $entry->credit,
                ];
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Account Code',
            'Account Name',
            'Date',
            'Description',
            'Debit',
            'Credit',
            'Beginning Balance',
            'Ending Balance',
        ];
    }
}
