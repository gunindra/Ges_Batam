<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index()
    {
    $contactData = DB::table('tbl_contact')->first();
    return view('content.contact.indexcontact', compact('contactData'));
    }
public function addContact(Request $request)
{
    $request->validate([
        'emailContact' => 'required|string|max:255', 
        'phoneContact' => 'required|string|max:255', 
        'phonesContact' => 'required|string|max:255', 
    ]);
    $emailContact = $request->input('emailContact');
    $phoneContact = $request->input('phoneContact');
    $phonesContact = $request->input('phonesContact');
   
    
    try {
        $existingData = DB::table('tbl_contact')->first();

        if ($existingData) {
            // Update data yang sudah ada
            DB::table('tbl_contact')->update([
                'email' => $emailContact,
                'phone' => $phoneContact,
                'phones' => $phonesContact,
                'updated_at' => now(),
            ]);
        } else {
            // Insert data baru
            DB::table('tbl_contact')->insert([
                'email' => $emailContact,
                'phone' => $phoneContact,
                'phones' => $phonesContact,
                'created_at' => now(),
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan', 'data' => ['emailContact' => $emailContact, 'phoneContact' => $phoneContact, 'phonesContact' => $phonesContact]], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
    }
    }
}



