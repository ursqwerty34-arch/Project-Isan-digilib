@extends('layouts.kepala')
@section('title', 'Detail Petugas')

@section('content')

<div class="show-card">

    {{-- Nama & Badge --}}
    <div class="show-header">
        <h2 class="show-name">{{ $user->name }}</h2>
        <span class="show-role">Petugas</span>
    </div>

    {{-- Body: foto + info --}}
    <div class="show-body">

        {{-- Foto --}}
        <div class="show-photo">
            @if($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}" alt="foto {{ $user->name }}"/>
            @else
                <div class="show-photo-placeholder">
                    <svg viewBox="0 0 100 120" xmlns="http://www.w3.org/2000/svg" width="100" height="120">
                        <rect width="100" height="120" fill="#d1d5db"/>
                        <circle cx="50" cy="42" r="22" fill="#9ca3af"/>
                        <ellipse cx="50" cy="95" rx="32" ry="22" fill="#9ca3af"/>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Info 2 kolom --}}
        <div class="show-info-grid">

            <div class="show-info-col">
                <div class="show-info-item">
                    <span class="info-label">Username :</span>
                    <span class="info-value">{{ $user->username ?? '-' }}</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Email :</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">No telp :</span>
                    <span class="info-value">{{ $user->phone ?? '-' }}</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Role :</span>
                    <span class="info-value">Petugas</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Password :</span>
                    <span class="info-value" style="display:flex;align-items:center;gap:8px;">
                        <span id="passDisplay">{{ $user->plain_password ?? '(tidak tersedia)' }}</span>
                        <button type="button" onclick="toggleShowPassword()" style="background:none;border:none;cursor:pointer;padding:0;display:flex;align-items:center;" title="Sembunyikan/Tampilkan">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Bergabung Pada :</span>
                    <span class="info-value">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            <div class="show-info-col">
                <div class="show-info-item">
                    <span class="info-label">NIK :</span>
                    <span class="info-value">{{ $user->nik ?? '-' }}</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Nama Lengkap Anggota :</span>
                    <span class="info-value">{{ $user->name }}</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Jenis Kelamin :</span>
                    <span class="info-value">{{ $user->gender ?? '-' }}</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Tanggal Lahir :</span>
                    <span class="info-value">
                        {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d/m/Y') : '-' }}
                    </span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Alamat Lengkap :</span>
                    <span class="info-value">{{ $user->address ?? '-' }}</span>
                </div>
            </div>

        </div>
    </div>

    {{-- Tombol aksi --}}
    <div class="show-actions">
        <a href="{{ route('kepala.petugas.index') }}" class="btn-kembali">Kembali</a>
        <div class="show-actions-right">
            <a href="{{ route('kepala.petugas.edit', $user) }}" class="btn-edit-akun">Edit Akun</a>
            <button type="button" class="btn-hapus-akun"
                onclick="showDeleteModal('{{ route('kepala.petugas.destroy', $user) }}')">Hapus Akun</button>
        </div>
    </div>

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
        <h3 class="modal-title">Yakin ingin hapus akun petugas ini?</h3>
        <p class="modal-subtitle">Tindakan ini akan hapus data secara permanen</p>
        <div class="modal-actions">
            <button type="button" class="btn-modal-batal" onclick="hideDeleteModal()">Batal</button>
            <form id="deleteForm" method="POST" action="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn-modal-hapus">Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
const plainPass = {{ json_encode($user->plain_password ?? '') }};
let passVisible = true;

function toggleShowPassword() {
    passVisible = !passVisible;
    document.getElementById('passDisplay').textContent = passVisible
        ? (plainPass || '(tidak tersedia)')
        : '••••••••';
    document.getElementById('passDisplay').style.letterSpacing = passVisible ? 'normal' : '2px';
    document.getElementById('eyeIcon').style.stroke = passVisible ? '#2f5d34' : '#9ca3af';
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
