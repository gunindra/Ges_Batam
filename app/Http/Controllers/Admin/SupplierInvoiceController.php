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

        return view('vendor.supplierinvoice.indexsupplierInvoice', [
            'listStatus' => $listStatus
        ]);
    }

    public function getListSupplierInvoice(Request $request)
    {
        if ($request->ajax()) {

            $data = SupInvoice::join('tbl_matauang', 'tbl_sup_invoice.matauang_id', '=', 'tbl_matauang.id')
                ->select('tbl_sup_invoice.*', 'tbl_matauang.singkatan_matauang')
                ->with('items');

            // Filter tanggal jika ada startDate dan endDate
            if (!empty($request->startDate) && !empty($request->endDate)) {
                $startDate = date('Y-m-d', strtotime($request->startDate));
                $endDate = date('Y-m-d', strtotime($request->endDate));
                $data->whereBetween('tbl_sup_invoice.tanggal', [$startDate, $endDate]);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('invoice_no', function($row) {
                    return $row->invoice_no;
                })
                ->addColumn('vendor', function($row) {
                    return $row->vendor;
                })
                ->addColumn('tanggal', function($row) {
                    return Carbon::parse($row->tanggal)->format('d F Y');
                })
                ->addColumn('matauang', function($row) {
                    return $row->singkatan_matauang;
                })
                ->addColumn('total_debit', function($row) {
                    $totalDebit = $row->items->sum('debit');
                    return number_format($totalDebit, 2);
                })
                ->addColumn('total_credit', function($row) {
                    $totalCredit = $row->items->sum('credit');
                    return number_format($totalCredit, 2);
                })
                ->addColumn('action', function($row) {
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btnDetailInvoice btn btn-primary btn-sm">Detail</a>';
                    // $btn .= ' <a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $btn;
                })

                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value'] != '') {
                        $searchValue = $request->search['value'];
                        $query->where(function($q) use ($searchValue) {
                            $q->where('tbl_sup_invoice.invoice_no', 'like', "%{$searchValue}%")
                              ->orWhere('tbl_sup_invoice.vendor', 'like', "%{$searchValue}%")
                              ->orWhere('tbl_matauang.singkatan_matauang', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
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
                $lastMarking = $lastInvoice->invoice_no;
                $lastYearMonth = substr($lastMarking, 2, 4);

                if ($lastYearMonth === $yearMonth) {

                    $lastNumber = (int)substr($lastMarking, 6);
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

            $supInvoice = SupInvoice::create([
                'invoice_no' => $request->invoice_no,
                'tanggal' => $formattedDate,
                'vendor' => $request->vendor,
                'matauang_id' => $request->matauang_id,
            ]);

            $totalDebit = 0;
            $totalCredit = 0;


            foreach ($request->items as $item) {
                SupInvoiceItem::create([
                    'invoice_id' => $supInvoice->id,
                    'coa_id' => $item['account'],
                    'description' => $item['itemDesc'],
                    'debit' => $item['debit'] ?? 0,
                    'credit' => $item['credit'] ?? 0,
                    'memo' => $item['memo'] ?? '',
                ]);


                $totalDebit += $item['debit'] ?? 0;
                $totalCredit += $item['credit'] ?? 0;
            }

            if ($totalDebit !== $totalCredit) {
                throw new \Exception('Total debit dan credit tidak seimbang.');
            }

            try {
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
                    'totalcredit' => $totalCredit,
                ]);

                foreach ($request->items as $item) {
                    if ($item['debit'] > 0) {
                        JurnalItem::create([
                            'jurnal_id' => $jurnal->id,
                            'code_account' => $item['account'],
                            'description' => "Debit untuk Invoice {$request->invoice_no}",
                            'debit' => $item['debit'],
                            'credit' => 0,
                        ]);
                    }


                    if ($item['credit'] > 0) {
                        JurnalItem::create([
                            'jurnal_id' => $jurnal->id,
                            'code_account' => $item['account'],
                            'description' => "Credit untuk Invoice {$request->invoice_no}",
                            'debit' => 0,
                            'credit' => $item['credit'],
                        ]);
                    }
                }

            } catch (\Exception $e) {
                throw new \Exception('Gagal menambahkan jurnal: ' . $e->getMessage());
            }

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
        $invoice = SupInvoice::with(['items.coa'])->find($id);

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
        $invoice->tanggal = Carbon::parse($invoice->tanggal)->translatedFormat('d F Y');
        return response()->json($invoice);
    }




}
