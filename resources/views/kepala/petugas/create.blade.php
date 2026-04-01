@extends('layouts.kepala')
@section('title', 'Tambah Petugas')

@section('content')

@if($errors->any())
    <div class="alert-error">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('kepala.petugas.store') }}" enctype="multipart/form-data">
@csrf

<div class="create-form-wrap">

    {{-- Foto profil --}}
    <div class="photo-upload-area">
        <div class="photo-preview" id="photoPreview">
            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" width="100" height="100">
                <rect width="100" height="100" fill="#d1d5db"/>
                <circle cx="50" cy="38" r="18" fill="#9ca3af"/>
                <ellipse cx="50" cy="80" rx="28" ry="20" fill="#9ca3af"/>
            </svg>
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
                <input type="text" name="username" value="{{ old('username') }}"
                       placeholder="Lisa" required/>
            </div>
            <div class="form-group">
                <label>Email*</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="Lisa@gmail.com" required/>
            </div>
            <div class="form-group">
                <label>No.tlp*</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       placeholder="08572253"/>
            </div>
            <div class="form-group">
                <label>Password*</label>
                <div class="input-password-wrap">
                    <input type="password" name="password" id="passwordInput"
                           placeholder="Masukan password" required/>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="#9ca3af" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Kolom kanan --}}
        <div class="form-col">
            <div class="form-group">
                <label>Nama Lengkap*</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       placeholder="Lisa Kardashian Halim Jenner" required/>
            </div>
            <div class="form-group">
                <label>Nomer induk*</label>
                <input type="text" name="nik" value="{{ old('nik') }}"
                       placeholder="32071770"/>
            </div>
            <div class="form-group">
                <label>Jenis Kelamin*</label>
                <select name="gender">
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki"  {{ old('gender') === 'Laki-laki'  ? 'selected' : '' }}>Laki - laki</option>
                    <option value="Perempuan"  {{ old('gender') === 'Perempuan'  ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal lahir*</label>
                <div class="input-date-wrap">
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}"/>
                </div>
            </div>
            <div class="form-group">
                <label>Alamat Lengkap*</label>
                <textarea name="address" rows="3" placeholder="LA">{{ old('address') }}</textarea>
            </div>
        </div>

    </div>{{-- end form-two-col --}}

    {{-- Tombol aksi --}}
    <div class="form-bottom-actions">
        <a href="{{ route('kepala.petugas.index') }}" class="btn-kembali">Kembali</a>
        <button type="submit" class="btn-buat-akun">Buat Akun</button>
    </div>

</div>{{-- end create-form-wrap --}}
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
