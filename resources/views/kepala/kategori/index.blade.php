@extends('layouts.kepala')
@section('title', 'Kategori Buku')

@section('content')

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="page-actions" style="margin-bottom:20px;">
    <span style="font-size:18px;font-weight:700;color:#1e1e1e;">🏷️ Kategori Buku</span>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

    {{-- Form Tambah --}}
    <div class="table-card" style="padding:24px;">
        <div style="font-weight:700;font-size:15px;color:#2f5d34;margin-bottom:16px;">Tambah Kategori Baru</div>
        <form method="POST" action="{{ route('kepala.kategori.store') }}" style="display:flex;gap:10px;">
            @csrf
            <input type="text" name="name" placeholder="Nama kategori..." required
                style="flex:1;padding:11px 14px;border:1.5px solid #d1d5db;border-radius:10px;font-size:14px;outline:none;">
            <button type="submit" class="btn-tambah">Tambah</button>
        </form>
        @error('name')<p style="color:#ef5350;font-size:12px;margin-top:6px;">{{ $message }}</p>@enderror
    </div>

    {{-- Daftar Kategori --}}
    <div class="table-card">
        <table class="data-table">
            <thead><tr>
                <th>Nama Kategori</th>
                <th>Jumlah Buku</th>
                <th>Aksi</th>
            </tr></thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->books_count }} buku</td>
                    <td>
                        <div class="aksi-group">
                            <button class="btn-aksi edit" onclick="openEdit({{ $cat->id }}, '{{ $cat->name }}')">Edit</button>
                            <form method="POST" action="{{ route('kepala.kategori.destroy', $cat) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-aksi hapus">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="empty-row">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- Modal Edit --}}
<div class="modal-overlay" id="modalEdit">
    <div class="modal-box" style="max-width:400px;">
        <div class="modal-title" style="margin-bottom:16px;">Edit Kategori</div>
        <form method="POST" id="formEdit">
            @csrf @method('PUT')
            <input type="text" id="editName" name="name" required
                style="width:100%;padding:11px 14px;border:1.5px solid #d1d5db;border-radius:10px;font-size:14px;outline:none;margin-bottom:16px;">
            <div class="modal-actions" style="justify-content:center;gap:12px;">
                <button type="button" class="btn-modal-batal" onclick="closeEdit()">Batal</button>
                <button type="submit" class="btn-modal-hapus">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEdit(id, name) {
    document.getElementById('editName').value = name;
    document.getElementById('formEdit').action = '/kepala/kategori/' + id;
    document.getElementById('modalEdit').classList.add('active');
}
function closeEdit() {
    document.getElementById('modalEdit').classList.remove('active');
}
</script>

@endsection
