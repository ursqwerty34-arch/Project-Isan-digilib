@extends('layouts.kepala')
@section('title', 'Edit Petugas')

@section('content')

@if($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('kepala.petugas.update', $user) }}" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="create-form-wrap">

    {{-- Foto profil --}}
    <div class="photo-upload-area">
        <div class="photo-preview" id="photoPreview">
            @if($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}" alt="foto"/>
            @else
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                    <rect width="100" height="100" fill="#d1d5db"/>
                    <circle cx="50" cy="38" r="18" fill="#9ca3af"/>
                    <ellipse cx="50" cy="80" rx="28" ry="20" fill="#9ca3af"/>
                </svg>
            @endif
        </div>
        <label for="photo" class="btn-upload">Upload File</label>
        <input type="file" id="photo" name="photo" accept="image/*" style="display:none"
               onchange="previewPhoto(this)"/>
    </div>

    {{-- Grid 2 kolom --}}
    <div class="form-two-col">

        {{-- Kolom kiri --}}
        <div class="form-col">
            <div class="form-group">
                <label>Username*</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}"
                       placeholder="Username" required/>
            </div>
            <div class="form-group">
                <label>Email*</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       placeholder="email@gmail.com" required/>
            </div>
            <div class="form-group">
                <label>No.tlp*</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                       placeholder="08xxxxxxxxxx"/>
            </div>

        </div>

        {{-- Kolom kanan --}}
        <div class="form-col">
            <div class="form-group">
                <label>Nama Lengkap*</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       placeholder="Nama lengkap" required/>
            </div>
            <div class="form-group">
                <label>Nomer induk*</label>
                <input type="text" name="nik" value="{{ old('nik', $user->nik) }}"
                       placeholder="NIK/NIS"/>
            </div>
            <div class="form-group">
                <label>Jenis Kelamin*</label>
                <select name="gender">
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" {{ old('gender', $user->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki - laki</option>
                    <option value="Perempuan" {{ old('gender', $user->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal lahir*</label>
                <div class="input-date-wrap">
                    <input type="date" name="birth_date"
                           value="{{ old('birth_date', $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') : '') }}"/>
                </div>
            </div>
            <div class="form-group">
                <label>Alamat Lengkap*</label>
                <textarea name="address" rows="3" placeholder="Alamat lengkap">{{ old('address', $user->address) }}</textarea>
            </div>
        </div>

    </div>

    {{-- Tombol aksi --}}
    <div class="form-bottom-actions">
        <a href="{{ route('kepala.petugas.index') }}" class="btn-kembali">Kembali</a>
        <button type="submit" class="btn-buat-akun">Simpan Perubahan</button>
    </div>

</div>
</form>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photoPreview').innerHTML =
                `<img src="${e.target.result}" alt="preview"/>`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function togglePassword() {
    const input = document.getElementById('passwordInput');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

@endsection
