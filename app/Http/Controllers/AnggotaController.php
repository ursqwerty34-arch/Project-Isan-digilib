<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $anggota = User::where('role', 'anggota')
            ->when($request->kelas, fn($q, $k) => $q->where('kelas_jurusan', 'like', "$k %"))
            ->when($request->jurusan, fn($q, $j) => $q->where('kelas_jurusan', 'like', "% $j%"))
            ->latest()->paginate(10)->withQueryString();
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

    public function resetPassword(User $user)
    {
        abort_if($user->role !== 'anggota', 403);
        $user->update(['password' => bcrypt('12345678'), 'plain_password' => '12345678', 'password_changed' => false]);
        return back()->with('success', "Password {$user->name} berhasil direset menjadi: 12345678");
    }
}
