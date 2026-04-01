<?php

namespace App\Http\Controllers;

use App\Models\User;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = User::where('role', 'anggota')->latest()->get();
        return view('kepala.anggota.index', compact('anggota'));
    }

    public function show(User $user)
    {
        abort_if($user->role !== 'anggota', 403);
        return view('kepala.anggota.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if($user->role !== 'anggota', 403);
        $user->delete();
        return redirect()->route('kepala.anggota.index')->with('success', 'Akun anggota berhasil dihapus.');
    }
}
