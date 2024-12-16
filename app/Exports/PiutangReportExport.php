<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;

class PiutangReportExport implements FromView
{
    protected $customer;
    protected $startDate;
    protected $endDate;

    public function __construct($customer, $startDate, $endDate)
    {
        $this->customer = $customer;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $query = DB::table('tbl_invoice as invoice')->select(
            'invoice.id',
            'invoice.no_invoice',
            DB::raw("CONCAT(DATE_FORMAT(invoice.tanggal_buat, '%d %M %Y'),CASE WHEN DATEDIFF(CURDATE(), invoice.tanggal_buat) >= 30 THEN CONCAT(' (', FLOOR(DATEDIFF(CURDATE(), invoice.tanggal_buat) / 30), ' bulan)')ELSE ''END) as tanggal_buat"),
            'pembeli.nama_pembeli',                  
        )
        ->where('invoice.status_bayar', '=', 'Belum Lunas')
        ->join('tbl_pembeli as pembeli', 'invoice.pembeli_id', '=', 'pembeli.id');

        $query->orderBy('invoice.tanggal_invoice', 'desc');

        if ($this->customer !== '-') {
            $query->where('pembeli.id', '=', $this->customer);
        }

        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('d M Y', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('d M Y', $this->endDate)->endOfDay();
            $query->whereBetween('invoice.tanggal_buat', [$startDate, $endDate]);
        }

        $piutang = $query->get();
        $customerName = '-';
        if ($this->customer !== '-') {
            $customerData = DB::table('tbl_pembeli')->where('id', $this->customer)->first();
            $customerName = $customerData ? $customerData->nama_pembeli : 'Unknown';
        }
        
        return view('exportExcel.piutangreport', [
            'piutang' => $piutang,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'customer' => $customerName
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach (range('A', 'C') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}

