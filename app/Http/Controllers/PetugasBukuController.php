<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetugasBukuController extends Controller
{
    private function bookRules(string $id = ''): array
    {
        return [
            'kode_buku' => 'required|string|unique:books,kode_buku' . ($id ? ",$id" : ''),
            'title'     => 'required|string',
            'author'    => 'required|string',
            'year'      => 'required|date',
            'stock'     => 'required|integer|min:0',
            'cover'     => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
        ];
    }

    private function bookData(Request $request): array
    {
        return [
            'kode_buku' => $request->kode_buku,
            'title'     => $request->title,
            'author'    => $request->author,
            'year'      => date('Y', strtotime($request->year)),
            'stock'     => $request->stock,
            'publisher' => $request->publisher,
        ];
    }

    public function index(Request $request)
    {
        $books = Book::when($request->q, fn($q, $s) =>
            $q->where('title', 'like', "%$s%")->orWhere('kode_buku', 'like', "%$s%")
        )->latest()->get();
        return view('petugas.buku.index', compact('books'));
    }

    public function create() { return view('petugas.buku.create'); }

    public function store(Request $request)
    {
        $request->validate($this->bookRules());
        $data = $this->bookData($request);
        if ($request->hasFile('cover')) $data['cover'] = $request->file('cover')->store('photos', 'public');
        Book::create($data);
        return redirect()->route('petugas.buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book) { return view('petugas.buku.edit', compact('book')); }

    public function update(Request $request, Book $book)
    {
        $request->validate($this->bookRules($book->id));
        $data = $this->bookData($request);
        if ($request->hasFile('cover')) {
            if ($book->cover) Storage::disk('public')->delete($book->cover);
            $data['cover'] = $request->file('cover')->store('photos', 'public');
        }
        $book->update($data);
        return redirect()->route('petugas.buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if ($book->cover) Storage::disk('public')->delete($book->cover);
        $book->delete();
        return redirect()->route('petugas.buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
