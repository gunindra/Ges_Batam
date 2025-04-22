<?php

namespace App\Exports;

use App\Models\Customer;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

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
        $isCustomerRole = auth()->user()->role === 'customer';
        $userId = auth()->user()->id;

        $query = "
        WITH combined_data AS (
            SELECT
                date,
                marking,
                CASE WHEN type = 'IN' THEN points ELSE 0 END AS in_points,
                CASE WHEN type = 'OUT' THEN points ELSE 0 END AS out_points,
                points * price_per_kg AS value,
                type AS status
            FROM (
                -- Data OUT (penggunaan poin)
                SELECT
                    MIN(tup.usage_date) AS date,
                    MIN(tup.created_at) AS created_at,
                    tp.marking,
                    SUM(tup.used_points) AS points,
                    MAX(tup.price_per_kg) AS price_per_kg,
                    'OUT' AS type
                FROM tbl_usage_points tup
                JOIN tbl_pembeli tp ON tup.customer_id = tp.id
                WHERE tup.usage_date BETWEEN ? AND ?
                AND tp.company_id = ?
                " . ($this->customer && $this->customer !== '-' ? "AND tp.id = ?" : "") . "
                " . ($isCustomerRole ? "AND tp.user_id = ?" : "") . "
                GROUP BY tup.payment_id, tp.marking

                UNION ALL

                -- Data IN (topup poin)
                SELECT
                    tht.date,
                    NULL AS created_at,
                    tp.marking,
                    tht.remaining_points AS points,
                    tht.price_per_kg,
                    'IN' AS type
                FROM tbl_history_topup tht
                JOIN tbl_pembeli tp ON tht.customer_id = tp.id
                WHERE tht.status != 'canceled'
                AND tht.date BETWEEN ? AND ?
                AND tp.company_id = ?
                " . ($this->customer && $this->customer !== '-' ? "AND tp.id = ?" : "") . "
                " . ($isCustomerRole ? "AND tp.user_id = ?" : "") . "
            ) AS raw_data
        ),
        calculated_data AS (
            SELECT
                date,
                marking,
                in_points,
                out_points,
                value,
                status,
                SUM(in_points - out_points) OVER (PARTITION BY marking ORDER BY date, in_points DESC) AS saldo
            FROM combined_data
        )
        SELECT
            date,
            marking,
            in_points,
            out_points,
            saldo,
            value,
            status
        FROM calculated_data
        ORDER BY marking, date;
        ";

        // Menyiapkan parameter query
        $params = [$this->startDate, $this->endDate, $companyId];

        // Untuk bagian OUT
        if ($this->customer && $this->customer !== '-') {
            $params[] = $this->customer;
        }
        if ($isCustomerRole) {
            $params[] = $userId;
        }

        // Untuk bagian IN
        $params = array_merge($params, [$this->startDate, $this->endDate, $companyId]);
        if ($this->customer && $this->customer !== '-') {
            $params[] = $this->customer;
        }
        if ($isCustomerRole) {
            $params[] = $userId;
        }

        // Eksekusi query
        $data = DB::select($query, $params);

        $marking = Customer::where('id', $this->customer)->value('marking');

        // Debug data (bisa dihapus setelah testing)
        logger()->info('TopupReportExport Data', [
            'data_count' => count($data),
            'params' => $params,
            'query' => $query
        ]);

        return view('exportExcel.topupreport', [
            'topup' => $data,
            'marking' => $marking,
            'isCustomerRole' => $isCustomerRole,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach (range('A', 'G') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }

                // Style untuk header
                $event->sheet->getDelegate()->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFD9D9D9'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }
}
