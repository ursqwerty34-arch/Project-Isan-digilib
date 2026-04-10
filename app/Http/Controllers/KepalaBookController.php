<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KepalaBookController extends Controller
{
    private function rules(string $id = ''): array
    {
        return [
            'kode_buku'   => 'required|string|unique:books,kode_buku' . ($id ? ",$id" : ''),
            'title'       => 'required|string',
            'author'      => 'required|string',
            'year'        => 'required|date',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'cover'       => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
        ];
    }

    private function data(Request $r): array
    {
        return [
            'kode_buku'   => $r->kode_buku,
            'title'       => $r->title,
            'author'      => $r->author,
            'year'        => date('Y', strtotime($r->year)),
            'stock'       => $r->stock,
            'category_id' => $r->category_id ?: null,
            'synopsis'    => $r->synopsis ?: null,
        ];
    }

    private function storeCover(Request $r, ?string $old = null): ?string
    {
        if (!$r->hasFile('cover')) return null;
        if ($old) Storage::disk('public')->delete($old);
        return $r->file('cover')->store('photos', 'public');
    }

    public function index(Request $request)
    {
        $books = Book::with('category')->when($request->q, fn($q, $s) =>
            $q->where('title', 'like', "%$s%")->orWhere('kode_buku', 'like', "%$s%")
        )->latest()->paginate(10)->withQueryString();
        return view('kepala.buku.index', ['books' => $books, 'categories' => Category::orderBy('name')->get()]);
    }

    public function create()
    {
        return view('kepala.buku.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules());
        $data = $this->data($request);
        if ($cover = $this->storeCover($request)) $data['cover'] = $cover;
        Book::create($data);
        return redirect()->route('kepala.buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        return view('kepala.buku.edit', ['book' => $book, 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(Request $request, Book $book)
    {
        $request->validate($this->rules($book->id));
        $data = $this->data($request);
        if ($cover = $this->storeCover($request, $book->cover)) $data['cover'] = $cover;
        $book->update($data);
        return redirect()->route('kepala.buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if ($book->cover) Storage::disk('public')->delete($book->cover);
        $book->delete();
        return redirect()->route('kepala.buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}
