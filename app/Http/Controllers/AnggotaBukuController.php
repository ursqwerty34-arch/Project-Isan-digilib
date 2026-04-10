<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookFavorite;
use App\Models\Category;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggotaBukuController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with('category')
            ->when($request->q, fn($q, $s) => $q->where('title', 'like', "%$s%")->orWhere('author', 'like', "%$s%"))
            ->when($request->category_id, fn($q, $c) => $q->where('category_id', $c))
            ->latest()->paginate(12)->withQueryString();
        return view('anggota.buku.index', ['books' => $books, 'categories' => Category::orderBy('name')->get()]);
    }

    public function show(Book $book)
    {
        $uid = Auth::id();
        $activeLoan = Loan::where('user_id', $uid)->where('book_id', $book->id)
            ->whereIn('pengajuan_status', ['pending', 'disetujui'])
            ->where('status', '!=', 'dikembalikan')->latest()->first();

        $statusPinjam = match(true) {
            $activeLoan?->pengajuan_status === 'pending'  => 'pending',
            $activeLoan?->status === 'dipinjam'           => 'dipinjam',
            default                                       => null,
        };

        return view('anggota.buku.show', [
            'book'        => $book,
            'statusPinjam'=> $statusPinjam,
            'activeLoan'  => $activeLoan,
            'bolehReview' => Loan::where('user_id', $uid)->where('book_id', $book->id)->where('pengajuan_status', 'disetujui')->exists(),
            'myReview'    => $book->reviews()->where('user_id', $uid)->first(),
            'reviews'     => $book->reviews()->with('user')->latest()->get(),
            'isFavorite'  => BookFavorite::where('user_id', $uid)->where('book_id', $book->id)->exists(),
        ]);
    }

    public function ajukan(Request $request, Book $book)
    {
        $request->validate([
            'qty'      => 'required|integer|min:1|max:' . $book->stock,
            'duration' => 'required|integer|in:7,14,20',
        ]);

        if (Loan::where('user_id', Auth::id())->where('book_id', $book->id)->whereIn('pengajuan_status', ['pending', 'disetujui'])->exists()) {
            return response()->json(['success' => false, 'message' => 'Sudah ada pengajuan aktif untuk buku ini.']);
        }

        Loan::create([
            'user_id'          => Auth::id(),
            'book_id'          => $book->id,
            'qty'              => $request->qty,
            'duration'         => $request->duration,
            'loan_date'        => now()->toDateString(),
            'pengajuan_status' => 'pending',
        ]);

        return response()->json(['success' => true]);
    }
}
