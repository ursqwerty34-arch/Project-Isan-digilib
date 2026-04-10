@extends('layouts.kepala')
@section('title', 'Daftar Anggota')

@section('content')

<div class="page-actions">
    <span class="breadcrumb-dots">...</span>
</div>

<form method="GET" action="{{ route('kepala.anggota.index') }}" id="filterForm" style="display:flex;gap:10px;align-items:center;margin-bottom:16px;flex-wrap:wrap;">
    {{-- Filter Kelas --}}
    <div class="custom-dropdown" id="dropKelas">
        <button type="button" class="custom-dropdown-btn" onclick="toggleDrop('dropKelas')">
            <span id="labelKelas">{{ request('kelas') ? 'Kelas '.request('kelas') : 'Semua Kelas' }}</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="custom-dropdown-menu">
            <div class="custom-dropdown-item {{ !request('kelas') ? 'active' : '' }}" onclick="selectFilter('kelas', '', 'Semua Kelas', 'labelKelas')">
                Semua Kelas
            </div>
            @foreach(['X','XI','XII'] as $k)
            <div class="custom-dropdown-item {{ request('kelas') === $k ? 'active' : '' }}" onclick="selectFilter('kelas', '{{ $k }}', 'Kelas {{ $k }}', 'labelKelas')">
                Kelas {{ $k }}
            </div>
            @endforeach
        </div>
        <input type="hidden" name="kelas" id="inputKelas" value="{{ request('kelas') }}">
    </div>

    {{-- Filter Jurusan --}}
    <div class="custom-dropdown" id="dropJurusan">
        <button type="button" class="custom-dropdown-btn" onclick="toggleDrop('dropJurusan')">
            <span id="labelJurusan">{{ request('jurusan') ? request('jurusan') : 'Semua Jurusan' }}</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="custom-dropdown-menu">
            <div class="custom-dropdown-item {{ !request('jurusan') ? 'active' : '' }}" onclick="selectFilter('jurusan', '', 'Semua Jurusan', 'labelJurusan')">
                Semua Jurusan
            </div>
            @foreach(['AKL','PPLG','TBSM','TKRO','APHP','APAT'] as $j)
            <div class="custom-dropdown-item {{ request('jurusan') === $j ? 'active' : '' }}" onclick="selectFilter('jurusan', '{{ $j }}', '{{ $j }}', 'labelJurusan')">
                {{ $j }}
            </div>
            @endforeach
        </div>
        <input type="hidden" name="jurusan" id="inputJurusan" value="{{ request('jurusan') }}">
    </div>

    @if(request('kelas') || request('jurusan'))
        <a href="{{ route('kepala.anggota.index') }}" class="filter-reset-btn">✕ Reset</a>
    @endif
    <span style="font-size:13px;color:#6b6b6b;margin-left:auto;">Total: <strong>{{ $anggota->count() }}</strong> anggota</span>
</form>

<style>
.custom-dropdown {
    position: relative;
    display: inline-block;
}
.custom-dropdown-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    background: #ffffff;
    border: 1.5px solid #c8e6c9;
    border-radius: 50px;
    font-family: 'Poppins-SemiBold', 'Segoe UI', Helvetica, sans-serif;
    font-size: 13px;
    font-weight: 600;
    color: #2f5d34;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s;
    white-space: nowrap;
    min-width: 140px;
    justify-content: space-between;
}
.custom-dropdown-btn:hover,
.custom-dropdown.open .custom-dropdown-btn {
    border-color: #2f5d34;
    box-shadow: 0 0 0 3px rgba(47,93,52,0.1);
}
.custom-dropdown-menu {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    background: #ffffff;
    border: 1.5px solid #c8e6c9;
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(47,93,52,0.12);
    min-width: 160px;
    z-index: 50;
    overflow: hidden;
    padding: 6px 0;
}
.custom-dropdown.open .custom-dropdown-menu { display: block; }
.custom-dropdown-item {
    padding: 10px 18px;
    font-size: 13px;
    font-family: 'Poppins-Regular', 'Segoe UI', Helvetica, sans-serif;
    color: #374151;
    cursor: pointer;
    transition: background 0.15s;
}
.custom-dropdown-item:hover { background: #f0faf0; color: #2f5d34; }
.custom-dropdown-item.active {
    background: #e8f5e9;
    color: #2f5d34;
    font-family: 'Poppins-SemiBold', 'Segoe UI', Helvetica, sans-serif;
    font-weight: 600;
}
.filter-reset-btn {
    padding: 9px 16px;
    background: #fef2f2;
    color: #ef5350;
    border: 1.5px solid #fca5a5;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s;
}
.filter-reset-btn:hover { background: #fee2e2; }
</style>

<script>
function toggleDrop(id) {
    document.querySelectorAll('.custom-dropdown').forEach(d => {
        if (d.id !== id) d.classList.remove('open');
    });
    document.getElementById(id).classList.toggle('open');
}
function selectFilter(field, value, label, labelId) {
    document.getElementById('input' + field.charAt(0).toUpperCase() + field.slice(1)).value = value;
    document.getElementById(labelId).textContent = label;
    document.querySelectorAll('.custom-dropdown').forEach(d => d.classList.remove('open'));
    document.getElementById('filterForm').submit();
}
document.addEventListener('click', e => {
    if (!e.target.closest('.custom-dropdown')) {
        document.querySelectorAll('.custom-dropdown').forEach(d => d.classList.remove('open'));
    }
});
</script>

<div class="table-card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Profile</th>
                <th>Username</th>
                <th>Email</th>
                <th>No Telp</th>
                <th>NISN</th>
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
                        <button type="button" class="btn-aksi edit"
                            onclick="showResetModal('{{ route('kepala.anggota.resetPassword', $a) }}', '{{ $a->name }}')">
                            Reset
                        </button>
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
    {!! $anggota->render('layouts._pagination') !!}
</div>

@endsection

@section('modal')
<div class="modal-overlay" id="resetModal" onclick="hideResetModal(event)">
    <div class="modal-box">
        <div class="modal-mascot">
            <svg viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                <circle cx="40" cy="40" r="38" fill="#e8f5e9" stroke="#c8e6c9" stroke-width="2"/>
                <rect x="18" y="18" width="44" height="44" rx="10" fill="#ffffff" stroke="#2f5d34" stroke-width="3"/>
                <circle cx="31" cy="37" r="4" fill="#2f5d34"/>
                <circle cx="49" cy="37" r="4" fill="#2f5d34"/>
                <path d="M29,50 Q40,58 51,50" stroke="#2f5d34" stroke-width="2.5" fill="none" stroke-linecap="round"/>
            </svg>
        </div>
        <h3 class="modal-title">Reset password <span id="resetName"></span>?</h3>
        <p class="modal-subtitle">Password akan direset menjadi <strong>12345678</strong></p>
        <div class="modal-actions">
            <button type="button" class="btn-modal-batal" onclick="hideResetModal()">Batal</button>
            <form id="resetForm" method="POST">
                @csrf
                <button type="submit" class="btn-modal-hapus">Reset</button>
            </form>
        </div>
    </div>
</div>

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
function showResetModal(url, name) {
    document.getElementById('resetForm').action = url;
    document.getElementById('resetName').textContent = name;
    document.getElementById('resetModal').classList.add('active');
}
function hideResetModal(e) {
    if (!e || e.target === document.getElementById('resetModal')) {
        document.getElementById('resetModal').classList.remove('active');
    }
}
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
