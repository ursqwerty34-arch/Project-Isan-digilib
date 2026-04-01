@extends('layouts.anggota')

@section('title', 'Beranda')

@section('content')

{{-- Hero Banner --}}
<div class="db-hero">
    <div class="db-hero-text">
        <div class="db-hero-greeting">Selamat datang, {{ Auth::user()->name }} 👋</div>
        <div class="db-hero-sub">Temukan buku favoritmu dan mulai membaca hari ini.</div>
        <a href="{{ route('anggota.buku.index') }}" class="db-hero-cta">Jelajahi Koleksi →</a>
    </div>
    <div class="db-hero-icon">📚</div>
</div>

{{-- Stats Row --}}
<div class="db-stats-row">
    <div class="db-stat-card db-stat-green">
        <div class="db-stat-icon">📖</div>
        <div class="db-stat-info">
            <div class="db-stat-label">Sedang Dipinjam</div>
            <div class="db-stat-value">{{ $activeLoan ? 1 : 0 }}</div>
        </div>
    </div>
    <div class="db-stat-card db-stat-orange">
        <div class="db-stat-icon">💰</div>
        <div class="db-stat-info">
            <div class="db-stat-label">Total Denda</div>
            <div class="db-stat-value">Rp {{ number_format($totalFine, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="db-stat-card {{ $unpaidFine ? 'db-stat-red' : 'db-stat-teal' }}">
        <div class="db-stat-icon">{{ $unpaidFine ? '⚠️' : '✅' }}</div>
        <div class="db-stat-info">
            <div class="db-stat-label">Status Denda</div>
            <div class="db-stat-value">{{ $unpaidFine ? 'Belum Lunas' : 'Lunas' }}</div>
        </div>
    </div>
</div>

{{-- Peminjaman Aktif --}}
@if($activeLoan)
@php
    $now     = now();
    $due     = \Carbon\Carbon::parse($activeLoan->due_date);
    $total   = \Carbon\Carbon::parse($activeLoan->loan_date)->diffInDays($due);
    $passed  = \Carbon\Carbon::parse($activeLoan->loan_date)->diffInDays($now);
    $pct     = $total > 0 ? min(100, round(($passed / $total) * 100)) : 100;
    $overdue = $now->gt($due);
    $sisa    = $overdue ? 0 : $now->diffInDays($due);
@endphp
<div class="db-active-loan">
    <div class="db-active-loan-left">
        @if($activeLoan->book->cover)
            <img src="{{ asset('storage/' . $activeLoan->book->cover) }}" alt="cover">
        @else
            <div class="db-active-loan-cover-ph">📖</div>
        @endif
    </div>
    <div class="db-active-loan-right">
        <div class="db-active-loan-badge">📌 Sedang Dipinjam</div>
        <div class="db-active-loan-title">{{ $activeLoan->book->title }}</div>
        <div class="db-active-loan-author">{{ $activeLoan->book->author }}</div>
        <div class="db-active-loan-meta">
            <span>📅 Dipinjam: {{ \Carbon\Carbon::parse($activeLoan->loan_date)->format('d M Y') }}</span>
            <span>⏰ Jatuh tempo: {{ $due->format('d M Y') }}</span>
            @if($overdue)
                <span class="db-overdue-tag">Terlambat!</span>
            @else
                <span class="db-sisa-tag">Sisa {{ $sisa }} hari</span>
            @endif
        </div>
        <div class="db-loan-bar-wrap">
            <div class="db-loan-bar">
                <div class="db-loan-bar-fill {{ $overdue ? 'overdue' : '' }}" style="width:{{ $pct }}%"></div>
            </div>
            <span class="db-loan-bar-pct">{{ $pct }}%</span>
        </div>
    </div>
</div>
@endif

{{-- Buku Terbaru --}}
<div class="db-section-header">
    <div class="db-section-title">🆕 Buku Terbaru</div>
    <a href="{{ route('anggota.buku.index') }}" class="db-see-all">Lihat semua →</a>
</div>

<div class="db-book-grid">
    @forelse($books as $book)
    <a href="{{ route('anggota.buku.show', $book) }}" class="db-book-card">
        <div class="db-book-cover">
            @if($book->cover)
                <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}">
            @else
                <div class="db-book-cover-ph">📖</div>
            @endif
            <span class="db-book-new-badge">Baru</span>
        </div>
        <div class="db-book-body">
            <div class="db-book-title">{{ $book->title }}</div>
            <div class="db-book-author">{{ $book->author }}</div>
            <div class="db-book-btn">Pinjam</div>
        </div>
    </a>
    @empty
    <p class="db-empty">Belum ada buku tersedia.</p>
    @endforelse
</div>

@endsection

@section('modal')
@if(!$profileComplete)
<div class="modal-overlay active" id="modalLengkapi">
    <div class="modal-box">
        <div class="modal-mascot-circle">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/>
                <circle cx="18" cy="22" r="2.5" fill="#2f5d34"/>
                <circle cx="30" cy="22" r="2.5" fill="#2f5d34"/>
                <path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                <line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/>
                <circle cx="24" cy="2" r="1.5" fill="#4caf50"/>
            </svg>
            <span class="modal-mascot-dot"></span>
        </div>
        <div class="modal-title">Lengkapi data diri anda terlebih dahulu</div>
        <div class="modal-actions">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-modal-nanti">Nanti saja</button>
            </form>
            <a href="{{ route('anggota.profil') }}" class="btn-modal-profil">Profile</a>
        </div>
    </div>
</div>
@endif
@endsection
