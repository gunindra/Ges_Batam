<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfitLossController extends Controller
{
    public function index() {

        return view('Report.ProfitLoss.indexprofitloss');
    }

    // public function getProfitOrLoss(Request $request)
    // {

    //     $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : date('Y-m-01');
    //     $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : date('Y-m-t');

    //     // Handling comparisons from the frontend
    //     $comparisons = $request->comparisons ?? [];

    //     $query = "
    //         SELECT coa.name AS account_name,
    //                coa.id AS coa_id,
    //                IFNULL(SUM(CASE
    //                                WHEN ju.status = 'Approve'
    //                                    AND ju.tanggal >= '$startDate'
    //                                    AND ju.tanggal <= '$endDate'
    //                                THEN ji.debit ELSE 0 END), 0) AS total_debit,
    //                IFNULL(SUM(CASE
    //                                WHEN ju.status = 'Approve'
    //                                    AND ju.tanggal >= '$startDate'
    //                                    AND ju.tanggal <= '$endDate'
    //                                THEN ji.credit ELSE 0 END), 0) AS total_credit,
    //                IFNULL(SUM(CASE
    //                                WHEN ju.status = 'Approve'
    //                                    AND coa.default_posisi = 'credit'
    //                                    AND ju.tanggal >= '$startDate'
    //                                    AND ju.tanggal <= '$endDate'
    //                                THEN ji.credit - ji.debit
    //                                WHEN ju.status = 'Approve'
    //                                    AND coa.default_posisi = 'debit'
    //                                    AND ju.tanggal >= '$startDate'
    //                                    AND ju.tanggal <= '$endDate'
    //                                THEN ji.debit - ji.credit
    //                                ELSE 0 END), 0) AS grand_total
    //     ";

    //     foreach ($comparisons as $index => $comparison) {
    //         $compareStart = date('Y-m-d', strtotime($comparison['start']));
    //         $compareEnd = date('Y-m-d', strtotime($comparison['end']));

    //         $query .= ",
    //             IFNULL(SUM(CASE
    //                            WHEN ju.status = 'Approve'
    //                                AND ju.tanggal >= '$compareStart'
    //                                AND ju.tanggal <= '$compareEnd'
    //                            THEN ji.debit ELSE 0 END), 0) AS compare_debit_$index,
    //             IFNULL(SUM(CASE
    //                            WHEN ju.status = 'Approve'
    //                                AND ju.tanggal >= '$compareStart'
    //                                AND ju.tanggal <= '$compareEnd'
    //                            THEN ji.credit ELSE 0 END), 0) AS compare_credit_$index,
    //             IFNULL(SUM(CASE
    //                            WHEN ju.status = 'Approve'
    //                                AND coa.default_posisi = 'credit'
    //                                AND ju.tanggal >= '$compareStart'
    //                                AND ju.tanggal <= '$compareEnd'
    //                            THEN ji.credit - ji.debit
    //                            WHEN ju.status = 'Approve'
    //                                AND coa.default_posisi = 'debit'
    //                                AND ju.tanggal >= '$compareStart'
    //                                AND ju.tanggal <= '$compareEnd'
    //                            THEN ji.debit - ji.credit
    //                            ELSE 0 END), 0) AS compare_total_$index
    //         ";
    //     }

    //     $query .= "
    //         FROM tbl_coa coa
    //         LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
    //         LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
    //         WHERE coa.parent_id = 88
    //         GROUP BY coa_id, account_name
    //         HAVING grand_total != 0
    //     ";

    //     foreach ($comparisons as $index => $comparison) {
    //         $query .= " OR compare_total_$index != 0";
    //     }

    //     $operatingRevenue = DB::select($query);

    //     $output = '
    //     <h5 style="text-align:center; width:100%">'
    //         . \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - '
    //         . \Carbon\Carbon::parse($endDate)->format('d M Y') .
    //     '</h5>
    //     <div class="card-body">
    //         <table class="table" width="100%">
    //             <thead>
    //                 <tr>
    //                     <th>Account Name</th>
    //                     <th style="text-align:right">Total</th>';

    //     foreach ($comparisons as $index => $comparison) {
    //         $output .= '<th style="text-align:right">'
    //                     . \Carbon\Carbon::parse($comparison['start'])->format('d M Y') . ' - '
    //                     . \Carbon\Carbon::parse($comparison['end'])->format('d M Y') .
    //                   '</th>';
    //     }

    //     $output .= '</tr></thead><tbody>';
    //     $total_operating_revenue = 0;
    //     $compare_operating_revenue = array_fill(0, count($comparisons), 0); // Initialize array to store comparison totals

    //     foreach ($operatingRevenue as $data) {
    //         $total_operating_revenue += $data->grand_total;
    //         foreach ($comparisons as $index => $comparison) {
    //             $compare_operating_revenue[$index] += $data->{'compare_total_' . $index};
    //         }
    //         $output .= ' <tr>
    //                         <td style="padding-left:50px;">' . ($data->account_name ?? '-') . '</td>';
    //         $output .= '<td style="text-align:right">' . number_format($data->grand_total, 2) . '</td>';

    //         foreach ($comparisons as $index => $comparison) {
    //             $output .= '<td style="text-align:right">' . number_format($data->{'compare_total_' . $index}, 2) . '</td>';
    //         }
    //         $output .= '</tr>';
    //     }

    //     $output .= ' <tr>
    //                         <td class="text-left"><b> TOTAL OPERATING REVENUE </b></td>';
    //     $output .= '<td style="text-align:right"> <b>' . number_format($total_operating_revenue, 2) . '</b> </td>';

    //     foreach ($comparisons as $index => $comparison) {
    //         $output .= '<td style="text-align:right"><b>' . number_format($compare_operating_revenue[$index], 2) . '</b></td>';
    //     }

    //     $output .= '</tr>';



    //     // $query2 = "
    //     //     SELECT coa.name AS account_name,
    //     //         coa.id AS coa_id,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND ju.tanggal >= '$startDate'
    //     //                             AND ju.tanggal <= '$endDate'
    //     //                         THEN ji.debit ELSE 0 END), 0) AS total_debit,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND ju.tanggal >= '$startDate'
    //     //                             AND ju.tanggal <= '$endDate'
    //     //                         THEN ji.credit ELSE 0 END), 0) AS total_credit,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND coa.default_posisi = 'credit'
    //     //                             AND ju.tanggal >= '$startDate'
    //     //                             AND ju.tanggal <= '$endDate'
    //     //                         THEN ji.credit - ji.debit
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND coa.default_posisi = 'debit'
    //     //                             AND ju.tanggal >= '$startDate'
    //     //                             AND ju.tanggal <= '$endDate'
    //     //                         THEN ji.debit - ji.credit
    //     //                         ELSE 0 END), 0) AS grand_total
    //     // ";

    //     // if ($compareStart && $compareEnd) {
    //     //     $query2 .= ",
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND ju.tanggal >= '$compareStart'
    //     //                             AND ju.tanggal <= '$compareEnd'
    //     //                         THEN ji.debit ELSE 0 END), 0) AS compare_debit,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND ju.tanggal >= '$compareStart'
    //     //                             AND ju.tanggal <= '$compareEnd'
    //     //                         THEN ji.credit ELSE 0 END), 0) AS compare_credit,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND coa.default_posisi = 'credit'
    //     //                             AND ju.tanggal >= '$compareStart'
    //     //                             AND ju.tanggal <= '$compareEnd'
    //     //                         THEN ji.credit - ji.debit
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND coa.default_posisi = 'debit'
    //     //                             AND ju.tanggal >= '$compareStart'
    //     //                             AND ju.tanggal <= '$compareEnd'
    //     //                         THEN ji.debit - ji.credit
    //     //                         ELSE 0 END), 0) AS compare_total
    //     //     ";
    //     // }

    //     // $query2 .= "
    //     //     FROM tbl_coa coa
    //     //     LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
    //     //     LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
    //     //     WHERE coa.parent_id IN (102, 111, 141, 94, 96)
    //     //     GROUP BY coa_id, account_name
    //     //     HAVING grand_total != 0
    //     // ";

    //     // if ($compareStart && $compareEnd) {
    //     //     $query2 .= " OR compare_total != 0";
    //     // }

    //     // $operatingExpenses = DB::select($query2);


    //     // $total_operating_expenses = 0;
    //     // $compare_operating_expenses = 0;
    //     // foreach ($operatingExpenses as $data) {
    //     //     $total_operating_expenses += $data->grand_total;
    //     //     if ($compareStart && $compareEnd) {
    //     //         $compare_operating_expenses += $data->compare_total;
    //     //     }
    //     //     $output .= ' <tr>
    //     //                     <td style="padding-left:50px;">' . ($data->account_name ?? '-') . '</td>';
    //     //     $output .= '<td style="text-align:right">' . number_format($data->grand_total, 2) . '</td>';
    //     //     if ($compareStart && $compareEnd) {
    //     //         $output .= '<td style="text-align:right">' . number_format($data->compare_total, 2) . '</td>';
    //     //     }
    //     //     $output .= '</tr>';

    //     // }
    //     // $output .= ' <tr>
    //     //                     <td class="text-left"><b> TOTAL OPEARTING EXPENSES </b></td>';
    //     // $output .= '<td style="text-align:right"> <b> (' . number_format($total_operating_expenses, 2) . ') </b> </td>';
    //     // if ($compareStart && $compareEnd) {
    //     //     $output .= '<td style="text-align:right"><b> (' . number_format($compare_operating_expenses, 2) . ') </b></td>';
    //     // }
    //     // $output .= '</tr>';


    //     // $query3 = "
    //     //     SELECT coa.name AS account_name,
    //     //         coa.id AS coa_id,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND ju.tanggal >= '$startDate'
    //     //                             AND ju.tanggal <= '$endDate'
    //     //                         THEN ji.debit ELSE 0 END), 0) AS total_debit,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND ju.tanggal >= '$startDate'
    //     //                             AND ju.tanggal <= '$endDate'
    //     //                         THEN ji.credit ELSE 0 END), 0) AS total_credit,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND coa.default_posisi = 'credit'
    //     //                             AND ju.tanggal >= '$startDate'
    //     //                             AND ju.tanggal <= '$endDate'
    //     //                         THEN ji.credit - ji.debit
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND coa.default_posisi = 'debit'
    //     //                             AND ju.tanggal >= '$startDate'
    //     //                             AND ju.tanggal <= '$endDate'
    //     //                         THEN ji.debit - ji.credit
    //     //                         ELSE 0 END), 0) AS grand_total
    //     // ";

    //     // if ($compareStart && $compareEnd) {
    //     //     $query3 .= ",
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND ju.tanggal >= '$compareStart'
    //     //                             AND ju.tanggal <= '$compareEnd'
    //     //                         THEN ji.debit ELSE 0 END), 0) AS compare_debit,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND ju.tanggal >= '$compareStart'
    //     //                             AND ju.tanggal <= '$compareEnd'
    //     //                         THEN ji.credit ELSE 0 END), 0) AS compare_credit,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND coa.default_posisi = 'credit'
    //     //                             AND ju.tanggal >= '$compareStart'
    //     //                             AND ju.tanggal <= '$compareEnd'
    //     //                         THEN ji.credit - ji.debit
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND coa.default_posisi = 'debit'
    //     //                             AND ju.tanggal >= '$compareStart'
    //     //                             AND ju.tanggal <= '$compareEnd'
    //     //                         THEN ji.debit - ji.credit
    //     //                         ELSE 0 END), 0) AS compare_total
    //     //     ";
    //     // }

    //     // $query3 .= "
    //     //     FROM tbl_coa coa
    //     //     LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
    //     //     LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
    //     //     WHERE coa.parent_id = 152
    //     //     GROUP BY coa_id, account_name
    //     //     HAVING grand_total != 0
    //     // ";

    //     // if ($compareStart && $compareEnd) {
    //     //     $query3 .= " OR compare_total != 0";
    //     // }

    //     // $nonBusinessRevenue = DB::select($query3);


    //     // $total_non_business_revenue = 0;
    //     // $compare_non_business_revenue = 0;
    //     // foreach ($nonBusinessRevenue as $data) {
    //     //     $total_non_business_revenue += $data->grand_total;
    //     //     if ($compareStart && $compareEnd) {
    //     //         $compare_non_business_revenue += $data->compare_total;
    //     //     }
    //     //     $output .= ' <tr>
    //     //                     <td style="padding-left:50px;">' . ($data->account_name ?? '-') . '</td>';

    //     //     $output .= '<td style="text-align:right">' . number_format($data->grand_total, 2) . '</td>';
    //     //     if ($compareStart && $compareEnd) {
    //     //         $output .= '<td style="text-align:right">' . number_format($data->compare_total, 2) . '</td>';
    //     //     }
    //     //     $output .= '</tr>';

    //     // }
    //     // $output .= ' <tr>
    //     //                     <td class="text-left"><b> TOTAL NON BUSINESS REVENUE </b></td>';
    //     // $output .= '<td style="text-align:right"> <b>' . number_format($total_non_business_revenue, 2) . '</b> </td>';
    //     // if ($compareStart && $compareEnd) {
    //     //     $output .= '<td style="text-align:right"><b>' . number_format($compare_non_business_revenue, 2) . '</b></td>';
    //     // }
    //     // $output .= '</tr>';

    //     // $query4 = "
    //     //     SELECT coa.name AS account_name,
    //     //         coa.id AS coa_id,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND ju.tanggal >= '$startDate'
    //     //                             AND ju.tanggal <= '$endDate'
    //     //                         THEN ji.debit ELSE 0 END), 0) AS total_debit,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND ju.tanggal >= '$startDate'
    //     //                             AND ju.tanggal <= '$endDate'
    //     //                         THEN ji.credit ELSE 0 END), 0) AS total_credit,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND coa.default_posisi = 'credit'
    //     //                             AND ju.tanggal >= '$startDate'
    //     //                             AND ju.tanggal <= '$endDate'
    //     //                         THEN ji.credit - ji.debit
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND coa.default_posisi = 'debit'
    //     //                             AND ju.tanggal >= '$startDate'
    //     //                             AND ju.tanggal <= '$endDate'
    //     //                         THEN ji.debit - ji.credit
    //     //                         ELSE 0 END), 0) AS grand_total
    //     // ";

    //     // if ($compareStart && $compareEnd) {
    //     //     $query4 .= ",
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND ju.tanggal >= '$compareStart'
    //     //                             AND ju.tanggal <= '$compareEnd'
    //     //                         THEN ji.debit ELSE 0 END), 0) AS compare_debit,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND ju.tanggal >= '$compareStart'
    //     //                             AND ju.tanggal <= '$compareEnd'
    //     //                         THEN ji.credit ELSE 0 END), 0) AS compare_credit,
    //     //         IFNULL(SUM(CASE
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND coa.default_posisi = 'credit'
    //     //                             AND ju.tanggal >= '$compareStart'
    //     //                             AND ju.tanggal <= '$compareEnd'
    //     //                         THEN ji.credit - ji.debit
    //     //                         WHEN ju.status = 'Approve'
    //     //                             AND coa.default_posisi = 'debit'
    //     //                             AND ju.tanggal >= '$compareStart'
    //     //                             AND ju.tanggal <= '$compareEnd'
    //     //                         THEN ji.debit - ji.credit
    //     //                         ELSE 0 END), 0) AS compare_total
    //     //     ";
    //     // }

    //     // $query4 .= "
    //     //     FROM tbl_coa coa
    //     //     LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
    //     //     LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
    //     //     WHERE coa.parent_id = 157
    //     //     GROUP BY coa_id, account_name
    //     //     HAVING grand_total != 0
    //     // ";

    //     // if ($compareStart && $compareEnd) {
    //     //     $query4 .= " OR compare_total != 0";
    //     // }

    //     // $nonBusinessExpenses = DB::select($query4);

    //     // $total_non_business_expenses = 0;
    //     // $compare_non_business_expenses = 0;
    //     // foreach ($nonBusinessExpenses as $data) {
    //     //     $total_non_business_expenses += $data->grand_total;
    //     //     if ($compareStart && $compareEnd) {
    //     //         $compare_non_business_expenses += $data->compare_total;
    //     //     }
    //     //     $output .= ' <tr>
    //     //                     <td style="padding-left:50px;">' . ($data->account_name ?? '-') . '</td>';
    //     //     $output .= '<td style="text-align:right">' . number_format($data->grand_total, 2) . '</td> ';
    //     //     if ($compareStart && $compareEnd) {
    //     //         $output .= '<td style="text-align:right">' . number_format($data->compare_total, 2) . '</td>';
    //     //     }
    //     //     $output .= '</tr>';

    //     // }
    //     // $output .= ' <tr>
    //     //                     <td class="text-left"><b> TOTAL NON BUSINESS REVENUE </b></td>';
    //     // $output .= '<td style="text-align:right"> <b> (' . number_format($total_non_business_expenses, 2) . ') </b> </td> ';
    //     // if ($compareStart && $compareEnd) {
    //     //     $output .= '<td style="text-align:right"> <b> (' . number_format($compare_non_business_expenses, 2) . ') </b> </td>';
    //     // }
    //     // $output .= '</tr>';


    //     // $net_profit = $total_operating_revenue - $total_operating_expenses + $total_non_business_revenue - $total_non_business_expenses;
    //     // if ($compareStart && $compareEnd) {
    //     //     $compare_profit = $compare_operating_revenue - $compare_operating_expenses + $compare_non_business_revenue - $compare_non_business_expenses;
    //     // }
    //     // $output .= '<tr>
    //     //                 <td style="width: 80%;text-align:left;"><b>NET PROFIT BEFORE TAX</b></td>
    //     //             ';


    //     // $output .= '<td style="text-align:right"> <b>' . number_format($net_profit, 2) . '</b> </td> ';
    //     // if ($compareStart && $compareEnd) {
    //     //     $output .= '<td style="text-align:right"> <b>' . number_format($compare_profit, 2) . '</b></td>';
    //     // }
    //     // $output .= '</tr></tbody></table></div>';

    //     return $output;
    // }

    public function getProfitOrLoss(Request $request)
    {
        // dd($request->all());
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : date('Y-m-01');
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : date('Y-m-t');

        $compareStart = $request->compareStart ? date('Y-m-d', strtotime($request->compareStart)) : null;
        $compareEnd = $request->compareEnd ? date('Y-m-d', strtotime($request->compareEnd)) : null;

        $query = "
            SELECT coa.name AS account_name,
                coa.id AS coa_id,
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
        ";

        if ($compareStart && $compareEnd) {
            $query .= ",
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.debit ELSE 0 END), 0) AS compare_debit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.credit ELSE 0 END), 0) AS compare_credit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'credit'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.credit - ji.debit
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'debit'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.debit - ji.credit
                                ELSE 0 END), 0) AS compare_total
            ";
        }

        $query .= "
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id = 86
            GROUP BY coa_id, account_name
            HAVING grand_total != 0
        ";
         // 86 (Pendapatan Usaha)

        if ($compareStart && $compareEnd) {
            $query .= " OR compare_total != 0";
        }

        $operatingRevenue = DB::select($query);

        $output = '
        <h5 style="text-align:center; width:100%">'
            . \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - '
            . \Carbon\Carbon::parse($endDate)->format('d M Y') .
        '</h5>
        <div class="card-body">
            <table class="table" width="100%">
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th style="text-align:right">Total</th>';
                if ($compareStart && $compareEnd) {
                    $output .= '<th style="text-align:right">'
                                    . \Carbon\Carbon::parse($compareStart)->format('d M Y') . ' - '
                                    . \Carbon\Carbon::parse($compareEnd)->format('d M Y') .
                                '</th>';
                }

                $output .= '</tr></thead><tbody>';
                $total_operating_revenue = 0;
                $compare_operating_revenue = 0 ;
                foreach ($operatingRevenue as $data) {
                    $total_operating_revenue += $data->grand_total;
                    if ($compareStart && $compareEnd) {
                        $compare_operating_revenue += $data->compare_total;
                    }
                    $output .= ' <tr>
                                    <td style="padding-left:50px;">' . ($data->account_name ?? '-') . '</td>';
                    $output .= '<td style="text-align:right">' . number_format($data->grand_total, 2) . '</td>';
                    if ($compareStart && $compareEnd) {
                        $output .= '<td style="text-align:right">' . number_format($data->compare_total, 2) . '</td>';
                    }
                    $output .= '</tr>';

                }
                $output .= ' <tr>
                                    <td class="text-left"><b> TOTAL OPEARTING REVENUE </b></td>';
                $output .= '<td style="text-align:right"> <b>' . number_format($total_operating_revenue, 2) . '</b> </td>';
                if ($compareStart && $compareEnd) {
                    $output .= '<td style="text-align:right"><b>' . number_format($compare_operating_revenue, 2) . '</b></td>';
                }
                $output .= '</tr>';


        $query2 = "
            SELECT coa.name AS account_name,
                coa.id AS coa_id,
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
        ";

        if ($compareStart && $compareEnd) {
            $query2 .= ",
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.debit ELSE 0 END), 0) AS compare_debit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.credit ELSE 0 END), 0) AS compare_credit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'credit'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.credit - ji.debit
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'debit'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.debit - ji.credit
                                ELSE 0 END), 0) AS compare_total
            ";
        }

        $query2 .= "
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id IN (97, 91, 106, 136)
            GROUP BY coa_id, account_name
            HAVING grand_total != 0
        ";
        //    97 (Beban Penjualan),
        //    91(Harga Pokok Penjualan),
        //    106 (Beban Administrasi dan Umum),
        //    136( Beban Penyusutan dan amortisasi),

        if ($compareStart && $compareEnd) {
            $query2 .= " OR compare_total != 0";
        }

        $operatingExpenses = DB::select($query2);


        $total_operating_expenses = 0;
        $compare_operating_expenses = 0;
        foreach ($operatingExpenses as $data) {
            $total_operating_expenses += $data->grand_total;
            if ($compareStart && $compareEnd) {
                $compare_operating_expenses += $data->compare_total;
            }
            $output .= ' <tr>
                            <td style="padding-left:50px;">' . ($data->account_name ?? '-') . '</td>';
            $output .= '<td style="text-align:right">' . number_format($data->grand_total, 2) . '</td>';
            if ($compareStart && $compareEnd) {
                $output .= '<td style="text-align:right">' . number_format($data->compare_total, 2) . '</td>';
            }
            $output .= '</tr>';

        }
        $output .= ' <tr>
                            <td class="text-left"><b> TOTAL OPEARTING EXPENSES </b></td>';
        $output .= '<td style="text-align:right"> <b> (' . number_format($total_operating_expenses, 2) . ') </b> </td>';
        if ($compareStart && $compareEnd) {
            $output .= '<td style="text-align:right"><b> (' . number_format($compare_operating_expenses, 2) . ') </b></td>';
        }
        $output .= '</tr>';


        $query3 = "
            SELECT coa.name AS account_name,
                coa.id AS coa_id,
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
        ";

        if ($compareStart && $compareEnd) {
            $query3 .= ",
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.debit ELSE 0 END), 0) AS compare_debit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.credit ELSE 0 END), 0) AS compare_credit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'credit'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.credit - ji.debit
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'debit'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.debit - ji.credit
                                ELSE 0 END), 0) AS compare_total
            ";
        }

        $query3 .= "
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id = 147
            GROUP BY coa_id, account_name
            HAVING grand_total != 0
        ";
        // 147(Pendapatan Di Luar Usaha)

        if ($compareStart && $compareEnd) {
            $query3 .= " OR compare_total != 0";
        }

        $nonBusinessRevenue = DB::select($query3);


        $total_non_business_revenue = 0;
        $compare_non_business_revenue = 0;
        foreach ($nonBusinessRevenue as $data) {
            $total_non_business_revenue += $data->grand_total;
            if ($compareStart && $compareEnd) {
                $compare_non_business_revenue += $data->compare_total;
            }
            $output .= ' <tr>
                            <td style="padding-left:50px;">' . ($data->account_name ?? '-') . '</td>';

            $output .= '<td style="text-align:right">' . number_format($data->grand_total, 2) . '</td>';
            if ($compareStart && $compareEnd) {
                $output .= '<td style="text-align:right">' . number_format($data->compare_total, 2) . '</td>';
            }
            $output .= '</tr>';

        }
        $output .= ' <tr>
                            <td class="text-left"><b> TOTAL NON BUSINESS REVENUE </b></td>';
        $output .= '<td style="text-align:right"> <b>' . number_format($total_non_business_revenue, 2) . '</b> </td>';
        if ($compareStart && $compareEnd) {
            $output .= '<td style="text-align:right"><b>' . number_format($compare_non_business_revenue, 2) . '</b></td>';
        }
        $output .= '</tr>';

        $query4 = "
            SELECT coa.name AS account_name,
                coa.id AS coa_id,
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
        ";

        if ($compareStart && $compareEnd) {
            $query4 .= ",
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.debit ELSE 0 END), 0) AS compare_debit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.credit ELSE 0 END), 0) AS compare_credit,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'credit'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.credit - ji.debit
                                WHEN ju.status = 'Approve'
                                    AND coa.default_posisi = 'debit'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.debit - ji.credit
                                ELSE 0 END), 0) AS compare_total
            ";
        }

        $query4 .= "
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id = 152
            GROUP BY coa_id, account_name
            HAVING grand_total != 0
        ";
        // 152(Beban DiLuar Usaha)

        if ($compareStart && $compareEnd) {
            $query4 .= " OR compare_total != 0";
        }

        $nonBusinessExpenses = DB::select($query4);

        $total_non_business_expenses = 0;
        $compare_non_business_expenses = 0;
        foreach ($nonBusinessExpenses as $data) {
            $total_non_business_expenses += $data->grand_total;
            if ($compareStart && $compareEnd) {
                $compare_non_business_expenses += $data->compare_total;
            }
            $output .= ' <tr>
                            <td style="padding-left:50px;">' . ($data->account_name ?? '-') . '</td>';
            $output .= '<td style="text-align:right">' . number_format($data->grand_total, 2) . '</td> ';
            if ($compareStart && $compareEnd) {
                $output .= '<td style="text-align:right">' . number_format($data->compare_total, 2) . '</td>';
            }
            $output .= '</tr>';

        }
        $output .= ' <tr>
                            <td class="text-left"><b> TOTAL NON BUSINESS REVENUE </b></td>';
        $output .= '<td style="text-align:right"> <b> (' . number_format($total_non_business_expenses, 2) . ') </b> </td> ';
        if ($compareStart && $compareEnd) {
            $output .= '<td style="text-align:right"> <b> (' . number_format($compare_non_business_expenses, 2) . ') </b> </td>';
        }
        $output .= '</tr>';


        $net_profit = $total_operating_revenue - $total_operating_expenses + $total_non_business_revenue - $total_non_business_expenses;
        if ($compareStart && $compareEnd) {
            $compare_profit = $compare_operating_revenue - $compare_operating_expenses + $compare_non_business_revenue - $compare_non_business_expenses;
        }
        $output .= '<tr>
                        <td style="width: 80%;text-align:left;"><b>NET PROFIT BEFORE TAX</b></td>
                    ';


        $output .= '<td style="text-align:right"> <b>' . number_format($net_profit, 2) . '</b> </td> ';
        if ($compareStart && $compareEnd) {
            $output .= '<td style="text-align:right"> <b>' . number_format($compare_profit, 2) . '</b></td>';
        }
        $output .= '</tr></tbody></table></div>';



        return $output;
    }



    public function generatePdf(Request $request)
    {
        $htmlOutput = $this->getProfitOrLoss($request);

        $pdf = PDF::loadHTML($htmlOutput);
        return $pdf->download('ProfitLoss_Report.pdf');
    }

}
