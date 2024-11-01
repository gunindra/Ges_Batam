<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class EquityController extends Controller
{
    public function index() {

        return view('Report.Equity.indexequity');
    }

    public function getEquity(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : date('Y-m-01');
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : date('Y-m-t');

        $capital = DB::select("SELECT coa.name AS account_name,
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
                                WHERE coa.id = 81
                                GROUP BY coa_id, account_name");

        $additionalCapital = DB::select("SELECT coa.name AS account_name,
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
                                        WHERE coa.id = 82
                                        GROUP BY coa_id, account_name");

        $returnedProfit = DB::select("SELECT coa.name AS account_name,
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
                                    WHERE coa.id = 84
                                    GROUP BY coa_id, account_name");

        $currentYearProfit = DB::select("SELECT coa.name AS account_name,
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
                                        WHERE coa.id = 85
                                        GROUP BY coa_id, account_name");

        $dividen = DB::select("SELECT coa.name AS account_name,
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
                                WHERE coa.id = 87
                                GROUP BY coa_id, account_name");

        $output = '<table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Opening Balance of Owner`s Equity</td>';
                        if ($capital[0]->grand_total >= 0){
                            $output .= '<td class="text-left">' . number_format($capital[0]->grand_total, 2) . '</td> </tr>';
                        }
                        else{
                            $output .= '<td class="text-left">' . number_format($capital[0]->grand_total * -1, 2) . '</td> </tr>';
                        }

        $output .= '<tr>
                        <td>Additional Capital</td>';
                        if ($additionalCapital[0]->grand_total >= 0){
                            $output .= '<td class="text-left">' . number_format($additionalCapital[0]->grand_total, 2) . '</td> </tr>';
                        }
                        else{
                            $output .= '<td class="text-left">' . number_format($additionalCapital[0]->grand_total * -1, 2) . '</td> </tr>';
                        }

        $output .= '<tr>
                        <td>Retained Earning</td>';
                        if ($returnedProfit[0]->grand_total >= 0){
                            $output .= '<td class="text-left">' . number_format($returnedProfit[0]->grand_total, 2) . '</td> </tr>';
                        }
                        else{
                            $output .= '<td class="text-left">' . number_format($returnedProfit[0]->grand_total * -1, 2) . '</td> </tr>';
                        }
                        $output .= '<tr>
                        <td>Current Year Earning</td>';
                        if ($currentYearProfit[0]->grand_total >= 0){
                            $output .= '<td class="text-left">' . number_format($currentYearProfit[0]->grand_total, 2) . '</td> </tr>';
                        }
                        else{
                            $output .= '<td class="text-left">' . number_format($currentYearProfit[0]->grand_total * -1, 2) . '</td> </tr>';
                        }

        $output .= '<tr>
                        <td>Prive or Dividend</td>';
                        if ($dividen[0]->grand_total >= 0){
                            $output .= '<td class="text-right" style="border-bottom:3px solid black;">' . number_format($dividen[0]->grand_total, 2) . '</td> </tr>';
                        }
                        else{
                            $output .= '<td class="text-right" style="border-bottom:3px solid black;">' . number_format($dividen[0]->grand_total * -1, 2) . '</td> </tr>';
                        }

        $output .= '<tr>
                        <td><strong>Ending Balance of Owner`s Equity</Strong></td>';

        $equity = $capital[0]->grand_total + $additionalCapital[0]->grand_total + $returnedProfit[0]->grand_total + $currentYearProfit[0]->grand_total - $dividen[0]->grand_total;

        if ($equity >= 0){
            $output .= '<td class="text-right">' . number_format($equity, 2) . '</td> </tr>';
        }
        else{
            $output .= '<td class="text-right">' . number_format($equity * -1, 2) . '</td> </tr>';
        }
        $output .= '</tbody></table>';


        return $output;
    }


    public function generatePdf(Request $request)
    {
        $htmlOutput = $this->getEquity($request);

        $pdf = PDF::loadHTML($htmlOutput);
        return $pdf->download('Equity_Report.pdf');
    }
}
