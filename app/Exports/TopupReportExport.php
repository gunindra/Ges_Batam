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
        $isCustomerRole = auth()->user() && auth()->user()->role === 'customer';
        $userId = $isCustomerRole ? auth()->user()->id : null;

        // Ambil data Topup dengan filter tanggal & user langsung pada query
        $topup = HistoryTopup::leftJoin('tbl_pembeli', 'tbl_history_topup.customer_id', '=', 'tbl_pembeli.id')
        ->where('tbl_history_topup.status', '!=', 'canceled')
        ->where('tbl_history_topup.company_id', $companyId)
        ->whereDate('date', '>=', $this->startDate)
        ->whereDate('date', '<=', $this->endDate)
        ->select('tbl_history_topup.*', 'tbl_pembeli.marking', 'tbl_pembeli.nama_pembeli as customer_name', 'tbl_history_topup.price_per_kg');

        $payment = PaymentInvoice::join('tbl_payment_customer', 'tbl_payment_invoice.payment_id', '=', 'tbl_payment_customer.id')
            ->leftJoin('tbl_pembeli', 'tbl_payment_customer.pembeli_id', '=', 'tbl_pembeli.id')
            ->where('tbl_payment_invoice.kuota', '!=', 0)
            ->where('tbl_payment_customer.company_id', $companyId)
            ->whereDate('payment_buat', '>=', $this->startDate)
            ->whereDate('payment_buat', '<=', $this->endDate)
            ->select('tbl_payment_invoice.*', 'tbl_pembeli.marking', 'tbl_pembeli.nama_pembeli as customer_name');

        if ($isCustomerRole) {
            $topup->where('tbl_pembeli.user_id', $userId);
            $payment->where('tbl_pembeli.user_id', $userId);
        }

        if ($this->customer !== '-') {
            $topup->where('tbl_history_topup.customer_id', '=', $this->customer);
            $payment->where('tbl_payment_customer.pembeli_id', '=', $this->customer);
        }

        $topup = $topup->get()->map(function ($item) {
            $item->type = 'topup';
            return $item;
        });

        $payment = $payment->get()->map(function ($item) {
            $item->type = 'payment';
            $item->date = $item->payment_buat;
            $item->customer_id = $item->pembeli_id;
            return $item;
        });

        $combined = $topup->concat($payment)->sortBy('date');

        return view('exportExcel.topupreport', [
            'topup' => $combined,
            'customer' => $this->customer,
            'isCustomerRole' =>   $isCustomerRole ,
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
