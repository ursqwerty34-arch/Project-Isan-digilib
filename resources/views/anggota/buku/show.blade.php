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
            @if($book->category)
                <span style="background:#e8f5e9;color:#2f5d34;padding:3px 12px;border-radius:50px;font-size:12px;font-weight:600;display:inline-block;margin-bottom:10px;">🏷️ {{ $book->category->name }}</span>
            @endif
            <div class="ang-detail-title">{{ $book->title }}</div>
            <div class="ang-detail-author">{{ $book->author }}</div>

            {{-- Tombol Favorit --}}
            <button id="btnFavorit"
                data-url="{{ route('anggota.buku.favorit', $book) }}"
                data-fav="{{ $isFavorite ? '1' : '0' }}"
                class="btn-favorit {{ $isFavorite ? 'active' : '' }}"
                title="{{ $isFavorite ? 'Hapus dari favorit' : 'Tambah ke favorit' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" class="fav-icon" fill="{{ $isFavorite ? '#ef5350' : 'none' }}" stroke="#ef5350" stroke-width="2">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <span id="favLabel">{{ $isFavorite ? 'Difavoritkan' : 'Tambah Favorit' }}</span>
            </button>

            {{-- Rating ringkasan --}}
            @php $avg = $book->avgRating(); $total = $book->reviews()->count(); @endphp
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                <div class="star-display" data-rating="{{ $avg }}"></div>
                <span style="font-size:13px;color:#6b6b6b;">{{ $avg > 0 ? number_format($avg,1) : '-' }} ({{ $total }} ulasan)</span>
            </div>

            <div class="ang-detail-grid">
                <div class="ang-detail-item">
                    <div class="ang-detail-label">Kode Buku</div>
                    <div class="ang-detail-value">{{ $book->kode_buku ?? '-' }}</div>
                </div>
                <div class="ang-detail-item">
                    <div class="ang-detail-label">Tahun Terbit</div>
                    <div class="ang-detail-value">{{ $book->year ?? '-' }}</div>
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

            @if($book->synopsis)
            <div style="margin-bottom:24px;">
                <div class="ang-detail-label" style="margin-bottom:8px;">Sinopsis</div>
                <div style="font-size:14px;color:#374151;line-height:1.8;">{{ $book->synopsis }}</div>
            </div>
            @endif

            @if($statusPinjam === 'pending')
                <button class="ang-btn-pinjam" disabled style="opacity:0.6;cursor:not-allowed;">Menunggu Konfirmasi 🕐</button>
            @elseif($statusPinjam === 'dipinjam')
                @if($activeLoan->return_requested)
                    <button class="ang-btn-pinjam" disabled style="opacity:0.6;cursor:not-allowed;background:#9ca3af;">Pengembalian Diproses 🔄</button>
                @else
                    <button class="ang-btn-pinjam" id="btnKembalikan"
                        style="background:#f59e0b;"
                        data-url="{{ route('anggota.transaksi.kembalikan', $activeLoan) }}">
                        Kembalikan Buku 📚
                    </button>
                @endif
            @elseif($book->stock < 1)
                <button class="ang-btn-pinjam" disabled style="opacity:0.6;cursor:not-allowed;">Stok Habis</button>
            @else
                <div class="ang-pinjam-form" id="pinjamForm" style="display:none;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                        <div>
                            <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Jumlah Buku</label>
                            <input type="number" id="inputQty" min="1" max="{{ $book->stock }}" value="1"
                                style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:10px;font-size:14px;outline:none;">
                            <span style="font-size:11px;color:#9ca3af;">Maks: {{ $book->stock }} buku</span>
                        </div>
                        <div>
                            <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Durasi Peminjaman</label>
                            <select id="inputDuration" data-cs data-cs-form style="display:none">
                                <option value="7">7 hari</option>
                                <option value="14">14 hari</option>
                                <option value="20">20 hari (maks)</option>
                            </select>
                            <span style="font-size:11px;color:#9ca3af;">Jatuh tempo dihitung saat disetujui</span>
                        </div>
                    </div>
                    <div style="display:flex;gap:10px;">
                        <button class="ang-btn-pinjam" id="btnKirimPinjam" style="flex:1;">Kirim Pengajuan 🖐</button>
                        <button onclick="document.getElementById('pinjamForm').style.display='none';document.getElementById('btnShowForm').style.display='inline-flex';"
                            style="padding:13px 20px;background:#f3f4f6;color:#374151;border:none;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;">Batal</button>
                    </div>
                </div>
                <button class="ang-btn-pinjam" id="btnShowForm" onclick="document.getElementById('pinjamForm').style.display='block';this.style.display='none';">
                    Ajukan Peminjaman 🖐
                </button>
            @endif
        </div>
    </div>
</div>

{{-- SECTION ULASAN --}}
@if($bolehReview)
<div class="ang-detail-card" style="margin-top:24px;">
    <div style="font-size:15px;font-weight:700;color:#2f5d34;margin-bottom:16px;">✍️ Tulis Ulasanmu</div>

    {{-- Form ulasan --}}
    <div style="margin-bottom:20px;">
        <div style="font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">Rating</div>
        <div class="star-input" id="starInput" data-value="{{ $myReview?->rating ?? 0 }}">
            @for($i = 1; $i <= 5; $i++)
                <span class="star-item {{ ($myReview && $myReview->rating >= $i) ? 'active' : '' }}" data-val="{{ $i }}">★</span>
            @endfor
        </div>
        <input type="hidden" id="ratingVal" value="{{ $myReview?->rating ?? 0 }}">
    </div>
    <div style="margin-bottom:16px;">
        <div style="font-size:13px;font-weight:600;color:#374151;margin-bottom:8px;">Komentar <span style="color:#9ca3af;font-weight:400;">(opsional)</span></div>
        <textarea id="reviewComment" rows="3" placeholder="Bagikan pengalamanmu membaca buku ini..."
            style="width:100%;padding:12px 14px;border:1.5px solid #d1d5db;border-radius:10px;font-size:14px;font-family:inherit;resize:vertical;outline:none;box-sizing:border-box;">{{ $myReview?->comment }}</textarea>
    </div>
    <button id="btnKirimReview" class="ang-btn-pinjam" style="padding:11px 28px;font-size:14px;">
        {{ $myReview ? 'Perbarui Ulasan' : 'Kirim Ulasan' }}
    </button>
    <span id="reviewMsg" style="margin-left:12px;font-size:13px;color:#2f5d34;display:none;">✓ Tersimpan!</span>
</div>
@endif

{{-- Daftar ulasan --}}
@if($reviews->count())
<div class="ang-detail-card" style="margin-top:16px;">
    <div style="font-size:15px;font-weight:700;color:#2f5d34;margin-bottom:16px;">💬 Ulasan Pembaca ({{ $reviews->count() }})</div>
    <div style="display:flex;flex-direction:column;gap:14px;">
        @foreach($reviews as $rv)
        <div style="background:#f9fdf9;border-radius:12px;padding:14px 16px;border:1px solid #e8f5e9;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                <div style="width:32px;height:32px;border-radius:50%;background:#c8e6c9;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#2f5d34;flex-shrink:0;">
                    {{ strtoupper(substr($rv->user->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:13px;font-weight:600;color:#1e1e1e;">{{ $rv->user->name }}</div>
                    <div class="star-display" data-rating="{{ $rv->rating }}" style="font-size:13px;"></div>
                </div>
                <div style="margin-left:auto;font-size:11px;color:#9ca3af;">{{ $rv->created_at->diffForHumans() }}</div>
            </div>
            @if($rv->comment)
            <div style="font-size:13px;color:#374151;line-height:1.6;padding-left:42px;">{{ $rv->comment }}</div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection

@section('modal')
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

{{-- Modal konfirmasi kembalikan --}}
<div class="modal-overlay" id="modalKembalikan">
    <div class="modal-box" style="max-width:400px;text-align:center;">
        <div class="modal-mascot-circle" style="margin-bottom:20px;">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                <rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/>
                <circle cx="18" cy="22" r="2.5" fill="#2f5d34"/>
                <circle cx="30" cy="22" r="2.5" fill="#2f5d34"/>
                <path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                <line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/>
                <circle cx="24" cy="2" r="1.5" fill="#4caf50"/>
            </svg>
            <span class="modal-mascot-dot"></span>
        </div>
        <div class="modal-title" style="font-size:16px;margin-bottom:24px;">Ingin kembalikan Buku Ini?</div>
        <div class="modal-actions" style="justify-content:center;gap:16px;">
            <button class="btn-modal-batal" id="btnNantiKembalikan">Nanti saja</button>
            <button class="btn-modal-hapus" id="btnYaKembalikan">Ya</button>
        </div>
    </div>
</div>

{{-- Modal sukses kembalikan --}}
<div class="modal-overlay" id="modalSuksesKembalikan">
    <div class="modal-box" style="max-width:420px;text-align:center;">
        <div class="modal-mascot-circle" style="margin-bottom:20px;">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                <rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/>
                <circle cx="18" cy="22" r="2.5" fill="#2f5d34"/>
                <circle cx="30" cy="22" r="2.5" fill="#2f5d34"/>
                <path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                <line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/>
                <circle cx="24" cy="2" r="1.5" fill="#4caf50"/>
            </svg>
            <span class="modal-mascot-dot"></span>
        </div>
        <div class="modal-title" style="color:#2f5d34;font-size:16px;margin-bottom:24px;">
            Pengajuan pengembalian buku telah<br>terkirim, silahkan tunggu konfirmasi...
        </div>
        <div class="modal-actions" style="justify-content:center;">
            <button class="btn-modal-hapus" id="btnMengertiKembalikan">Mengerti</button>
        </div>
    </div>
</div>

<script>
// ── Peminjaman ──
document.getElementById('btnKirimPinjam')?.addEventListener('click', () => {
    const qty      = document.getElementById('inputQty').value;
    const duration = document.getElementById('inputDuration').value;
    if (!qty || qty < 1) { alert('Masukkan jumlah buku.'); return; }
    fetch('{{ route('anggota.buku.ajukan', $book) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ qty, duration })
    }).then(r => r.json()).then(d => {
        if (d.success) document.getElementById('modalSukses').classList.add('active');
        else alert(d.message || 'Gagal mengajukan peminjaman.');
    });
});
document.getElementById('btnMengerti')?.addEventListener('click', () => {
    document.getElementById('modalSukses').classList.remove('active');
    const btn = document.getElementById('btnShowForm');
    if (btn) { btn.innerHTML = 'Menunggu Konfirmasi 🕐'; btn.disabled = true; btn.style.opacity = '0.6'; btn.style.cursor = 'not-allowed'; }
    document.getElementById('pinjamForm').style.display = 'none';
});

// ── Favorit ──
const btnFav = document.getElementById('btnFavorit');
btnFav?.addEventListener('click', () => {
    fetch(btnFav.dataset.url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }).then(r => r.json()).then(d => {
        if (!d.success) return;
        const icon = btnFav.querySelector('.fav-icon');
        const label = document.getElementById('favLabel');
        if (d.is_favorite) {
            icon.setAttribute('fill', '#ef5350');
            label.textContent = 'Difavoritkan';
            btnFav.classList.add('active');
        } else {
            icon.setAttribute('fill', 'none');
            label.textContent = 'Tambah Favorit';
            btnFav.classList.remove('active');
        }
    });
});

// ── Kembalikan buku dari halaman detail ──
document.getElementById('btnKembalikan')?.addEventListener('click', function() {
    document.getElementById('modalKembalikan').classList.add('active');
});
document.getElementById('btnNantiKembalikan')?.addEventListener('click', () => {
    document.getElementById('modalKembalikan').classList.remove('active');
});
document.getElementById('btnYaKembalikan')?.addEventListener('click', function() {
    const btn = document.getElementById('btnKembalikan');
    fetch(btn.dataset.url, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }).then(r => r.json()).then(d => {
        if (d.success) {
            document.getElementById('modalKembalikan').classList.remove('active');
            document.getElementById('modalSuksesKembalikan').classList.add('active');
        }
    });
});
document.getElementById('btnMengertiKembalikan')?.addEventListener('click', () => {
    const btn = document.getElementById('btnKembalikan');
    btn.textContent = 'Pengembalian Diproses 🔄';
    btn.disabled = true;
    btn.style.opacity = '0.6';
    btn.style.background = '#9ca3af';
    btn.style.cursor = 'not-allowed';
    document.getElementById('modalSuksesKembalikan').classList.remove('active');
});

// ── Star input ──
document.querySelectorAll('.star-item').forEach(star => {
    star.addEventListener('mouseover', () => {
        const val = +star.dataset.val;
        document.querySelectorAll('.star-item').forEach(s => s.classList.toggle('active', +s.dataset.val <= val));
    });
    star.addEventListener('mouseleave', () => {
        const cur = +document.getElementById('ratingVal').value;
        document.querySelectorAll('.star-item').forEach(s => s.classList.toggle('active', +s.dataset.val <= cur));
    });
    star.addEventListener('click', () => {
        document.getElementById('ratingVal').value = star.dataset.val;
        document.querySelectorAll('.star-item').forEach(s => s.classList.toggle('active', +s.dataset.val <= +star.dataset.val));
    });
});

// ── Kirim ulasan ──
document.getElementById('btnKirimReview')?.addEventListener('click', () => {
    const rating  = document.getElementById('ratingVal').value;
    const comment = document.getElementById('reviewComment').value;
    if (!rating || rating < 1) { alert('Pilih rating bintang terlebih dahulu.'); return; }
    fetch('{{ route('anggota.buku.review', $book) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ rating, comment })
    }).then(r => r.json()).then(d => {
        if (d.success) {
            const msg = document.getElementById('reviewMsg');
            msg.style.display = 'inline';
            document.getElementById('btnKirimReview').textContent = 'Perbarui Ulasan';
            setTimeout(() => msg.style.display = 'none', 3000);
        } else alert(d.message || 'Gagal menyimpan ulasan.');
    });
});

// ── Star display (read-only) ──
document.querySelectorAll('.star-display').forEach(el => {
    const rating = parseFloat(el.dataset.rating) || 0;
    let html = '';
    for (let i = 1; i <= 5; i++) {
        if (rating >= i) html += '<span style="color:#f59e0b;">★</span>';
        else if (rating >= i - 0.5) html += '<span style="color:#f59e0b;">½</span>';
        else html += '<span style="color:#d1d5db;">★</span>';
    }
    el.innerHTML = html;
});
</script>
@endsection
