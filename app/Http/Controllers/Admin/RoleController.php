<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return view('masterdata.role.indexmasterrole');
    }
    public function getlistRole(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $data = DB::table('tbl_role')
            ->select('id', 'role')
            ->where(function ($q) use ($txSearch) {
                $q->whereRaw('UPPER(role) LIKE UPPER(?)', [$txSearch]);
            })
            ->get();


        $output = '  <table class="table align-items-center table-flush table-hover" id="tableRole">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {


            $output .=
                '
                <tr>
                    <td class="">' . ($item->role ?? '-') . '</td>
                   <td>
                        <a  class="btn btnUpdateRole btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-role="' . $item->role . '"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyRole btn-sm btn-danger text-white" data-id="' . $item->id . '" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
        return $output;
    }
    public function addRole(Request $request)
    {
        $request->validate([
            'roleMaster' => 'required|string|max:255|unique:tbl_role,role',
        ]);
    
        try {
            $Role = new Role();
            $Role->role = $request->input('roleMaster');
    
            $Role->save();
    
            return response()->json(['success' => 'Berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan']);
        }
    }
    
    public function updateRole(Request $request, $id)
    {
        $validated = $request->validate([
            'roleMaster' => 'required|string|max:255',
        ]);
        try {
            $Role = Role::findOrFail($id);
            $Role->role = $request->input('roleMaster');

            $Role->update($validated);


            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => 'Data gagal diperbarui']);
        }
    }
    public function destroyRole($id)
    {
        $Role = Role::findOrFail($id);

        try {
            $Role->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function show($id)
    {
        $Role = Role::findOrFail($id);
        return response()->json($Role);
    }
    public function getlistMenu(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $data = DB::table('tbl_role')
            ->select('id', 'role')
            ->get();


        $output = '  <table class="table align-items-center table-flush table-hover" id="tableMenuAkses">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {


            $output .=
                '
                <tr>
                    <td class="">' . ($item->role ?? '-') . '</td>
                   <td>
                        <a  class="btn btnUpdateMenuAkses btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-role="' . $item->role . '"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
        return $output;
    }
}