<?php

namespace App\Exports;

use DB;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

class SalesExport implements FromView, WithEvents
{
    protected $NoDo;
    protected $txSearch;
    protected $customer;
    protected $startDate;
    protected $endDate;

    public function __construct($NoDo, $txSearch, $customer, $startDate, $endDate)
    {
        $this->NoDo = $NoDo;
        $this->txSearch = $txSearch;
        $this->customer = $customer;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $companyId = session('active_company_id');
        $NoDo = $this->NoDo;
        $Customer = $this->customer;
        $txSearch = $this->txSearch;

        $query = DB::table('tbl_invoice')
            ->select(
                'tbl_invoice.no_invoice',
                DB::raw("DATE_FORMAT(tbl_invoice.tanggal_buat, '%d %M %Y') AS tanggal_buat"),
                'tbl_resi.no_do',
                'tbl_resi.no_resi',
                'tbl_pembeli.nama_pembeli AS customer',
                'tbl_invoice.metode_pengiriman',
                'tbl_status.status_name AS status_transaksi',
                DB::raw("CEIL(tbl_resi.harga / 1000) * 1000 AS total_harga"),
                'tbl_pembeli.marking',
                DB::raw("IFNULL(
                    IF(tbl_resi.berat IS NOT NULL,
                        CONCAT(tbl_resi.berat),
                        CONCAT(tbl_resi.panjang * tbl_resi.lebar * tbl_resi.tinggi / 1000000)
                    ), '') AS berat_volume")
            )
            ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
            ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
            ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
            ->where('tbl_invoice.company_id', $companyId)
            ->whereIn('tbl_invoice.metode_pengiriman', ['Delivery', 'Pickup'])
            ->when($Customer, fn($q) => $q->where('tbl_pembeli.marking', 'LIKE', '%' . $Customer . '%'))
            ->when($NoDo, fn($q) => $q->where('tbl_resi.no_do', 'LIKE', '%' . $NoDo . '%'))
            ->when($txSearch, fn($q) => $q->where(function($query) use ($txSearch) {
                $query->where('tbl_resi.no_resi', 'LIKE', '%' . $txSearch . '%')
                      ->orWhere('tbl_invoice.no_invoice', 'LIKE', '%' . $txSearch . '%');
            }))
            ->groupBy(
                'tbl_invoice.no_invoice',
                'tbl_invoice.tanggal_buat',
                'tbl_resi.no_do',
                'tbl_resi.no_resi',
                'tbl_pembeli.nama_pembeli',
                'tbl_invoice.metode_pengiriman',
                'tbl_status.status_name',
                'tbl_pembeli.marking',
                'tbl_resi.harga',
                'tbl_resi.berat',
                'tbl_resi.panjang',
                'tbl_resi.lebar',
                'tbl_resi.tinggi'
            );

        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::createFromFormat('d M Y', $this->startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('d M Y', $this->endDate)->endOfDay();
            $query->whereBetween('tbl_invoice.tanggal_buat', [$startDate, $endDate]);
        }

        $Sales = $query->get();

        $journalQuery = DB::table('tbl_jurnal_items AS ji')
                            ->select(
                                'ji.id AS items_id',
                                'ji.jurnal_id AS jurnal_id',
                                'ji.code_account AS account_id',
                                'ji.debit AS debit',
                                'ji.credit AS credit',
                                'ji.description AS items_description',
                                'ji.memo AS memo',
                                'ju.tanggal AS tanggal',
                                'ju.tanggal_payment AS tanggal_payment',
                                'ju.no_journal AS no_journal',
                                'pem_inv.marking AS pembeli_invoice',
                                'pem_pay.marking AS pembeli_payment'
                            )
                            ->leftJoin('tbl_jurnal AS ju', 'ju.id', '=', 'ji.jurnal_id')
                            ->leftJoin('tbl_invoice AS inv', 'ju.invoice_id', '=', 'inv.id')
                            ->leftJoin('tbl_payment_customer AS pc', 'ju.payment_id', '=', 'pc.id')
                            ->leftJoin('tbl_pembeli AS pem_inv', 'inv.pembeli_id', '=', 'pem_inv.id')
                            ->leftJoin('tbl_pembeli AS pem_pay', 'pc.pembeli_id', '=', 'pem_pay.id')
                            ->where('ji.code_account', 84)
                            ->orderBy('ju.tanggal', 'ASC');

        if ($this->startDate && $this->endDate) {
            $startDateCarbon = Carbon::createFromFormat('d M Y', $this->startDate)->startOfDay();
            $endDateCarbon = Carbon::createFromFormat('d M Y', $this->endDate)->endOfDay();

            $journalQuery->whereBetween('ju.tanggal', [$startDateCarbon, $endDateCarbon]);

            $startDate = $startDateCarbon->format('d F Y');
            $endDate = $endDateCarbon->format('d F Y');
        } else {
            $startDate = '-';
            $endDate = '-';
        }

        $journalResults = $journalQuery->get();

        $journalTotal = $journalResults->sum('credit') - $journalResults->sum('debit');

        return view('exportExcel.sales', [
            'Sales' => $Sales,
            'NoDo' => $this->NoDo,
            'customer' => $this->customer,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'journalTotal' => $journalTotal,
            'txSearch' => $txSearch !== null ? $txSearch : null
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

