<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

    class JournalController extends Controller
    {
        public function index()
    {
        return view('accounting.journal.indexjournal');
    }
    public function getlistJournal(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $status = $request->status;
        $startDate = $request->startDate ? date('Y-m-d', strtotime($request->startDate)) : null;
        $endDate = $request->endDate ? date('Y-m-d', strtotime($request->endDate)) : null;

        $data = DB::table('tbl_jurnal')
        ->select(
            'id',
            'no_journal',
            'tipe_kode',
            DB::raw("DATE_FORMAT(tanggal, '%d %M %Y') AS tanggal"),
            'no_ref',
            'totalcredit',
            'status',
            'description'
        )
            ->where(function ($query) use ($txSearch) {
                $query->where(DB::raw('UPPER(no_journal)'), 'LIKE', $txSearch)
                      ->orWhere(DB::raw('UPPER(tipe_kode)'), 'LIKE', $txSearch)
                      ->orWhere(DB::raw('UPPER(description)'), 'LIKE', $txSearch);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->get();
        // dd($data);

        $output = '
            <table class="table align-items-center table-flush table-hover" id="tableJournal">
                <thead class="thead-light">
                    <tr>
                        <th>No. Journal</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($data as $item) {

            $statusBadgeClass = '';


            switch ($item->status) {
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

            $output .= '
                <tr>
                    <td>' . ($item->no_journal ?? '-') . '</td>
                    <td>' . ($item->description ?? '-') . '</td>
                    <td>' . ($item->tanggal ?? '-') . '</td>
                  <td>' . number_format($item->totalcredit ?? 0, 0, ',', '.') . '</td>
                    <td><span class="badge ' . $statusBadgeClass . '">' . ($item->status ?? '-') . '</span></td>
                    <td>
                       <a class="btn btnUpdateJournal btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-no_journal="' . $item->no_journal . '" data-description="' . $item->description . '" data-tanggal="' . $item->tanggal . '" data-status="' . $item->status . '"><i class="fas fa-edit"></i></a>
                         <a class="btn btnDestroyJournal btn-sm btn-danger text-white" data-id="' . $item->id . '"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>';
        }

        $output .= '</tbody></table>';
        return $output;
    }


    public function addjournal()
    {

        $coas = COA::all();

        return view('accounting.journal.indexbuatjournal',compact('coas'));
    }


    public function generateNoJurnal(Request $request)
    {
        $kodetype = $request->code_type;


        $currentYear = date('y');


        $lastJournal = Jurnal::where('tipe_kode', $kodetype)
            ->where('no_journal', 'like', $kodetype . $currentYear . '%')
            ->orderBy('no_journal', 'desc')
            ->first();


        if ($lastJournal) {

            $lastNoJournal = $lastJournal->no_journal;


            $lastSequence = intval(substr($lastNoJournal, -4));
            $newSequence = $lastSequence + 1;
        } else {

            $newSequence = 1;
        }


        $newNoJournal = $kodetype . $currentYear . str_pad($newSequence, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'no_journal' => $newNoJournal
        ]);
    }


    public function store(Request $request)
{

    // Validasi jika diperlukan
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
        // Simpan data ke tbl_jurnal
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

        // Simpan setiap item ke tbl_jurnal_items
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









}

