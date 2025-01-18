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
        $listCompany = DB::table('tbl_company')
        ->select('name','id')
        ->distinct()
        ->get();

        return view('masterdata.user.indexmasteruser', compact('listRole','listCompany'));
    }
    public function getlistUser(Request $request)
{
    $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';
    $role = $request->role;

    $data = DB::table('tbl_users')
        ->select(
            'tbl_users.id',
            'tbl_users.name',
            'tbl_users.email',
            'tbl_users.password',
            'tbl_users.role',
            'tbl_company.name as company_name'
        )
        ->leftJoin('tbl_company', 'tbl_users.company_id', '=', 'tbl_company.id') // Menyesuaikan nama kolom FK
        ->where(function ($q) use ($txSearch) {
            $q->whereRaw('UPPER(tbl_users.name) LIKE ?', [$txSearch])
                ->orWhereRaw('UPPER(tbl_users.email) LIKE ?', [$txSearch]);
        })
        ->when($role, function ($q) use ($role) {
            return $q->where('tbl_users.role', $role);
        })
        ->orderBy('tbl_users.updated_at', 'desc')
        ->get();

    $output = '<table class="table align-items-center table-flush table-hover" id="tableUser">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Company</th>
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
                <td class="">' . ($item->company_name ?? '-') . '</td>
             <td>
                    <a class="btn btnUpdateUsers btn-sm btn-secondary text-white" data-id="' . $item->id . '" data-name="' . $item->name . '" data-email="' . $item->email . '" data-role="' . $item->role . '"><i class="fas fa-edit"></i></a>
                    <a class="btn btnDestroyUsers btn-sm btn-danger text-white" data-id="' . $item->id . '"><i class="fas fa-trash"></i></a>
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
        'passwordUsers' => 'required|min:6|confirmed',
    ]);

    try {
        $User = new User();
        $User->name = $request->input('nameUsers');
        $User->email = $request->input('emailUsers');
        $User->password = bcrypt($request->input('passwordUsers'));
        $User->role = $request->input('roleUsers');
        $User->company_id = $request->input('companyUsers'); 

        $User->save();

        return response()->json(['success' => 'Berhasil ditambahkan']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Gagal menambahkan', 'details' => $e->getMessage()]);
    }
}

public function updateUsers(Request $request, $id)
{
    $rules = [
        'nameUsers' => 'required|string|max:255',
        'emailUsers' => 'required|email|max:255|unique:tbl_users,email,' . $id,
        'passwordUsers' => 'nullable|min:6|confirmed',
    ];

    if ($request->input('roleUsers') !== 'driver' && $request->input('roleUsers') !== 'customer') {
        $rules['roleUsers'] = 'nullable|string|max:50';
    } else {
        $rules['roleUsers'] = 'required|string|max:50';
    }

    $validated = $request->validate($rules);

    try {
        $User = User::findOrFail($id);
        $User->name = $request->input('nameUsers');
        $User->email = $request->input('emailUsers');

        if ($request->filled('passwordUsers')) {
            $User->password = bcrypt($request->input('passwordUsers'));
        }

        if ($request->input('roleUsers') !== null) {
            $User->role = $request->input('roleUsers');
        }

        if ($request->input('companyUsers') !== null) {
            $User->company_id = $request->input('companyUsers'); 
        }

        $User->update($validated);

        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
    } catch (\Exception $e) {
        return response()->json(['error' => false, 'message' => 'Data gagal diperbarui', 'details' => $e->getMessage()]);
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
