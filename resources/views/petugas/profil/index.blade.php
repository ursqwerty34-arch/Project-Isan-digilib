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
                    <span class="info-label">NIK :</span>
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

@endsection
