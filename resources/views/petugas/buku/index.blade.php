@extends('layouts.petugas')
@section('title', 'Daftar Buku')

@section('content')

{{-- Toolbar --}}
<div style="display:flex; align-items:center; gap:16px; margin-bottom:24px;">
    <a href="{{ route('petugas.buku.create') }}" class="btn-tambah-buku">
        📚 Tambah Buku
    </a>
    <form method="GET" action="{{ route('petugas.buku.index') }}" style="display:flex; gap:0; flex:1; max-width:480px; margin-left:auto;">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari kode buku, judul buku"
               class="input-cari-buku">
        <button type="submit" class="btn-cari-buku">Cari</button>
    </form>
</div>

{{-- Tabel --}}
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
                        <a href="{{ route('petugas.buku.edit', $book) }}" class="btn-aksi-edit">Edit</a>
                        <button class="btn-aksi-hapus"
                            data-id="{{ $book->id }}"
                            data-action="{{ route('petugas.buku.destroy', $book) }}">
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

<script>
document.querySelectorAll('.btn-aksi-hapus').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('formHapus').action = btn.dataset.action;
        document.getElementById('modalHapus').classList.add('active');
    });
});
document.getElementById('btnBatalHapus').addEventListener('click', () => {
    document.getElementById('modalHapus').classList.remove('active');
});
</script>

@endsection
