<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\COA;
use App\Models\CreditNote;

class CreditNoteController extends Controller
{
    public function index() {


        return view('customer.creditnote.indexcreditnote');
    }



    public function addCreditNote()
    {

        $coas = COA::all();
        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $listInvoice = DB::select("SELECT id, no_invoice FROM tbl_invoice");


        return view('customer.creditnote.buatcreditnote', [
            'listCurrency' => $listCurrency,
            'coas' => $coas,
            'listInvoice' => $listInvoice
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'invoiceCredit' => 'required|string|max:255',
            'accountCredit' => 'required|string|max:255',
            'currencyCredit' => 'required|string|max:10',
            'rateCurrency' => 'nullable|numeric',
            'noteCredit' => 'nullable|string',
            'items' => 'required|array',
            'items.*.noresi' => 'required|string|max:255',
            'items.*.deskripsi' => 'required|string|max:255',
            'items.*.harga' => 'required|numeric',
            'items.*.jumlah' => 'required|numeric',
            'items.*.total' => 'required|numeric',
            'totalKeseluruhan' => 'required|numeric',
        ]);

        $creditNote = new CreditNote();
        $creditNote->invoice_id = $request->invoiceCredit;
        $creditNote->account_id = $request->accountCredit;
        $creditNote->matauang_id = $request->currencyCredit;
        $creditNote->rate_currency = $request->rateCurrency;
        $creditNote->note = $request->noteCredit;
        $creditNote->total_keseluruhan = $request->totalKeseluruhan;
        $creditNote->save();


        foreach ($request->items as $item) {
            $creditNote->items()->create([
                'no_resi' => $item['noresi'],
                'deskripsi' => $item['deskripsi'],
                'harga' => $item['harga'],
                'jumlah' => $item['jumlah'],
                'total' => $item['total'],
            ]);
        }

        return response()->json(['message' => 'Credit note berhasil disimpan!'], 200);
    }


}
