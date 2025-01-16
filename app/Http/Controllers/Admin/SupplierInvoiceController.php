<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use App\Models\SupInvoice;
use App\Models\SupInvoiceItem;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Admin\JournalController;

class SupplierInvoiceController extends Controller
{


    protected $jurnalController;

    public function __construct(JournalController $jurnalController)
    {
        $this->jurnalController = $jurnalController;
    }


    public function index()
    {
        $listStatus = DB::select("SELECT status_name FROM tbl_status");

        return view('vendor.supplierinvoice.indexsupplierinvoice', [
            'listStatus' => $listStatus
        ]);
    }

    public function getListSupplierInvoice(Request $request)
    {
        $companyId = session('active_company_id');
        if ($request->ajax()) {

            // Join with tbl_vendors to get the vendor name and with tbl_matauang to get currency abbreviation
            $data = SupInvoice::join('tbl_matauang', 'tbl_sup_invoice.matauang_id', '=', 'tbl_matauang.id')
                ->where('tbl_sup_invoice.company_id', $companyId)
                ->join('tbl_vendors', 'tbl_sup_invoice.vendor_id', '=', 'tbl_vendors.id')
                ->select('tbl_sup_invoice.*', 'tbl_matauang.singkatan_matauang', 'tbl_vendors.name as vendor_name') // Select vendor name
                ->orderBy('id', 'desc')
                ->with('items');

            // Filter by date range if startDate and endDate are provided
            if (!empty($request->startDate) && !empty($request->endDate)) {
                $startDate = date('Y-m-d', strtotime($request->startDate));
                $endDate = date('Y-m-d', strtotime($request->endDate));
                $data->whereBetween('tbl_sup_invoice.tanggal', [$startDate, $endDate]);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('invoice_no', function ($row) {
                    return $row->invoice_no;
                })
                ->addColumn('vendor', function ($row) {
                    return $row->vendor_name;
                })
                ->addColumn('tanggal', function ($row) {
                    return Carbon::parse($row->tanggal)->format('d F Y');
                })
                ->addColumn('matauang', function ($row) {
                    return $row->singkatan_matauang;
                })
                ->addColumn('status_bayar', function ($row) {
                    return $row->status_bayar == 'Lunas'
                        ? '<span class="text-success"><i class="fas fa-check-circle"></i> Lunas</span>'
                        : '<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Belum Lunas</span>';
                })
                ->addColumn('total_harga', function ($row) {
                    return number_format($row->total_harga, 2, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btnDetailInvoice btn btn-primary btn-sm">Detail</a>';
                    return $btn;
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value'] != '') {
                        $searchValue = $request->search['value'];
                        $query->where(function ($q) use ($searchValue) {
                            $q->where('tbl_sup_invoice.invoice_no', 'like', "%{$searchValue}%")
                                ->orWhere('tbl_vendors.name', 'like', "%{$searchValue}%")
                                ->orWhere('tbl_matauang.singkatan_matauang', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->rawColumns(['status_bayar', 'action'])
                ->make(true);
        }
    }


    public function addSupplierInvoice()
    {

        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $coas = COA::all();
        $listVendor = Vendor::pluck('name', 'id');

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
                $lastMarking = $lastInvoice->invoice_no;
                $lastYearMonth = substr($lastMarking, 2, 4);

                if ($lastYearMonth === $yearMonth) {

                    $lastNumber = (int) substr($lastMarking, 6);
                    $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $newNumber = '0001';
                }
            } else {
                $newNumber = '0001';
            }

            $newNoinvoice = 'VO' . $yearMonth . $newNumber;

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'No invoice berhasil di-generate',
                'no_invoice' => $newNoinvoice
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghasilkan nomor invoice: ' . $e->getMessage()
            ], 500);
        }
    }



    public function store(Request $request)
    {
        $companyId = session('active_company_id');
        $request->validate([
            'invoice_no' => 'required|unique:tbl_sup_invoice,invoice_no',
            'tanggal' => 'required|date',
            'vendor' => 'required',
            'noReferenceVendor' => 'required',
            'matauang_id' => 'required',
            'items' => 'required|array',
            'items.*.account' => 'required',
            'items.*.itemDesc' => 'required',
            'items.*.debit' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $formattedDate = Carbon::createFromFormat('d F Y', $request->tanggal)->format('Y-m-d');

            // Ensure all debit values are numeric and calculate the total debit
            $totalDebit = array_sum(array_map(function ($item) {
                return (float) $item['debit'];
            }, $request->items));

            // Fetch the vendor's account ID to use as the credit account
            $vendor = Vendor::findOrFail($request->vendor);
            $vendorAccountId = $vendor->account_id;

            if (!$vendorAccountId) {
                throw new \Exception('Vendor tidak memiliki account_id.');
            }

            // Create the supplier invoice
            $supInvoice = SupInvoice::create([
                'invoice_no' => $request->invoice_no,
                'tanggal' => $formattedDate,
                'vendor_id' => $request->vendor,
                'no_ref' => $request->noReferenceVendor,
                'matauang_id' => $request->matauang_id,
                'total_harga' => $totalDebit,
                'company_id' => $companyId,
                'total_bayar' => 0,
            ]);

            // Create each item as a debit entry in SupInvoiceItem
            foreach ($request->items as $item) {
                SupInvoiceItem::create([
                    'invoice_id' => $supInvoice->id,
                    'coa_id' => $item['account'],
                    'description' => $item['itemDesc'],
                    'debit' => (float) $item['debit'], // Cast debit to float to avoid string issues
                    'credit' => 0,
                ]);
            }

            $request->merge(['code_type' => 'AP']);
            $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

            $jurnal = Jurnal::create([
                'no_journal' => $noJournal,
                'tipe_kode' => 'AP',
                'tanggal' => $formattedDate,
                'no_ref' => $request->invoice_no,
                'status' => 'Approve',
                'description' => "Jurnal untuk Invoice {$request->invoice_no}",
                'totaldebit' => $totalDebit,
                'totalcredit' => $totalDebit,
            ]);

            foreach ($request->items as $item) {
                JurnalItem::create([
                    'jurnal_id' => $jurnal->id,
                    'code_account' => $item['account'],
                    'description' => "Debit untuk Invoice {$request->invoice_no}",
                    'debit' => (float) $item['debit'],
                    'credit' => 0,
                ]);
            }

            SupInvoiceItem::create([
                'invoice_id' => $supInvoice->id,
                'coa_id' => $vendorAccountId,
                'description' => "Credit untuk invoice {$request->invoice_no}",
                'debit' => 0,
                'credit' => $totalDebit,
            ]);

            JurnalItem::create([
                'jurnal_id' => $jurnal->id,
                'code_account' => $vendorAccountId,
                'description' => "Credit untuk Invoice {$request->invoice_no}",
                'debit' => 0,
                'credit' => $totalDebit,
            ]);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Invoice dan Jurnal berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan invoice: ' . $e->getMessage()], 500);
        }
    }





    public function showDetail(Request $request)
    {
        $id = $request->input('id');
        $invoice = SupInvoice::with(['items.coa', 'vendor'])->find($id);

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
        $invoice->tanggal = Carbon::parse($invoice->tanggal)->translatedFormat('d F Y');
        $response = [
            'invoice_no' => $invoice->invoice_no,
            'tanggal' => $invoice->tanggal,
            'no_ref' => $invoice->no_ref,
            'matauang_id' => $invoice->matauang_id,
            'total_harga' => $invoice->total_harga,
            'total_bayar' => $invoice->total_bayar,
            'vendor_name' => $invoice->vendor->name,
            'items' => $invoice->items
        ];

        return response()->json($response);
    }





}
