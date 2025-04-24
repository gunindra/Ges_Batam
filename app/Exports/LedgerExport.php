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

        // Pastikan filterCode adalah array
        $filterCode = is_array($this->filterCode) ? $this->filterCode : [];

        $filterCodeNames = DB::table('tbl_coa')
            ->whereIn('tbl_coa.id', $filterCode)
            ->pluck('tbl_coa.name')
            ->toArray();

        $filterCodeStr = implode(', ', $filterCodeNames);

        return view('exportExcel.ledgerreport', [
            'ledgerAccounts' => $ledgerAccounts,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'filterCode' => $filterCodeStr,
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
        $filterCode = is_array($this->filterCode) ? $this->filterCode : [];
        $startDate = $this->startDate ? date('Y-m-d', strtotime($this->startDate)) : date('Y-m-01');
        $endDate = $this->endDate ? date('Y-m-d', strtotime($this->endDate)) : date('Y-m-t');

        $coaQuery = DB::table('tbl_coa')
            ->select('tbl_coa.name AS account_name', 'tbl_coa.id AS coa_id', 'tbl_coa.code_account_id AS code', 'tbl_coa.default_posisi AS position')
            ->when(!empty($filterCode), function ($query) use ($filterCode) {
                return $query->whereIn('tbl_coa.id', $filterCode);
            })
            ->orderBy('tbl_coa.code_account_id', 'ASC')
            ->get();

        $ledgerAccounts = [];
        foreach ($coaQuery as $coa) {
            $journalQuery = DB::select("SELECT ji.id AS items_id,
                                            ji.jurnal_id AS jurnal_id,
                                            ji.code_account AS account_id,
                                            ji.debit AS debit,
                                            ji.credit AS credit,
                                            ji.description AS items_description,
                                            ji.memo AS memo,
                                            ju.tanggal AS tanggal,
                                            ju.tanggal_payment AS tanggal_payment,
                                            ju.no_journal AS no_journal,
                                            pem_inv.marking AS pembeli_invoice,
                                            res.no_do AS resi_no_do,
                                            pem_pay.marking AS pembeli_payment
                                        FROM tbl_jurnal_items ji
                                        LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
                                        LEFT JOIN tbl_invoice inv ON ju.invoice_id = inv.id
                                        LEFT JOIN tbl_resi res ON inv.id = res.invoice_id
                                        LEFT JOIN tbl_payment_customer pc ON ju.payment_id = pc.id
                                        LEFT JOIN tbl_pembeli pem_inv ON inv.pembeli_id = pem_inv.id
                                        LEFT JOIN tbl_pembeli pem_pay ON pc.pembeli_id = pem_pay.id
                                        WHERE ji.code_account = $coa->coa_id
                                        AND ju.tanggal >= '$startDate'
                                        AND ju.tanggal <= '$endDate'
                                        ORDER BY ju.tanggal ASC");

            $beginningBalanceQuery = DB::select("SELECT SUM(ji.debit) AS total_debit,
                                                            SUM(ji.credit) AS total_credit
                                                    FROM tbl_jurnal_items ji
                                                    LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
                                                    WHERE ji.code_account = ?
                                                    AND ju.tanggal < ?", [$coa->coa_id, $startDate]);

            $totalDebit = $beginningBalanceQuery[0]->total_debit ?? 0;
            $totalCredit = $beginningBalanceQuery[0]->total_credit ?? 0;

            $beginningBalance = ($coa->position == 'Debit')
                ? ($totalDebit - $totalCredit)
                : ($totalCredit - $totalDebit);

            $totalDebit = array_sum(array_column($journalQuery, 'debit'));
            $totalCredit = array_sum(array_column($journalQuery, 'credit'));

            $endingBalance = ($coa->position == 'Debit')
                ? ($beginningBalance + $totalDebit - $totalCredit)
                : ($beginningBalance + $totalCredit - $totalDebit);

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
