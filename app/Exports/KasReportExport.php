<?php

namespace App\Exports;

use App\Models\Payment;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

class KasReportExport implements FromView, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $customer;
    protected $account;

    public function __construct($startDate, $endDate,$customer,$account)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->customer = $customer;
        $this->account = $account;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        // Query dasar untuk mengambil data asset
        $payment = Payment::join('tbl_payment_invoice', 'tbl_payment_customer.id', '=', 'tbl_payment_invoice.payment_id')
            ->join('tbl_invoice', 'tbl_payment_invoice.invoice_id', '=', 'tbl_invoice.id')
            ->join('tbl_pembeli', 'tbl_payment_customer.pembeli_id', '=', 'tbl_pembeli.id')
            ->join('tbl_coa', 'tbl_payment_customer.payment_method_id', '=', 'tbl_coa.id');
            if ($this->startDate && $this->endDate) {
                $payment->whereDate('tbl_payment_customer.payment_date', '>=', $this->startDate)
                        ->whereDate('tbl_payment_customer.payment_date', '<=', $this->endDate);
            }

        if ($this->customer) {
            $payment->where('tbl_payment_customer.id', '=', $this->customer);
        }

        if ($this->account) {
            $payment->where('tbl_payment_customer.id', '=', $this->account);
        }

            $payment->selectRaw("
            tbl_payment_customer.kode_pembayaran as kode_pembayaran,
            tbl_payment_customer.payment_buat as created_date,
            tbl_payment_customer.payment_date as payment_date,
            tbl_payment_customer.discount as discount,
            tbl_pembeli.nama_pembeli as customer_name,
            tbl_coa.name as payment_method,
            GROUP_CONCAT(CONCAT(tbl_invoice.no_invoice, ' (', tbl_payment_invoice.amount, ')') SEPARATOR ', ') as no_invoice_with_amount,
            SUM(tbl_payment_invoice.amount) as total_amount
            ")
            ->groupBy(
                'tbl_payment_customer.id',
                'tbl_payment_customer.payment_buat',
                'tbl_payment_customer.payment_date',
                'tbl_payment_customer.kode_pembayaran',
                'tbl_payment_customer.discount',
                'tbl_coa.name',
                'tbl_pembeli.nama_pembeli'
            );

        // Get the results
        $payments = $payment->get();

        return view('exportExcel.penerimaankas', [
            'payments' => $payments,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'customer' => $this->customer,
            'account' => $this->account
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach (range('A', 'G') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}

