<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        return view('masterdata.role.indexmasterrole');
    }
    public function getlistRole(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        role
                FROM tbl_role
        ";

        // dd($q);

        $data = DB::select($q);

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
                    <td class="">' . ($item->role ?? '-') .'</td>
                   <td>
                        <a  class="btn btnUpdateRole btn-sm btn-secondary text-white" data-id="' .$item->id.'" data-role="' .$item->role.'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyRole btn-sm btn-danger text-white" data-id="' .$item->id.'" ><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }
    public function addRole(Request $request)
    {

        $roleMaster = $request->input('roleMaster');

        try {
       
            DB::table('tbl_role')->insert([
                'role' => $roleMaster,
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan : ' . $e->getMessage()], 500);
        }
    }
    public function updateRole(Request $request)
    {
        $id = $request->input('id');
        $roleMaster = $request->input('roleMaster');
        try {
           
            $dataUpdate = [
                'role' => $roleMaster,
                'updated_at' => now(),
            ];


            DB::table('tbl_role')
                ->where('id', $id)
                ->update($dataUpdate);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }
    public function destroyRole(Request $request)
    {
        $id = $request->input('id');

        try {
            DB::table('tbl_role')
                ->where('id', $id)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function getlistMenu(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT id,
                        role
                FROM tbl_role
        ";

        // dd($q);

        $data = DB::select($q);

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
                    <td class="">' . ($item->role ?? '-') .'</td>
                   <td>
                        <a  class="btn btnUpdateMenuAkses btn-sm btn-secondary text-white" data-id="' .$item->id.'" data-role="' .$item->role.'"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
         return $output;
    }
}