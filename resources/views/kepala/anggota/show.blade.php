@extends('layouts.kepala')
@section('title', 'Detail Anggota')

@section('content')

<div class="show-card">

    <div class="show-header">
        <h2 class="show-name">{{ $user->name }}</h2>
        <span class="show-role">Anggota</span>
    </div>

    <div class="show-body">

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
                    <span class="info-value">Anggota</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Bergabung Pada :</span>
                    <span class="info-value">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Password :</span>
                    <span class="info-value" style="display:flex; align-items:center; gap:8px;">
                        <input type="password" id="passInput"
                            value="{{ $user->plain_password ?? '-' }}"
                            readonly
                            style="border:none; outline:none; background:transparent; font-size:14px; color:#374151; font-family:inherit; width:120px; cursor:default;">
                        <button type="button" onclick="togglePass()"
                            style="background:none; border:none; cursor:pointer; font-size:16px; color:#2f5d34; padding:0; line-height:1;"
                            id="passToggleBtn">&#128065;</button>
                    </span>
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

    <div class="show-actions">
        <a href="{{ route('kepala.anggota.index') }}" class="btn-kembali">Kembali</a>
    </div>

</div>

<script>
function togglePass() {
    const input = document.getElementById('passInput');
    const btn   = document.getElementById('passToggleBtn');
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '&#128584;';
    } else {
        input.type = 'password';
        btn.innerHTML = '&#128065;';
    }
}
</script>

@endsection
