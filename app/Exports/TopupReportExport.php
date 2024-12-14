<?php

namespace App\Exports;

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
        $topup = HistoryTopup::where('status', '!=', 'cancel');

        if ($this->customer !== '-') {
            $topup->where('customer_id', '=', $this->customer);
        }

        $topup->whereDate('date', '>=', $this->startDate);
        $topup->whereDate('date', '<=', $this->endDate);

        $topup = $topup->get();
        $customerName = '-';
        if ($this->customer !== '-') {
            $customerData = DB::table('tbl_pembeli')->where('id', $this->customer)->first();
            $customerName = $customerData ? $customerData->nama_pembeli : 'Unknown';
        }

        return view('exportExcel.topupreport', [
            'topup' => $topup,
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
