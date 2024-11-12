<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class CashFlowController extends Controller
{
    public function index() {


        return view('Report.CashFlow.indexcashflow');
    }

    public function getCashFlow(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : date('Y-m-01');
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : date('Y-m-t');

        $operationsAccount = DB::select("SELECT coa.name AS account_name,
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
                        ELSE 0 END), 0) AS grand_total,
            IFNULL(SUM(CASE
                        WHEN ju.status = 'Approve' AND coa.default_posisi = 'credit'
                        AND ju.tanggal < '$startDate'
                        THEN ji.credit - ji.debit
                        WHEN ju.status = 'Approve' AND coa.default_posisi = 'debit'
                        AND ju.tanggal < '$startDate'
                        THEN ji.debit - ji.credit
                        ELSE 0 END), 0) AS begining_balance
        FROM tbl_coa coa
        LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
        LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
        WHERE coa.parent_id NOT IN (35, 80, 83, 86)
        AND coa.id NOT IN (35, 80, 83, 86)
        GROUP BY coa_id, account_name
        HAVING grand_total != 0");

        $investingAccount = DB::select("SELECT coa.name AS account_name,
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
                        ELSE 0 END), 0) AS grand_total,
            IFNULL(SUM(CASE
                        WHEN ju.status = 'Approve' AND coa.default_posisi = 'credit'
                        AND ju.tanggal < '$startDate'
                        THEN ji.credit - ji.debit
                        WHEN ju.status = 'Approve' AND coa.default_posisi = 'debit'
                        AND ju.tanggal < '$startDate'
                        THEN ji.debit - ji.credit
                        ELSE 0 END), 0) AS begining_balance
        FROM tbl_coa coa
        LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
        LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
        WHERE coa.parent_id = 35
        GROUP BY coa_id, account_name
        HAVING grand_total != 0");

    $financingAccount = DB::select("SELECT coa.name AS account_name,
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
                    ELSE 0 END), 0) AS grand_total,
        IFNULL(SUM(CASE
                    WHEN ju.status = 'Approve' AND coa.default_posisi = 'credit'
                    AND ju.tanggal < '$startDate'
                    THEN ji.credit - ji.debit
                    WHEN ju.status = 'Approve' AND coa.default_posisi = 'debit'
                    AND ju.tanggal < '$startDate'
                    THEN ji.debit - ji.credit
                    ELSE 0 END), 0) AS begining_balance
        FROM tbl_coa coa
        LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
        LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
        WHERE coa.parent_id IN (80, 83, 86)
        GROUP BY coa_id, account_name
        HAVING grand_total != 0");
        $total_beginning_balance = 0;

        foreach ($operationsAccount as $data) {
            $total_beginning_balance += $data->begining_balance;
        }
        foreach ($investingAccount as $data) {
            $total_beginning_balance += $data->begining_balance;
        }
        foreach ($financingAccount as $data) {
            $total_beginning_balance += $data->begining_balance;
        }

        $output = '
        <div class="card-body">
            <table class="table" width="100%">
                <thead>
                    <tr>

                        <td><h5 class="page-title"> <b> OPENING CASH BALANCE </b> </h5>
                ';
        $output .= '<td class="text-right"><h5><b>' . number_format($total_beginning_balance, 2) . '</b></h5></td></tr></thead></table>';
        

        $output .= '<table width="100%" class="table table-vcenter card-table">
                        <tr style="font-size: 15px;">
                        <td><h5 class="page-title"> <b> OPERATIONS </b> </h5></td>
                        <td></td>
                        ';
        $operations_end_balance = 0;
        foreach ($operationsAccount as $data) {
            $operations_end_balance += $data->grand_total;
            $output .= ' <tr> <td style="padding-left:50px;">' . ($data->account_name ?? '-') . '</td>';

            $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
           
        }
        $output .= ' <tr>
                            <td class="text-left"><b> OPERATIONS SUBTOTAL </b></td>';
        $output .= '<td class="text-right"> <b>' . number_format($operations_end_balance, 2) . '</b> </td> </tr>';
        

        $output .= '<tr style="font-size: 15px;">
                        <td><h5 class="page-title"> <b> INVESTING </b> </h5></td>
                        <td></td>
                        ';
        $investing_end_balance = 0;
        foreach ($investingAccount as $data) {
            $investing_end_balance += $data->grand_total;
            $output .= ' <tr> <td style="padding-left:50px;">' . ($data->account_name ?? '-') . '</td>';

            $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
            
        }
        $output .= ' <tr>
                            <td class="text-left"><b> INVESTING SUBTOTAL </b></td>';
        $output .= '<td class="text-right"> <b>' . number_format($investing_end_balance, 2) . '</b> </td> </tr>';
       

        $output .= '<tr style="font-size: 15px;">
                        <td><h5 class="page-title"> <b> FINANCING </b> </h5></td>
                        <td></td>
                        ';
        $financing_end_balance = 0;
        foreach ($financingAccount as $data) {
            $financing_end_balance += $data->grand_total;
            $output .= ' <tr> <td style="padding-left:50px;">' . ($data->account_name ?? '-') . '</td>';

            $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
            
        }
        $output .= ' <tr>
                            <td class="text-left"><b> FINANCING SUBTOTAL </b></td>';

        $output .= '<td class="text-right"> <b>' . number_format($financing_end_balance, 2) . '</b> </td> </tr>';
      
        $total_ending_balance = $operations_end_balance + $financing_end_balance + $investing_end_balance;
        $output .= '<tr>
                        <td><h5 class="page-title"> <b> ENDING CASH BALANCE </b> </h5>
                ';
        $output .= '<td class="text-right"><h5><b>' . number_format($total_ending_balance, 2) . '</b></h5></td></tr></thead></table>';
       

        $output .= '</div>';

        return $output;
    }

    public function generatePdf(Request $request)
    {
        $htmlOutput = $this->getCashFlow($request);

        $pdf = PDF::loadHTML($htmlOutput);
        return $pdf->download('CashFLow_Report.pdf');
    }
}
