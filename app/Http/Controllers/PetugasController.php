<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = User::where('role', 'petugas')->latest()->get();
        return view('kepala.petugas.index', compact('petugas'));
    }

    public function create()
    {
        return view('kepala.petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'unique:users,username'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'nik'        => ['nullable', 'string', 'max:30'],
            'gender'     => ['nullable', 'in:Laki-laki,Perempuan'],
            'birth_date' => ['nullable', 'date'],
            'address'    => ['nullable', 'string'],
            'password'   => ['required', 'min:6'],
            'photo'      => ['nullable', 'image', 'max:10240'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        User::create([
            'name'           => $request->name,
            'username'       => $request->username,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'nik'            => $request->nik,
            'gender'         => $request->gender,
            'birth_date'     => $request->birth_date,
            'address'        => $request->address,
            'photo'          => $photoPath,
            'password'       => bcrypt($request->password),
            'plain_password' => $request->password,
            'role'           => 'petugas',
        ]);

        return redirect()->route('kepala.petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        abort_if($user->role !== 'petugas', 403);
        return view('kepala.petugas.show', compact('user'));
    }

    public function edit(User $user)
    {
        abort_if($user->role !== 'petugas', 403);
        return view('kepala.petugas.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->role !== 'petugas', 403);

        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'unique:users,username,' . $user->id],
            'email'      => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone'      => ['nullable', 'string', 'max:20'],
            'nik'        => ['nullable', 'string', 'max:30'],
            'gender'     => ['nullable', 'in:Laki-laki,Perempuan'],
            'birth_date' => ['nullable', 'date'],
            'address'    => ['nullable', 'string'],
            'photo'      => ['nullable', 'image', 'max:10240'],
        ]);

        $data = $request->only(['name', 'username', 'email', 'phone', 'nik', 'gender', 'birth_date', 'address']);

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $user->update($data);

        return redirect()->route('kepala.petugas.show', $user)->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_if($user->role !== 'petugas', 403);
        $user->delete();
        return redirect()->route('kepala.petugas.index')->with('success', 'Petugas berhasil dihapus.');
    }
}
