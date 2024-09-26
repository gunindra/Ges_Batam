<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index()
    {

        $listRole = DB::select("SELECT role FROM tbl_role");

        return view('masterdata.user.indexmasteruser', [
            'listRole' => $listRole
        ]);
    }
    public function getlistUser(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $role = $request->role;
        
        $data = DB::table('tbl_users')
            ->select('id', 'name', 'email', 'role')
            ->where(function ($q) use ($txSearch) {
                $q->whereRaw('UPPER(name) LIKE ?', [$txSearch])
                  ->orWhereRaw('UPPER(email) LIKE ?', [$txSearch]);
            })
            ->when($role, function ($q) use ($role) {
                return $q->where('role', $role);
            })
            ->get();

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableUser">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>';
        foreach ($data as $item) {

            $output .=
                '
                <tr>
                    <td class="">' . ($item->name ?? '-') . '</td>
                    <td class="">' . ($item->email ?? '-') . '</td>
                    <td class="">' . ($item->role ?? '-') . '</td>
                
                 <td>
                        <a  class="btn btnUpdateUsers btn-sm btn-secondary text-white" data-id="' .$item->id .'" data-name="' .$item->name .'" data-email="' .$item->email .'"  data-role="' .$item->role .'"><i class="fas fa-edit"></i></a>
                        <a  class="btn btnDestroyUsers btn-sm btn-danger text-white" data-id="' .$item->id .'" ><i class="fas fa-trash"></i></a> 
                </td>
                </tr>
            ';
        }

        $output .= '</tbody></table>';
        return $output;
    }

    public function addUsers(Request $request)
    {
        $request->validate([
            'nameUsers' => 'required|string|max:255',
            'emailUsers' => 'required|email|max:255|unique:tbl_users,email',
            'roleUsers' => 'required|string|max:50',
        ]);
        $nameUsers = $request->input('nameUsers');
        $emailUsers = $request->input('emailUsers');
        $roleUsers = $request->input('roleUsers');

        try {

            DB::table('tbl_users')->insert([
                'name' => $nameUsers,
                'email' => $emailUsers,
                'role' => $roleUsers,
                'password' => Hash::make('password'),
                'created_at' => now(),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Berhasil ditambahkan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan: ' . $e->getMessage()], 500);
        }
    }
     public function updateUsers(Request $request)
    {
        $request->validate([
            'nameUsers' => 'required|string|max:255',
            'emailUsers' => 'required|email|max:255|unique:tbl_users,email',
            'roleUsers' => 'required|string|max:50',
        ]);
        $id = $request->input('id');
        $nameUsers = $request->input('nameUsers');
        $emailUsers = $request->input('emailUsers');
        $roleUsers = $request->input('roleUsers');

        try {
            $dataUpdate = [
                'name' => $nameUsers,
                'email' => $emailUsers,
                'role' => $roleUsers,
                'updated_at' => now(),
            ];

            DB::table('tbl_users')
                ->where('id', $id)
                ->update($dataUpdate);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal Mengupdate Data: ' . $e->getMessage()], 500);
        }
    }
    public function destroyUsers(Request $request)
    {
        $id = $request->input('id');

        try {
            DB::table('tbl_users')
                ->where('id', $id)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

}