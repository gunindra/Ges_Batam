<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WhatsappController extends Controller
{
    public function index() {

        $waData = DB::table('tbl_wa')->first();
        return view('content.whatsapp.indexwhatsapp', compact('waData'));
    }
    public function addWa(Request $request)
    {
        $request->validate([
            'nomorWa' => 'required|string|max:255', 
            'pesanWa' => 'required|string', 
        ]);
        $nomorWa = $request->input('nomorWa');
        $pesanWa = $request->input('pesanWa');
    
        try {
            $existingData = DB::table('tbl_wa')->first();
    
            if ($existingData) {
                // Update existing record
                DB::table('tbl_wa')->update([
                    'No_wa' => $nomorWa,
                    'pesan_wa' => $pesanWa,
                    'updated_at' => now(),
                ]);
                $id = $existingData->id; // Use existing ID
            } else {
                // Insert new record
                $id = DB::table('tbl_wa')->insertGetId([
                    'No_wa' => $nomorWa,
                    'pesan_wa' => $pesanWa,
                    'created_at' => now(),
                ]);
            }
            
            $waData = DB::table('tbl_wa')->where('id', $id)->first();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => [
                    'id' => $waData->id,
                    'nomorWa' => $waData->No_wa,
                    'pesanWa' => $waData->pesan_wa
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }


    public function destroyWa(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['status' => 'warning', 'message' => 'Tidak dapat menghapus data.'], 400);
        }

        try {
            $deleted = DB::table('tbl_wa')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
            } else {
                return response()->json(['status' => 'info', 'message' => 'Data tidak ditemukan'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
