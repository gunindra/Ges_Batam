<?php

namespace App\Exports;

use App\Models\Invoice;
use App\Models\Payment;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

class SoaCustomerExport implements FromView, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $customer;

    public function __construct($startDate, $endDate,$customer)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->customer = $customer;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $invoice = Invoice::where('status_bayar', '=', 'Belum Lunas')
                    ->where('pembeli_id', '=', $this->customer);


        if ($this->startDate){
            $invoice->whereDate('tanggal_invoice', '>=', date('Y-m-d', strtotime($this->startDate)));
        }
        if ($this->endDate){
            $invoice->whereDate('tanggal_invoice', '<=', date('Y-m-d', strtotime($this->endDate)));
        }

        $invoice = $invoice->get();

        return view('exportExcel.soacustomer', [
            'invoice' => $invoice,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'customer' => $this->customer,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach (range('A', 'E') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}

