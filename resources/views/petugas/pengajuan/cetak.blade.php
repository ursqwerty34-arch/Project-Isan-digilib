<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Detail Pengajuan - {{ $loan->book->title ?? '' }}</title>
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
    <h2>Detail Pengajuan Peminjaman Buku</h2>
    <div class="grid">
        <div>
            <div class="section-title">Informasi Anggota</div>
            <div class="field"><label>NIS/NIK</label><div class="val">{{ $loan->user->nik ?? '-' }}</div></div>
            <div class="field"><label>Nama Anggota</label><div class="val">{{ $loan->user->name ?? '-' }}</div></div>
            <div class="field"><label>Jenis Kelamin</label><div class="val">{{ $loan->user->gender ?? '-' }}</div></div>
            <div class="field"><label>Alamat</label><div class="val">{{ $loan->user->address ?? '-' }}</div></div>
        </div>
        <div>
            <div class="section-title">Detail Pengajuan</div>
            <div class="sub-label">INFORMASI PENGAJUAN</div>
            <div class="field"><label>Tanggal Pinjam</label><div class="val">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</div></div>
            <div class="field"><label>Tanggal Jatuh Tempo</label><div class="val">{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-' }}</div></div>
            <div class="field"><label>Status</label><div class="val">{{ $loan->pengajuan_status === 'disetujui' ? 'Dipinjam' : ucfirst($loan->pengajuan_status) }}</div></div>
            @if($loan->pengajuan_status === 'ditolak')
            <div class="field"><label>Alasan ditolak</label><div class="val">{{ $loan->rejection_reason ?? '-' }}</div></div>
            @endif
            <div class="sub-label">INFORMASI BUKU</div>
            <div class="field"><label>Kode Buku</label><div class="val">{{ $loan->book->kode_buku ?? '-' }}</div></div>
            <div class="field"><label>Judul Buku</label><div class="val">{{ $loan->book->title ?? '-' }}</div></div>
            <div class="field"><label>Penulis</label><div class="val">{{ $loan->book->author ?? '-' }}</div></div>
            <div class="field"><label>Tahun Terbit</label><div class="val">{{ $loan->book->year ?? '-' }}</div></div>
        </div>
    </div>
    <script>window.onload = () => window.print();</script>
</body>
</html>
