<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookReview;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $request->validate(['rating' => 'required|integer|min:1|max:5', 'comment' => 'nullable|string|max:1000']);

        if (!Loan::where('user_id', Auth::id())->where('book_id', $book->id)->where('pengajuan_status', 'disetujui')->exists()) {
            return response()->json(['success' => false, 'message' => 'Kamu belum pernah meminjam buku ini.'], 403);
        }

        BookReview::updateOrCreate(
            ['user_id' => Auth::id(), 'book_id' => $book->id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return response()->json(['success' => true]);
    }
}
