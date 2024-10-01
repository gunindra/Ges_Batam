<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Coa;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    public function index()
    {
        $groupAccounts = COA::select('id', 'code_account_id', 'name')->get();

        return view('accounting.coa.indexcoa', compact('groupAccounts'));
    }

    public function getlistcoa()
    {
        // Ambil parent utama yang tidak punya parent (parent_id is NULL)
        $data = COA::with('children')->whereNull('parent_id')->get();

        // Fungsi untuk membangun HTML dari list COA
        $buildList = function($items) use (&$buildList) {
            $html = '<ul>';
            foreach ($items as $item) {
                $html .= '<li class="pt-2">';
                $html .= '<a href="#" class="editCOA" data-id="' . $item->id . '">' . $item->code_account_id . ' - ' . $item->name . '</a>';
                $html .= '<a class="btn btndeleteCOA btn-sm ml-2 btn-danger text-white" data-id="' . $item->id . '" style="font-size: 12px;" "><i class="fas fa-trash"></i></a>';
                if ($item->children->isNotEmpty()) {
                    $html .= $buildList($item->children);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
            return $html;
        };

        // Bangun HTML dari list COA
        $output = $buildList($data);

        // Mengembalikan HTML sebagai respons JSON
        return response()->json(['html' => $output]);
    }



    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'code_account_id' => 'required|unique:tbl_coa,code_account_id',
            'name' => 'required',
            'default_position' => 'required',
        ]);

        // Tentukan apakah ini Parent atau Child
        $parent_id = $request->input('group_account');

        // Buat data COA baru
        $coa = new COA();
        $coa->code_account_id = $request->input('code_account_id');
        $coa->name = $request->input('name');
        $coa->description = $request->input('description');
        $coa->set_as_group = $request->has('setGroup') ? true : false;  // Cek apakah set sebagai grup
        $coa->default_posisi = $request->input('default_position');

        // Jika ini child, set parent_id
        if ($parent_id) {
            $coa->parent_id = $parent_id;
        }

        $coa->save();

        return response()->json(['success' => 'COA berhasil ditambahkan']);
    }

    public function destroy($id)
    {
        $coa = COA::findOrFail($id);
        $coa->delete();

        return response()->json(['success' => true]);
    }
    public function show($id)
    {
        $coa = COA::findOrFail($id);
        return response()->json($coa);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code_account_id' => 'required|unique:tbl_coa,code_account_id,' . $id,
            'name'            => 'required|string',
            'description'     => 'nullable|string',
            'set_as_group'    => 'required|boolean',
            'default_posisi'  => 'nullable|string',
            'parent_id'       => 'nullable|exists:tbl_coa,id',
        ]);

        $coa = COA::findOrFail($id);
        $coa->update($validated);
        return response()->json(['success' => true]);
    }


}
