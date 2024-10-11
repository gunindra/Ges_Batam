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
            ->join('tbl_invoice as b', 'a.invoice_id', '=', 'b.id')
            ->join('tbl_coa as c', 'a.payment_method_id', '=', 'c.id')
            ->select([
                'a.kode_pembayaran',
                'b.no_invoice',
                DB::raw("DATE_FORMAT(a.payment_date, '%d %M %Y') as tanggal_bayar"),
                'a.amount',
                'c.name as payment_method', // Menggunakan 'name' dari tbl_coa
                'b.status_bayar',
                'a.id'
            ]);

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
