@extends('layouts.anggota')
@section('title', 'Detail Buku')

@section('content')

<a href="{{ route('anggota.buku.index') }}" class="ang-back-link">← Kembali ke Koleksi</a>

<div class="ang-detail-card">
    <div class="ang-detail-inner">
        <div class="ang-detail-cover">
            @if($book->cover)
                <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}">
            @else
                <div class="ang-detail-cover-ph">📖</div>
            @endif
        </div>
        <div class="ang-detail-info">
            <div class="ang-detail-title">{{ $book->title }}</div>
            <div class="ang-detail-author">{{ $book->author }}</div>
            <div class="ang-detail-grid">
                <div class="ang-detail-item">
                    <div class="ang-detail-label">Kode Buku</div>
                    <div class="ang-detail-value">{{ $book->kode_buku ?? '-' }}</div>
                </div>
                <div class="ang-detail-item">
                    <div class="ang-detail-label">Tahun Terbit</div>
                    <div class="ang-detail-value">{{ $book->year ? \Carbon\Carbon::parse($book->year)->format('d/m/Y') : '-' }}</div>
                </div>
                <div class="ang-detail-item">
                    <div class="ang-detail-label">Penulis</div>
                    <div class="ang-detail-value">{{ $book->author }}</div>
                </div>
                <div class="ang-detail-item">
                    <div class="ang-detail-label">Stok Tersedia</div>
                    <div class="ang-detail-value">{{ $book->stock ?? 0 }} Buku</div>
                </div>
            </div>
            @if($sudahDiajukan)
                <button class="ang-btn-pinjam" disabled style="opacity:0.6;cursor:not-allowed;">Menunggu Konfirmasi 🕐</button>
            @else
                <button class="ang-btn-pinjam" id="btnAjukanPeminjaman">Ajukan Peminjaman 🖐</button>
            @endif
        </div>
    </div>
</div>

@endsection

@section('modal')
<div class="modal-overlay" id="modalKonfirmasi">
    <div class="modal-box" style="max-width:400px;text-align:center;">
        <div class="modal-mascot-circle" style="margin-bottom:16px;">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/><circle cx="18" cy="22" r="2.5" fill="#2f5d34"/><circle cx="30" cy="22" r="2.5" fill="#2f5d34"/><path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/><line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/><circle cx="24" cy="2" r="1.5" fill="#4caf50"/></svg>
            <span class="modal-mascot-dot"></span>
        </div>
        <div class="modal-title" style="margin-bottom:24px;">Ingin Ajukan Peminjaman Buku Ini?</div>
        <div class="modal-actions" style="justify-content:center;gap:16px;">
            <button class="btn-modal-batal" id="btnNantiSaja">Nanti saja</button>
            <button class="btn-modal-hapus" id="btnYaPinjam">Ya, Pinjam</button>
        </div>
    </div>
</div>
<div class="modal-overlay" id="modalSukses">
    <div class="modal-box" style="max-width:400px;text-align:center;">
        <div class="modal-mascot-circle" style="margin-bottom:16px;">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none"><rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/><circle cx="18" cy="22" r="2.5" fill="#2f5d34"/><circle cx="30" cy="22" r="2.5" fill="#2f5d34"/><path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/><line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/><circle cx="24" cy="2" r="1.5" fill="#4caf50"/></svg>
            <span class="modal-mascot-dot"></span>
        </div>
        <div class="modal-title" style="font-size:15px;margin-bottom:24px;">Pengajuan peminjaman buku telah terkirim, silahkan tunggu konfirmasi...</div>
        <div class="modal-actions" style="justify-content:center;">
            <button class="btn-modal-hapus" id="btnMengerti">Mengerti</button>
        </div>
    </div>
</div>
<script>
document.getElementById('btnAjukanPeminjaman')?.addEventListener('click', () => document.getElementById('modalKonfirmasi').classList.add('active'));
document.getElementById('btnNantiSaja').addEventListener('click', () => document.getElementById('modalKonfirmasi').classList.remove('active'));
document.getElementById('btnYaPinjam').addEventListener('click', () => {
    fetch('{{ route('anggota.buku.ajukan', $book) }}', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}
    }).then(r=>r.json()).then(()=>{
        document.getElementById('modalKonfirmasi').classList.remove('active');
        document.getElementById('modalSukses').classList.add('active');
    });
});
document.getElementById('btnMengerti').addEventListener('click', () => {
    document.getElementById('modalSukses').classList.remove('active');
    const btn = document.getElementById('btnAjukanPeminjaman');
    if(btn){ btn.innerHTML='Menunggu Konfirmasi 🕐'; btn.disabled=true; btn.style.opacity='0.6'; btn.style.cursor='not-allowed'; }
});
</script>
@endsection
