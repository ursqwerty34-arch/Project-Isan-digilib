@extends('layouts.kepala')
@section('title', 'Daftar Anggota')

@section('content')

<div class="page-actions">
    <span class="breadcrumb-dots">...</span>
</div>

<div class="table-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Profile</th>
                <th>Username</th>
                <th>Email</th>
                <th>No Telp</th>
                <th>NIK/NIS</th>
                <th>Nama Lengkap Anggota</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggota as $a)
            <tr>
                <td>
                    <div class="avatar">
                        @if($a->photo)
                            <img src="{{ asset('storage/' . $a->photo) }}" alt="foto"/>
                        @else
                            <div class="avatar-placeholder">👤</div>
                        @endif
                    </div>
                </td>
                <td>{{ $a->username ?? '-' }}</td>
                <td>{{ $a->email }}</td>
                <td>{{ $a->phone ?? '-' }}</td>
                <td>{{ $a->nik ?? '-' }}</td>
                <td>{{ $a->name }}</td>
                <td>
                    <div class="aksi-group">
                        <a href="{{ route('kepala.anggota.show', $a) }}" class="btn-aksi view" title="Lihat">👁</a>
                        <button type="button" class="btn-aksi hapus"
                            onclick="showDeleteModal('{{ route('kepala.anggota.destroy', $a) }}')">
                            Hapus
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="empty-row">Belum ada anggota terdaftar.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@section('modal')
<div class="modal-overlay" id="deleteModal" onclick="hideDeleteModal(event)">
    <div class="modal-box">
        <div class="modal-mascot">
            <svg viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                <circle cx="40" cy="40" r="38" fill="#e8f5e9" stroke="#c8e6c9" stroke-width="2"/>
                <rect x="18" y="18" width="44" height="44" rx="10" fill="#ffffff" stroke="#2f5d34" stroke-width="3"/>
                <circle cx="31" cy="37" r="4" fill="#2f5d34"/>
                <circle cx="49" cy="37" r="4" fill="#2f5d34"/>
                <path d="M29,50 Q40,58 51,50" stroke="#2f5d34" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                <ellipse cx="58" cy="20" rx="7" ry="4" fill="#4caf50" transform="rotate(-35 58 20)"/>
                <line x1="57" y1="22" x2="56" y2="15" stroke="#2f5d34" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
        </div>
        <h3 class="modal-title">Yakin ingin hapus akun anggota ini?</h3>
        <p class="modal-subtitle">Tindakan ini akan hapus data secara permanen</p>
        <div class="modal-actions">
            <button type="button" class="btn-modal-batal" onclick="hideDeleteModal()">Batal</button>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn-modal-hapus">Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
function showDeleteModal(url) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteModal').classList.add('active');
}
function hideDeleteModal(e) {
    if (!e || e.target === document.getElementById('deleteModal')) {
        document.getElementById('deleteModal').classList.remove('active');
    }
}
</script>
@endsection
