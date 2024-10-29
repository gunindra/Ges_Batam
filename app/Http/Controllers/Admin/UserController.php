<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $listRole = DB::table('tbl_users')
        ->select('role')
        ->distinct()
        ->get();

        return view('masterdata.user.indexmasteruser', compact('listRole'));
    }
    public function getlistUser(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
        $role = $request->role;
    
        $data = DB::table('tbl_users')
            ->select('id', 'name', 'email', 'password', 'role')
            ->where(function ($q) use ($txSearch) {
                $q->whereRaw('UPPER(name) LIKE ?', [$txSearch])
                    ->orWhereRaw('UPPER(email) LIKE ?', [$txSearch]);
            })
            ->when($role, function ($q) use ($role) {
                return $q->where('role', $role);
            })
            ->orderBy('updated_at', 'desc') 
            ->get();
    
        $output = '<table class="table align-items-center table-flush table-hover" id="tableUser">
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
                '<tr>
                    <td>' . ($item->name ?? '-') . '</td>
                    <td>' . ($item->email ?? '-') . '</td>
                    <td>' . ($item->role ?? '-') . '</td>
                    <td>
                        <a class="btn btnUpdateUsers btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-name="' . $item->name . '" data-email="' . $item->email . '" data-password="' . $item->password . '" data-role="' . $item->role . '">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a class="btn btnDestroyUsers btn-sm btn-danger text-white" data-id="' . $item->id . '">
                            <i class="fas fa-trash"></i>
                        </a> 
                    </td>
                </tr>';
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
            'passwordUsers' => 'required|min:8|confirmed',
        ]);

        try {
            $User = new User();
            $User->name = $request->input('nameUsers');
            $User->email = $request->input('emailUsers');
            $User->password = bcrypt($request->input('passwordUsers')); 
            $User->role = $request->input('roleUsers'); 

            $User->save();

            return response()->json(['success' => 'berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan']);
        }
    }
    public function updateUsers(Request $request, $id)
    {
        $validated = $request->validate([
            'nameUsers' => 'required|string|max:255',
            'emailUsers' => 'required|email|max:255',
            'roleUsers' => 'required|string|max:50',
           'passwordUsers' => 'nullable|min:8|confirmed', 
        ]);
        try {
            $User = User::findOrFail($id);
            $User->name = $request->input('nameUsers');
            $User->email = $request->input('emailUsers');
            if ($request->filled('passwordUsers')) {
                $User->password = bcrypt($request->input('passwordUsers'));
            }
            $User->role = $request->input('roleUsers'); 

            $User->update($validated);


            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['error' => false, 'message' => 'Data gagal diperbarui']);
        }
    }
    public function destroyUsers($id)
    {
        try {
            $User = User::findOrFail($id);

            $User->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function show($id)
    {

        $User = User::findOrFail($id);
        return response()->json($User);
    }

}