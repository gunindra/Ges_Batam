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

    public function __construct($startDate, $endDate, $customer)
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
        $companyId = session('active_company_id');
        $invoice = Invoice::where('tbl_invoice.status_bayar', '=', 'Belum lunas')
        ->where('tbl_invoice.pembeli_id', '=',  $this->customer)
        ->where('tbl_invoice.company_id', $companyId)
        ->where('tbl_invoice.soa_closing', false)
        ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id') // Join ke tbl_pembeli
        ->leftJoin('tbl_resi', 'tbl_invoice.id', '=', 'tbl_resi.invoice_id')
        ->select(
            'tbl_invoice.*',
            'tbl_pembeli.marking',
            'tbl_resi.no_do'
        );

        if ($this->startDate) {
        $invoice->whereDate('tbl_invoice.tanggal_invoice', '>=', date('Y-m-d', strtotime($this->startDate)));
        }
        if ($this->customer) {
        $invoice->whereDate('tbl_invoice.tanggal_invoice', '<=', date('Y-m-d', strtotime($this->endDate)));
        }

        $invoice = $invoice->get();

        $customerName = '-';
        if ($this->customer !== '-') {
            $customerData = DB::table('tbl_pembeli')->where('id', $this->customer)->first();
            $customerName = $customerData ? $customerData->nama_pembeli : 'Unknown';
        }

        return view('exportExcel.soacustomer', [
            'invoice' => $invoice,
            'startDate' => $this->startDate ?? '-',
            'endDate' => $this->endDate ?? '-',
            'customer' => $customerName,
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

