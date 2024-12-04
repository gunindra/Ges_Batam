<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class PaymentSupExport implements FromView
{
    protected $status;
    protected $startDate;
    protected $endDate;

    public function __construct($status, $startDate, $endDate)
    {
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        // Query untuk export data pembayaran supplier
        $query = DB::table('tbl_payment_sup as a')
        ->join('tbl_payment_invoice_sup as d', 'a.id', '=', 'd.payment_id')
        ->join('tbl_sup_invoice as b', 'd.invoice_id', '=', 'b.id')
        ->join('tbl_coa as c', 'a.payment_method_id', '=', 'c.id')
        ->select([
            'a.id',
                'a.kode_pembayaran',
                'a.kode_pembayaran',
                'b.invoice_no',
            'a.kode_pembayaran',
                'b.invoice_no',
            DB::raw("DATE_FORMAT(a.payment_date, '%d %M %Y') as tanggal_bayar"),
            'c.name as payment_method',
            DB::raw("SUM(d.amount) as total_amount"), 
            'd.amount', 
            'b.invoice_no',
        ])
        ->groupBy('a.id', 'a.kode_pembayaran', 'a.payment_date', 'c.name', 'd.amount', 'b.invoice_no'); 
    


        if (!empty($this->status)) {
            $query->where('c.name', $this->status);
        }


        if (!empty($this->startDate) && !empty($this->endDate)) {
            $startDate = date('Y-m-d', strtotime($this->startDate));
            $endDate = date('Y-m-d', strtotime($this->endDate));
            $query->whereBetween('a.payment_date', [$startDate, $endDate]);
        }

        $query->orderBy('a.id', 'desc');

        $payments = $query->get();

        return view('exportExcel.paymentSup', [
            'payments' => $payments,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'status' => $this->status
        ]);
    }
}

