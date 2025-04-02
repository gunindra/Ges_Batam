<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\ReportAccount;

class ProfitLossController extends Controller
{
    public function index() {

        return view('Report.ProfitLoss.indexprofitloss');
    }

    public function getProfitOrLoss(Request $request)
    {

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

        $ors = implode(',', $or);
        $oes = implode(',', $oe);
        $nors = implode(',', $nor);
        $noes = implode(',', $noe);
        $hpps = implode(',', $hpp);
        // Handling comparisons from the frontend
        $comparisons = $request->comparisons ?? [];

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
        ";

        foreach ($comparisons as $index => $comparison) {
            $compareStart = date('Y-m-d', strtotime($comparison['start']));
            $compareEnd = date('Y-m-d', strtotime($comparison['end']));

            $query .= ",
                IFNULL(SUM(CASE
                               WHEN ju.status = 'Approve'
                                   AND ju.tanggal >= '$compareStart'
                                   AND ju.tanggal <= '$compareEnd'
                               THEN ji.debit ELSE 0 END), 0) AS compare_debit_$index,
                IFNULL(SUM(CASE
                               WHEN ju.status = 'Approve'
                                   AND ju.tanggal >= '$compareStart'
                                   AND ju.tanggal <= '$compareEnd'
                               THEN ji.credit ELSE 0 END), 0) AS compare_credit_$index,
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
                               ELSE 0 END), 0) AS compare_total_$index
            ";
        }

        $query .= "
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id IN ($ors)
            GROUP BY coa_id, account_name, default_posisi
            HAVING grand_total != 0
        ";

        foreach ($comparisons as $index => $comparison) {
            $query .= " OR compare_total_$index != 0";
        }

        $operatingRevenue = DB::select($query);

        $output = '
        <div class="card-body">
            <table class="table" width="100%">
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th>'
                         . \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - '
                         . \Carbon\Carbon::parse($endDate)->format('d M Y') .'
                        </th>';

        foreach ($comparisons as $index => $comparison) {
            $output .= '<th>'
                        . \Carbon\Carbon::parse($comparison['start'])->format('d M Y') . ' - '
                        . \Carbon\Carbon::parse($comparison['end'])->format('d M Y') .
                      '</th>';
        }

        $output .= '</tr></thead><tbody>';
        $total_operating_revenue = 0;
        $compare_operating_revenue = array_fill(0, count($comparisons), 0); // Initialize array to store comparison totals

        foreach ($operatingRevenue as $data) {
            $total_operating_revenue += ($data->default_posisi === 'Credit') ? $data->grand_total : -$data->grand_total;
            foreach ($comparisons as $index => $comparison) {
                $compare_operating_revenue[$index] += ($data->default_posisi === 'Credit') 
                    ? $data->{'compare_total_' . $index} 
                    : -$data->{'compare_total_' . $index};
            }
            $output .= ' <tr>
                            <td>' . ($data->account_name ?? '-') . '</td>';
            $output .= '<td>' . number_format(abs($data->grand_total), 2) . '</td>';

            foreach ($comparisons as $index => $comparison) {
                $output .= '<td>' . number_format(abs($data->{'compare_total_' . $index}), 2) . '</td>';
            }
            $output .= '</tr>';
        }

        $output .= ' <tr>
                            <td class="text-left"><b> TOTAL OPERATING REVENUE </b></td>';
        $output .= '<td> <b>' . number_format(abs($total_operating_revenue), 2) . '</b> </td>';

        foreach ($comparisons as $index => $comparison) {
            $output .= '<td><b>' . number_format(abs($compare_operating_revenue[$index]), 2) . '</b></td>';
        }

        $output .= '</tr>';

        $query5 = "
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
        ";

        foreach ($comparisons as $index => $comparison) {
            $compareStart = date('Y-m-d', strtotime($comparison['start']));
            $compareEnd = date('Y-m-d', strtotime($comparison['end']));

            $query5 .= ",
                IFNULL(SUM(CASE
                               WHEN ju.status = 'Approve'
                                   AND ju.tanggal >= '$compareStart'
                                   AND ju.tanggal <= '$compareEnd'
                               THEN ji.debit ELSE 0 END), 0) AS compare_debit_$index,
                IFNULL(SUM(CASE
                               WHEN ju.status = 'Approve'
                                   AND ju.tanggal >= '$compareStart'
                                   AND ju.tanggal <= '$compareEnd'
                               THEN ji.credit ELSE 0 END), 0) AS compare_credit_$index,
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
                               ELSE 0 END), 0) AS compare_total_$index
            ";
        }

        $query5 .= "
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id IN ($hpps)
            GROUP BY coa_id, account_name, default_posisi
            HAVING grand_total != 0
        ";

        foreach ($comparisons as $index => $comparison) {
            $query5 .= " OR compare_total_$index != 0";
        }

        $hargaPokokPenjualan = DB::select($query5);
    
        $total_hpp = 0;
        $compare_hpp = array_fill(0, count($comparisons), 0); // Initialize array to store comparison totals

        foreach ($hargaPokokPenjualan as $data) {
            $total_hpp += ($data->default_posisi === 'Credit') ? $data->grand_total : -$data->grand_total;
            foreach ($comparisons as $index => $comparison) {
                $compare_hpp[$index] += ($data->default_posisi === 'Credit') 
                    ? $data->{'compare_total_' . $index} 
                    : -$data->{'compare_total_' . $index};
            }
            $output .= ' <tr>
                            <td>' . ($data->account_name ?? '-') . '</td>';
            $output .= '<td>' . number_format(abs($data->grand_total), 2) . '</td>';

            foreach ($comparisons as $index => $comparison) {
                $output .= '<td>' . number_format(abs($data->{'compare_total_' . $index}), 2) . '</td>';
            }
            $output .= '</tr>';
        }

        $output .= ' <tr>
                            <td class="text-left"><b> TOTAL HPP </b></td>';
        $output .= '<td> <b>(' . number_format(abs($total_hpp), 2) . ')</b> </td>';

        foreach ($comparisons as $index => $comparison) {
            $output .= '<td><b>(' . number_format(abs($compare_hpp[$index]), 2) . ')</b></td>';
        }

        $output .= '</tr>';

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
        ";

        // Tambahkan comparisons ke query2
        foreach ($comparisons as $index => $comparison) {
            $compareStart = date('Y-m-d', strtotime($comparison['start']));
            $compareEnd = date('Y-m-d', strtotime($comparison['end']));

            $query2 .= ",
                IFNULL(SUM(CASE
                            WHEN ju.status = 'Approve'
                                AND ju.tanggal >= '$compareStart'
                                AND ju.tanggal <= '$compareEnd'
                            THEN ji.debit ELSE 0 END), 0) AS compare_debit_$index,
                IFNULL(SUM(CASE
                            WHEN ju.status = 'Approve'
                                AND ju.tanggal >= '$compareStart'
                                AND ju.tanggal <= '$compareEnd'
                            THEN ji.credit ELSE 0 END), 0) AS compare_credit_$index,
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
                            ELSE 0 END), 0) AS compare_total_$index
            ";
        }

        $query2 .= "
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id IN ($oes)
            GROUP BY coa_id, account_name, default_posisi
            HAVING grand_total != 0
        ";


        foreach ($comparisons as $index => $comparison) {
            $query2 .= " OR compare_total_$index != 0";
        }


        $operatingExpenses = DB::select($query2);


        $total_operating_expenses = 0;
        $compare_operating_expenses = array_fill(0, count($comparisons), 0);

        foreach ($operatingExpenses as $data) {
            $total_operating_expenses += ($data->default_posisi === 'Credit') ? $data->grand_total : -$data->grand_total;
            foreach ($comparisons as $index => $comparison) {
                $compare_operating_expenses[$index] += ($data->default_posisi === 'Credit') 
                    ? $data->{'compare_total_' . $index} 
                    : -$data->{'compare_total_' . $index};
            }
            $output .= ' <tr>
                            <td>' . ($data->account_name ?? '-') . '</td>';
            $output .= '<td>' . number_format(abs($data->grand_total), 2) . '</td>';

            foreach ($comparisons as $index => $comparison) {
                $output .= '<td>' . number_format(abs($data->{'compare_total_' . $index}), 2) . '</td>';
            }
            $output .= '</tr>';
        }

        $output .= ' <tr>
                        <td class="text-left"><b> TOTAL OPERATING EXPENSES </b></td>';
        $output .= '<td> <b> (' . number_format(abs($total_operating_expenses), 2) . ') </b> </td>';

        foreach ($comparisons as $index => $comparison) {
            $output .= '<td><b> (' . number_format(abs($compare_operating_expenses[$index]), 2) . ') </b></td>';
        }

        $output .= '</tr>';

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
        ";

        foreach ($comparisons as $index => $comparison) {
            $compareStart = date('Y-m-d', strtotime($comparison['start']));
            $compareEnd = date('Y-m-d', strtotime($comparison['end']));

            $query3 .= ",
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.debit ELSE 0 END), 0) AS compare_debit_$index,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.credit ELSE 0 END), 0) AS compare_credit_$index,
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
                                ELSE 0 END), 0) AS compare_total_$index
            ";
        }

        $query3 .= "
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id IN ($nors)
            GROUP BY coa_id, account_name, default_posisi
            HAVING grand_total != 0
        ";

        foreach ($comparisons as $index => $comparison) {
            $query3 .= " OR compare_total_$index != 0";
        }


        $nonBusinessRevenue = DB::select($query3);


        $total_non_business_revenue = 0;
        $compare_non_business_revenue = array_fill(0, count($comparisons), 0);

        foreach ($nonBusinessRevenue as $data) {
            $total_non_business_revenue += ($data->default_posisi === 'Credit') ? $data->grand_total : -$data->grand_total;
            foreach ($comparisons as $index => $comparison) {
                $compare_non_business_revenue[$index] += ($data->default_posisi === 'Credit') 
                    ? $data->{'compare_total_' . $index} 
                    : -$data->{'compare_total_' . $index};
            }

            $output .= ' <tr>
                            <td>' . ($data->account_name ?? '-') . '</td>
                            <td>' . number_format(abs($data->grand_total), 2) . '</td>';

            foreach ($comparisons as $index => $comparison) {
                $output .= '<td>' . number_format(abs($data->{'compare_total_' . $index}), 2) . '</td>';
            }

            $output .= '</tr>';
        }

        $output .= ' <tr>
                        <td class="text-left"><b> TOTAL NON BUSINESS REVENUE </b></td>
                        <td> <b>' . number_format(abs($total_non_business_revenue), 2) . '</b> </td>';

        foreach ($compare_non_business_revenue as $total) {
            $output .= '<td><b>' . number_format(abs($total), 2) . '</b></td>';
        }

        $output .= '</tr>';

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
        ";

        foreach ($comparisons as $index => $comparison) {
            $compareStart = date('Y-m-d', strtotime($comparison['start']));
            $compareEnd = date('Y-m-d', strtotime($comparison['end']));

            $query4 .= ",
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.debit ELSE 0 END), 0) AS compare_debit_$index,
                IFNULL(SUM(CASE
                                WHEN ju.status = 'Approve'
                                    AND ju.tanggal >= '$compareStart'
                                    AND ju.tanggal <= '$compareEnd'
                                THEN ji.credit ELSE 0 END), 0) AS compare_credit_$index,
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
                                ELSE 0 END), 0) AS compare_total_$index
            ";
        }

        $query4 .= "
            FROM tbl_coa coa
            LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
            LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
            WHERE coa.parent_id IN ($noes)
            GROUP BY coa_id, account_name, default_posisi
            HAVING grand_total != 0
        ";
        foreach ($comparisons as $index => $comparison) {
            $query4 .= " OR compare_total_$index != 0";
        }

        $nonBusinessExpenses = DB::select($query4);

        $total_non_business_expenses = 0;
        $compare_non_business_expenses = array_fill(0, count($comparisons), 0);

        foreach ($nonBusinessExpenses as $data) {
            $total_non_business_expenses += ($data->default_posisi === 'Credit') ? $data->grand_total : -$data->grand_total;
            foreach ($comparisons as $index => $comparison) {
                $compare_non_business_expenses[$index] += ($data->default_posisi === 'Credit') 
                    ? $data->{'compare_total_' . $index} 
                    : -$data->{'compare_total_' . $index};
            }

            $output .= ' <tr>
                            <td>' . ($data->account_name ?? '-') . '</td>
                            <td>' . number_format(abs($data->grand_total), 2) . '</td>';

            foreach ($comparisons as $index => $comparison) {
                $output .= '<td>' . number_format(abs($data->{'compare_total_' . $index}), 2) . '</td>';
            }

            $output .= '</tr>';
        }

        $output .= ' <tr>
                        <td class="text-left"><b> TOTAL NON BUSINESS EXPENSES </b></td>
                        <td> <b> (' . number_format(abs($total_non_business_expenses), 2) . ') </b> </td>';

        foreach ($compare_non_business_expenses as $total) {
            $output .= '<td><b> (' . number_format(abs($total), 2) . ') </b></td>';
        }

        $output .= '</tr>';

        $netProfit = 0;

        $allTransactions = collect($operatingRevenue)
                            ->merge($operatingExpenses)
                            ->merge($hargaPokokPenjualan)
                            ->merge($nonBusinessRevenue)
                            ->merge($nonBusinessExpenses);

        // Menghitung total berdasarkan default_posisi
        
        $netProfit = $allTransactions->sum(function ($item) {
            return ($item->default_posisi === 'Credit') ? $item->grand_total : -$item->grand_total;
        });
        

        $netProfit = round($netProfit, 2);

        $output .= '<tr>
                        <td ><b>NET PROFIT BEFORE TAX</b></td>
                        <td> <b>' . number_format(abs($netProfit), 2) . '</b> </td>';

        $compare_net_profit = [];
        
        foreach ($comparisons as $index => $comparison) {
            $compare_net_profit[$index] = collect($operatingRevenue)
                ->merge($operatingExpenses)
                ->merge($hargaPokokPenjualan)
                ->merge($nonBusinessRevenue)
                ->merge($nonBusinessExpenses)
                ->sum(function ($item) use ($index) {
                    return ($item->default_posisi === 'Credit') 
                        ? ($item->{'compare_total_' . $index} ?? 0) 
                        : -($item->{'compare_total_' . $index} ?? 0);
                });
        
            $output .= '<td><b>' . number_format(abs($compare_net_profit[$index]), 2) . '</b></td>';
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
