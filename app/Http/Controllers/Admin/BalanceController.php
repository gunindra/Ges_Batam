<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $assetAccount = DB::select("SELECT coa.name AS account_name,
                                        coa.id AS coa_id,
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
                                    GROUP BY coa_id, account_name, code");

        $liabilityAccount = DB::select("SELECT coa.name AS account_name,
                                        coa.id AS coa_id,
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
                                    GROUP BY coa_id, account_name, code");

        $equityAccount = DB::select("SELECT coa.name AS account_name,
                                        coa.id AS coa_id,
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
                                    GROUP BY coa_id, account_name, code");
        $output = '<div class="card-body">
                    <table class="table" width="100%">';

        $total_sum_asset = 0;
        foreach ($assetAccount as $data) {
            $total_sum_asset += $data->grand_total;
            $output .= '<tr>
                            <td>' . (($data->code ?? '-') . ' ' . ($data->account_name ?? '-') . '') . '</td>';
                            if ($data->grand_total >= 0){
                                $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
                            }
                            else{
                                $output .= '<td class="text-right">' . number_format($data->grand_total * -1, 2) . '</td> </tr>';
                            }
        }
        $output .= '<tr>
                        <td> <b> TOTAL </b></td>';
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
                            <td>' . (($data->code ?? '-') . ' ' . ($data->account_name ?? '-') . '') . '</td>';
                            if ($data->grand_total >= 0){
                                $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
                            }
                            else{
                                $output .= '<td class="text-right">' . number_format($data->grand_total * -1, 2) . '</td> </tr>';
                            }
        }
        $output .= '<tr>
                        <td> <b> TOTAL </b></td>';
                        if ($total_sum_liability >= 0){
                            $output .= '<td class="text-right"><b>' . number_format($total_sum_liability, 2) . '</b> </td> </tr>';
                        }
                        else{
                            $output .= '<td class="text-right"><b>' . number_format($total_sum_liability * -1, 2) . '</b> </td> </tr>';
                        }

        $total_sum_equity = 0;
        foreach ($equityAccount as $data) {
            $total_sum_equity += $data->grand_total;
            $output .= '<tr>
                            <td>' . (($data->code ?? '-') . ' ' . ($data->account_name ?? '-') . '') . '</td>';
                            if ($data->grand_total >= 0){
                                $output .= '<td class="text-right">' . number_format($data->grand_total, 2) . '</td> </tr>';
                            }
                            else{
                                $output .= '<td class="text-right">' . number_format($data->grand_total * -1, 2) . '</td> </tr>';
                            }
        }
        $output .= '<tr>
                        <td> <b> TOTAL </b></td>';
                        if ($total_sum_equity >= 0){
                            $output .= '<td class="text-right"><b>' . number_format($total_sum_equity, 2) . '</b> </td> </tr>';
                        }
                        else{
                            $output .= '<td class="text-right"><b>' . number_format($total_sum_equity * -1, 2) . '</b> </td> </tr>';
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
