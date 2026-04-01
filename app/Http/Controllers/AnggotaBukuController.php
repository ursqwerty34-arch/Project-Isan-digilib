<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggotaBukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('title', 'like', "%$q%")
                  ->orWhere('author', 'like', "%$q%");
        }

        $books = $query->latest()->get();

        return view('anggota.buku.index', compact('books'));
    }

    public function show(Book $book)
    {
        $sudahDiajukan = Loan::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->whereIn('pengajuan_status', ['pending', 'disetujui'])
            ->exists();

        return view('anggota.buku.show', compact('book', 'sudahDiajukan'));
    }

    public function ajukan(Book $book)
    {
        // Cek sudah ada pengajuan pending/disetujui
        $exists = Loan::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->whereIn('pengajuan_status', ['pending', 'disetujui'])
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Sudah ada pengajuan aktif untuk buku ini.']);
        }

        Loan::create([
            'user_id'          => Auth::id(),
            'book_id'          => $book->id,
            'loan_date'        => now()->toDateString(),
            'pengajuan_status' => 'pending',
        ]);

        return response()->json(['success' => true]);
    }
}
