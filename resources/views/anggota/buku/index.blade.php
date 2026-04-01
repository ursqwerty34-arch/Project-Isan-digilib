@extends('layouts.anggota')
@section('title', 'Koleksi Buku')

@section('content')

<div class="db-section-header" style="margin-bottom:20px;">
    <div class="db-section-title">📖 Koleksi Buku</div>
</div>

<form method="GET" action="{{ route('anggota.buku.index') }}" class="ang-search-form">
    <svg width="18" height="18" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau penulis..." oninput="this.form.submit()">
</form>

<div class="db-book-grid">
    @forelse($books as $book)
    <a href="{{ route('anggota.buku.show', $book) }}" class="db-book-card">
        <div class="db-book-cover">
            @if($book->cover)
                <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}">
            @else
                <div class="db-book-cover-ph">📖</div>
            @endif
        </div>
        <div class="db-book-body">
            <div class="db-book-title">{{ $book->title }}</div>
            <div class="db-book-author">{{ $book->author }}</div>
            <div class="db-book-btn">Pinjam</div>
        </div>
    </a>
    @empty
    <p class="db-empty">Tidak ada buku ditemukan.</p>
    @endforelse
</div>

@endsection
