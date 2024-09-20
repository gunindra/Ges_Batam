<?php

namespace App\Http\Controllers;

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
                    // Ambil no_invoice dari tbl_invoice berdasarkan invoice_id
                    $noInvoice = DB::table('tbl_invoice')
                        ->where('id', $invoiceId)
                        ->value('no_invoice');

                    if (!$noInvoice) {
                        throw new \Exception("No invoice found for invoice_id {$invoiceId}");
                    }

                    // Proses tanda tangan jika ada (dalam bentuk Blob)
                    if ($request->hasFile('signature')) {
                        $signatureFile = $request->file('signature');
                        // Gabungkan timestamp dan no_invoice sebagai nama file tanda tangan
                        $signatureFilename = time() . '_signature_' . $noInvoice . '.' . $signatureFile->getClientOriginalExtension();
                        $signaturePath = $signatureFile->storeAs('ttd_pengantaran', $signatureFilename, 'public'); // Simpan di folder ttd_pengantaran
                    } else {
                        $signaturePath = null;
                    }

                    // Proses foto jika ada
                    if ($request->hasFile('photo')) {
                        $photoFile = $request->file('photo');
                        // Gabungkan timestamp dan no_invoice sebagai nama file foto
                        $photoFilename = time() . '_photo_' . $noInvoice . '.' . $photoFile->getClientOriginalExtension();
                        $photoPath = $photoFile->storeAs('bukti_pengantaran', $photoFilename, 'public'); // Simpan di folder bukti_pengantaran
                    } else {
                        $photoPath = null;
                    }

                    // Update tbl_pengantaran_detail
                    DB::table('tbl_pengantaran_detail')
                        ->where('invoice_id', $invoiceId)
                        ->update([
                            'bukti_pengantaran' => $photoPath,
                            'tanda_tangan' => $signaturePath,
                            'updated_at' => now(),
                        ]);

                    // Update tbl_invoice, set status_id = 6
                    DB::table('tbl_invoice')
                        ->where('id', $invoiceId)
                        ->update([
                            'status_id' => 6,
                            'updated_at' => now(),
                        ]);

                    // Ambil pengantaran_id dari tbl_pengantaran_detail
                    $pengantaranId = DB::table('tbl_pengantaran_detail')
                        ->where('invoice_id', $invoiceId)
                        ->value('pengantaran_id');

                    if ($pengantaranId) {
                        // Update status pengiriman di tbl_pengantaran
                        DB::table('tbl_pengantaran')
                            ->where('id', $pengantaranId)
                            ->update([
                                'status_id' => 6,
                                'updated_at' => now(),
                            ]);
                    }

                    // Ambil no_resi dari tbl_resi berdasarkan invoice_id
                    $noResi = DB::table('tbl_resi')
                        ->where('invoice_id', $invoiceId)
                        ->value('no_resi');

                    if ($noResi) {
                        // Update data di tbl_tracking berdasarkan no_resi
                        DB::table('tbl_tracking')
                            ->where('no_resi', $noResi)
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

}
