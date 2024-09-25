<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('tbl_users')->ignore($request->user()->id)],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $request->user()->password,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Profile updated successfully!']);
        }

        return redirect()->route('profile.edit')->with('status', 'Profile updated successfully.');
    }


    public function destroy(Request $request)
    {
        $request->user()->delete();

        return redirect()->route('home');
    }
}
