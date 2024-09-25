<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SupirController extends Controller
{
    public function index(Request $request)
    {
    $listInvoice = DB::select("SELECT a.invoice_id,
                                             b.no_invoice
                                        FROM tbl_pengantaran_detail AS a
                                        JOIN tbl_invoice AS b ON b.id = a.invoice_id
                                        WHERE a.bukti_pengantaran IS NULL AND a.tanda_tangan IS NULL");

        return view('supir.indexsupir', [
            'listInvoice' => $listInvoice
        ]);
    }
    public function tambahdata(Request $request)
    {
        try {
            $invoiceIds = explode(',', $request->input('selectedValues'));

            foreach ($invoiceIds as $invoiceId) {
                try {

                    $noInvoice = DB::table('tbl_invoice')
                        ->where('id', $invoiceId)
                        ->value('no_invoice');

                    if (!$noInvoice) {
                        throw new \Exception("No invoice found for invoice_id {$invoiceId}");
                    }


                    if ($request->hasFile('signature')) {
                        $signatureFile = $request->file('signature');
                        $signatureFilename = time() . '_signature_' . $noInvoice . '.' . $signatureFile->getClientOriginalExtension();
                        $signaturePath = $signatureFile->storeAs('ttd_pengantaran', $signatureFilename, 'public'); // Simpan di folder ttd_pengantaran
                    } else {
                        $signaturePath = null;
                    }


                    if ($request->hasFile('photo')) {
                        $photoFile = $request->file('photo');
                        $photoFilename = time() . '_photo_' . $noInvoice . '.' . $photoFile->getClientOriginalExtension();
                        $photoPath = $photoFile->storeAs('bukti_pengantaran', $photoFilename, 'public'); // Simpan di folder bukti_pengantaran
                    } else {
                        $photoPath = null;
                    }

                    DB::table('tbl_pengantaran_detail')
                        ->where('invoice_id', $invoiceId)
                        ->update([
                            'bukti_pengantaran' => $photoPath,
                            'tanda_tangan' => $signaturePath,
                            'updated_at' => now(),
                        ]);

                    DB::table('tbl_invoice')
                        ->where('id', $invoiceId)
                        ->update([
                            'status_id' => 6,
                            'updated_at' => now(),
                        ]);

                    $pengantaranId = DB::table('tbl_pengantaran_detail')
                        ->where('invoice_id', $invoiceId)
                        ->value('pengantaran_id');

                    if ($pengantaranId) {
                        DB::table('tbl_pengantaran')
                            ->where('id', $pengantaranId)
                            ->update([
                                'status_id' => 6,
                                'updated_at' => now(),
                            ]);
                    }

                    $noResiList = DB::table('tbl_resi')
                        ->where('invoice_id', $invoiceId)
                        ->pluck('no_resi');

                    if ($noResiList->isNotEmpty()) {

                        DB::table('tbl_tracking')
                            ->whereIn('no_resi', $noResiList)
                            ->update([
                                'status' => 'Done',
                                'updated_at' => now(),
                            ]);
                    }

                } catch (\Exception $e) {
                    \Log::error("Error processing invoice_id {$invoiceId}: " . $e->getMessage());
                    return response()->json(['error' => 'Terjadi kesalahan saat memproses data.'], 500);
                }
            }

            return response()->json(['message' => 'Data berhasil diupdate.'], 200);

        } catch (\Exception $e) {
            \Log::error('General error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengupdate data.'], 500);
        }
    }

    public function jumlahresi(Request $request)
    {
        $invoiceIds = $request->input('invoice_ids');
        $count = DB::table('tbl_resi')
                    ->whereIn('invoice_id', $invoiceIds)
                    ->count();
        return response()->json(['count' => $count]);
    }

}
