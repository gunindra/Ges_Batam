<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\ReportAccount;
use App\Models\COA;

class BalanceController extends Controller
{
    public function index() {

        return view('Report.Balance.indexbalance');
    }

    public function getBalance(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : date('Y-m-01');
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : date('Y-m-t');

        $or = ReportAccount::where('type', '=', 'Operating Revenue')
                            ->pluck('coa_id')
                            ->toArray();
        $oe = ReportAccount::where('type', '=', 'Operating Expense')
                            ->pluck('coa_id')
                            ->toArray();
        $nor = ReportAccount::where('type', '=', 'Non Operating Revenue')
                            ->pluck('coa_id')
                            ->toArray();
        $noe = ReportAccount::where('type', '=', 'Non Operating Expense')
                            ->pluck('coa_id')
                            ->toArray();
        $hpp = ReportAccount::where('type', '=', 'HPP')
                            ->pluck('coa_id')
                            ->toArray();
        $CurrentProfitAccounts = ReportAccount::where('type', '=', 'Current Profit')
                            ->pluck('coa_id') 
                            ->toArray();

        $CurrentProfitAccount = implode(',', $CurrentProfitAccounts);
        $accounts = COA::whereIn('id', $CurrentProfitAccounts)->first();
        
        $ors = implode(',', $or);
        $oes = implode(',', $oe);
        $nors = implode(',', $nor);
        $noes = implode(',', $noe);
        $hpps = implode(',', $hpp);
        
        $query = "
            SELECT coa.name AS account_name,
                   coa.id AS coa_id,
                   coa.default_posisi AS default_posisi,
                   IFNULL(SUM(CASE
                                   WHEN ju.status = 'Approve'
                                       AND ju.tanggal >= '$startDate'
                                       AND ju.tanggal <= '$endDate'
                                   THEN ji.debit ELSE 0 END), 0) AS total_debit,
                   IFNULL(SUM(CASE
                                   WHEN ju.status = 'Approve'
                                       AND ju.tanggal >= '$startDate'
                                       AND ju.tanggal <= '$endDate'
                                   THEN ji.credit ELSE 0 END), 0) AS total_credit,
                   IFNULL(SUM(CASE
                                   WHEN ju.status = 'Approve'
                                       AND coa.default_posisi = 'credit'
                                       AND ju.tanggal >= '$startDate'
                                       AND ju.tanggal <= '$endDate'
                                   THEN ji.credit - ji.debit
                                   WHEN ju.status = 'Approve'
                                       AND coa.default_posisi = 'debit'
                                       AND ju.tanggal >= '$startDate'
                                       AND ju.tanggal <= '$endDate'
                                   THEN ji.debit - ji.credit
                                   ELSE 0 END), 0) AS grand_total
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id IN ($ors)
            GROUP BY coa_id, account_name, default_posisi
            HAVING grand_total != 0
        ";
        
        $query2 = "
            SELECT coa.name AS account_name,
                coa.id AS coa_id,
                coa.default_posisi AS default_posisi,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$startDate'
                                    AND ju.tanggal <= '$endDate'
                                THEN ji.debit ELSE 0 END), 0) AS total_debit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$startDate'
                                    AND ju.tanggal <= '$endDate'
                                THEN ji.credit ELSE 0 END), 0) AS total_credit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'credit'
                                    AND ju.tanggal >= '$startDate'
                                    AND ju.tanggal <= '$endDate'
                                THEN ji.credit - ji.debit
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'debit'
                                    AND ju.tanggal >= '$startDate'
                                    AND ju.tanggal <= '$endDate'
                                THEN ji.debit - ji.credit
                                ELSE 0 END), 0) AS grand_total
            
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id IN ($oes, $hpps)
            GROUP BY coa_id, account_name, default_posisi
            HAVING grand_total != 0
        ";

        $query3 = "
            SELECT coa.name AS account_name,
                coa.id AS coa_id,
                coa.default_posisi AS default_posisi,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$startDate'
                                    AND ju.tanggal <= '$endDate'
                                THEN ji.debit ELSE 0 END), 0) AS total_debit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$startDate'
                                    AND ju.tanggal <= '$endDate'
                                THEN ji.credit ELSE 0 END), 0) AS total_credit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'credit'
                                    AND ju.tanggal >= '$startDate'
                                    AND ju.tanggal <= '$endDate'
                                THEN ji.credit - ji.debit
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'debit'
                                    AND ju.tanggal >= '$startDate'
                                    AND ju.tanggal <= '$endDate'
                                THEN ji.debit - ji.credit
                                ELSE 0 END), 0) AS grand_total
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id IN ($nors)
            GROUP BY coa_id, account_name, default_posisi
            HAVING grand_total != 0
        ";

        $query4 = "
            SELECT coa.name AS account_name,
                coa.id AS coa_id,
                coa.default_posisi AS default_posisi,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$startDate'
                                    AND ju.tanggal <= '$endDate'
                                THEN ji.debit ELSE 0 END), 0) AS total_debit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$startDate'
                                    AND ju.tanggal <= '$endDate'
                                THEN ji.credit ELSE 0 END), 0) AS total_credit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'credit'
                                    AND ju.tanggal >= '$startDate'
                                    AND ju.tanggal <= '$endDate'
                                THEN ji.credit - ji.debit
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'debit'
                                    AND ju.tanggal >= '$startDate'
                                    AND ju.tanggal <= '$endDate'
                                THEN ji.debit - ji.credit
                                ELSE 0 END), 0) AS grand_total
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id IN ($noes)
            GROUP BY coa_id, account_name, default_posisi
            HAVING grand_total != 0
        ";

        $operatingRevenue = DB::select($query);
        $operatingExpenses = DB::select($query2);
        $nonBusinessRevenue = DB::select($query3);
        $nonBusinessExpenses = DB::select($query4);

        $netProfit = 0;

        $allTransactions = collect($operatingRevenue)
                            ->merge($operatingExpenses)
                            ->merge($nonBusinessRevenue)
                            ->merge($nonBusinessExpenses);

        // Menghitung total berdasarkan default_posisi
        
        $netProfit = $allTransactions->sum(function ($item) {
            return ($item->default_posisi === 'Credit') ? $item->grand_total : -$item->grand_total;
        });

        $netProfit = round($netProfit, 2);
        
        $assetAccount = DB::select("SELECT coa.name AS account_name,
                                        coa.id AS coa_id,
                                        coa.set_as_group AS set_as_group,
                                        coa.code_account_id AS code,
                                        IFNULL(SUM(CASE WHEN ju.status = 'Approve'
                                                        AND ju.tanggal >= '$startDate'
                                                        AND ju.tanggal <= '$endDate'
                                                        THEN ji.debit ELSE 0 END), 0) AS total_debit,
                                        IFNULL(SUM(CASE WHEN ju.status = 'Approve'
                                                        AND ju.tanggal >= '$startDate'
                                                        AND ju.tanggal <= '$endDate'
                                                        THEN ji.credit ELSE 0 END), 0) AS total_credit,
                                        IFNULL(SUM(CASE
                                                    WHEN ju.status = 'Approve' AND coa.default_posisi = 'credit'
                                                    AND ju.tanggal >= '$startDate'
                                                    AND ju.tanggal <= '$endDate'
                                                    THEN ji.credit - ji.debit
                                                    WHEN ju.status = 'Approve' AND coa.default_posisi = 'debit'
                                                    AND ju.tanggal >= '$startDate'
                                                    AND ju.tanggal <= '$endDate'
                                                    THEN ji.debit - ji.credit
                                                    ELSE 0 END), 0) AS grand_total
                                    FROM tbl_coa coa
                                    LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
                                    LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
                                    WHERE coa.code_account_id LIKE '1%'
                                    AND coa.set_as_group = 0
                                    GROUP BY coa_id, account_name, code, set_as_group
                                    HAVING grand_total != 0");

        $liabilityAccount = DB::select("SELECT coa.name AS account_name,
                                        coa.id AS coa_id,
                                        coa.set_as_group AS set_as_group,
                                        coa.code_account_id AS code,
                                        IFNULL(SUM(CASE WHEN ju.status = 'Approve'
                                                        AND ju.tanggal >= '$startDate'
                                                        AND ju.tanggal <= '$endDate'
                                                        THEN ji.debit ELSE 0 END), 0) AS total_debit,
                                        IFNULL(SUM(CASE WHEN ju.status = 'Approve'
                                                        AND ju.tanggal >= '$startDate'
                                                        AND ju.tanggal <= '$endDate'
                                                        THEN ji.credit ELSE 0 END), 0) AS total_credit,
                                        IFNULL(SUM(CASE
                                                    WHEN ju.status = 'Approve' AND coa.default_posisi = 'credit'
                                                    AND ju.tanggal >= '$startDate'
                                                    AND ju.tanggal <= '$endDate'
                                                    THEN ji.credit - ji.debit
                                                    WHEN ju.status = 'Approve' AND coa.default_posisi = 'debit'
                                                    AND ju.tanggal >= '$startDate'
                                                    AND ju.tanggal <= '$endDate'
                                                    THEN ji.debit - ji.credit
                                                    ELSE 0 END), 0) AS grand_total
                                    FROM tbl_coa coa
                                    LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
                                    LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
                                    WHERE coa.code_account_id LIKE '2%'
                                    AND coa.set_as_group = 0
                                    GROUP BY coa_id, account_name, code, set_as_group
                                    HAVING grand_total != 0");

        $equityAccount = DB::select("SELECT coa.name AS account_name,
                                        coa.id AS coa_id,
                                        coa.set_as_group AS set_as_group,
                                        coa.code_account_id AS code,
                                        IFNULL(SUM(CASE WHEN ju.status = 'Approve'
                                                        AND ju.tanggal >= '$startDate'
                                                        AND ju.tanggal <= '$endDate'
                                                        THEN ji.debit ELSE 0 END), 0) AS total_debit,
                                        IFNULL(SUM(CASE WHEN ju.status = 'Approve'
                                                        AND ju.tanggal >= '$startDate'
                                                        AND ju.tanggal <= '$endDate'
                                                        THEN ji.credit ELSE 0 END), 0) AS total_credit,
                                        IFNULL(SUM(CASE
                                                    WHEN ju.status = 'Approve' AND coa.default_posisi = 'credit'
                                                    AND ju.tanggal >= '$startDate'
                                                    AND ju.tanggal <= '$endDate'
                                                    THEN ji.credit - ji.debit
                                                    WHEN ju.status = 'Approve' AND coa.default_posisi = 'debit'
                                                    AND ju.tanggal >= '$startDate'
                                                    AND ju.tanggal <= '$endDate'
                                                    THEN ji.debit - ji.credit
                                                    ELSE 0 END), 0) AS grand_total
                                    FROM tbl_coa coa
                                    LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
                                    LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
                                    WHERE coa.code_account_id LIKE '3%'
                                    AND coa.set_as_group = 0
                                    GROUP BY coa_id, account_name, code, set_as_group
                                    HAVING grand_total != 0");
        $output = '<div class="card-body">
                    <table class="table" width="100%">';

        $total_sum_asset = 0;
        foreach ($assetAccount as $data) {
            $total_sum_asset += $data->grand_total;
            $output .= '<tr>
                            <td style="padding-left:50px;">' . (($data->code ?? '-') . ' ' . ($data->account_name ?? '-') . '') . '</td>';
                            if ($data->grand_total >= 0){
                                $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
                            }
                            else{
                                $output .= '<td class="text-right">' . number_format($data->grand_total * -1, 2) . '</td> </tr>';
                            }
        }
        $output .= '<tr>
                        <td> <b> TOTAL ASSET </b></td>';
                        if ($total_sum_asset >= 0){
                            $output .= '<td class="text-right"><b>' . number_format($total_sum_asset, 2) . '</b> </td> </tr>';
                        }
                        else{
                            $output .= '<td class="text-right"><b>' . number_format($total_sum_asset * -1, 2) . '</b> </td> </tr>';
                        }

        $total_sum_liability = 0;
        foreach ($liabilityAccount as $data) {
            $total_sum_liability += $data->grand_total;
            $output .= '<tr>
                            <td style="padding-left:50px;">' . (($data->code ?? '-') . ' ' . ($data->account_name ?? '-') . '') . '</td>';
                            if ($data->grand_total >= 0){
                                $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
                            }
                            else{
                                $output .= '<td class="text-right">' . number_format($data->grand_total * -1, 2) . '</td> </tr>';
                            }
        }
        $output .= '<tr>
                        <td> <b> TOTAL LIABILITY</b></td>';
                        if ($total_sum_liability >= 0){
                            $output .= '<td class="text-right"><b>' . number_format($total_sum_liability, 2) . '</b> </td> </tr>';
                        }
                        else{
                            $output .= '<td class="text-right"><b>' . number_format($total_sum_liability * -1, 2) . '</b> </td> </tr>';
                        }

        $total_sum_equity = 0;
        $total_equity_and_profit = 0;
        foreach ($equityAccount as $data) {
            $total_sum_equity += $data->grand_total;
            
            $output .= '<tr>
                            <td style="padding-left:50px;">' . (($data->code ?? '-') . ' ' . ($data->account_name ?? '-') . '') . '</td>';
                            if ($data->grand_total >= 0){
                                $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
                            }
                            else{
                                $output .= '<td class="text-right">' . number_format($data->grand_total * -1, 2) . '</td>';
                            }
        }
        //Net Profit
        $output .= '<td style="padding-left:50px;">' . (($accounts->code_account_id ?? '-') . ' ' . ($accounts->name ?? '-') . '') . '</td>';
                            if ($netProfit >= 0){
                                $output .= '<td class="text-right">' . number_format($netProfit, 2) . '</td> </tr>';
                            }
                            else{
                                $output .= '<td class="text-right">' . number_format($netProfit * -1, 2) . '</td> </tr>';
                            }
        
        $total_equity_and_profit = $total_sum_equity + $netProfit;
        $output .= '<tr>
                        <td> <b> TOTAL EQUITY</b></td>';
                        if ($total_equity_and_profit >= 0){
                            $output .= '<td class="text-right"><b>' . number_format($total_equity_and_profit, 2) . '</b> </td> </tr>';
                        }
                        else{
                            $output .= '<td class="text-right"><b>' . number_format($total_equity_and_profit * -1, 2) . '</b> </td> </tr>';
                        }
        
        $total_pasiva = $total_equity_and_profit + $total_sum_liability;
        $output .= '<tr>
                        <td> <b> TOTAL PASIVA</b></td>';
                        if ($total_pasiva >= 0){
                            $output .= '<td class="text-right"><b>' . number_format($total_pasiva, 2) . '</b> </td> </tr>';
                        }
                        else{
                            $output .= '<td class="text-right"><b>' . number_format($total_pasiva * -1, 2) . '</b> </td> </tr>';
                        }

        $output .= '</table> </div>';

        return $output;
    }



    public function generatePdf(Request $request)
    {
        $htmlOutput = $this->getBalance($request);

        $pdf = PDF::loadHTML($htmlOutput);
        return $pdf->download('Balance_Report.pdf');
    }
}
