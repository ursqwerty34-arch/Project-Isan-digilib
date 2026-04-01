<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilAnggotaController extends Controller
{
    public function index()
    {
        return view('anggota.profil', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username'      => 'required|string|max:50|unique:users,username,' . $user->id,
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'phone'         => 'required|string|max:20',
            'nik'           => 'required|string|max:30',
            'gender'        => 'required|in:Laki-laki,Perempuan',
            'birth_date'    => 'required|date',
            'address'       => 'required|string',
            'kelas_jurusan' => 'required|string|max:100',
            'photo'         => 'nullable|image|max:10240',
        ]);

        $data = $request->only([
            'username', 'name', 'email', 'phone', 'nik',
            'gender', 'birth_date', 'address', 'kelas_jurusan',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) Storage::disk('public')->delete($user->photo);
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $user->update($data);

        return redirect()->route('dashboard.anggota')->with('success', 'Profil berhasil dilengkapi!');
    }

    public function deletePhoto()
    {
        $user = Auth::user();
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
            $user->update(['photo' => null]);
        }
        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
