<?php

namespace App\Exports;

use App\Models\Payment;
use Carbon\Carbon;
use DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\View\View;

class KasReportExport implements FromView, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $customer;
    protected $account;

    public function __construct($startDate, $endDate, $customer, $account)
    {
        $this->startDate = Carbon::parse($startDate)->format('Y-m-d');
        $this->endDate = Carbon::parse($endDate)->format('Y-m-d');
        $this->customer = $customer;
        $this->account = $account;
    }

    public function view(): View
    {
        $query = DB::table('tbl_payment_customer')
            ->select(
                'tbl_payment_customer.kode_pembayaran',
                'tbl_payment_customer.payment_buat AS created_date',
                'tbl_payment_customer.payment_date',
                'tbl_payment_customer.discount',
                'tbl_pembeli.nama_pembeli AS customer_name',
                'tbl_pembeli.marking',
                'tbl_coa.name AS payment_method',
                DB::raw("GROUP_CONCAT(DISTINCT CONCAT(tbl_invoice.no_invoice, ' (',
                    TRIM(TRAILING '.00' FROM FORMAT(
                        (SELECT SUM(pi.amount) FROM tbl_payment_invoice pi WHERE pi.invoice_id = tbl_invoice.id AND pi.payment_id = tbl_payment_customer.id), 2
                    )), ')') ORDER BY tbl_invoice.no_invoice SEPARATOR ', ') AS no_invoice_with_amount"),
                DB::raw('SUM(tbl_payment_invoice.amount) AS total_invoice_amount'),
                DB::raw('IFNULL(payment_items.total_nominal, 0) AS total_payment_items'),
                DB::raw('SUM(tbl_payment_invoice.amount) + IFNULL(payment_items.total_nominal, 0) AS total_amount')
            )
            ->join('tbl_payment_invoice', 'tbl_payment_customer.id', '=', 'tbl_payment_invoice.payment_id')
            ->join('tbl_invoice', 'tbl_payment_invoice.invoice_id', '=', 'tbl_invoice.id')
            ->join('tbl_pembeli', 'tbl_payment_customer.pembeli_id', '=', 'tbl_pembeli.id')
            ->join('tbl_coa', 'tbl_payment_customer.payment_method_id', '=', 'tbl_coa.id')
            ->leftJoin(DB::raw("(
                SELECT payment_id, SUM(CASE WHEN tipe = 'debit' THEN -nominal ELSE nominal END) AS total_nominal
                FROM tbl_payment_items GROUP BY payment_id
            ) AS payment_items"), 'tbl_payment_customer.id', '=', 'payment_items.payment_id')
            ->whereBetween(DB::raw('DATE(tbl_payment_customer.payment_buat)'), [$this->startDate, $this->endDate]);

        if ($this->customer !== '-') {
            $query->where('tbl_pembeli.id', $this->customer);
        }

        if ($this->account !== '-') {
            $query->where('tbl_coa.id', $this->account);
        }

        $payments = $query->groupBy(
            'tbl_payment_customer.id',
            'tbl_payment_customer.payment_buat',
            'tbl_payment_customer.payment_date',
            'tbl_payment_customer.kode_pembayaran',
            'tbl_payment_customer.discount',
            'tbl_coa.name',
            'tbl_pembeli.nama_pembeli',
            'tbl_pembeli.marking',
            'payment_items.total_nominal'
        )->get();

        return view('exportExcel.penerimaankas', [
            'payments' => $payments,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'customer' => $this->customer !== '-' ? DB::table('tbl_pembeli')->where('id', $this->customer)->value('marking') ?? 'Unknown' : '-',
            'account' => $this->account !== '-' ? DB::table('tbl_coa')->where('id', $this->account)->value('name') ?? 'Unknown' : '-',
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
