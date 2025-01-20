<?php

namespace App\Exports;

use App\Models\PaymentInvoice;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use App\Models\HistoryTopup;

class TopupReportExport implements FromView, WithEvents
{
    protected $customer;
    protected $startDate;
    protected $endDate;

    public function __construct($customer, $startDate, $endDate)
    {
        $this->customer = $customer;
        $this->startDate = $startDate ?: now()->startOfMonth()->format('Y-m-d');
        $this->endDate = $endDate ?: now()->endOfMonth()->format('Y-m-d');
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $companyId = session('active_company_id');

        // Ambil data Topup dengan filter tanggal langsung pada query
        $topup = HistoryTopup::where('status', '!=', 'cancel')
        ->where('tbl_history_topup.company_id', $companyId)
        ->whereDate('date', '>=', $this->startDate)
        ->whereDate('date', '<=', $this->endDate);

        if ($this->customer !== '-') {
        $topup->where('customer_id', '=', $this->customer);
        }

        $topup = $topup->get();

        // Ambil data PaymentInvoice dengan filter tanggal langsung pada query
        $payment = PaymentInvoice::join('tbl_payment_customer', 'tbl_payment_invoice.payment_id', '=', 'tbl_payment_customer.id')
        ->where('tbl_payment_invoice.kuota', '!=', 0)
        ->where('tbl_payment_customer.company_id', $companyId)
        ->whereDate('payment_date', '>=', $this->startDate)
        ->whereDate('payment_date', '<=', $this->endDate);

        if ($this->customer !== '-') {
        $payment->where('tbl_payment_customer.id', '=', $this->customer);
        }

        $payment = $payment->get();

        // Dapatkan nama customer jika diperlukan
        $customerName = '-';
        if ($this->customer !== '-') {
            $customerData = DB::table('tbl_pembeli')->where('id', $this->customer)->first();
            $customerName = $customerData ? $customerData->nama_pembeli : 'Unknown';
        }

        return view('exportExcel.topupreport', [
            'topup' => $topup,
            'payment' => $payment,
            'customer' => $customerName,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach (range('A', 'H') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
