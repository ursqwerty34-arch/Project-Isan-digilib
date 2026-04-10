@extends('layouts.petugas')
@section('title', 'Profil')

@section('content')

<div class="show-card" style="max-width:100%;">

    <div style="margin-bottom:20px;">
        <div class="show-name">{{ $user->username ?? $user->name }}</div>
        <div class="show-role" style="color:#4caf50;">Petugas</div>
    </div>

    <div class="show-body">
        {{-- Foto --}}
        <div class="show-photo" style="width:120px; height:120px; border-radius:50%; background:#d1d5db; overflow:hidden; flex-shrink:0;">
            @if($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
            @else
                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#9ca3af;">
                    <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                        <circle cx="32" cy="24" r="14" fill="#fff" opacity="0.8"/>
                        <ellipse cx="32" cy="54" rx="22" ry="14" fill="#fff" opacity="0.8"/>
                    </svg>
                </div>
            @endif
        </div>

        {{-- Info grid 2 kolom --}}
        <div class="show-info-grid" style="flex:1;">
            <div class="show-info-col">
                <div class="show-info-item">
                    <span class="info-label">Username :</span>
                    <span class="info-value">{{ $user->username ?? '-' }}</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Email :</span>
                    <span class="info-value">{{ $user->email ?? '-' }}</span>
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
                    <span class="info-label">Bergabung Pada :</span>
                    <span class="info-value">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') : '-' }}</span>
                </div>
            </div>
            <div class="show-info-col">
                <div class="show-info-item">
                    <span class="info-label">NISN :</span>
                    <span class="info-value">{{ $user->nik ?? '-' }}</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Nama Lengkap Anggota :</span>
                    <span class="info-value">{{ $user->name ?? '-' }}</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Jenis Kelamin :</span>
                    <span class="info-value">{{ $user->gender ?? '-' }}</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Tanggal Lahir :</span>
                    <span class="info-value">{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="show-info-item">
                    <span class="info-label">Alamat Lengkap :</span>
                    <span class="info-value">{{ $user->address ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex; justify-content:flex-end; padding-top:16px; border-top:1px solid #f0f0f0;">
        <a href="{{ route('petugas.profil.edit') }}" class="btn-edit-akun">Edit Akun</a>
    </div>

</div>

{{-- Ubah Password --}}
<div style="margin-top:32px;background:#fff;border-radius:16px;padding:28px 32px;box-shadow:0 2px 12px rgba(47,93,52,0.08);">
    <div style="font-family:'Poppins-Bold','Segoe UI',sans-serif;font-size:15px;font-weight:700;color:#1e1e1e;margin-bottom:20px;">🔒 Ubah Password</div>

    @if(session('password_success'))
        <div class="alert-success" style="margin-bottom:16px">{{ session('password_success') }}</div>
    @endif

    @if($user->password_changed)
        <div style="display:flex;align-items:center;gap:10px;padding:14px 18px;background:#e8f5e9;border-radius:10px;border:1.5px solid #a5d6a7;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2f5d34" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span style="font-size:13px;color:#2f5d34;font-weight:600;">Password sudah diubah. Hubungi admin jika ingin mereset ulang.</span>
        </div>
    @else
        @if(session('password_error'))
            <div class="alert-error" style="margin-bottom:16px">{{ session('password_error') }}</div>
        @endif
        <form action="{{ route('petugas.profil.password') }}" method="POST" style="max-width:400px;">
            @csrf @method('PUT')
            <div class="profil-form-grid">
                <div class="profil-col">
                    <div class="profil-field">
                        <label>Password Baru*</label>
                        <input type="password" name="new_password" placeholder="Minimal 6 karakter">
                    </div>
                    <div class="profil-field">
                        <label>Konfirmasi Password Baru*</label>
                        <input type="password" name="new_password_confirmation" placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>
            <div class="profil-save-row" style="margin-top:20px;">
                <button type="submit" class="btn-buat-akun">Ubah Password</button>
            </div>
        </form>
    @endif
</div>

@endsection
