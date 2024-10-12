<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitLossController extends Controller
{
    public function index() {
                                
        return view('report.ProfitLoss.indexprofitloss');
    }

    public function getProfitOrLoss(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : date('Y-m-01');
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : date('Y-m-t');

        $operatingRevenue = DB::select("SELECT coa.name AS account_name,
            coa.id AS coa_id,
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
        WHERE coa.parent_id = 88
        GROUP BY coa_id, account_name");
            
            $output = '
            <div class="card-header">
                <h2 class="card-title">OPERATING REVENUE</h2>
            </div>
            <div class="card-body">
                <table class="table" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 80%;">Account</th>
                            <th style="width: 20%;" class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $total_operating_revenue = 0;
                    foreach ($operatingRevenue as $data) {
                        $total_operating_revenue += $data->grand_total;
                        $output .= ' <tr>
                                        <td>' . ($data->account_name ?? '-') . '</td>';
                                        if ($data->grand_total >= 0){
                                            $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
                                        }
                                        else{
                                            $output .= '<td class="text-right">' . number_format($data->grand_total * -1, 2) . '</td> </tr>';
                                        }
                    }
                    $output .= ' <tr>
                                        <td class="text-left"><b> TOTAL OPEARTING REVENUE </b></td>';
                    if ($total_operating_revenue >= 0){
                        $output .= '<td class="text-right"> <b>' . number_format($total_operating_revenue, 2) . '</b> </td> </tr>';
                    }
                    else{
                        $output .= '<td class="text-right"> <b>' . number_format($total_operating_revenue * -1, 2) . '</b> </td> </tr>';
                    }
                    


        $output .= '</tbody></table></div>';

        $operatingExpenses = DB::select("SELECT coa.name AS account_name,
            coa.id AS coa_id,
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
        WHERE coa.parent_id IN (102, 111, 141, 94, 96)
        GROUP BY coa_id, account_name");
            
            $output .= '
            <div class="card-header">
                <h2 class="card-title">OPERATING EXPENSES</h2>
            </div>
            <div class="card-body">
                <table class="table" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 80%;">Account</th>
                            <th style="width: 20%;" class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $total_operating_expenses = 0;
                    foreach ($operatingExpenses as $data) {
                        $total_operating_expenses += $data->grand_total;
                        $output .= ' <tr>
                                        <td>' . ($data->account_name ?? '-') . '</td>';
                                        if ($data->grand_total >= 0){
                                            $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
                                        }
                                        else{
                                            $output .= '<td class="text-right">' . number_format($data->grand_total * -1, 2) . '</td> </tr>';
                                        }
                    }
                    $output .= ' <tr>
                                        <td class="text-left"><b> TOTAL OPEARTING EXPENSES </b></td>';
                    if ($total_operating_expenses >= 0){
                        $output .= '<td class="text-right"> <b>' . number_format($total_operating_expenses, 2) . '</b> </td> </tr>';
                    }
                    else{
                        $output .= '<td class="text-right"> <b>' . number_format($total_operating_expenses * -1, 2) . '</b> </td> </tr>';
                    }
                    


        $output .= '</tbody></table></div>';

        $nonBusinessRevenue = DB::select("SELECT coa.name AS account_name,
            coa.id AS coa_id,
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
        WHERE coa.parent_id IN (102, 111, 141, 94, 96)
        GROUP BY coa_id, account_name");
            
            $output .= '
            <div class="card-header">
                <h2 class="card-title">NON-BUSINESS REVENUE</h2>
            </div>
            <div class="card-body">
                <table class="table" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 80%;">Account</th>
                            <th style="width: 20%;" class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $total_non_business_revenue = 0;
                    foreach ($nonBusinessRevenue as $data) {
                        $total_non_business_revenue += $data->grand_total;
                        $output .= ' <tr>
                                        <td>' . ($data->account_name ?? '-') . '</td>';
                                        if ($data->grand_total >= 0){
                                            $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
                                        }
                                        else{
                                            $output .= '<td class="text-right">' . number_format($data->grand_total * -1, 2) . '</td> </tr>';
                                        }
                    }
                    $output .= ' <tr>
                                        <td class="text-left"><b> TOTAL NON BUSINESS REVENUE </b></td>';
                    if ($total_non_business_revenue >= 0){
                        $output .= '<td class="text-right"> <b>' . number_format($total_non_business_revenue, 2) . '</b> </td> </tr>';
                    }
                    else{
                        $output .= '<td class="text-right"> <b>' . number_format($total_non_business_revenue * -1, 2) . '</b> </td> </tr>';
                    }

        $output .= '</tbody></table></div>';
        
        $nonBusinessExpenses = DB::select("SELECT coa.name AS account_name,
            coa.id AS coa_id,
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
        WHERE coa.parent_id IN (102, 111, 141, 94, 96)
        GROUP BY coa_id, account_name");
            
            $output .= '
            <div class="card-header">
                <h2 class="card-title">NON-BUSINESS EXPENSES</h2>
            </div>
            <div class="card-body">
                <table class="table" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 80%;">Account</th>
                            <th style="width: 20%;" class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $total_non_business_expenses = 0;
                    foreach ($nonBusinessExpenses as $data) {
                        $total_non_business_expenses += $data->grand_total;
                        $output .= ' <tr>
                                        <td>' . ($data->account_name ?? '-') . '</td>';
                                        if ($data->grand_total >= 0){
                                            $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
                                        }
                                        else{
                                            $output .= '<td class="text-right">' . number_format($data->grand_total * -1, 2) . '</td> </tr>';
                                        }
                    }
                    $output .= ' <tr>
                                        <td class="text-left"><b> TOTAL NON BUSINESS REVENUE </b></td>';
                    if ($total_non_business_expenses >= 0){
                        $output .= '<td class="text-right"> <b>' . number_format($total_non_business_expenses, 2) . '</b> </td> </tr>';
                    }
                    else{
                        $output .= '<td class="text-right"> <b>' . number_format($total_non_business_expenses * -1, 2) . '</b> </td> </tr>';
                    }
                    


        $output .= '</tbody></table></div>';
        
        $net_profit = $total_operating_revenue + $total_operating_expenses + $total_non_business_revenue + $total_non_business_expenses;

        $output .= '
            <div class="card-body">
                <table class="table" width="100%">
                    <thead>
                        <tr>
                            <th style="width: 80%;"><b>NET PROFIT BEFORE TAX</b></th>
                    ';
                    
                    if ($net_profit >= 0){
                        $output .= '<th class="text-right"> <b>' . number_format($net_profit, 2) . '</b> </th> </tr> </thead></table></div>';
                    }
                    else{
                        $output .= '<th class="text-right"> <b>' . number_format($net_profit * -1, 2) . '</b> </th> </tr> </thead></table></div>';
                    }
                    


        $output .= '</thead></table></div>';

        return $output;
    }
}