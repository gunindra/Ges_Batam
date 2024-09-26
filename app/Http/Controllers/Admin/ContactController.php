<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
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

        try {
            $existingData = Contact::first();
            
            Contact::updateOrCreate(
                [],
                [
                    'email' => $request->input('emailContact'),
                    'phone' => $request->input('phoneContact'),
                    'phones' => $request->input('phonesContact'),
                    'updated_at' => now(),
                ]
            );

            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan', 'data' => $request->only('emailContact', 'phoneContact', 'phonesContact')], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
}
