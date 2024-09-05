<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {


        return view('masterdata.user.indexmasteruser');
    }
    public function getlistUser(Request $request)
    {
        $txSearch = '%' . strtoupper(trim($request->txSearch)) . '%';

        $q = "SELECT
		            a.name, 
		            a.email, 
		            a.role 
		FROM tbl_users AS a 
        ";

        // dd($q);

        $data = DB::select($q);

        $output = '  <table class="table align-items-center table-flush table-hover" id="tableUser">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
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
                </tr>
            ';
        }

        $output .= '</tbody></table>';
        return $output;
    }


}