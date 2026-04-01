<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Detail Pengembalian</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #1e1e1e; padding: 32px; }
        h2 { color: #2f5d34; margin-bottom: 24px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; }
        .section-title { font-weight: 700; color: #2f5d34; margin-bottom: 12px; font-size: 14px; }
        .sub-label { font-size: 10px; color: #9ca3af; font-weight: 600; letter-spacing: 0.5px; margin: 12px 0 6px; }
        .field { margin-bottom: 10px; }
        .field label { display: block; font-size: 11px; font-weight: 600; color: #4caf50; margin-bottom: 3px; }
        .field .val { border: 1px solid #d1d5db; border-radius: 6px; padding: 8px 10px; background: #fff; }
        @media print { body { padding: 16px; } }
    </style>
</head>
<body>
@php
    $loan  = $bookReturn->loan;
    $book  = $loan->book;
    $user  = $loan->user;
    $due   = \Carbon\Carbon::parse($loan->due_date);
    $ret   = \Carbon\Carbon::parse($bookReturn->return_date);
    $telat = $ret->gt($due) ? $ret->diffInDays($due) : 0;
@endphp
<h2>Detail Pengembalian Buku</h2>
<div class="grid">
    <div>
        <div class="section-title">Informasi Anggota</div>
        <div class="field"><label>NIS/NIK</label><div class="val">{{ $user->nik ?? '-' }}</div></div>
        <div class="field"><label>Nama Anggota</label><div class="val">{{ $user->name ?? '-' }}</div></div>
        <div class="field"><label>Jenis Kelamin</label><div class="val">{{ $user->gender ?? '-' }}</div></div>
        <div class="field"><label>Alamat</label><div class="val">{{ $user->address ?? '-' }}</div></div>

        <div class="section-title" style="margin-top:20px;">Informasi Buku</div>
        <div class="field"><label>Kode Buku</label><div class="val">{{ $book->kode_buku ?? '-' }}</div></div>
        <div class="field"><label>Judul Buku</label><div class="val">{{ $book->title ?? '-' }}</div></div>
        <div class="field"><label>Penulis</label><div class="val">{{ $book->author ?? '-' }}</div></div>
        <div class="field"><label>Tahun Terbit</label><div class="val">{{ $book->year ?? '-' }}</div></div>
    </div>
    <div>
        <div class="section-title">Detail Pengembalian</div>
        <div class="sub-label">INFORMASI PENGEMBALIAN</div>
        <div class="field"><label>Tanggal Pinjam</label><div class="val">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</div></div>
        <div class="field"><label>Tanggal Jatuh Tempo</label><div class="val">{{ $due->format('d/m/Y') }}</div></div>
        <div class="field"><label>Tanggal Pengembalian</label><div class="val">{{ $ret->format('d/m/Y') }}</div></div>
        <div class="field"><label>Total Telat</label><div class="val">{{ $telat > 0 ? $telat . ' Hari' : '-' }}</div></div>
        <div class="field"><label>Status</label><div class="val">Dikembalikan</div></div>
        <div class="sub-label">INFORMASI DENDA</div>
        <div class="field"><label>Total Denda</label><div class="val">{{ $bookReturn->fine > 0 ? 'Rp. ' . number_format($bookReturn->fine, 0, ',', '.') : '-' }}</div></div>
        <div class="field"><label>Jumlah Bayar - Kembalian</label><div class="val">{{ $bookReturn->fine > 0 ? 'Rp' . number_format($bookReturn->fine, 0, ',', '.') . ' - 0' : '-' }}</div></div>
        <div class="field"><label>Status Denda</label><div class="val">{{ $bookReturn->fine_status === 'lunas' ? 'Lunas' : ($bookReturn->fine_status === 'belum_lunas' ? 'Belum Lunas' : '-') }}</div></div>
    </div>
</div>
<script>window.onload = () => window.print();</script>
</body>
</html>
