@extends('layouts.anggota')
@section('title', 'Koleksi Buku')

@section('content')

<div class="db-section-header" style="margin-bottom:20px;">
    <div class="db-section-title">📖 Koleksi Buku</div>
</div>

{{-- Search + Filter --}}
<form method="GET" action="{{ route('anggota.buku.index') }}" class="ang-filter-bar" style="flex-wrap:nowrap;align-items:center;">
    <div class="ang-search-form" style="margin-bottom:0;flex:1;">
        <svg width="18" height="18" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau penulis...">
    </div>
    <select name="category_id" onchange="this.form.submit()" class="ang-filter-select" style="min-width:180px;flex-shrink:0;" data-cs>
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-buku-submit" style="padding:11px 20px;flex-shrink:0;">Cari</button>
</form>

{{-- Chips kategori aktif --}}
@if(request('category_id'))
    @php $activeCat = $categories->firstWhere('id', request('category_id')); @endphp
    @if($activeCat)
    <div style="margin-bottom:16px;">
        <span style="background:#e8f5e9;color:#2f5d34;padding:4px 14px;border-radius:50px;font-size:13px;font-weight:600;">
            🏷️ {{ $activeCat->name }}
            <a href="{{ route('anggota.buku.index', ['q' => request('q')]) }}" style="color:#ef5350;margin-left:6px;text-decoration:none;">✕</a>
        </span>
    </div>
    @endif
@endif

<div class="db-book-grid db-book-grid-sm">
    @forelse($books as $book)
    <a href="{{ route('anggota.buku.show', $book) }}" class="db-book-card">
        <div class="db-book-cover">
            @if($book->cover)
                <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}">
            @else
                <div class="db-book-cover-ph">📖</div>
            @endif
            @if($book->category)
                <span class="db-book-cat-badge">{{ $book->category->name }}</span>
            @endif
            @php $r = $book->avgRating(); @endphp
            @if($r > 0)
                <span class="db-book-rating-badge">⭐ {{ number_format($r,1) }}</span>
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

@if($books->hasPages())
<div class="table-card" style="margin-top:16px;">
    {!! $books->render('layouts._pagination') !!}
</div>
@endif

@endsection
