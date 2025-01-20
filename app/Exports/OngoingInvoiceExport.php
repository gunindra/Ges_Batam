<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use App\Models\User;

class OngoingInvoiceExport implements FromView, WithEvents
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
        $query = DB::table('tbl_pengantaran')
            ->select(
                'tbl_invoice.no_invoice',
                'tbl_resi.no_do',
                'tbl_supir.nama_supir',
                DB::raw("DATE_FORMAT(tbl_pengantaran.tanggal_pengantaran, '%d %M %Y') AS tanggal_pengantaran"),
                DB::raw("DATE_FORMAT(tbl_invoice.tanggal_buat, '%d %M %Y') AS tanggal_buat"),
                'tbl_invoice.alamat',
                'tbl_pembeli.nama_pembeli AS nama_pembeli',
                'tbl_status.status_name AS status_transaksi'
            )
            ->where('tbl_pengantaran.company_id', $companyId)
            ->leftJoin('tbl_supir', 'tbl_pengantaran.supir_id', '=', 'tbl_supir.id')
            ->join('tbl_pengantaran_detail', 'tbl_pengantaran.id', '=', 'tbl_pengantaran_detail.pengantaran_id')
            ->join('tbl_invoice', 'tbl_pengantaran_detail.invoice_id', '=', 'tbl_invoice.id')
            ->join('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
            ->join('tbl_status', 'tbl_invoice.status_id', '=', 'tbl_status.id')
            ->join('tbl_resi', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
            ->whereIn('tbl_status.id', [1, 4]);

        // Filter berdasarkan no_do dan nama_pembeli
        if ($this->NoDo) {
            $query->where('tbl_resi.no_do', 'LIKE', '%' . $this->NoDo . '%');
        }

        if ($this->customer) {
            $query->where('tbl_pembeli.nama_pembeli', 'LIKE', '%' . $this->customer . '%');
        }

        $query->orderBy('tbl_pengantaran.id', 'desc');

        $OngoingInvoice = $query->get();

        return view('exportExcel.ongoinginvoice', [
            'OngoingInvoice' => $OngoingInvoice,
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

