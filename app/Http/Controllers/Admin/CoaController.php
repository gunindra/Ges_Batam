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
        ->whereNotNull('parent_id')
        ->where('set_as_group', 1)
        ->get();

        $coaList = COA::whereNull('parent_id')
        ->with('children.children')
        ->get();


        return view('accounting.coa.indexcoa', [
            'groupAccounts' =>  $groupAccounts,
            'coaList' => $coaList
        ]);
    }
    public function getNextAccountCode(Request $request)
    {
        $accountId = $request->accountId;
        $lastAccount = COA::where('parent_id', $accountId)
                          ->orderBy('code_account_id', 'desc')
                          ->first();
        $nextAccountCode = $this->generateNextAccountCode($lastAccount);

        return response()->json(['next_account_code' => $nextAccountCode]);
    }

    private function generateNextAccountCode($lastAccount)
    {
        if ($lastAccount) {
            $lastCodeParts = explode('.', $lastAccount->code_account_id);

            $lastSubCode = (int) end($lastCodeParts);
            $nextSubCode = $lastSubCode + 1;
            $nextAccountNumber = implode('.', array_slice($lastCodeParts, 0, -1)) . '.' . str_pad($nextSubCode, 2, '0', STR_PAD_LEFT);
        } else {
            $nextAccountNumber = str_pad($lastAccount->parent_id, 3, '0', STR_PAD_LEFT) . '.00';
        }

        return $nextAccountNumber;
    }

    public function getlistcoa()
    {
        $data = COA::with('children')->whereNull('parent_id')->get();

        // Start building the output for the table
        $output = '<table class="table align-items-center table-flush table-hover" id="tableCostumer">
                    <thead class="thead-light">
                        <tr>
                            <th>Account Code</th>
                            <th>Nama</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($data as $item) {
            $output .= $this->renderRow($item);
        }

        $output .= '</tbody></table>';
        return response()->json(['html' => $output]);
    }

    private function renderRow($item, $level = 0)
    {
        $padding = $level * 20;
        $row = '
        <tr>
            <td style="padding-left: ' . $padding . 'px;">' . ($item->code_account_id ?? '-') . '</td>
            <td>' . ($item->name ?? '-') . '</td>
            <td>
                <a class="btn btndeleteCOA btn-sm btn-danger text-white" data-id="' . $item->id . '"><i class="fas fa-trash-alt"></i></a>
                <a class="btn editCOA btn-sm btn-secondary text-white" data-id="' . $item->id . '" ><i class="fas fa-edit"></i></a>
            </td>
        </tr>';

        // Jika ada children, loop melalui setiap child dan tambahkan ke tabel
        if ($item->children) {
            foreach ($item->children as $child) {
                $row .= $this->renderRow($child, $level + 1);  // Panggil fungsi renderRow secara rekursif untuk children
            }
        }

        return $row;
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
