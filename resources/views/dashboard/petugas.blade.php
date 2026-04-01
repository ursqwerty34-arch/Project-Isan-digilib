@extends('layouts.petugas')
@section('title', 'Beranda')

@section('content')

<div class="section-title">📋 Pengajuan Terbaru</div>

<div class="table-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th>Nama Anggota</th>
                <th>Tanggal Pinjam</th>
                <th>Keterangan Pengajuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuan as $item)
                <tr>
                    <td>{{ $item->book->title ?? '-' }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->loan_date)->format('d/m/Y') }}</td>
                    <td>{{ $item->bookReturn ? 'Pengembalian' : 'Pinjaman' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty-row">Belum ada pengajuan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
