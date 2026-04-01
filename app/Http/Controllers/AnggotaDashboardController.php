<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggotaDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $books = Book::latest()->take(4)->get();

        $activeLoan = Loan::with('book')
            ->where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->latest()
            ->first();

        $totalFine = Loan::with('bookReturn')
            ->where('user_id', $user->id)
            ->get()
            ->sum(fn($loan) => $loan->bookReturn?->fine ?? 0);

        $unpaidFine = Loan::whereHas('bookReturn', fn($q) => $q->where('fine', '>', 0))
            ->where('user_id', $user->id)
            ->exists();

        $profileComplete = $user->isProfileComplete();

        return view('dashboard.anggota', compact('books', 'activeLoan', 'totalFine', 'unpaidFine', 'profileComplete'));
    }
}
