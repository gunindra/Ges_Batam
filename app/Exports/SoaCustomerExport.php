<?php

namespace App\Exports;

use App\Models\Invoice;
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
    protected $paymentMethod;

    public function __construct($startDate, $endDate, $customer, $paymentMethod)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->customer = $customer;
        $this->paymentMethod = $paymentMethod;
    }

    public function view(): View
    {
        $companyId = session('active_company_id');

        $query = Invoice::where('tbl_invoice.status_bayar', 'Belum lunas')
            ->where('tbl_invoice.status_id', 6)
            ->where('tbl_invoice.company_id', $companyId)
            ->where('tbl_invoice.soa_closing', false)
            ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
            ->leftJoin('tbl_resi', 'tbl_invoice.id', '=', 'tbl_resi.invoice_id')
            ->select(
                'tbl_invoice.id',
                'tbl_invoice.tanggal_invoice',
                'tbl_invoice.no_invoice',
                'tbl_invoice.total_harga',
                'tbl_invoice.total_bayar',
                'tbl_invoice.payment_type',
                'tbl_pembeli.marking',
                DB::raw('COALESCE(MIN(tbl_resi.no_do), "-") as no_do')
            )
            ->groupBy(
                'tbl_invoice.id',
                'tbl_invoice.tanggal_invoice',
                'tbl_invoice.no_invoice',
                'tbl_invoice.total_harga',
                'tbl_invoice.total_bayar',
                'tbl_invoice.payment_type',
                'tbl_pembeli.marking'
            );

        // Filter tanggal
        if ($this->startDate) {
            $query->whereDate('tbl_invoice.tanggal_invoice', '>=', date('Y-m-d', strtotime($this->startDate)));
        }
        if ($this->endDate) {
            $query->whereDate('tbl_invoice.tanggal_invoice', '<=', date('Y-m-d', strtotime($this->endDate)));
        }

        // Filter customer
        if ($this->customer && $this->customer !== '-') {
            $query->where('tbl_pembeli.id', $this->customer);
        }

        // Filter payment method
        if ($this->paymentMethod && $this->paymentMethod[0] !== '-') {
            $query->whereIn('tbl_invoice.payment_type', $this->paymentMethod);
        }

        $invoices = $query->get();

        $customerName = '-';
        if ($this->customer && $this->customer !== '-') {
            $customerData = DB::table('tbl_pembeli')->where('id', $this->customer)->first();
            $customerName = $customerData ? $customerData->marking : 'Unknown';
        }

        $paymentMethodsString = implode(', ', $this->paymentMethod ?? []);

        return view('exportExcel.soacustomer', [
            'invoice' => $invoices,
            'startDate' => $this->startDate ?? '-',
            'endDate' => $this->endDate ?? '-',
            'customer' => $customerName,
            'paymentMethods' => $paymentMethodsString,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach (range('A', 'H') as $columnID) { // Diperlebar sampai H untuk menampung semua kolom
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
