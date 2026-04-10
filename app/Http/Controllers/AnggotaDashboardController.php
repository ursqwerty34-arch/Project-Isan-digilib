<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;

class AnggotaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activeLoan = Loan::with('book')->where('user_id', $user->id)
            ->where('status', 'dipinjam')->where('pengajuan_status', 'disetujui')
            ->latest()->first();

        $totalFine = Loan::with('bookReturn')->where('user_id', $user->id)->get()
            ->sum(fn($l) => $l->bookReturn?->fine_status === 'belum_lunas' ? ($l->bookReturn->fine ?? 0) : 0);

        $unpaidFine = Loan::whereHas('bookReturn', fn($q) => $q->where('fine', '>', 0)->where('fine_status', 'belum_lunas'))
            ->where('user_id', $user->id)->exists();

        return view('dashboard.anggota', [
            'books'           => Book::latest()->take(6)->get(),
            'activeLoan'      => $activeLoan,
            'totalFine'       => $totalFine,
            'unpaidFine'      => $unpaidFine,
            'profileComplete' => $user->isProfileComplete(),
        ]);
    }
}
