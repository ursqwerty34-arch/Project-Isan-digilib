@extends('layouts.petugas')
@section('title', 'Edit Profil')

@section('content')

<form method="POST" action="{{ route('petugas.profil.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Foto --}}
    <div style="margin-bottom:28px;">
        <div style="position:relative; display:inline-block;">
            <div style="width:100px; height:100px; border-radius:50%; background:#9ca3af; overflow:hidden;">
                @if($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" id="photoPreview" style="width:100%; height:100%; object-fit:cover;">
                @else
                    <img id="photoPreview" src="" style="width:100%; height:100%; object-fit:cover; display:none;">
                    <div id="photoPlaceholder" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                        <svg width="56" height="56" viewBox="0 0 64 64" fill="none">
                            <circle cx="32" cy="24" r="14" fill="#fff" opacity="0.8"/>
                            <ellipse cx="32" cy="54" rx="22" ry="14" fill="#fff" opacity="0.8"/>
                        </svg>
                    </div>
                @endif
            </div>
            <label for="photoInput" style="position:absolute; bottom:-10px; left:50%; transform:translateX(-50%); background:#9ca3af; color:#fff; font-size:11px; font-weight:600; padding:3px 10px; border-radius:4px; cursor:pointer; white-space:nowrap;">Upload File</label>
            <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none;" onchange="previewPhoto(this)">
        </div>
    </div>

    {{-- Form fields --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px 32px;">

        <div class="profil-field">
            <label>Username*</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}" required>
            @error('username')<span style="color:#ef5350; font-size:11px;">{{ $message }}</span>@enderror
        </div>

        <div class="profil-field">
            <label>Nama Lengkap*</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')<span style="color:#ef5350; font-size:11px;">{{ $message }}</span>@enderror
        </div>

        <div class="profil-field">
            <label>Email*</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')<span style="color:#ef5350; font-size:11px;">{{ $message }}</span>@enderror
        </div>

        <div class="profil-field">
            <label>NISN*</label>
            <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" required>
            @error('nik')<span style="color:#ef5350; font-size:11px;">{{ $message }}</span>@enderror
        </div>

        <div class="profil-field">
            <label>No.tlp*</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required>
            @error('phone')<span style="color:#ef5350; font-size:11px;">{{ $message }}</span>@enderror
        </div>

        <div class="profil-field">
            <label>Jenis Kelamin*</label>
            <select name="gender" required data-cs data-cs-form>
                <option value="">Pilih...</option>
                <option value="Laki-laki" {{ old('gender', $user->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ old('gender', $user->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('gender')<span style="color:#ef5350; font-size:11px;">{{ $message }}</span>@enderror
        </div>

        <div></div>

        <div class="profil-field">
            <label>Tanggal lahir*</label>
            <div style="position:relative;">
                <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}" required style="width:100%; padding-right:40px;">
            </div>
            @error('birth_date')<span style="color:#ef5350; font-size:11px;">{{ $message }}</span>@enderror
        </div>

        <div class="profil-field" style="grid-column:2;">
            <label>Alamat Lengkap*</label>
            <textarea name="address" rows="4" required>{{ old('address', $user->address) }}</textarea>
            @error('address')<span style="color:#ef5350; font-size:11px;">{{ $message }}</span>@enderror
        </div>

    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:32px;">
        <a href="{{ route('petugas.profil') }}" class="btn-kembali">Kembali</a>
        <button type="submit" class="btn-buat-akun">Simpan Perubahan</button>
    </div>

</form>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('photoPreview');
            const ph  = document.getElementById('photoPlaceholder');
            img.src = e.target.result;
            img.style.display = 'block';
            if (ph) ph.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
