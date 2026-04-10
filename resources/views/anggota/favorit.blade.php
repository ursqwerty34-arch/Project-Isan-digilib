@extends('layouts.anggota')
@section('title', 'Favorit Saya')

@section('content')

<div class="db-section-header" style="margin-bottom:20px;">
    <div class="db-section-title">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="#ef5350" style="vertical-align:middle;margin-right:6px;"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        Favorit Saya
    </div>
</div>

@if($favorites->isEmpty())
    <div style="text-align:center;padding:60px 0;color:#9ca3af;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="#d1d5db" style="margin-bottom:12px;display:block;margin-left:auto;margin-right:auto;"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        <div style="font-size:15px;">Belum ada buku favorit.</div>
        <a href="{{ route('anggota.buku.index') }}" style="display:inline-block;margin-top:16px;padding:10px 24px;background:#2f5d34;color:#fff;border-radius:10px;font-size:14px;font-weight:600;text-decoration:none;">Jelajahi Koleksi</a>
    </div>
@else
    <div class="db-book-grid db-book-grid-sm">
        @foreach($favorites as $fav)
        @php $book = $fav->book; $r = $book->avgRating(); @endphp
        <a href="{{ route('anggota.buku.show', $book) }}" class="db-book-card">
            <div class="db-book-cover">
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}">
                @else
                    <div class="db-book-cover-ph">📖</div>
                @endif
                @if($r > 0)
                    <span class="db-book-rating-badge">⭐ {{ number_format($r,1) }}</span>
                @endif
                <span class="fav-heart-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#ef5350"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </span>
            </div>
            <div class="db-book-body">
                <div class="db-book-title">{{ $book->title }}</div>
                <div class="db-book-author">{{ $book->author }}</div>
                <div class="db-book-btn">Pinjam</div>
            </div>
        </a>
        @endforeach
    </div>
@endif

@endsection
