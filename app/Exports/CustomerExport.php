<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class CustomerExport implements FromView
{
    protected $companyId;
    protected $txSearch;
    protected $status;

    public function __construct($companyId, $txSearch, $status)
    {
        $this->companyId = $companyId;
        $this->txSearch = $txSearch;
        $this->status = $status;
    }

    public function view(): View
    {
        $customers = DB::table('tbl_pembeli')
            ->select(
                'tbl_pembeli.id',
                'tbl_pembeli.marking',
                'tbl_pembeli.nama_pembeli',
                DB::raw('GROUP_CONCAT(tbl_alamat.alamat SEPARATOR "; ") AS alamat'),
                DB::raw('COUNT(tbl_alamat.alamat) AS alamat_count'),
                'tbl_pembeli.no_wa',
                'tbl_pembeli.sisa_poin',
                'tbl_pembeli.metode_pengiriman',
                DB::raw("DATE_FORMAT(tbl_pembeli.transaksi_terakhir, '%d %M %Y') AS tanggal_bayar"),
                'tbl_pembeli.status',
                'tbl_pembeli.category_id',
                'tbl_category.category_name',
                'tbl_users.email'
            )
            ->leftJoin('tbl_alamat', 'tbl_alamat.pembeli_id', '=', 'tbl_pembeli.id')
            ->leftJoin('tbl_category', 'tbl_pembeli.category_id', '=', 'tbl_category.id')
            ->leftJoin('tbl_users', 'tbl_users.id', '=', 'tbl_pembeli.user_id')
            ->whereNull('tbl_pembeli.deleted_at')
            ->where('tbl_pembeli.company_id', $this->companyId)
            ->when($this->txSearch, function ($q) {
                $q->where(function ($query) {
                    $query->where(DB::raw('UPPER(tbl_pembeli.nama_pembeli)'), 'LIKE', '%' . strtoupper($this->txSearch) . '%')
                        ->orWhere(DB::raw('UPPER(tbl_pembeli.marking)'), 'LIKE', '%' . strtoupper($this->txSearch) . '%')
                        ->orWhere(DB::raw('UPPER(tbl_alamat.alamat)'), 'LIKE', '%' . strtoupper($this->txSearch) . '%');
                });
            })
            ->when($this->status, function ($q) {
                if ($this->status === 'Active') {
                    $q->where('tbl_pembeli.status', 1);
                } elseif ($this->status === 'Non Active') {
                    $q->where('tbl_pembeli.status', 0);
                }
            })
            ->groupBy(
                'tbl_pembeli.id',
                'tbl_pembeli.marking',
                'tbl_pembeli.nama_pembeli',
                'tbl_pembeli.no_wa',
                'tbl_pembeli.sisa_poin',
                'tbl_pembeli.metode_pengiriman',
                'tbl_pembeli.transaksi_terakhir',
                'tbl_pembeli.status',
                'tbl_pembeli.category_id',
                'tbl_category.category_name',
                'tbl_users.email'
            )
            ->orderBy('tbl_pembeli.status', 'DESC')
            ->orderBy('tbl_pembeli.transaksi_terakhir', 'DESC')
            ->get();

        return view('exportExcel.customerExcel', [
            'customers' => $customers,
            'status' => $this->status,
        ]);
    }
}


