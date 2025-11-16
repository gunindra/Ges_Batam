<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Customer;
use App\Models\HistoryTopup;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class JournalController extends Controller
{
    public function index()
    {
        $uniqueStatuses = DB::table('tbl_jurnal')
            ->select('status')
            ->distinct()
            ->get();

        return view('accounting.journal.indexjournal', compact('uniqueStatuses'));
    }

    public function getjournalData(Request $request)
    {
        $companyId = session('active_company_id');

        $query = DB::table('tbl_jurnal')
            ->select(
                'id',
                'no_journal',
                'tipe_kode',
                'tanggal',
                DB::raw("DATE_FORMAT(tanggal, '%d %M %Y') AS tanggalFormat"),
                'no_ref',
                'totalcredit',
                'status',
                'description'
            )
            ->where('tbl_jurnal.company_id', $companyId);
            // ->orderBy('id', 'desc');

        if ($request->tipe_kode) {
            $query->where('tipe_kode', $request->tipe_kode);
        }

        if ($request->excludeTypes && is_array($request->excludeTypes)) {
            $query->whereNotIn('tipe_kode', $request->excludeTypes);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->startDate && $request->endDate) {
            $startDate = date('Y-m-d', strtotime($request->startDate));
            $endDate = date('Y-m-d', strtotime($request->endDate));
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        if (!$request->has('order')) {
            $query->orderBy('id', 'desc');
        } else {
            $order = $request->order[0];
            $column = $request->columns[$order['column']]['data'];
            $direction = $order['dir'];

            $query->orderBy($column, $direction);
        }

        // Integrasi dengan DataTables
        return DataTables::of($query)
            ->filterColumn('tanggalFormat', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(tanggal, '%d %M %Y') LIKE ?", ["%{$keyword}%"]);
            })
            ->editColumn('totalcredit', function ($row) {
                return 'Rp ' . number_format($row->totalcredit, 0, ',', '.');
            })
            ->editColumn('tanggal', function ($row) {
                return $row->tanggal;
            })
            ->editColumn('status', function ($row) {
                $statusBadgeClass = '';
                switch ($row->status) {
                    case 'Approve':
                        $statusBadgeClass = 'badge-success';
                        break;
                    case 'Draft':
                        $statusBadgeClass = 'badge-primary';
                        break;
                    default:
                        $statusBadgeClass = 'badge-secondary';
                        break;
                }
                return '<span class="badge ' . $statusBadgeClass . '">' . $row->status . '</span>';
            })
            ->addColumn('action', function ($row) {
                $periodStatus = DB::table('tbl_periode')
                    ->whereDate('periode_start', '<=', $row->tanggal)
                    ->whereDate('periode_end', '>=', $row->tanggal)
                    ->value('status');

                if ($periodStatus == 'Closed') {
                    return '<button class="btn btn-info btn-sm btnViewJurnal" data-id="' . $row->id . '">Lihat Jurnal</button>';
                }
                $btnEditInvoice = '<a class="btn btnUpdateJournal btn-sm btn-secondary" data-id="' . $row->id . '"><i class="fas fa-edit text-white"></i></a>';

                return $btnEditInvoice . '
                    <a class="btn btnDestroyJournal btn-sm btn-danger" data-id="' . $row->id . '"><i class="fas fa-trash text-white"></i></a>
                ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }


    public function showJurnalDetail($id)
    {
        $jurnal = Jurnal::with('items.coa')
            ->where('id', $id)
            ->first();


        if (!$jurnal) {
            return response()->json(['error' => 'Data not found.'], 404);
        }

        return response()->json($jurnal);
    }



    public function addjournal()
    {

          $coas = COA::whereNotNull('parent_id')
            ->where('set_as_group', 0)
            ->get();

        return view('accounting.journal.indexbuatjournal', compact('coas'));
    }

    public function generateNoJurnal(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $kodetype = $request->code_type; // e.g. AR
            $currentYear = date('y');        // e.g. 25
            $prefix = $kodetype . $currentYear;

            // Sequence starts after the prefix
            $prefixLength = strlen($prefix) + 1;

            $lastSequence = Jurnal::where('tipe_kode', $kodetype)
                ->where('no_journal', 'like', $prefix . '%')
                ->lockForUpdate()
                ->max(DB::raw("CAST(SUBSTRING(no_journal, {$prefixLength}) AS UNSIGNED)"));

            $newSequence = ($lastSequence ?? 0) + 1;

            // Always pad to 4 minimum, but supports >9999 safely
            $sequencePadded = str_pad($newSequence, 4, '0', STR_PAD_LEFT);

            $newNoJournal = $prefix . $sequencePadded;

            return response()->json([
                'no_journal' => $newNoJournal
            ]);
        });
    }


    public function store(Request $request)
    {
        $companyId = session('active_company_id');
        $validatedData = $request->validate([
            'tanggalJournal' => 'required|date',
            'codeType' => 'required|string|max:10',
            'noJournal' => 'required|string|max:20|unique:tbl_jurnal,no_journal',
            // 'noRef' => 'required|string',
            'descriptionJournal' => 'nullable|string',
            'items' => 'required|array',
            'items.*.account' => 'required|integer',
            'items.*.item_desc' => 'required|string',
            'items.*.debit' => 'required|numeric',
            'items.*.credit' => 'required|numeric',
            'totalDebit' => 'required|numeric',
            'totalCredit' => 'required|numeric',
        ]);
        $tanggal = Carbon::createFromFormat('d F Y', $request->tanggalJournal)->format('Y-m-d');
        DB::beginTransaction();
        try {
            $jurnal = new Jurnal();
            $jurnal->no_journal = $request->noJournal;
            $jurnal->tipe_kode = $request->codeType;
            $jurnal->tanggal = $tanggal;
            $jurnal->no_ref = $request->noRef;
            $jurnal->status = $request->status;
            $jurnal->description = $request->descriptionJournal;
            $jurnal->totaldebit = $request->totalDebit;
            $jurnal->totalcredit = $request->totalCredit;
            $jurnal->company_id = $companyId;
            $jurnal->save();
            foreach ($request->items as $item) {
                $jurnalItem = new JurnalItem();
                $jurnalItem->jurnal_id = $jurnal->id;
                $jurnalItem->code_account = $item['account'];
                $jurnalItem->description = $item['item_desc'];
                $jurnalItem->debit = $item['debit'];
                $jurnalItem->credit = $item['credit'];
                $jurnalItem->memo = $item['memo'];
                $jurnalItem->save();
            }
            DB::commit();
            return response()->json(['message' => 'Journal saved successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to save journal: ' . $e->getMessage()], 500);
        }
    }
    public function updatejournal($id)
    {
        $journal = Jurnal::with('items')->find($id);
        $coas = COA::whereNotNull('parent_id')
            ->where('set_as_group', 0)
            ->get();
        return view('accounting.journal.indexupdatejournal', compact('journal', 'coas'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tanggalJournal' => 'required|date',
            'codeType' => 'required|string|max:10',
            'noJournal' => 'required|string|max:20',
            // 'noRef' => 'required|string',
            'descriptionJournal' => 'nullable|string',
            'items' => 'required|array',
            'items.*.account' => 'required|integer',
            'items.*.item_desc' => 'required|string',
            'items.*.debit' => 'required|numeric',
            'items.*.credit' => 'required|numeric',
            'totalDebit' => 'required|numeric',
            'totalCredit' => 'required|numeric',
        ]);
        $tanggal = Carbon::createFromFormat('d F Y', $request->tanggalJournal)->format('Y-m-d');
        DB::beginTransaction();
        try {
            $jurnal = Jurnal::findOrFail($id);
            $jurnal->no_journal = $request->noJournal;
            $jurnal->tipe_kode = $request->codeType;
            $jurnal->tanggal = $tanggal;
            $jurnal->no_ref = $request->noRef;
            $jurnal->status = $request->status;
            $jurnal->description = $request->descriptionJournal;
            $jurnal->totaldebit = $request->totalDebit;
            $jurnal->totalcredit = $request->totalCredit;
            $jurnal->save();
            $jurnal->items()->delete();
            foreach ($request->items as $item) {
                $jurnalItem = new JurnalItem();
                $jurnalItem->jurnal_id = $jurnal->id;
                $jurnalItem->code_account = $item['account'];
                $jurnalItem->description = $item['item_desc'];
                $jurnalItem->debit = $item['debit'];
                $jurnalItem->credit = $item['credit'];
                $jurnalItem->memo = $item['memo'];
                $jurnalItem->save();
            }
            DB::commit();
            return response()->json(['message' => 'Journal updated successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to update journal: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $jurnal = Jurnal::findOrFail($id);
            JurnalItem::where('jurnal_id', $jurnal->id)->delete();
            $jurnal->delete();
            DB::commit();
            return response()->json(['message' => 'Journal deleted successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to delete journal: ' . $e->getMessage()], 500);
        }
    }
    public function generateNoJournalBKK()
    {
        $codeType = "BKK";
        $currentYear = date('y');

        $lastBKK = Jurnal::where('no_journal', 'like', $codeType . $currentYear . '%')
            ->orderBy('no_journal', 'desc')
            ->first();

        $newSequence = 1;
        if ($lastBKK) {
            $lastSequence = intval(substr($lastBKK->no_journal, -4));
            $newSequence = $lastSequence + 1;
        }

        $no_journal = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'status' => 'success',
            'no_journal' => $no_journal
        ]);
    }
    public function generateNoJournalBKM()
    {
        $codeType = "BKM";
        $currentYear = date('y');

        $lastBKM = Jurnal::where('no_journal', 'like', $codeType . $currentYear . '%')
            ->orderBy('no_journal', 'desc')
            ->first();

        $newSequence = 1;
        if ($lastBKM) {
            $lastSequence = intval(substr($lastBKM->no_journal, -4));
            $newSequence = $lastSequence + 1;
        }

        $no_journal = $codeType . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'status' => 'success',
            'no_journal' => $no_journal
        ]);
    }


    public function createExpiredTopupJurnal(HistoryTopup $topup, Customer $customer, $companyId, $journalDate = null)
    {
        DB::beginTransaction();
        try {
            // Validasi input
            if (!$topup || !$customer || !$companyId) {
                throw new \Exception("Data topup, customer, atau company tidak valid");
            }

            // Hitung amount yang akan digunakan untuk jurnal
            $amount = 0;

            if ($topup->remaining_points == $topup->balance) {
                // Jika remaining_points dan balance sama, gunakan topup_amount
                $amount = $topup->topup_amount;
            } else {
                // Jika berbeda, hitung dari balance * price_per_kg
                if (!is_numeric($topup->expired_amount)) {
                    throw new \Exception("Balance topup tidak valid");
                }
                if (!is_numeric($topup->price_per_kg) || $topup->price_per_kg <= 0) {
                    throw new \Exception("Harga per kg tidak valid");
                }

                $amount = $topup->expired_amount * $topup->price_per_kg;
            }



            // Generate nomor jurnal
            $fakeRequest = new \Illuminate\Http\Request();
            $fakeRequest->merge(['code_type' => 'JU']);
            $noJournal = $this->generateNoJurnal($fakeRequest)->getData()->no_journal;

            // Buat entri jurnal utama
            $jurnal = new Jurnal();
            $jurnal->no_journal = $noJournal;
            $jurnal->tipe_kode = 'JU';
            $jurnal->tanggal = $journalDate ?? now();
            $jurnal->no_ref = $topup->code;
            $jurnal->status = 'Approve';
            $jurnal->description = "Expired Top-up untuk Customer {$customer->nama_pembeli}";
            $jurnal->totaldebit = $amount;
            $jurnal->totalcredit = $amount;
            $jurnal->company_id = $companyId;
            $jurnal->save();

            // Buat jurnal item debit
            $jurnalItemDebit = new JurnalItem();
            $jurnalItemDebit->jurnal_id = $jurnal->id;
            $jurnalItemDebit->code_account = $topup->account_id;
            $jurnalItemDebit->description = "Expired debit untuk Top-up Customer {$customer->nama_pembeli}";
            $jurnalItemDebit->debit = 0;
            $jurnalItemDebit->credit = $amount;
            $jurnalItemDebit->save();

            // Buat jurnal item kredit
            $profitAccountId = DB::table('tbl_account_settings')->value('purchase_profit_rate_account_id');
            if (!$profitAccountId) {
                throw new \Exception("Account untuk profit rate tidak ditemukan");
            }

            $jurnalItemCredit = new JurnalItem();
            $jurnalItemCredit->jurnal_id = $jurnal->id;
            $jurnalItemCredit->code_account = $profitAccountId;
            $jurnalItemCredit->description = "Expired kredit untuk Top-up Customer {$customer->nama_pembeli}";
            $jurnalItemCredit->debit = $amount;
            $jurnalItemCredit->credit = 0;
            $jurnalItemCredit->save();
            DB::commit();
            return $jurnal; // Mengembalikan data jurnal yang dibuat

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Gagal membuat jurnal expired topup: ' . $e->getMessage(), [
                'topup_id' => $topup->id ?? null,
                'customer_id' => $customer->id ?? null,
                'company_id' => $companyId
            ]);
            throw $e; // Re-throw exception agar bisa ditangkap oleh caller
        }
    }
}



