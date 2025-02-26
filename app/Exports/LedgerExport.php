<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;

class LedgerExport implements FromView
{
    protected $filterCode;
    protected $startDate;
    protected $endDate;

    public function __construct($filterCode, $startDate, $endDate)
    {
        $this->filterCode = $filterCode ?? '-';
        $this->startDate = $startDate ;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $ledgerAccounts = $this->getLedgerData();

        return view('exportExcel.ledgerreport', [
            'ledgerAccounts' => $ledgerAccounts,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'filterCode' => $this->filterCode,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach (range('A', 'C') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }

    private function getLedgerData()
    {
        $coaQuery = DB::table('tbl_coa')
            ->select('tbl_coa.name AS account_name', 'tbl_coa.id AS coa_id', 'tbl_coa.code_account_id AS code', 'tbl_coa.default_posisi AS position')
            ->when($this->filterCode, function ($query, $filterCode) {
                return $query->whereIn('tbl_coa.id', $filterCode);
            })
            ->orderBy('tbl_coa.code_account_id', 'ASC')
            ->get();

        $ledgerAccounts = [];
        foreach ($coaQuery as $coa) {
            $journalQuery = DB::select("SELECT ji.id AS items_id, ji.jurnal_id AS jurnal_id, ji.code_account AS account_id, ji.debit AS debit, ji.credit AS credit, ji.description AS items_description, ju.tanggal AS tanggal FROM tbl_jurnal_items ji LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id WHERE ji.code_account = ? AND ju.tanggal BETWEEN ? AND ?", [$coa->coa_id, $this->startDate, $this->endDate]);

            $beginningBalanceQuery = DB::select("SELECT SUM(ji.debit) AS total_debit, SUM(ji.credit) AS total_credit FROM tbl_jurnal_items ji LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id WHERE ji.code_account = ? AND ju.tanggal < ?", [$coa->coa_id, $this->startDate]);

            $beginningBalance = ($coa->position == 'Debit')
                ? ($beginningBalanceQuery[0]->total_debit ?? 0) - ($beginningBalanceQuery[0]->total_credit ?? 0)
                : ($beginningBalanceQuery[0]->total_credit ?? 0) - ($beginningBalanceQuery[0]->total_debit ?? 0);

            $totalDebit = array_sum(array_column($journalQuery, 'debit'));
            $totalCredit = array_sum(array_column($journalQuery, 'credit'));

            $endingBalance = ($coa->position == 'Debit')
                ? $beginningBalance + $totalDebit - $totalCredit
                : $beginningBalance + $totalCredit - $totalDebit;

            if (!empty($journalQuery) || $beginningBalance != 0) {
                $ledgerAccounts[] = [
                    'coa_id' => $coa->coa_id,
                    'account_name' => $coa->account_name,
                    'code' => $coa->code,
                    'beginning_balance' => $beginningBalance,
                    'ending_balance' => $endingBalance,
                    'journal_entries' => $journalQuery,
                ];
            }
        }

        return $ledgerAccounts;
    }
}
