@extends('layouts.kepala')
@section('title', 'Daftar Buku')

@section('content')

<div style="display:flex; align-items:center; gap:16px; margin-bottom:24px;">
    <a href="{{ route('kepala.buku.create') }}" class="btn-tambah-buku">
        📚 Tambah Buku
    </a>
    <form method="GET" action="{{ route('kepala.buku.index') }}" style="display:flex; gap:0; flex:1; max-width:480px; margin-left:auto;">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari kode buku, judul buku"
               class="input-cari-buku">
        <button type="submit" class="btn-cari-buku">Cari</button>
    </form>
</div>

<div class="table-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Judul Buku</th>
                <th>Kode Buku</th>
                <th>Penulis</th>
                <th>Tahun terbit</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $book)
            <tr>
                <td>
                    <div class="cover-thumb">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="">
                        @endif
                    </div>
                </td>
                <td style="font-weight:600;">{{ $book->title }}</td>
                <td>{{ $book->kode_buku ?? '-' }}</td>
                <td>{{ $book->author }}</td>
                <td>{{ $book->year ? '20/01/' . $book->year : '-' }}</td>
                <td>{{ $book->stock }}</td>
                <td>
                    <div class="aksi-group">
                        <button class="btn-view-buku"
                            data-title="{{ $book->title }}"
                            data-kode="{{ $book->kode_buku }}"
                            data-author="{{ $book->author }}"
                            data-year="{{ $book->year }}"
                            data-stock="{{ $book->stock }}"
                            data-cover="{{ $book->cover ? asset('storage/'.$book->cover) : '' }}"
                            title="Lihat Detail">
                            👁
                        </button>
                        <a href="{{ route('kepala.buku.edit', $book) }}" class="btn-aksi-edit">Edit</a>
                        <button class="btn-aksi-hapus"
                            data-id="{{ $book->id }}"
                            data-action="{{ route('kepala.buku.destroy', $book) }}">
                            Hapus
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="empty-row">Belum ada buku.</td></tr>
            @endforelse
        </tbody>
    </table>
    {!! $books->render('layouts._pagination') !!}
</div>

{{-- Modal Hapus --}}
<div class="modal-overlay" id="modalHapus">
    <div class="modal-box" style="max-width:420px; text-align:center;">
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
        <div class="modal-title" style="font-size:18px; margin-bottom:6px;">Yakin ingin hapus buku ini?</div>
        <div class="modal-subtitle">Tindakan ini akan hapus data secara permanen</div>
        <div class="modal-actions" style="justify-content:center; gap:16px; margin-top:24px;">
            <button class="btn-modal-batal" id="btnBatalHapus">Batal</button>
            <form id="formHapus" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modal-hapus">Hapus</button>
            </form>
        </div>
    </div>
</div>

{{-- Modal View Detail --}}
<div class="modal-overlay" id="modalView">
    <div class="modal-box" style="max-width:460px; text-align:left;">
        <div style="display:flex; gap:20px; align-items:flex-start; margin-bottom:20px;">
            <div id="viewCover" style="width:90px; height:120px; border-radius:8px; background:#e0e0e0; overflow:hidden; flex-shrink:0;"></div>
            <div style="flex:1;">
                <div id="viewTitle" style="font-size:18px; font-weight:700; color:#1e1e1e; margin-bottom:4px;"></div>
                <div id="viewAuthor" style="font-size:13px; color:#9ca3af; margin-bottom:16px;"></div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; font-size:13px;">
                    <div><span style="color:#4caf50; font-weight:600;">Kode Buku</span><br><span id="viewKode"></span></div>
                    <div><span style="color:#4caf50; font-weight:600;">Tahun Terbit</span><br><span id="viewYear"></span></div>
                    <div><span style="color:#4caf50; font-weight:600;">Stok</span><br><span id="viewStock"></span></div>
                </div>
            </div>
        </div>
        <div class="modal-actions" style="justify-content:flex-end;">
            <button class="btn-modal-hapus" id="btnTutupView">Tutup</button>
        </div>
    </div>
</div>

<script>
// Hapus
document.querySelectorAll('.btn-aksi-hapus').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('formHapus').action = btn.dataset.action;
        document.getElementById('modalHapus').classList.add('active');
    });
});
document.getElementById('btnBatalHapus').addEventListener('click', () => {
    document.getElementById('modalHapus').classList.remove('active');
});

// View detail
document.querySelectorAll('.btn-view-buku').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('viewTitle').textContent  = btn.dataset.title;
        document.getElementById('viewAuthor').textContent = btn.dataset.author;
        document.getElementById('viewKode').textContent   = btn.dataset.kode;
        document.getElementById('viewYear').textContent   = btn.dataset.year ? '20/01/' + btn.dataset.year : '-';
        document.getElementById('viewStock').textContent  = btn.dataset.stock + ' Buku';
        const cover = document.getElementById('viewCover');
        cover.innerHTML = btn.dataset.cover
            ? `<img src="${btn.dataset.cover}" style="width:100%;height:100%;object-fit:cover;">`
            : '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:36px;">📖</div>';
        document.getElementById('modalView').classList.add('active');
    });
});
document.getElementById('btnTutupView').addEventListener('click', () => {
    document.getElementById('modalView').classList.remove('active');
});
</script>

@endsection
