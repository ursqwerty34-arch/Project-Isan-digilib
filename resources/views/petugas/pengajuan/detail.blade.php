@extends('layouts.petugas')
@section('title', 'Detail Pengajuan')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <a href="{{ route('petugas.pengajuan') }}" class="btn-detail-kembali">Kembali</a>
    <a href="{{ route('petugas.pengajuan.detail.cetak', $loan) }}" target="_blank" class="btn-cetak-pdf">
        Cetak <span style="font-size:10px; background:#fff; color:#2f5d34; border-radius:4px; padding:1px 5px; margin-left:4px; font-weight:700;">PDF</span>
    </a>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">

    {{-- Informasi Anggota --}}
    <div class="detail-panel">
        <div class="detail-panel-title">Informasi Anggota</div>

        <div class="detail-field">
            <label>NIS/NIK</label>
            <input type="text" readonly value="{{ $loan->user->nik ?? '-' }}">
        </div>
        <div class="detail-field">
            <label>Nama Anggota</label>
            <input type="text" readonly value="{{ $loan->user->name ?? '-' }}">
        </div>
        <div class="detail-field">
            <label>Jenis Kelamin</label>
            <input type="text" readonly value="{{ $loan->user->gender ?? '-' }}">
        </div>
        <div class="detail-field">
            <label>Alamat</label>
            <textarea readonly rows="3">{{ $loan->user->address ?? '-' }}</textarea>
        </div>
    </div>

    {{-- Detail Pengajuan --}}
    <div class="detail-panel">
        <div class="detail-panel-title">Detail Pengajuan</div>

        <div class="detail-section-label">INFORMASI PENGAJUAN</div>

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
                <input type="text" readonly value="{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-' }}">
                <span>&#128197;</span>
            </div>
        </div>
        <div class="detail-field">
            <label>Status</label>
            <input type="text" readonly value="{{ $loan->pengajuan_status === 'disetujui' ? 'Dipinjam' : ucfirst($loan->pengajuan_status) }}">
        </div>

        @if($loan->pengajuan_status === 'ditolak')
        <div class="detail-field">
            <label>Alasan ditolak</label>
            <textarea readonly rows="3">{{ $loan->rejection_reason ?? '-' }}</textarea>
        </div>
        @endif

        <div class="detail-section-label" style="margin-top:16px;">INFORMASI BUKU</div>

        <div class="detail-field">
            <label>Kode Buku</label>
            <input type="text" readonly value="{{ $loan->book->kode_buku ?? '-' }}">
        </div>
        <div class="detail-field">
            <label>Judul Buku</label>
            <input type="text" readonly value="{{ $loan->book->title ?? '-' }}">
        </div>
        <div class="detail-field">
            <label>Penulis</label>
            <input type="text" readonly value="{{ $loan->book->author ?? '-' }}">
        </div>
        <div class="detail-field">
            <label>Tahun Terbit</label>
            <div class="detail-input-icon">
                <input type="text" readonly value="{{ $loan->book->year ?? '-' }}">
                <span>&#128197;</span>
            </div>
        </div>
    </div>

</div>

@endsection
