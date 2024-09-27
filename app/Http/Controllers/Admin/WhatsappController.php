<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Whatsapp; 
use Illuminate\Support\Facades\Storage;


class WhatsappController extends Controller
{
    public function index()
    {
        $waData = DB::table('tbl_wa')->first();
        return view('content.whatsapp.indexwhatsapp', compact('waData'));
    }
    public function addWa(Request $request)
{
    $request->validate([
        'numberWa' => 'required|string|max:255',
        'messageWa' => 'required|string',
    ]);

    $numberWa = $request->input('numberWa');
    $messageWa = $request->input('messageWa');

    try {
        // Get existing data
        $existingData = Whatsapp::first();

        // Update or create the record using the Wa model
        Whatsapp::updateOrCreate(
            [],
            [
                'No_wa' => $numberWa,
                'Message_wa' => $messageWa,
                'updated_at' => now(),
                'created_at' => $existingData ? $existingData->created_at : now(),
            ]
        );

        $waData = Whatsapp::first(); // Fetch the updated data

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan',
            'data' => [
                'numberWa' => $waData->No_wa,
                'messageWa' => $waData->Message_wa,
            ]
        ], 200);
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
        $waData = Whatsapp::find($id); 

        if ($waData) {
            $waData->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } else {
            return response()->json(['status' => 'info', 'message' => 'Data tidak ditemukan'], 404);
        }
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
    }
}
}
