<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\COA;
use App\Models\Jurnal;
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
            ->select('id', 'no_journal', 'tipe_kode', 'tanggal', 'no_ref','status','description')
            ->where(function ($query) use ($txSearch) {
                $query->where(DB::raw('UPPER(no_journal)'), 'LIKE', $txSearch)
                      ->orWhere(DB::raw('UPPER(tipe_kode)'), 'LIKE', $txSearch)
                      ->orWhere(DB::raw('UPPER(description)'), 'LIKE', $txSearch);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->get();
    
        $output = '
            <table class="table align-items-center table-flush table-hover" id="tableJournal">
                <thead class="thead-light">
                    <tr>
                        <th>No. Journal</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
    
        foreach ($data as $item) {
            $output .= '
                <tr>
                    <td>' . ($item->no_journal ?? '-') . '</td>
                    <td>' . ($item->description ?? '-') . '</td>
                    <td>' . ($item->tanggal ?? '-') . '</td>
                     <td>' . ($item->status ?? '-') . '</td>
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




}

