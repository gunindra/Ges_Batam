<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\COA;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    public function index()
    {
        $groupAccounts = COA::select('id', 'code_account_id', 'name')
        ->where('set_as_group', 1)
        ->get();

        return view('accounting.coa.indexcoa', compact('groupAccounts'));
    }

    public function getlistcoa()
    {

        $data = COA::with('children')->whereNull('parent_id')->get();


        $buildList = function($items) use (&$buildList) {
            $html = '<ul style="font-size: 20px;">';
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
        $output = $buildList($data);
        return response()->json(['html' => $output]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'code_account_id' => 'required|unique:tbl_coa,code_account_id',
            'name' => 'required',
            'default_position' => 'required',
        ]);

        $parent_id = $request->input('group_account');

        $coa = new COA();
        $coa->code_account_id = $request->input('code_account_id');
        $coa->name = $request->input('name');
        $coa->description = $request->input('description');
        $coa->set_as_group = $request->input('set_group') == 1;
        $coa->default_posisi = $request->input('default_position');

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
            'group_account'   => 'nullable|exists:tbl_coa,id',
        ]);

        $coa = COA::findOrFail($id);
        $coa->code_account_id = $validated['code_account_id'];
        $coa->name = $validated['name'];
        $coa->description = $validated['description'];
        $coa->set_as_group = $validated['set_as_group'];
        $coa->default_posisi = $validated['default_posisi'];
        $coa->parent_id = $validated['group_account'];

        $coa->save();

        return response()->json(['success' => true]);
    }



}
