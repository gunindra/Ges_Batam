<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class SupirController extends Controller
{
    // public function index(Request $request)
    // {
    //     // Get the currently logged-in user's name
    //     $userName = Auth::user()->name;

    //     $listInvoice = DB::select("
    //         SELECT a.invoice_id, b.metode_pengiriman,
    //                b.no_invoice,
    //                d.nama_supir
    //         FROM tbl_pengantaran_detail AS a
    //         JOIN tbl_invoice AS b ON b.id = a.invoice_id
    //         JOIN tbl_pengantaran AS c ON c.id = a.pengantaran_id
    //         JOIN tbl_supir AS d ON d.id = c.supir_id
    //         WHERE a.bukti_pengantaran IS NULL
    //           AND a.tanda_tangan IS NULL
    //           AND b.metode_pengiriman = 'Delivery'
    //           AND d.nama_supir = ?
    //     ", [$userName]);

    //     return view('supir.indexsupir', [
    //         'listInvoice' => $listInvoice
    //     ]);
    // }

    public function index(Request $request)
    {

        $listInvoice = DB::table('tbl_pengantaran_detail as a')
        ->join('tbl_invoice as b', 'b.id', '=', 'a.invoice_id')
        ->join('tbl_pengantaran as c', 'c.id', '=', 'a.pengantaran_id')
        ->join('tbl_supir as d', 'd.id', '=', 'c.supir_id')
        ->join('tbl_pembeli as e', 'e.id', '=', 'b.pembeli_id')
        ->select(
            'a.invoice_id',
            'b.metode_pengiriman',
            'b.no_invoice',
            'd.nama_supir',
            'e.marking',
            'e.nama_pembeli'
        )
        ->whereNull('a.bukti_pengantaran')
        ->whereNull('a.tanda_tangan')
        ->where('b.metode_pengiriman', 'Delivery')
        ->get();

        $listPembayaran = DB::table('tbl_tipe_pembayaran')
        ->select('id', 'tipe_pembayaran')
        ->get();



        return view('supir.indexsupir', [
            'listInvoice' => $listInvoice,
            'listPembayaran'=> $listPembayaran
        ]);
    }

    public function tambahdata(Request $request)
    {
        $request->validate([
            'bukti_pengantaran' => 'nullable|mimes:jpg,jpeg,png',
            'selectedPayment' => 'required'
        ]);


        DB::beginTransaction();
        
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
                        $signaturePath = $signatureFile->storeAs('ttd_pengantaran', $signatureFilename, 'public');
                    } else {
                        $signaturePath = null;
                    }

                    if ($request->hasFile('photo')) {
                        $photoFile = $request->file('photo');
                        $photoFilename = time() . '_photo_' . $noInvoice . '.' . $photoFile->getClientOriginalExtension();
                        $photoPath = $photoFile->storeAs('bukti_pengantaran', $photoFilename, 'public');
                    } else {
                        $photoPath = null;
                    }
                    
                    $verifiedUsername = Auth::user()->name;

                    DB::table('tbl_pengantaran_detail')
                        ->where('invoice_id', $invoiceId)
                        ->update([
                            'bukti_pengantaran' => $photoPath,
                            'tanda_tangan' => $signaturePath,
                            'keterangan' => 'Barang Telah Selesai Di antarkan',
                            'updated_at' => now(),
                            'tanggal_penerimaan' => now(),
                            'createby' => $verifiedUsername,
                        ]);

                    $pengantaranDetails = DB::table('tbl_pengantaran_detail')
                        ->where('invoice_id', $invoiceId)
                        ->get();

                    $allCompleted = $pengantaranDetails->every(function ($detail) {
                        return !empty($detail->bukti_pengantaran) || !empty($detail->tanda_tangan);
                    });

                    if ($allCompleted) {
                        DB::table('tbl_invoice')
                            ->where('id', $invoiceId)
                            ->update([
                                'status_id' => 6,
                                'payment_type' => $request->input('selectedPayment'),
                                'updated_at' => now(),
                            ]);

                        $pengantaranId = DB::table('tbl_pengantaran_detail')
                            ->where('invoice_id', $invoiceId)
                            ->value('pengantaran_id');

                        if ($pengantaranId) {
                            DB::table('tbl_pengantaran')
                                ->where('id', $pengantaranId)
                                ->update([
                                    'updated_at' => now(),
                                ]);
                        }
                    }

                    $noResiList = DB::table('tbl_resi')
                        ->where('invoice_id', $invoiceId)
                        ->pluck('no_resi');

                    if ($noResiList->isNotEmpty()) {
                        DB::table('tbl_tracking')
                            ->whereIn('no_resi', $noResiList)
                            ->update([
                                'status' => 'Received',
                                'updated_at' => now(),
                            ]);
                    }

                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error("Error processing invoice_id {$invoiceId}: " . $e->getMessage());
                    return response()->json(['error' => 'Terjadi kesalahan saat memproses data.'], 500);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Data berhasil diupdate.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('General error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengupdate data.'], 500);
        }
    }



    // public function batalAntar(Request $request)
    // {
    //     $request->validate([
    //         'alasan' => 'required|string|max:255',
    //     ]);

    //     $invoiceIds = explode(',', $request->input('selectedValues'));

    //     DB::beginTransaction();

    //     try {
    //         foreach ($invoiceIds as $invoiceId) {
    //             try {
    //                 DB::table('tbl_pengantaran_detail')
    //                     ->where('invoice_id', $invoiceId)
    //                     ->update([
    //                         'keterangan' => $request->alasan,
    //                         'updated_at' => now(),
    //                     ]);
    //             } catch (\Exception $e) {
    //                 DB::rollBack();
    //                 \Log::error("Error processing invoice_id {$invoiceId}: " . $e->getMessage());
    //                 return response()->json(['error' => 'Terjadi kesalahan saat memproses penghapusan.'], 500);
    //             }
    //         }

    //         DB::commit(); // Commit if all updates are successful
    //         return response()->json(['message' => 'Data berhasil diperbarui.'], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack(); // Rollback if a general error occurs
    //         \Log::error('General error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data.'], 500);
    //     }
    // }


    public function batalAntar(Request $request)
    {
        $request->validate([
            'alasan' => 'nullable|string|max:255',
        ]);

        $invoiceIds = explode(',', $request->input('selectedValues'));

        DB::beginTransaction();

        try {
            foreach ($invoiceIds as $invoiceId) {
                // Ambil data pengantaran detail berdasarkan invoice_id
                $pengantaranDetail = DB::table('tbl_pengantaran_detail')
                    ->where('invoice_id', $invoiceId)
                    ->first();

                if (!$pengantaranDetail) {
                    throw new \Exception("Data pengantaran tidak ditemukan untuk invoice_id: " . $invoiceId);
                }

                $pengantaranId = $pengantaranDetail->pengantaran_id;

                // Hapus data dari tbl_pengantaran_details
                DB::table('tbl_pengantaran_detail')
                    ->where('invoice_id', $invoiceId)
                    ->delete();

                // Cek apakah masih ada pengantaran terkait, jika tidak hapus dari tbl_pengantaran
                $remainingDetails = DB::table('tbl_pengantaran_detail')
                    ->where('pengantaran_id', $pengantaranId)
                    ->count();

                if ($remainingDetails == 0) {
                    DB::table('tbl_pengantaran')
                        ->where('id', $pengantaranId)
                        ->delete();
                }

                // Kembalikan status tbl_invoice ke 1 (sebelum delivery)
                DB::table('tbl_invoice')
                    ->where('id', $invoiceId)
                    ->update(['status_id' => 1]);

                // Ambil semua no_resi dari tbl_resi berdasarkan invoice_id
                $resiList = DB::table('tbl_resi')
                    ->where('invoice_id', $invoiceId)
                    ->pluck('no_resi');

                // Kembalikan status tbl_tracking ke "Batam / Sortir"
                DB::table('tbl_tracking')
                    ->whereIn('no_resi', $resiList)
                    ->update(['status' => 'Batam / Sortir']);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pengantaran berhasil dibatalkan!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function jumlahresi(Request $request)
    {
        $invoiceIds = $request->input('invoice_ids');
        $count = DB::table('tbl_resi')
            ->whereIn('invoice_id', $invoiceIds)
            ->count();

            $totalHarga = DB::table('tbl_resi')
            ->whereIn('invoice_id', $invoiceIds)
            ->sum('harga');

        return response()->json([
            'count' => $count,
            'total_harga' => $totalHarga
        ]);
    }

}
