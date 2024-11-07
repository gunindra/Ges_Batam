<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PTges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index()
    {
        $contactData = PTges::first();
        return view('content.contact.indexcontact', compact('contactData'));
    }

    public function addContact(Request $request)
    {
        $request->validate([
            'alamatContact' => 'required|string|max:255',
            'emailContact' => 'required|string|max:255',
            'phoneContact' => 'required|string|max:255',
            'phonesContact' => 'required|string|max:255',
        ]);

        $alamatContact = $request->input('alamatContact');
        $emailContact = $request->input('emailContact');
        $phoneContact = $request->input('phoneContact');
        $phonesContact = $request->input('phonesContact');

        try {
            $contact = PTges::first();
            
            PTges::updateOrCreate(
                ['id' => $contact ? $contact->id : null],
                [
                    'alamat' =>$alamatContact ,
                    'email' =>$emailContact ,
                    'phone' =>$phoneContact,
                    'phones' => $phonesContact,
                ]
            );

            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan', 'data' => ['alamatContact' => $alamatContact , 'emailContact' => $emailContact, 'phoneContact' => $phoneContact,'phonesContact' => $phonesContact,],200]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }
}
