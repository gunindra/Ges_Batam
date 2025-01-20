<?php

namespace App\Exports;

use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;
use App\Models\Asset;

class AssetReportExport implements FromView, WithEvents
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $companyId = session('active_company_id');
        // Query dasar untuk mengambil data asset
        $query = Asset::select(
                'tbl_assets.id as asset_id',
                'tbl_assets.acquisition_price',
                'tbl_assets.estimated_age',
                'tbl_assets.asset_name',
                'tbl_assets.acquisition_date',
                DB::raw('SUM(tbl_jurnal.totalcredit) as credit'),
                DB::raw('SUM(tbl_jurnal.begining_value) as begining_value'),
                DB::raw('SUM(tbl_jurnal.ending_value) as ending_value')
            )
            ->join('tbl_jurnal', 'tbl_assets.id', '=', 'tbl_jurnal.asset_id')
            ->where('tbl_assets.company_id', $companyId)
        ->groupBy(
            'tbl_assets.id',
            'tbl_assets.acquisition_price',
            'tbl_assets.estimated_age',
            'tbl_assets.asset_name',
            'tbl_assets.acquisition_date'
        );

        // Jika rentang tanggal tersedia, filter berdasarkan tanggal
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tbl_jurnal.tanggal', [$this->startDate, $this->endDate]);
        }

        // Ambil hasil query
        $asset = $query->get()

        ->map(function ($asset) {
            // Adjusting the balance calculation
            // Use the correct beginning balance from the subquery
            $asset->beginning_balance = $asset->acquisition_price - $asset->total_credit_before;
            $asset->ending_balance = $asset->beginning_balance - $asset->credit;
            return $asset;
        });

        // Return view dengan data asset dan tanggal
        return view('exportExcel.assetreport', [
            'asset' => $asset,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach (range('A', 'E') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}

