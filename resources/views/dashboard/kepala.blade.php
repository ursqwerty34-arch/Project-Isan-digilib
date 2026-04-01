@extends('layouts.kepala')
@section('title', 'Beranda')

@section('content')
<div class="stats-grid">
    <div class="stat-card green">
        <p class="stat-label">Jumlah Keseluruhan Anggota</p>
        <p class="stat-value">{{ $totalAnggota }}</p>
    </div>
    <div class="stat-card orange">
        <p class="stat-label">Jumlah Keseluruhan Petugas</p>
        <p class="stat-value">{{ $totalPetugas }}</p>
    </div>
    <div class="stat-card dark-green">
        <p class="stat-label">Jumlah Keseluruhan Buku</p>
        <p class="stat-value">{{ $totalBuku }} Buku</p>
    </div>
    <div class="stat-card slate">
        <p class="stat-label">Jumlah Keseluruhan Peminjaman</p>
        <p class="stat-value">{{ $totalPeminjaman }}</p>
    </div>
    <div class="stat-card light-green">
        <p class="stat-label">Jumlah Keseluruhan Pengembalian</p>
        <p class="stat-value">{{ $totalPengembalian }}</p>
    </div>
</div>
@endsection
