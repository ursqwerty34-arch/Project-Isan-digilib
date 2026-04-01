<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.anggota');
        }
        return view('register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'password'              => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => bcrypt($request->password),
            'plain_password' => $request->password,
            'role'           => 'anggota',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.anggota');
    }
}
