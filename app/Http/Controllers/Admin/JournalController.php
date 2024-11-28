<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            ->orderBy('id','desc');

        // Filter by tipe_kode
        if ($request->tipe_kode) {
            $query->where('tipe_kode', $request->tipe_kode);
        }

        if ($request->excludeTypes && is_array($request->excludeTypes)) {
            $query->whereNotIn('tipe_kode', $request->excludeTypes);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->excludeTypes && is_array($request->excludeTypes)) {
            $query->whereNotIn('tipe_kode', $request->excludeTypes);
        }

        if ($request->startDate && $request->endDate) {
            $startDate = date('Y-m-d', strtotime($request->startDate));
            $endDate = date('Y-m-d', strtotime($request->endDate));
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        return DataTables::of($query)
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
                    return '-';
                }
                $btnEditInvoice = '<a class="btn btnUpdateJournal btn-sm btn-secondary" data-id="' . $row->id . '"><i class="fas fa-edit text-white"></i></a>';

                return $btnEditInvoice . '
                    <a class="btn btnDestroyJournal btn-sm btn-danger" data-id="' . $row->id . '"><i class="fas fa-trash text-white"></i></a>
                ';
            })

            ->rawColumns(['status', 'action'])
            ->make(true);
    }



    public function addjournal()
    {

        $coas = COA::all();

        return view('accounting.journal.indexbuatjournal', compact('coas'));
    }

    public function generateNoJurnal(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $kodetype = $request->code_type;
            $currentYear = date('y');
            $foundDuplicate = true;
            $attempt = 0;

            while ($foundDuplicate) {
                $lastJournal = Jurnal::where('tipe_kode', $kodetype)
                    ->where('no_journal', 'like', $kodetype . $currentYear . '%')
                    ->lockForUpdate()
                    ->orderBy('no_journal', 'desc')
                    ->first();

                $newSequence = $lastJournal ? intval(substr($lastJournal->no_journal, -4)) + 1 + $attempt : 1 + $attempt;
                $newNoJournal = $kodetype . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);


                $exists = Jurnal::where('no_journal', $newNoJournal)->exists();
                if (!$exists) {
                    $foundDuplicate = false;
                } else {
                    $attempt++;
                }
            }

            return response()->json([
                'no_journal' => $newNoJournal
            ]);
        });
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggalJournal' => 'required|date',
            'codeType' => 'required|string|max:10',
            'noJournal' => 'required|string|max:20|unique:tbl_jurnal,no_journal',
            'noRef' => 'required|string|max:20',
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
        $coas = COA::all();
        return view('accounting.journal.indexupdatejournal', compact('journal', 'coas'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tanggalJournal' => 'required|date',
            'codeType' => 'required|string|max:10',
            'noJournal' => 'required|string|max:20',
            'noRef' => 'required|string|max:20',
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
}



