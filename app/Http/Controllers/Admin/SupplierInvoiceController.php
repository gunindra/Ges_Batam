<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\SupInvoice;
use App\Models\SupInvoiceItem;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SupplierInvoiceController extends Controller
{
    public function index()
    {
        $listStatus = DB::select("SELECT status_name FROM tbl_status");

        return view('vendor.supplierinvoice.indexsupplierInvoice', [
            'listStatus' => $listStatus
        ]);
    }
    public function getlistSupplierInvoice(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : null;
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : null;

        $dateCondition = '';
        if ($startDate && $endDate) {
            $dateCondition = "AND a.tanggal_invoice BETWEEN '$startDate' AND '$endDate'";
        }

        $statusCondition = $status ? "AND d.status_name LIKE '$status'" : "";

        $q = "SELECT a.id,
                    a.no_resi,
                    DATE_FORMAT(a.tanggal_invoice, '%d %M %Y') AS tanggal_bayar,
                    b.nama_pembeli AS pembeli,
                    a.alamat,
                    b.metode_pengiriman,
                    a.berat,
                    a.panjang,
                    a.lebar,
                    a.tinggi,
                    a.harga,
                    a.matauang_id,
                    a.rate_matauang,
                    d.status_name
            FROM tbl_invoice AS a
            JOIN tbl_pembeli AS b ON a.pembeli_id = b.id
            JOIN tbl_status AS d ON a.status_id = d.id
            WHERE (
                UPPER(b.nama_pembeli) LIKE UPPER('$txSearch')
                OR UPPER(a.no_resi) LIKE UPPER('$txSearch')
                )
                $statusCondition
                 $dateCondition
            ORDER BY CASE d.status_name
                        WHEN 'Pending Payment' THEN 1
                        WHEN 'Debt' THEN 2
                        WHEN 'Out For Delivery' THEN 3
                        WHEN 'Ready For Pickup' THEN 4
                        WHEN 'Delivering' THEN 5
                        ELSE 6
                    END,
                     a.id DESC
            LIMIT 100;";

        $data = DB::select($q);

        $currencySymbols = [
            1 => 'Rp ',
            2 => '$ ',
            3 => '¥ '
        ];

        $output = '<table class="table align-items-center table-flush table-hover" id="tableSupplierInvoice">
                    <thead class="thead-light">
                        <tr>
                            <th>No Resi</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Pengiriman</th>
                            <th>Alamat</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($data as $item) {

            $statusBadgeClass = '';
            $btnEditSupplierinvoice = '';

            switch ($item->status_name) {
                case 'Batam / Sortir':
                    $statusBadgeClass = 'badge-warning';
                    $$btnEditSupplierinvoice = '<a class="btn  $btnEditSupplierinvoice  btn-sm btn-primary text-white" data-id="' . $item->id . '" ><i class="fas fa-edit"></i></a>';
                    break;
                case 'Ready For Pickup':
                    $statusBadgeClass = 'badge-success';
                    $$btnEditSupplierinvoice = '<a class="btn  $btnEditSupplierinvoice  btn-sm btn-primary text-white" data-id="' . $item->id . '" ><i class="fas fa-edit"></i></a>';
                    break;
                case 'Out For Delivery':
                    $statusBadgeClass = 'badge-primary';
                    $$btnEditSupplierinvoice = '<a class="btn  $btnEditSupplierinvoice  btn-sm btn-primary text-white" data-id="' . $item->id . '" ><i class="fas fa-edit"></i></a>';
                    break;
                case 'Delivering':
                    $statusBadgeClass = 'badge-delivering';
                    break;
                case 'Done':
                    $statusBadgeClass = 'badge-secondary';
                    break;
                default:
                    $statusBadgeClass = 'badge-secondary';
                    break;
            }

            $convertedHarga = $item->harga;
            if ($item->matauang_id != 1) {
                $convertedHarga = $item->harga / $item->rate_matauang;
            }

            $output .=
                '
                            <tr>
                                <td>' . ($item->no_resi ?? '-') . '</td>
                                <td>' . ($item->tanggal_bayar ?? '-') . '</td>
                                <td>' . ($item->pembeli ?? '-') . '</td>
                                <td>' . ($item->metode_pengiriman ?? '-') . '</td>
                                <td>' . ($item->alamat ?? '-') . '</td>
                                <td>' . $currencySymbols[$item->matauang_id] . number_format($convertedHarga, 2, '.', ',') . '</td>
                                <td><span class="badge ' . $statusBadgeClass . '">' . ($item->status_name ?? '-') . '</span></td>
                                <td>


                                </td>
                            </tr>
                        ';
        }
        $output .= '</tbody></table>';
        return $output;
    }
    public function addSupplierInvoice()
    {

        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $coas = COA::all();
        $listVendor = Vendor::pluck('name');

        return view('vendor.supplierinvoice.buatsupplierinvoice', [
                'listCurrency' => $listCurrency,
                'coas' => $coas,
                'listVendor' => $listVendor,
            ]);

    }


    public function generateSupInvoice()
    {
        DB::beginTransaction();

        try {
            $yearMonth = date('ym');

            $lastInvoice = DB::table('tbl_sup_invoice')
                ->select('invoice_no')
                ->orderBy('invoice_no', 'desc')
                ->first();

            if ($lastInvoice) {
                $lastMarking = $lastInvoice->no_invoice;
                $lastYearMonth = substr($lastMarking, 0, 4);

                if ($lastYearMonth === $yearMonth) {
                    $lastNumber = (int)substr($lastMarking, 4);
                    $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
                } else {
                    $newNumber = '0001';
                }
            } else {
                $newNumber = '0001';
            }

            $newNoinvoice = 'VO' . $yearMonth . $newNumber;

            DB::commit();

            // Return response sukses
            return response()->json([
                'status' => 'success',
                'message' => 'No invoice berhasil di-generate',
                'no_invoice' => $newNoinvoice
            ], 200);
        } catch (\Exception $e) {
            // Rollback jika ada error
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghasilkan nomor invoice: ' . $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|unique:tbl_sup_invoice,invoice_no',
            'tanggal' => 'required|date',
            'vendor' => 'required',
            'matauang_id' => 'required',
            'items' => 'required|array',
            'items.*.account' => 'required',
            'items.*.itemDesc' => 'required',
            'items.*.debit' => 'nullable|numeric',
            'items.*.credit' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {

            $formattedDate = Carbon::createFromFormat('d F Y', $request->tanggal)->format('Y-m-d');
            // Simpan invoice ke tbl_sup_invoice
            $supInvoice = SupInvoice::create([
                'invoice_no' => $request->invoice_no,
                'tanggal' => $formattedDate,
                'vendor' => $request->vendor,
                'matauang_id' => $request->matauang_id,
            ]);

            // Simpan items ke tbl_sup_invoice_items
            foreach ($request->items as $item) {
                SupInvoiceItem::create([
                    'invoice_id' => $supInvoice->id,
                    'coa_id' => $item['account'],
                    'description' => $item['itemDesc'],
                    'debit' => $item['debit'] ?? 0,
                    'credit' => $item['credit'] ?? 0,
                    'memo' => $item['memo'] ?? '',
                ]);
            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Invoice berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan invoice: ' . $e->getMessage()], 500);
        }
    }



}
