<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetugasController extends Controller
{
    private function userRules(string $id = ''): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'unique:users,username' . ($id ? ",$id" : '')],
            'email'      => ['required', 'email', 'unique:users,email' . ($id ? ",$id" : '')],
            'phone'      => ['nullable', 'string', 'max:20'],
            'nik'        => ['nullable', 'string', 'max:30'],
            'gender'     => ['nullable', 'in:Laki-laki,Perempuan'],
            'birth_date' => ['nullable', 'date'],
            'address'    => ['nullable', 'string'],
            'photo'      => ['nullable', 'image', 'max:10240'],
        ];
    }

    public function index()
    {
        return view('kepala.petugas.index', ['petugas' => User::where('role', 'petugas')->latest()->paginate(10)->withQueryString()]);
    }

    public function create()
    {
        return view('kepala.petugas.create');
    }

    public function store(Request $request)
    {
        $rules = $this->userRules();
        $rules['password'] = ['required', 'min:6'];
        $request->validate($rules);

        User::create([
            ...$request->only(['name', 'username', 'email', 'phone', 'nik', 'gender', 'birth_date', 'address']),
            'photo'          => $request->hasFile('photo') ? $request->file('photo')->store('photos', 'public') : null,
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
        $request->validate($this->userRules($user->id));

        $data = $request->only(['name', 'username', 'email', 'phone', 'nik', 'gender', 'birth_date', 'address']);
        if ($request->hasFile('photo')) {
            if ($user->photo) Storage::disk('public')->delete($user->photo);
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

    public function resetPassword(User $user)
    {
        abort_if($user->role !== 'petugas', 403);
        $user->update(['password' => bcrypt('12345678'), 'plain_password' => '12345678', 'password_changed' => false]);
        return back()->with('success', "Password {$user->name} berhasil direset menjadi: 12345678");
    }
}
