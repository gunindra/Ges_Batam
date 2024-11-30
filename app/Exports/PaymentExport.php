<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class PaymentExport implements FromView
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
            $query = DB::table('tbl_payment_customer as a')
            ->join('tbl_payment_invoice as f', 'f.payment_id', '=', 'a.id')
            ->join('tbl_invoice as b', 'f.invoice_id', '=', 'b.id')
            ->join('tbl_coa as c', 'a.payment_method_id', '=', 'c.id')
            ->join('tbl_pembeli as d', 'b.pembeli_id', '=', 'd.id')
            ->select(
                'a.id',
                'a.kode_pembayaran',
                'd.marking',
                DB::raw("DATE_FORMAT(a.payment_buat, '%d %M %Y %H:%i:%s') as tanggal_buat"),
                'c.name as payment_method',
                DB::raw('SUM(f.amount) as total_amount'),
                'a.discount'
            )
            ->groupBy(
                'a.id',
                'a.kode_pembayaran',
                'd.marking',
                DB::raw("DATE_FORMAT(a.payment_buat, '%d %M %Y %H:%i:%s')"),
                'c.name',
                'a.discount'
            );

        if (!empty($this->status)) {
            $query->where('c.name', $this->status); // Filter berdasarkan nama metode pembayaran
        }

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $startDate = date('Y-m-d', strtotime($this->startDate));
            $endDate = date('Y-m-d', strtotime($this->endDate));
            $query->whereBetween('a.payment_date', [$startDate, $endDate]);
        }

        $query->orderBy('a.id', 'desc');

        $payments = $query->get();

        return view('exportExcel.payment', [
            'payments' => $payments,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'status' => $this->status
        ]);
    }
}
