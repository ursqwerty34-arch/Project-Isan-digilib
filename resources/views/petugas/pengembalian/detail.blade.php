@extends('layouts.petugas')
@section('title', 'Detail Pengembalian')

@section('content')

@php
    $loan   = $bookReturn->loan;
    $book   = $loan->book;
    $user   = $loan->user;
    $due    = \Carbon\Carbon::parse($loan->due_date);
    $ret    = \Carbon\Carbon::parse($bookReturn->return_date);
    $telat  = $ret->gt($due) ? $ret->diffInDays($due) : 0;
@endphp

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <a href="{{ route('petugas.pengembalian') }}" class="btn-detail-kembali">Kembali</a>
    <a href="{{ route('petugas.pengembalian.cetak', $bookReturn) }}" target="_blank" class="btn-cetak-pdf">
        Cetak <span style="font-size:10px; background:#fff; color:#2f5d34; border-radius:4px; padding:1px 5px; margin-left:4px; font-weight:700;">PDF</span>
    </a>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; align-items:start;">

    {{-- Kolom kiri: Informasi Anggota + Informasi Buku --}}
    <div style="display:flex; flex-direction:column; gap:24px;">

        <div class="detail-panel">
            <div class="detail-panel-title">Informasi Anggota</div>
            <div class="detail-field"><label>NIS/NIK</label><input type="text" readonly value="{{ $user->nik ?? '-' }}"></div>
            <div class="detail-field"><label>Nama Anggota</label><input type="text" readonly value="{{ $user->name ?? '-' }}"></div>
            <div class="detail-field"><label>Jenis Kelamin</label><input type="text" readonly value="{{ $user->gender ?? '-' }}"></div>
            <div class="detail-field"><label>Alamat</label><textarea readonly rows="3">{{ $user->address ?? '-' }}</textarea></div>
        </div>

        <div class="detail-panel">
            <div class="detail-panel-title">Informasi Buku</div>
            <div class="detail-field"><label>Kode Buku</label><input type="text" readonly value="{{ $book->kode_buku ?? '-' }}"></div>
            <div class="detail-field"><label>Judul Buku</label><input type="text" readonly value="{{ $book->title ?? '-' }}"></div>
            <div class="detail-field"><label>Penulis</label><input type="text" readonly value="{{ $book->author ?? '-' }}"></div>
            <div class="detail-field">
                <label>Tahun Terbit</label>
                <div class="detail-input-icon">
                    <input type="text" readonly value="{{ $book->year ?? '-' }}">
                    <span>&#128197;</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Kolom kanan: Detail Pengembalian --}}
    <div class="detail-panel">
        <div class="detail-panel-title">Detail Pengembalian</div>

        <div class="detail-section-label">INFORMASI PENGEMBALIAN</div>

        <div class="detail-field">
            <label>Tanggal Pinjam</label>
            <div class="detail-input-icon">
                <input type="text" readonly value="{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}">
                <span>&#128197;</span>
            </div>
        </div>
        <div class="detail-field">
            <label>Tanggal Jatuh Tempo</label>
            <div class="detail-input-icon">
                <input type="text" readonly value="{{ $due->format('d/m/Y') }}">
                <span>&#128197;</span>
            </div>
        </div>
        <div class="detail-field">
            <label>Tanggal Pengembalian</label>
            <div class="detail-input-icon">
                <input type="text" readonly value="{{ $ret->format('d/m/Y') }}">
                <span>&#128197;</span>
            </div>
        </div>
        <div class="detail-field">
            <label>Total Telat</label>
            <input type="text" readonly value="{{ $telat > 0 ? $telat . ' Hari' : '-' }}">
        </div>
        <div class="detail-field">
            <label>Status</label>
            <input type="text" readonly value="Dikembalikan">
        </div>

        <div class="detail-section-label" style="margin-top:16px;">INFORMASI DENDA</div>

        <div class="detail-field">
            <label>Total Denda</label>
            <input type="text" readonly value="{{ $bookReturn->fine > 0 ? 'Rp. ' . number_format($bookReturn->fine, 0, ',', '.') : '-' }}">
        </div>
        <div class="detail-field">
            <label>Jumlah Bayar - Kembalian</label>
            <input type="text" readonly value="{{ $bookReturn->fine > 0 ? 'Rp' . number_format($bookReturn->fine, 0, ',', '.') . ' - 0' : '-' }}">
        </div>
        <div class="detail-field">
            <label>Status Denda</label>
            <input type="text" readonly value="{{ $bookReturn->fine_status === 'lunas' ? 'Lunas' : ($bookReturn->fine_status === 'belum_lunas' ? 'Belum Lunas' : '-') }}">
        </div>
    </div>

</div>

@endsection
