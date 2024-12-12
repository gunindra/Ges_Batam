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

    public function __construct($customer,$startDate, $endDate)
    {
        $this->customer = $customer;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $topup = HistoryTopup::where('status', '!=', 'cancel');
        if ($this->customer){
            $topup->where('customer_id', '=', $this->customer);
        }

        // if ($this->namacustomer){
        //     $topup->where('nama_pembeli', '=', $this->namacustomer);
        // }

        if ( $this->startDate){
            $startDate = date('Y-m-d', strtotime( $this->startDate));
            $topup->whereDate('date', '>=', $startDate);    
        }
        if ($this->endDate){
            $endDate = date('Y-m-d', strtotime($this->endDate));
            $topup->whereDate('date', '<=', $endDate);
        }
     
        $topup = $topup->get();

        return view('exportExcel.topupreport', [
            'topup' => $topup,
            'customer' => $this->customer,
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

