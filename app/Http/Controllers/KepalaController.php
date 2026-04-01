<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookReturn;
use App\Models\Loan;
use App\Models\User;

class KepalaController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.kepala', [
            'totalAnggota'      => User::where('role', 'anggota')->count(),
            'totalPetugas'      => User::where('role', 'petugas')->count(),
            'totalBuku'         => Book::count(),
            'totalPeminjaman'   => Loan::count(),
            'totalPengembalian' => BookReturn::count(),
        ]);
    }
}
