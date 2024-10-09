<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitLossController extends Controller
{
    public function index() {

        $operatingRevenue = DB::select("SELECT coa.name AS account_name,
                                        coa.id AS coa_id
                                FROM tbl_coa coa
                                LEFT JOIN tbl_jurnal_items ji ON ji.code_account = coa.id
                                LEFT JOIN tbl_jurnal ju ON ju.id = ji.jurnal_id
                                GROUP BY coa_id,
                                account_name");
                                
                                // IFNULL(SUM(CASE WHEN journal.tanggal >= \'' . '2024-10-09' . '\' 
                                //             AND ju.tanggal <= \'' . '2024-10-09' . '\' 
                                //             AND ju.status = 'Approve'  
                                //         THEN ji.total_debit ELSE 0 END), 0) as total_debit,
        return view('report.ProfitLoss.indexprofitloss');
    }
}