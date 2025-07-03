<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Customer;
use App\Models\HistoryTopup;
use App\Http\Controllers\Admin\JournalController;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\PricePoin;
use Illuminate\Support\Facades\DB;
use Str;
use Yajra\DataTables\Facades\DataTables;
use Log;

class TopupController extends Controller
{

    protected $jurnalController;

    public function __construct(JournalController $jurnalController)
    {
        $this->jurnalController = $jurnalController;
    }

    public function index(Request $request)
    {
        $coas = COA::whereNotNull('parent_id')
            ->where('set_as_group', 0)
            ->get();
        $listRateVolume = DB::select("SELECT id, nilai_rate, rate_for FROM tbl_rate");

        return view('topup.indextopup', [
            'coas' => $coas,
            'listRateVolume' => $listRateVolume,
        ]);
    }

    public function getPricePoints()
    {
        $prices = PricePoin::all();
        return response()->json($prices);
    }

    public function getCustomers()
    {
        $companyId = session('active_company_id');
        $customers = Customer::select('id', 'nama_pembeli', 'marking')
        ->where('tbl_pembeli.company_id', $companyId)
        ->get();
        return response()->json($customers);
    }
    public function getData(Request $request)
    {
        $companyId = session('active_company_id');
        $query = HistoryTopup::with(['customer', 'account'])
            ->leftJoin('tbl_coa', 'tbl_history_topup.account_id', '=', 'tbl_coa.id')
            ->leftJoin('tbl_pembeli', 'tbl_history_topup.customer_id', '=', 'tbl_pembeli.id')
            ->where('tbl_history_topup.company_id', $companyId)
            ->select([
                'tbl_history_topup.id',
                'tbl_pembeli.marking',
                'tbl_history_topup.code',
                'tbl_history_topup.customer_name',
                'tbl_history_topup.remaining_points',
                'tbl_history_topup.topup_amount',
                'tbl_history_topup.price_per_kg',
                'tbl_history_topup.account_id',
                'tbl_history_topup.date',
                'tbl_history_topup.expired_date',
                'tbl_history_topup.balance',
                'tbl_history_topup.status',
                'tbl_coa.name as account_name'
            ]);

        // Handle global search
        if ($request->has('search.value') && !empty($request->search['value'])) {
            $searchTerm = $request->search['value'];
            $query->where(function($q) use ($searchTerm) {
                $q->where('tbl_pembeli.marking', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('tbl_history_topup.customer_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('tbl_history_topup.code', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('tbl_coa.name', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Handle date range filter
        if ($request->has('startDate') && $request->has('endDate') && $request->startDate && $request->endDate) {
            $startDate = Carbon::parse($request->startDate)->startOfDay();
            $endDate = Carbon::parse($request->endDate)->endOfDay();
            $query->whereBetween('tbl_history_topup.date', [$startDate, $endDate]);
        }

        // Handle ordering
        if (!$request->has('order')) {
            $query->orderBy('tbl_history_topup.id', 'desc');
        } else {
            $order = $request->order[0];
            $column = $request->columns[$order['column']]['data'];
            $direction = $order['dir'];

            // Handle special cases for joined table columns
            switch ($column) {
                case 'marking':
                    $query->orderBy('tbl_pembeli.marking', $direction);
                    break;
                case 'account_name':
                    $query->orderBy('tbl_coa.name', $direction);
                    break;
                default:
                    // For all other columns, ensure they belong to tbl_history_topup
                    if (in_array($column, ['id', 'code', 'customer_name', 'remaining_points', 'topup_amount',
                        'price_per_kg', 'date', 'expired_date', 'balance', 'status'])) {
                        $query->orderBy('tbl_history_topup.'.$column, $direction);
                    }
                    break;
            }
        }

        return DataTables::of($query)
            ->editColumn('marking', function ($row) {
                return $row->marking ? $row->marking : 'Marking tidak tersedia';
            })
            ->editColumn('topup_amount', function ($row) {
                $total = $row->topup_amount;
                return 'Rp ' . number_format($total, 2);
            })
            ->editColumn('price_per_kg', function ($row) {
                return 'Rp ' . number_format($row->price_per_kg, 2);
            })
            ->editColumn('date', function ($row) {
                return $row->date ? Carbon::parse($row->date)->format('d F Y') : 'Tanggal tidak tersedia';
            })
            ->editColumn('expired_date', function ($row) {
                return $row->expired_date ? Carbon::parse($row->expired_date)->format('d F Y') : 'Expired date not available';
            })
            ->editColumn('status', function ($row) {
                if ($row->status === 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } elseif ($row->status === 'canceled') {
                    return '<span class="badge badge-secondary">Canceled</span>';
                } elseif ($row->status === 'expired') {
                    return '<span class="badge badge-danger">Expired</span>';
                } else {
                    return '<span class="badge badge-danger">Expired</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $buttons = '';

                // Cancel hanya kalau remaining_points == balance dan status active
                if ($row->remaining_points == $row->balance && $row->status === 'active') {
                    $buttons .= '<button class="btn btnCancelTopup btn-sm btn-danger" data-id="' . $row->id . '">Cancel</button>';
                }

                // Expired tetap muncul selama status active
                if ($row->status === 'active') {
                    $buttons .= '<button class="btn btnExpiredTopup btn-sm mt-1 btn-secondary" data-id="' . $row->id . '">Expired</button>';
                }

                return $buttons ?: '-'; // Kalau tidak ada tombol sama sekali, tampilkan '-'
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    public function storeTopup(Request $request)
    {
        $companyId = session('active_company_id');
        $request->validate([
            'customer_id' => 'required|exists:tbl_pembeli,id',
            'remaining_points' => 'required|numeric|min:1',
            'price_per_kg' => 'required|numeric|min:0.01',
            'coa_id' => 'required|exists:tbl_coa,id',
            'expired_date' => "required|date"
        ]);

        $formattedDate = Carbon::parse($request->date)->format('Y-m-d');
        $formattedDates = Carbon::parse($request->expired_date)->format('Y-m-d');


        DB::beginTransaction();

        try {
            $closedPeriod = DB::table('tbl_periode')
                ->whereDate('periode_start', '<=', $formattedDate)
                ->whereDate('periode_end', '>=', $formattedDate)
                ->where('status', 'Closed')
                ->first();

            if ($closedPeriod) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak dapat membuat invoice karena tanggal tersebut berada di dalam periode yang sudah ditutup: ' . $closedPeriod->periode,
                ], 400);
            }
            $customer = Customer::findOrFail($request->customer_id);
            Log::info("Sisa poin sebelum increment: " . $customer->sisa_poin);
            $topupAmount = $request->topupAmount;
            $topup = HistoryTopup::create([
                'customer_id' => $request->customer_id,
                'customer_name' => $customer->nama_pembeli,
                'topup_amount' => $topupAmount,
                'remaining_points' => $request->remaining_points,
                'price_per_kg' => $request->price_per_kg,
                'balance' => $request->remaining_points,
                'date' => $formattedDate,
                'account_id' => $request->coa_id,
                'code' => $request->code,
                'expired_date' => $formattedDates,
                'company_id' => $companyId,
            ]);

            $initialSisaPoin = $customer->sisa_poin ?? 0;

            $customer->increment('sisa_poin', $request->remaining_points);

            $updatedSisaPoin = Customer::where('id', $request->customer_id)->value('sisa_poin');
            Log::info("Sisa poin setelah increment (database): " . $updatedSisaPoin);

            if (round($updatedSisaPoin, 2) != round($initialSisaPoin + $request->remaining_points, 2)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan sisa poin. Transaksi dibatalkan.'
                ], 500);
            }


            // Proses jurnal
            $creditAccount = DB::table('tbl_account_settings')->value('purchase_profit_rate_account_id');
            if (!$creditAccount) {
                throw new \Exception("Pengaturan akun belum lengkap. Silakan periksa pengaturan akun di Account Setting.");
            }

            $request->merge(['code_type' => 'TU']);
            $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;

            $jurnal = new Jurnal();
            $jurnal->no_journal = $noJournal;
            $jurnal->tipe_kode = 'TU';
            $jurnal->tanggal = $formattedDate;
            $jurnal->no_ref = $topup->id;
            $jurnal->status = 'Approve';
            $jurnal->description = "Jurnal untuk Top-up Customer {$customer->nama_pembeli}";
            $jurnal->totaldebit = $topupAmount;
            $jurnal->totalcredit = $topupAmount;
            $jurnal->company_id = $companyId;
            $jurnal->save();

            $jurnalItemDebit = new JurnalItem();
            $jurnalItemDebit->jurnal_id = $jurnal->id;
            $jurnalItemDebit->code_account = $request->coa_id;
            $jurnalItemDebit->description = "Debit untuk Top-up Customer {$customer->nama_pembeli}";
            $jurnalItemDebit->debit = $topupAmount;
            $jurnalItemDebit->credit = 0;
            $jurnalItemDebit->save();

            $jurnalItemCredit = new JurnalItem();
            $jurnalItemCredit->jurnal_id = $jurnal->id;
            $jurnalItemCredit->code_account = $creditAccount;
            $jurnalItemCredit->description = "Kredit untuk Top-up Customer {$customer->nama_pembeli}";
            $jurnalItemCredit->debit = 0;
            $jurnalItemCredit->credit = $topupAmount;
            $jurnalItemCredit->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Top-up berhasil disimpan dan jurnal diperbarui', 'data' => $topup]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan top-up: ' . $e->getMessage()], 500);
        }
    }


    public function cancleTopup(Request $request)
    {
        $companyId = session('active_company_id');

        $request->validate([
            'topup_id' => 'required|exists:tbl_history_topup,id',
        ]);



        DB::beginTransaction();

        try {

            $topup = HistoryTopup::findOrFail($request->topup_id);
            $customer = Customer::findOrFail($topup->customer_id);

            if ($topup->status === 'canceled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Top-up ini sudah dibatalkan sebelumnya.'
                ], 400);
            }
            $topup->status = 'canceled';
            $topup->save();

            $customer->decrement('sisa_poin', $topup->remaining_points);

            $request->merge(['code_type' => 'TC']);
            $noJournal = $this->jurnalController->generateNoJurnal($request)->getData()->no_journal;
            $jurnal = new Jurnal();
            $jurnal->no_journal = $noJournal;
            $jurnal->tipe_kode = 'TC';
            $jurnal->tanggal = $topup->date;
            $jurnal->no_ref = $topup->code;
            $jurnal->status = 'Approve';
            $jurnal->description = "Pembatalan Top-up untuk Customer {$customer->nama_pembeli}";
            $jurnal->totaldebit = $topup->topup_amount;
            $jurnal->totalcredit = $topup->topup_amount;
            $jurnal->company_id = $companyId;
            $jurnal->save();

            $jurnalItemDebit = new JurnalItem();
            $jurnalItemDebit->jurnal_id = $jurnal->id;
            $jurnalItemDebit->code_account = $topup->account_id;
            $jurnalItemDebit->description = "Pembatalan debit untuk Top-up Customer {$customer->nama_pembeli}";
            $jurnalItemDebit->debit = 0;
            $jurnalItemDebit->credit = $topup->topup_amount;
            $jurnalItemDebit->save();

            $jurnalItemCredit = new JurnalItem();
            $jurnalItemCredit->jurnal_id = $jurnal->id;
            $jurnalItemCredit->code_account = DB::table('tbl_account_settings')->value('purchase_profit_rate_account_id');
            $jurnalItemCredit->description = "Pembatalan kredit untuk Top-up Customer {$customer->nama_pembeli}";
            $jurnalItemCredit->debit = $topup->topup_amount;
            $jurnalItemCredit->credit = 0;
            $jurnalItemCredit->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Top-up berhasil dibatalkan.',
                'data' => $topup
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan top-up: ' . $e->getMessage()
            ], 500);
        }
    }
    public function generateCodeVoucher()
    {
        $codeType = "VC";
        $currentYear = date('y');

        $lastVoucher = HistoryTopup::where('code', 'like', $codeType . $currentYear . '%')
            ->orderBy('code', 'desc')
            ->first();

        $newSequence = 1;
        if ($lastVoucher) {
            $lastSequence = intval(substr($lastVoucher->code, -4));
            $newSequence = $lastSequence + 1;
        }

        $code = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'status' => 'success',
            'code' => $code
        ]);
    }

    public function expireTopup(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Validasi input
            if (!is_numeric($id) || $id <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID top-up tidak valid.'
                ], 400);
            }

            $topup = HistoryTopup::findOrFail($id);

            // Validasi status top-up
            if ($topup->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Top-up tidak aktif atau sudah kedaluwarsa.'
                ], 400);
            }

            $customer = Customer::findOrFail($topup->customer_id);
            $companyId = session('active_company_id');

            // Validasi company ID
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company ID tidak ditemukan dalam session.'
                ], 400);
            }

            $initialBalance = $topup->balance;

            // Pastikan balance tidak negatif
            if ($initialBalance < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Balance top-up tidak valid.'
                ], 400);
            }

            // Update customer points
            $customer->sisa_poin = max(0, $customer->sisa_poin - $initialBalance);
            $customer->save();

            // Update top-up status
            $topup->expired_amount = $topup->balance;
            $topup->balance = 0;
            $topup->status = 'expired';
            $topup->save();


            // Buat jurnal
            $this->jurnalController->createExpiredTopupJurnal($topup, $customer, $companyId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Top-up berhasil di-expire.',
                'data' => [
                    'topup_id' => $topup->id,
                    'remaining_points' => $customer->sisa_poin
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data top-up atau customer tidak ditemukan.'
            ], 404);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to expire top-up: ' . $e->getMessage(), [
                'exception' => $e,
                'topup_id' => $id,
                'company_id' => session('active_company_id')
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengeksekusi expire top-up: ' . $e->getMessage()
            ], 500);
        }
    }

    public function topupNotification()
    {
        $now = Carbon::now();
        $companyId = session('active_company_id');
        $lowQuota = HistoryTopup::select([
                'tbl_history_topup.customer_id',
                'tp.marking',
                'tbl_history_topup.balance',
                'tbl_history_topup.remaining_points',
                DB::raw('(tbl_history_topup.balance / tbl_history_topup.remaining_points * 100) as percentage')
            ])
            ->join('tbl_pembeli as tp', 'tp.id', '=', 'tbl_history_topup.customer_id')
            ->whereIn('tbl_history_topup.id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('tbl_history_topup')
                    ->where('status', 'active')
                    ->groupBy('customer_id');
            })
            ->where('tbl_history_topup.status', 'active')
            ->where('tbl_history_topup.balance', '>', 0)
            ->whereRaw('tbl_history_topup.balance < 0.2 * tbl_history_topup.remaining_points')
            ->when($companyId, function($query) use ($companyId) {
                $query->where('tp.company_id', $companyId);
            })
            ->with(['customer' => function($query) {
                $query->select('id', 'marking', 'nama_pembeli');
            }])
            ->get();

        $nearingExpiry = HistoryTopup::where('status', 'active')
            ->whereDate('expired_date', '<=', $now->addMonth())
            ->with('customer')
            ->get();

        $notifications = [
            'low_quota' => $lowQuota,
            'nearing_expiry' => $nearingExpiry,
        ];

        return response()->json($notifications);
    }


}

