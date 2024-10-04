<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Jurnal;
use App\Models\JurnalItem;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;


    class JournalController extends Controller
    {
        public function index()
    {
        return view('accounting.journal.indexjournal');
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

