<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetugasProfilController extends Controller
{
    public function index()  { return view('petugas.profil.index', ['user' => Auth::user()]); }
    public function edit()   { return view('petugas.profil.edit',  ['user' => Auth::user()]); }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'username'   => 'required|string|max:50|unique:users,username,' . $user->id,
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'phone'      => 'required|string|max:20',
            'nik'        => 'required|string|max:30',
            'gender'     => 'required|in:Laki-laki,Perempuan',
            'birth_date' => 'required|date',
            'address'    => 'required|string',
            'photo'      => 'nullable|image|max:10240',
        ]);

        $data = $request->only(['username', 'name', 'email', 'phone', 'nik', 'gender', 'birth_date', 'address']);
        if ($request->hasFile('photo')) {
            if ($user->photo) Storage::disk('public')->delete($user->photo);
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }
        $user->update($data);
        return redirect()->route('petugas.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['new_password' => 'required|min:6|confirmed']);
        Auth::user()->update(['password' => bcrypt($request->new_password), 'plain_password' => $request->new_password, 'password_changed' => true]);
        return redirect()->route('petugas.profil')->with('password_success', 'Password berhasil diubah.');
    }
}
