<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $listRole = DB::table('tbl_users')
            ->select('role')
            ->distinct()
            ->get();
        $listCompany = DB::table('tbl_company')
            ->select('name', 'id')
            ->distinct()
            ->get();

        return view('masterdata.user.indexmasteruser', compact('listRole', 'listCompany'));
    }
    public function getlistUser(Request $request)
    {
        $role = $request->role;

        // Query awal
        $query = DB::table('tbl_users')
            ->select(
                'tbl_users.id',
                'tbl_users.name',
                'tbl_users.email',
                'tbl_users.role',
                'tbl_company.name as company_name',
                'tbl_users.company_id'
            )
            ->leftJoin('tbl_company', 'tbl_users.company_id', '=', 'tbl_company.id');

        // Filter role jika ada
        if ($role) {
            $query->where('tbl_users.role', $role);
        }

        // Gunakan Yajra DataTables
        return DataTables::of($query)
            ->addColumn('action', function ($item) {
                return '
                <a class="btn btnUpdateUsers btn-sm btn-secondary text-white" 
                    data-id="' . $item->id . '" 
                    data-name="' . $item->name . '" 
                    data-email="' . $item->email . '" 
                    data-role="' . $item->role . '">
                    <i class="fas fa-edit"></i>
                </a>
                <a class="btn btnDestroyUsers btn-sm btn-danger text-white" 
                    data-id="' . $item->id . '">
                    <i class="fas fa-trash"></i>
                </a>
            ';
            })
            ->editColumn('company_name', function ($item) {
                return $item->company_id === null ? 'Semua Company' : ($item->company_name ?? '-');
            })
            ->rawColumns(['action'])
            ->make(true);
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
            $User->company_id = $request->input('companyUsers');

            if ($request->filled('passwordUsers')) {
                $User->password = bcrypt($request->input('passwordUsers'));
            }

            if ($request->input('roleUsers') !== null) {
                $User->role = $request->input('roleUsers');
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
