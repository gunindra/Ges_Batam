<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

class SalesExport implements FromView, WithEvents
{
    protected $NoDo;
    protected $customer;

    public function __construct($NoDo, $customer)
    {
        $this->NoDo = $NoDo;
        $this->customer = $customer;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $companyId = session('active_company_id');
        $NoDo = $this->NoDo;
        $Customer = $this->customer;

        $query = DB::table('tbl_invoice')
            ->select(
                'tbl_invoice.no_invoice',
                DB::raw("DATE_FORMAT(tbl_invoice.tanggal_buat, '%d %M %Y') AS tanggal_buat"),
                DB::raw("MIN(tbl_resi.no_do) AS no_do"),
                'tbl_resi.no_resi',
                'tbl_pembeli.nama_pembeli AS customer',
                'tbl_invoice.metode_pengiriman',
                'tbl_status.status_name AS status_transaksi',
                'tbl_invoice.total_harga',
                'tbl_pembeli.marking',
                DB::raw("GROUP_CONCAT(tbl_resi.harga SEPARATOR '; ') AS harga_resi"),
                DB::raw("IFNULL(
                    IF(MIN(tbl_resi.berat) IS NOT NULL,
                        CONCAT(MIN(tbl_resi.berat)),
                        CONCAT(MIN(tbl_resi.panjang) * MIN(tbl_resi.lebar) * MIN(tbl_resi.tinggi) / 1000000)
                    ), '') AS berat_volume")
            )
            ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
            ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
            ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
            ->where('tbl_invoice.company_id', $companyId)
            ->whereIn('tbl_invoice.metode_pengiriman', ['Delivery', 'Pickup'])
            ->groupBy(
                'tbl_invoice.id',
                'tbl_invoice.no_invoice',
                'tbl_invoice.tanggal_buat',
                'tbl_pembeli.nama_pembeli',
                'tbl_invoice.metode_pengiriman',
                'tbl_status.status_name',
                'tbl_invoice.total_harga',
                'tbl_pembeli.marking',
                'tbl_resi.no_resi'
            );


        if ($this->NoDo) {
            $query->where('tbl_resi.no_do', 'LIKE', '%' . $this->NoDo . '%');
        }

        if ($this->customer) {
            $query->where('tbl_pembeli.nama_pembeli', 'LIKE', '%' . $this->customer . '%');
        }
        $Sales = $query->get();

        return view('exportExcel.sales', [
            'Sales' => $Sales,
            'NoDo' => $this->NoDo,
            'customer' => $this->customer
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

