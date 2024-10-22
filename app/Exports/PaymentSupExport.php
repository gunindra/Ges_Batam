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
            ->join('tbl_sup_invoice as b', 'a.invoice_id', '=', 'b.id')
            ->join('tbl_coa as c', 'a.payment_method_id', '=', 'c.id')
            ->select([
                'a.kode_pembayaran',
                'b.invoice_no',
                DB::raw("DATE_FORMAT(a.payment_date, '%d %M %Y') as tanggal_bayar"),
                'a.amount',
                'c.name as payment_method', // Menggunakan 'name' dari tbl_coa
                'b.status_bayar',
                'a.id'
            ]);

        // Filter berdasarkan metode pembayaran (status)
        if (!empty($this->status)) {
            $query->where('c.name', $this->status); // Filter berdasarkan nama metode pembayaran
        }

        // Filter berdasarkan rentang tanggal pembayaran
        if (!empty($this->startDate) && !empty($this->endDate)) {
            $startDate = date('Y-m-d', strtotime($this->startDate));
            $endDate = date('Y-m-d', strtotime($this->endDate));
            $query->whereBetween('a.payment_date', [$startDate, $endDate]);
        }

        // Urutkan berdasarkan ID terbaru
        $query->orderBy('a.id', 'desc');

        // Ambil data hasil query
        $payments = $query->get();

        // Return view untuk di-export ke Excel
        return view('exportExcel.paymentSup', [
            'payments' => $payments,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'status' => $this->status
        ]);
    }
}

