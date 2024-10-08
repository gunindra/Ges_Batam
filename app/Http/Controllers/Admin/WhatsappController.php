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
        $waData = Whatsapp::first();
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

            $Whatsapp = Whatsapp::first();

             Whatsapp::updateOrCreate(
                ['id' => $Whatsapp ? $Whatsapp->id : null],
                [
                    'No_wa' => $numberWa,
                    'Message_wa' => $messageWa,
                ]
            );

            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan', 'data' => ['numberWa' => $numberWa, 'messageWa' => $messageWa,]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function destroyWa($id)
    {
        if (!$id) {
            return response()->json(['status' => 'warning', 'message' => 'Tidak dapat menghapus data.'], 400);
        }

        $waData = Whatsapp::find($id);

        if ($waData) {
            $waData->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } else {
            return response()->json(['status' => 'info', 'message' => 'Data tidak ditemukan'], 404);
        }
    }
}
