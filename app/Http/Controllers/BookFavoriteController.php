<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookFavorite;
use Illuminate\Support\Facades\Auth;

class BookFavoriteController extends Controller
{
    public function index()
    {
        return view('anggota.favorit', [
            'favorites' => BookFavorite::with('book.category')->where('user_id', Auth::id())->latest()->get(),
        ]);
    }

    public function toggle(Book $book)
    {
        $fav = BookFavorite::where('user_id', Auth::id())->where('book_id', $book->id)->first();
        if ($fav) {
            $fav->delete();
            return response()->json(['success' => true, 'is_favorite' => false]);
        }
        BookFavorite::create(['user_id' => Auth::id(), 'book_id' => $book->id]);
        return response()->json(['success' => true, 'is_favorite' => true]);
    }
}
