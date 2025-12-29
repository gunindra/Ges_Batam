<?php

namespace App\Exports;

use App\Models\Customer;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class TopupReportExport implements FromView, WithEvents
{
    protected $customerId;
    protected $startDate;
    protected $endDate;

    public function __construct($customerId, $startDate, $endDate)
    {
        $this->customerId = $customerId;

        $this->startDate = $startDate
            ? Carbon::parse($startDate)->format('Y-m-d')
            : '2025-01-01';

        $this->endDate = $endDate
            ? Carbon::parse($endDate)->format('Y-m-d')
            : now()->endOfMonth()->format('Y-m-d');
    }

    public function view(): View
    {
        $companyId = session('active_company_id');
        $isCustomerRole = auth()->user() && auth()->user()->role === 'customer';
        $userId = auth()->id();

        /**
         * ===========================
         * SQL (SAMA PERSIS DENGAN PDF)
         * ===========================
         */
        $query = "
            WITH combined_data AS (
                SELECT
                    date, created_at, marking,
                    CASE WHEN type IN ('IN','IN (Retur)') THEN points ELSE 0 END AS in_points,
                    CASE WHEN type IN ('OUT','OUT (expired)')
                        THEN CASE WHEN type = 'OUT (expired)' THEN expired_amount ELSE points END
                        ELSE 0 END AS out_points,
                    CASE
                        WHEN type = 'OUT (expired)' THEN expired_amount * price_per_kg
                        ELSE points * price_per_kg
                    END AS value,
                    price_per_kg,
                    type AS status,
                    no_invoice
                FROM (
                    -- OUT
                    SELECT
                        MIN(tup.usage_date) AS date,
                        MIN(tup.created_at) AS created_at,
                        tp.marking,
                        SUM(tup.used_points) AS points,
                        MAX(tup.price_per_kg) AS price_per_kg,
                        NULL AS expired_amount,
                        'OUT' AS type,
                        (
                            SELECT GROUP_CONCAT(ti.no_invoice SEPARATOR ', ')
                            FROM tbl_payment_invoice tpi
                            JOIN tbl_invoice ti ON tpi.invoice_id = ti.id
                            WHERE tup.payment_id = tpi.payment_id
                        ) AS no_invoice
                    FROM tbl_usage_points tup
                    JOIN tbl_pembeli tp ON tup.customer_id = tp.id
                    WHERE tup.usage_date BETWEEN ? AND ?
                      AND tp.company_id = ?
                      " . ($this->customerId ? "AND tp.id = ?" : "") . "
                      " . ($isCustomerRole ? "AND tp.user_id = ?" : "") . "
                    GROUP BY tup.payment_id, tp.marking

                    UNION ALL

                    -- IN
                    SELECT
                        tht.date,
                        tht.created_at,
                        tp.marking,
                        tht.remaining_points AS points,
                        tht.price_per_kg,
                        NULL AS expired_amount,
                        'IN' AS type,
                        '-' AS no_invoice
                    FROM tbl_history_topup tht
                    JOIN tbl_pembeli tp ON tht.customer_id = tp.id
                    WHERE tht.status != 'canceled'
                      AND tht.date BETWEEN ? AND ?
                      AND tp.company_id = ?
                      " . ($this->customerId ? "AND tp.id = ?" : "") . "
                      " . ($isCustomerRole ? "AND tp.user_id = ?" : "") . "

                    UNION ALL

                    -- OUT EXPIRED
                    SELECT
                        tht.expired_date AS date,
                        tht.created_at,
                        tp.marking,
                        0 AS points,
                        tht.price_per_kg,
                        tht.expired_amount AS expired_amount,
                        'OUT (expired)' AS type,
                        '-' AS no_invoice
                    FROM tbl_history_topup tht
                    JOIN tbl_pembeli tp ON tht.customer_id = tp.id
                    WHERE tht.status = 'expired'
                      AND tht.expired_date BETWEEN ? AND ?
                      AND tp.company_id = ?
                      " . ($this->customerId ? "AND tp.id = ?" : "") . "
                      " . ($isCustomerRole ? "AND tp.user_id = ?" : "") . "

                    UNION ALL

                    -- IN (RETUR)
                    SELECT
                        tr.created_at AS date,
                        tr.created_at,
                        tp.marking,
                        resi.berat AS points,
                        (resi.harga / resi.berat) AS price_per_kg,
                        NULL AS expired_amount,
                        'IN (Retur)' AS type,
                        ti.no_invoice AS no_invoice
                    FROM tbl_retur_item tri
                    JOIN tbl_resi resi ON tri.resi_id = resi.id
                    JOIN tbl_retur tr ON tri.retur_id = tr.id
                    JOIN tbl_invoice ti ON tr.invoice_id = ti.id
                    JOIN tbl_pembeli tp ON ti.pembeli_id = tp.id
                    WHERE tr.account_id = 159
                      AND DATE(tr.created_at) BETWEEN ? AND ?
                      AND tp.company_id = ?
                      " . ($this->customerId ? "AND tp.id = ?" : "") . "
                      " . ($isCustomerRole ? "AND tp.user_id = ?" : "") . "
                ) raw_data
            ),
            calculated_data AS (
                SELECT
                    date,
                    created_at,
                    marking,
                    in_points,
                    out_points,
                    SUM(in_points - out_points)
                        OVER (PARTITION BY marking ORDER BY date, created_at) AS saldo,
                    price_per_kg,
                    value,
                    status,
                    no_invoice
                FROM combined_data
            )
            SELECT
                date,
                created_at,
                marking,
                in_points,
                out_points,
                saldo,
                saldo * price_per_kg AS saldo_value,
                value,
                status,
                no_invoice
            FROM calculated_data
            ORDER BY marking, date, created_at
        ";

        /**
         * ===========================
         * PARAMETER (AMAN & URUT)
         * ===========================
         */
        $params = [];

        $addParams = function () use (&$params, $companyId, $isCustomerRole, $userId) {
            $params[] = $this->startDate;
            $params[] = $this->endDate;
            $params[] = $companyId;

            if ($this->customerId) {
                $params[] = $this->customerId;
            }
            if ($isCustomerRole) {
                $params[] = $userId;
            }
        };

        $addParams(); // OUT
        $addParams(); // IN
        $addParams(); // EXPIRED
        $addParams(); // RETUR

        $data = DB::select($query, $params);

        $marking = $this->customerId
            ? Customer::where('id', $this->customerId)->value('marking')
            : '-';

        return view('exportExcel.topupreport', [
            'topup' => $data,
            'marking' => $marking,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach (range('A', 'J') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
