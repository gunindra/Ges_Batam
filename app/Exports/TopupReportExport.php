<?php

namespace App\Exports;

use DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\{
    FromQuery,
    WithHeadings,
    WithMapping,
    WithChunkReading,
    ShouldAutoSize
};

class TopupReportExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithChunkReading,
    ShouldAutoSize
{
    protected $customer;
    protected $startDate;
    protected $endDate;
    protected $companyId;
    protected $isCustomerRole;
    protected $userId;

    public function __construct($customer, $startDate, $endDate)
    {
        $this->customer = $customer !== '-' ? $customer : null;
        $this->startDate = $startDate
            ? Carbon::parse($startDate)->format('Y-m-d')
            : '2025-01-01';

        $this->endDate = $endDate
            ? Carbon::parse($endDate)->format('Y-m-d')
            : now()->endOfMonth()->format('Y-m-d');

        $this->companyId = session('active_company_id');
        $this->isCustomerRole = auth()->user()->role === 'customer';
        $this->userId = auth()->id();
    }

    /**
     * QUERY (TETAP STREAM + ORDER)
     */
    public function query()
    {
        return DB::table(DB::raw("
        (
            WITH combined_data AS (
                SELECT
                    date,
                    created_at,
                    marking,
                    CASE WHEN type IN ('IN','IN (Retur)') THEN points ELSE 0 END AS in_points,
                    CASE WHEN type IN ('OUT','OUT (expired)') THEN points ELSE 0 END AS out_points,
                    CASE
                        WHEN type='OUT (expired)' THEN expired_amount * price_per_kg
                        ELSE points * price_per_kg
                    END AS value,
                    price_per_kg,
                    type AS status,
                    no_invoice
                FROM (
                    -- OUT (USAGE)
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
                            WHERE tpi.payment_id = tup.payment_id
                        ) AS no_invoice
                    FROM tbl_usage_points tup
                    JOIN tbl_pembeli tp ON tup.customer_id = tp.id
                    WHERE tup.usage_date BETWEEN '{$this->startDate}' AND '{$this->endDate}'
                    AND tp.company_id = {$this->companyId}
                    " . ($this->customer ? "AND tp.id = {$this->customer}" : "") . "
                    " . ($this->isCustomerRole ? "AND tp.user_id = {$this->userId}" : "") . "
                    GROUP BY tup.payment_id, tp.marking

                    UNION ALL

                    -- IN (TOPUP)
                    SELECT
                        tht.date,
                        tht.created_at,
                        tp.marking,
                        tht.remaining_points AS points,
                        tht.price_per_kg,
                        NULL,
                        'IN',
                        '-' AS no_invoice
                    FROM tbl_history_topup tht
                    JOIN tbl_pembeli tp ON tht.customer_id = tp.id
                    WHERE tht.status != 'canceled'
                    AND tht.date BETWEEN '{$this->startDate}' AND '{$this->endDate}'
                    AND tp.company_id = {$this->companyId}
                    " . ($this->customer ? "AND tp.id = {$this->customer}" : "") . "
                    " . ($this->isCustomerRole ? "AND tp.user_id = {$this->userId}" : "") . "

                    UNION ALL

                    -- OUT (EXPIRED)
                    SELECT
                        tht.expired_date AS date,
                        tht.created_at,
                        tp.marking,
                        tht.expired_amount AS points,
                        tht.price_per_kg,
                        tht.expired_amount,
                        'OUT (expired)',
                        '-' AS no_invoice
                    FROM tbl_history_topup tht
                    JOIN tbl_pembeli tp ON tht.customer_id = tp.id
                    WHERE tht.status = 'expired'
                    AND tht.expired_date BETWEEN '{$this->startDate}' AND '{$this->endDate}'
                    AND tp.company_id = {$this->companyId}
                    " . ($this->customer ? "AND tp.id = {$this->customer}" : "") . "
                    " . ($this->isCustomerRole ? "AND tp.user_id = {$this->userId}" : "") . "

                    UNION ALL

                    -- IN (RETUR)
                    SELECT
                        tr.created_at AS date,
                        tr.created_at,
                        tp.marking,
                        resi.berat AS points,
                        (resi.harga / resi.berat) AS price_per_kg,
                        NULL,
                        'IN (Retur)',
                        ti.no_invoice
                    FROM tbl_retur_item tri
                    JOIN tbl_resi resi ON tri.resi_id = resi.id
                    JOIN tbl_retur tr ON tri.retur_id = tr.id
                    JOIN tbl_invoice ti ON tr.invoice_id = ti.id
                    JOIN tbl_pembeli tp ON ti.pembeli_id = tp.id
                    WHERE tr.account_id = 159
                    AND DATE(tr.created_at) BETWEEN '{$this->startDate}' AND '{$this->endDate}'
                    AND tp.company_id = {$this->companyId}
                    " . ($this->customer ? "AND tp.id = {$this->customer}" : "") . "
                    " . ($this->isCustomerRole ? "AND tp.user_id = {$this->userId}" : "") . "
                ) x
            )
            SELECT
                date,
                created_at,
                marking,
                in_points,
                out_points,
                value,
                price_per_kg,
                SUM(in_points - out_points)
                    OVER (PARTITION BY marking ORDER BY date, created_at) AS saldo,
                status,
                no_invoice
            FROM combined_data
        ) q
    "))
    ->orderBy('marking')
    ->orderBy('date')
    ->orderBy('created_at');
    }

    /**
     * HEADER — PERSIS SEPERTI AWAL
     */
    public function headings(): array
    {
        return [
            'Date',
            'Marking',
            'Invoice',
            'In (Kg)',
            'Out (Kg)',
            'Saldo (Kg)',
            'Value (Rp)',
            'Saldo Value (Rp)',
            'Status',
        ];
    }

    /**
     * MAP — URUTAN DIKUNCI DI SINI
     */
    public function map($row): array
    {
        $saldoValue = $row->saldo * ($row->price_per_kg ?? 0);

        return [
            Carbon::parse($row->date)->format('d-m-Y'), // Date
            $row->marking,                              // Marking
            $row->no_invoice ?? '-',                    // Invoice
            number_format($row->in_points, 2),          // In
            number_format($row->out_points, 2),         // Out
            number_format($row->saldo, 2),              // Saldo
            number_format($row->value ?? 0, 2),         // Value
            number_format($saldoValue, 2),              // Saldo Value
            strtoupper($row->status),                   // Status
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
