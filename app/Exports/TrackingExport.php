<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Illuminate\Contracts\View\View;

class TrackingExport implements FromView, WithEvents
{
    use RegistersEventListeners;

    protected $status;
    protected $userid;

    public function __construct($status, $userid)
    {
        $this->status = $status;
        $this->userid = $userid;
    }

    public function view(): View
    {
        $companyId = session('active_company_id');
        $user = auth()->user();

        if (!$companyId) {
            throw new \Exception("Session active_company_id tidak ditemukan.");
        }

        \Log::info("Export Excel - Company ID: " . $companyId);
        \Log::info("Export Excel - User Role: " . $user->role);
        \Log::info("Export Excel - Status Filter: " . $this->status);

        $query = DB::table('tbl_tracking')
            ->select([
                'tbl_tracking.id',
                'tbl_tracking.no_resi',
                'tbl_tracking.no_do',
                'tbl_tracking.status',
                'tbl_tracking.keterangan',
                DB::raw('(SELECT status_bayar FROM tbl_invoice WHERE tbl_invoice.id = IFNULL(tbl_resi.invoice_id, 0) LIMIT 1) AS status_bayar')
            ])
            ->leftJoin('tbl_resi', 'tbl_tracking.no_resi', '=', 'tbl_resi.no_resi')
            ->where('tbl_tracking.company_id', $companyId)
            ->orderBy('tbl_tracking.id', 'desc');

        if ($user->role === 'customer') {
            $query->addSelect([
                DB::raw("IFNULL(tbl_resi.berat, ROUND((tbl_resi.panjang * tbl_resi.lebar * tbl_resi.tinggi) / 1000000, 2)) AS berat"),
                DB::raw("IFNULL(ROUND((tbl_resi.panjang * tbl_resi.lebar * tbl_resi.tinggi) / 1000000, 2), '-') AS volume"),
                DB::raw("IF(tbl_resi.berat IS NOT NULL, CONCAT(tbl_resi.berat, ' Kg'), CONCAT(ROUND((tbl_resi.panjang * tbl_resi.lebar * tbl_resi.tinggi) / 1000000, 2), ' m³')) AS quantitas"),
                DB::raw("IFNULL(DATE_FORMAT(tbl_pengantaran_detail.tanggal_penerimaan, '%d %M %Y %H:%i:%s'), '-') AS tanggal_penerimaan")
            ])
            ->leftJoin('tbl_invoice', 'tbl_resi.invoice_id', '=', 'tbl_invoice.id')
            ->leftJoin('tbl_pembeli', 'tbl_invoice.pembeli_id', '=', 'tbl_pembeli.id')
            ->leftJoin('tbl_pengantaran_detail', 'tbl_invoice.id', '=', 'tbl_pengantaran_detail.invoice_id')
            ->where('tbl_pembeli.user_id', $user->id);
        }

        if ($this->status) {
            $query->where('tbl_tracking.status', $this->status);
        }

        $data = $query->get();

        if ($data->isEmpty()) {
            throw new \Exception("Data tracking tidak ditemukan untuk export.");
        }

        return view('exportExcel.tracking', [
            'trackingData' => $data,
            'status' =>  $this->status,
        ]);
    }

}
