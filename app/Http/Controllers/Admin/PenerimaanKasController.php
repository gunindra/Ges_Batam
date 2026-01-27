<?php

namespace App\Http\Controllers\Admin;
use App\Exports\KasReportExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Asset;
use App\Models\Payment;
use App\Models\PaymentAccount;
use App\Models\COA;
use App\Traits\WhatsappTrait;
use Carbon\Carbon;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Str;

class PenerimaanKasController extends Controller
{
    use WhatsappTrait;

    public function index()
    {

        $companyId = session('active_company_id');

        $customers = Customer::where('status', '=', 1)
        ->where('tbl_pembeli.company_id', $companyId)
        ->get();

        $payment = DB::table('tbl_payment_account')
        ->join('tbl_coa', 'tbl_payment_account.coa_id', '=', 'tbl_coa.id')
        ->select('tbl_payment_account.coa_id', 'tbl_coa.code_account_id', 'tbl_coa.name')


        ->get();

        return view('Report.PenerimaanKas.indexpenerimaankas', [
            'customers' => $customers,
            'payment' => $payment
        ]);
    }

    public function getPenerimaanKas(Request $request)
    {
        $companyId = session('active_company_id');
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;


        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : Carbon::now()->startOfMonth();
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : Carbon::now()->endOfMonth();

        $payment = Payment::leftjoin('tbl_payment_invoice', 'tbl_payment_customer.id', '=', 'tbl_payment_invoice.payment_id')
        ->join('tbl_invoice', 'tbl_payment_invoice.invoice_id', '=', 'tbl_invoice.id')
        ->join('tbl_pembeli', 'tbl_payment_customer.pembeli_id', '=', 'tbl_pembeli.id')
        ->join('tbl_coa', 'tbl_payment_customer.payment_method_id', '=', 'tbl_coa.id')
        ->leftJoin(DB::raw("(
            SELECT payment_id,
                SUM(CASE WHEN tipe = 'debit' THEN -nominal ELSE nominal END) AS total_nominal
            FROM tbl_payment_items
            GROUP BY payment_id
        ) AS payment_items"), 'tbl_payment_customer.id', '=', 'payment_items.payment_id')
        ->where('tbl_payment_customer.company_id', $companyId)
        ->whereDate('tbl_payment_customer.payment_buat', '>=', $startDate)
        ->whereDate('tbl_payment_customer.payment_buat', '<=', $endDate);

    if ($request->customer) {
        $payment->where('tbl_payment_customer.pembeli_id', '=', $request->customer);
    }

    if ($request->payment) {
        $payment->where('tbl_payment_customer.payment_method_id', '=', $request->payment);
    }

    // Fixed SUM calculation
    $payment->selectRaw("
        tbl_payment_customer.kode_pembayaran as kode_pembayaran,
        tbl_payment_customer.payment_buat as created_date,
        tbl_payment_customer.payment_date as payment_date,
        tbl_payment_customer.discount as discount,
        tbl_pembeli.nama_pembeli as customer_name,
        tbl_pembeli.marking as marking,
        tbl_coa.name as payment_method,
                GROUP_CONCAT(DISTINCT CONCAT(tbl_invoice.no_invoice, ' (',
                TRIM(TRAILING '.00' FROM FORMAT(
                    (SELECT SUM(pi.amount)
                    FROM tbl_payment_invoice pi
                    WHERE pi.invoice_id = tbl_invoice.id
                    AND pi.payment_id = tbl_payment_customer.id), 2
                )),
            ')') ORDER BY tbl_invoice.no_invoice SEPARATOR ', ') AS no_invoice_with_amount ,
        SUM(tbl_payment_invoice.amount) AS total_invoice_amount,
        IFNULL(payment_items.total_nominal, 0) AS total_payment_items,
        SUM(tbl_payment_invoice.amount) + IFNULL(payment_items.total_nominal, 0) AS total_amount
    ")
    ->groupBy(
        'tbl_payment_customer.id',
        'tbl_payment_customer.payment_buat',
        'tbl_payment_customer.payment_date',
        'tbl_payment_customer.kode_pembayaran',
        'tbl_payment_customer.discount',
        'tbl_coa.name',
        'tbl_pembeli.nama_pembeli',
        'tbl_pembeli.marking',
        'payment_items.total_nominal'
    );



        // Get the results
        $payments = $payment->get();
        $output = '
            <h5 style="text-align:center; width:100%">'
            . \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - '
            . \Carbon\Carbon::parse($endDate)->format('d M Y') .
            '</h5>

            <div class="card-body">
            <table class="table" id="penerimaanKasTable" width="100%">
            <thead>
                <th onclick="sortTable(0)" width="10%" style="text-align:center;">No</th>
                <th onclick="sortTable(1)" width="15%" style="text-align:center;">Date</th>
                <th onclick="sortTable(2)" width="15%" style="text-align:center;">Transfer Date</th>
                <th onclick="sortTable(3)" width="15%" style="text-align:center;">Marking</th>
                <th onclick="sortTable(4)" width="10%" style="text-align:center;">Method</th>
                <th onclick="sortTable(5)" width="20%" style="text-align:center;">No Invoice</th>
                <th onclick="sortTable(6)" width="15%" style="text-align:right;">Total</th>
            </thead>
            <tbody>';

        $grandTotal = 0; // Initialize grand total

        foreach ($payments as $data) {
            $totalAmount = $data->total_amount;
            $grandTotal += $totalAmount;

            $output .= '<tr>
                <td style="text-align:center;">' . $data->kode_pembayaran . ' </td>
                <td style="text-align:center;">' . \Carbon\Carbon::parse($data->created_date)->format('d M Y') . '</td>
                <td style="text-align:center;">' . \Carbon\Carbon::parse($data->payment_date)->format('d M Y H:i') . '</td>
                <td style="text-align:center;">' . $data->marking . ' </td>
                <td style="text-align:center;">' . $data->payment_method . '</td>
                <td style="text-align:center;">' . $data->no_invoice_with_amount . '</td>
                <td style="text-align:right;">' . number_format($totalAmount, 0) . '</td>
            </tr>';
        }

        // Append footer row
        $output .= '</tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align:right; font-weight:bold;">Grand Total:</td>
                    <td style="text-align:right; font-weight:bold;">' . number_format($grandTotal, 0) . '</td>
                </tr>
            </tfoot>
        </table>
        </div>';

        return $output;

    }
    public function generatePdf(Request $request)
    {

        $companyId = session('active_company_id');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $customer = $request->nama_pembeli ?? '-';
        $account = $request->name ?? '-';

        try {
            $customerName = DB::table('tbl_pembeli')
                ->where('id', $customer)
                ->value('marking');

            $accountName = DB::table('tbl_coa')
                ->where('id', $account)
                ->value('name');
            // Query dasar untuk mengambil data asset
            $payment = Payment::join('tbl_payment_invoice', 'tbl_payment_customer.id', '=', 'tbl_payment_invoice.payment_id')
                ->join('tbl_invoice', 'tbl_payment_invoice.invoice_id', '=', 'tbl_invoice.id')
                ->join('tbl_pembeli', 'tbl_payment_customer.pembeli_id', '=', 'tbl_pembeli.id')
                ->join('tbl_coa', 'tbl_payment_customer.payment_method_id', '=', 'tbl_coa.id')
                ->where('tbl_payment_customer.company_id', $companyId);

                if ($request->startDate && $request->endDate) {
                    $startDateCarbon = Carbon::createFromFormat('d M Y', $request->startDate)->startOfDay();
                    $endDateCarbon = Carbon::createFromFormat('d M Y', $request->endDate)->endOfDay();

                    $payment->whereBetween('tbl_payment_customer.payment_buat', [$startDateCarbon, $endDateCarbon]);

                    $startDate = $startDateCarbon->format('d F Y');
                    $endDate = $endDateCarbon->format('d F Y');
                } else {
                    $startDateCarbon = now()->startOfMonth();
                    $endDateCarbon = now()->endOfMonth();

                    $payment->whereBetween('tbl_payment_customer.payment_buat', [$startDateCarbon, $endDateCarbon]);

                    $startDate = $startDateCarbon->format('d F Y');
                    $endDate = $endDateCarbon->format('d F Y');
                }


            if ($customer && $customer !== '-') {
                $payment->where('tbl_payment_customer.pembeli_id', '=', $customer);
            }


            if ($account && $account !== '-') {
                $payment->where('tbl_payment_customer.payment_method_id', '=', $account);
            }

            $payments = DB::table('tbl_payment_customer')
                    ->select(
                        'tbl_payment_customer.kode_pembayaran AS kode_pembayaran',
                        'tbl_payment_customer.payment_buat AS created_date',
                        'tbl_payment_customer.payment_date AS payment_date',
                        'tbl_payment_customer.discount AS discount',
                        'tbl_pembeli.nama_pembeli AS customer_name',
                        'tbl_pembeli.marking AS marking',
                        'tbl_coa.name AS payment_method',
                        DB::raw("GROUP_CONCAT(DISTINCT CONCAT(tbl_invoice.no_invoice, ' (',
                        TRIM(TRAILING '.00' FROM FORMAT(
                            (SELECT SUM(pi.amount)
                            FROM tbl_payment_invoice pi
                            WHERE pi.invoice_id = tbl_invoice.id
                            AND pi.payment_id = tbl_payment_customer.id), 2
                        )),
                        ')') ORDER BY tbl_invoice.no_invoice SEPARATOR ', ') AS no_invoice_with_amount"),
                        DB::raw('SUM(tbl_payment_invoice.amount) AS total_invoice_amount'),
                        DB::raw('IFNULL(payment_items.total_nominal, 0) AS total_payment_items'),
                        DB::raw('SUM(tbl_payment_invoice.amount) + IFNULL(payment_items.total_nominal, 0) AS total_amount')
                    )
                    ->join('tbl_payment_invoice', 'tbl_payment_customer.id', '=', 'tbl_payment_invoice.payment_id')
                    ->join('tbl_invoice', 'tbl_payment_invoice.invoice_id', '=', 'tbl_invoice.id')
                    ->join('tbl_pembeli', 'tbl_payment_customer.pembeli_id', '=', 'tbl_pembeli.id')
                    ->join('tbl_coa', 'tbl_payment_customer.payment_method_id', '=', 'tbl_coa.id')
                    ->leftJoin(DB::raw("(
                        SELECT
                            payment_id,
                            SUM(CASE WHEN tipe = 'debit' THEN -nominal ELSE nominal END) AS total_nominal
                        FROM tbl_payment_items
                        GROUP BY payment_id
                    ) AS payment_items"), 'tbl_payment_customer.id', '=', 'payment_items.payment_id')

                    // Filter tanggal
                    ->whereBetween('tbl_payment_customer.payment_buat', [$startDateCarbon, $endDateCarbon]);

                // Tambahkan filter jika customer dipilih
                if ($customer !== '-') {
                    $payments->where('tbl_payment_customer.pembeli_id', $customer);
                }

                // Tambahkan filter jika account dipilih
                if ($account !== '-') {
                    $payments->where('tbl_payment_customer.payment_method_id', $account);
                }

                // Grouping
                $payments = $payments->groupBy(
                        'tbl_payment_customer.id',
                        'tbl_payment_customer.payment_buat',
                        'tbl_payment_customer.payment_date',
                        'tbl_payment_customer.kode_pembayaran',
                        'tbl_payment_customer.discount',
                        'tbl_coa.name',
                        'tbl_pembeli.nama_pembeli',
                        'tbl_pembeli.marking',
                        'payment_items.total_nominal'
                    )
                    ->get();

            if ($payments->isEmpty()) {
                return response()->json(['error' => 'No payments report found'], 404);
            }
            $customerName = '-';
            if ($customer !== '-') {
                $customerData = DB::table('tbl_pembeli')->where('id', $customer)->first();
                $customerName = $customerData ? $customerData->marking : 'Unknown';
            }

            $accountName = '-';
            if ($account !== '-') {
                $accountData = DB::table('tbl_coa')->where('id', $account)->first();
                $accountName = $accountData ? $accountData->name : 'Unknown';
            }

            try {
                $pdf = pdf::loadView('exportPDF.penerimaankaspdf', [
                    'payments' => $payments,
                    'customer' => $customerName,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'account' => $accountName
                ])
                    ->setPaper('A4', 'portrait')
                    ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                    ->setWarnings(false);
            } catch (\Exception $e) {
                Log::error('Error generating kas report PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to generate PDF'], 500);
            }
            try {
                $folderPath = storage_path('app/public/penerimaankas');

                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }

                $fileName = 'penerimaankas' . (string) Str::uuid() . '.pdf';
                $filePath = $folderPath . '/' . $fileName;

                $pdf->save($filePath);
            } catch (\Exception $e) {
                Log::error('Error saving PDF: ' . $e->getMessage(), ['exception' => $e]);
                return response()->json(['error' => 'Failed to save PDF'], 500);
            }

            $url = asset('storage/penerimaankas/' . $fileName);
            return response()->json(['url' => $url]);

        } catch (\Exception $e) {
            Log::error('Error generating penerimaan kas report PDF: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'An error occurred while generating the penerimaan kas report PDF'], 500);
        }
    }
    public function exportKasReport(Request $request)
    {

        $customer = $request->marking ?? '-';
        $account = $request->name ?? '-';

        if ($request->filled('startDate') && $request->filled('endDate')) {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');
        } else {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        }

        return Excel::download(new KasReportExport($startDate, $endDate, $customer, $account), 'Penerimaan_Kas.xlsx');
    }
}
