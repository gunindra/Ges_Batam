<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Jurnal;
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




}

