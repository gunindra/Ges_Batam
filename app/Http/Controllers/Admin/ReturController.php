<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Resi;
use App\Models\Retur;
use App\Models\ReturItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\COA;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Admin\JournalController;
use App\Models\Jurnal;
use App\Models\JurnalItem;


class ReturController extends Controller
{
    public function index()
    {
        $listStatus = DB::select("SELECT b.name
            FROM tbl_credit_note AS a
            JOIN tbl_coa AS b ON b.id = a.account_id
            GROUP BY a.account_id, b.name");

        return view('customer.retur.indexretur',  [
            'listStatus' => $listStatus]);
    }



    public function tambahRetur()
    {
        $companyId = session('active_company_id');
        $coas = COA::all();
        $listCurrency = DB::select("SELECT id, nama_matauang, singkatan_matauang FROM tbl_matauang");
        $listInvoice = DB::select("SELECT
                                                tbl_invoice.id,
                                                tbl_invoice.no_invoice,
                                                tbl_pembeli.marking,
                                                tbl_pembeli.nama_pembeli
                                            FROM tbl_invoice
                                            JOIN tbl_pembeli ON tbl_invoice.pembeli_id = tbl_pembeli.id
                                            WHERE tbl_invoice.company_id = $companyId
                                        ");

        return view('customer.retur.buatretur', [
            'listCurrency' => $listCurrency,
            'coas' => $coas,
            'listInvoice' => $listInvoice
        ]);
    }


    public function listresi(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:tbl_invoice,id',
        ]);

        $invoiceId = $request->input('invoice_id');
        $search = $request->input('search');

        // Ambil semua resi yang termasuk invoice tersebut dan belum pernah diretur
        $query = Resi::where('invoice_id', $invoiceId)
            ->whereDoesntHave('returItem') // hanya resi yang belum pernah diretur
            ->when($search, function ($q) use ($search) {
                $q->where('no_resi', 'like', '%' . $search . '%');
            })
            ->select('id', 'no_resi', 'harga')
            ->get();

        return response()->json($query);
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $validated = $request->validate([
            // 'no_retur' => 'required|string|unique:tbl_retur,no_retur',
            'invoice_id' => 'required|exists:tbl_invoice,id',
            'currency_id' => 'required|exists:tbl_matauang,id',
            'account_id' => 'required|exists:tbl_coa,id',
            'deskripsi' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.resi_id' => 'required|exists:tbl_resi,id',
        ]);

        $resiIds = collect($validated['items'])->pluck('resi_id');

        // Validasi: resi_id tidak boleh sudah pernah dipakai
        $usedResi = ReturItem::whereIn('resi_id', $resiIds)->pluck('resi_id')->toArray();
        if (count($usedResi)) {
            return response()->json([
                'error' => 'Resi berikut sudah digunakan di retur lain.',
                'resi_id' => $usedResi,
            ], 422);
        }




        DB::beginTransaction();
        try {
            $totalNominal = Resi::whereIn('id', $resiIds)->sum('harga');

            $retur = Retur::create([
                // 'no_retur' => $validated['no_retur'],
                'invoice_id' => $validated['invoice_id'],
                'currency_id' => $validated['currency_id'],
                'account_id' => $validated['account_id'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'total_nominal' => $totalNominal,
            ]);

            foreach ($resiIds as $resiId) {
                ReturItem::create([
                    'retur_id' => $retur->id,
                    'resi_id' => $resiId,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Retur berhasil disimpan.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan retur.'], 500);
        }
    }


    // UPDATE
    public function update(Request $request, $id)
    {
        $retur = Retur::with('items')->findOrFail($id);

        $validated = $request->validate([
            // 'no_retur' => 'required|string|unique:tbl_retur,no_retur,' . $retur->id,
            'invoice_id' => 'required|exists:tbl_invoice,id',
            'currency_id' => 'required|exists:tbl_matauang,id',
            'account_id' => 'required|exists:tbl_coa,id',
            'deskripsi' => 'nullable|string',
            'items' => 'required|array',
            'items.*.resi_id' => 'required|exists:tbl_resi,id',
        ]);

        DB::beginTransaction();
        try {
            $retur->update([
                // 'no_retur' => $validated['no_retur'],
                'invoice_id' => $validated['invoice_id'],
                'currency_id' => $validated['currency_id'],
                'account_id' => $validated['account_id'],
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);

            // Hapus item lama
            $retur->items()->delete();

            // Simpan ulang item baru
            foreach ($validated['items'] as $item) {
                $retur->items()->create([
                    'resi_id' => $item['resi_id'],
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Retur updated successfully', 'data' => $retur->load('items')], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update retur', 'message' => $th->getMessage()], 500);
        }
    }






}
