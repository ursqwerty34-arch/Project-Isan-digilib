@extends('layouts.anggota')

@section('title', 'Profil')

@section('content')

<div class="db-section-title" style="margin-bottom:20px;">👤 Profil Saya</div>

@if($errors->any())
    <div class="alert-error" style="margin-bottom:16px">
        Harap lengkapi semua data yang wajib diisi.
    </div>
@endif

@if(session('success'))
    <div class="alert-success" style="margin-bottom:16px">{{ session('success') }}</div>
@endif

<form action="{{ route('anggota.profil.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Foto --}}
    <div class="profil-photo-row">
        <div class="profil-photo-wrap">
            @if($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto" id="photoPreview">
            @else
                <div class="profil-photo-placeholder" id="photoPreview">👤</div>
            @endif
        </div>
        <div class="profil-photo-name">{{ $user->username ?? $user->name }}</div>
        <div class="profil-photo-actions">
            <label for="photoInput" class="btn-upload-foto">Upload Foto Baru</label>
            <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none"
                   onchange="previewPhoto(this)">
            <button type="button" class="btn-hapus-foto" onclick="confirmHapusFoto()">Hapus Foto</button>
        </div>
    </div>

    {{-- Form 2 kolom --}}
    <div class="profil-form-grid">

        {{-- Kolom kiri --}}
        <div class="profil-col">
            <div class="profil-field">
                <label>Username*</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" placeholder="Username"
                       class="{{ $errors->has('username') ? 'input-error' : '' }}">
            </div>
            <div class="profil-field">
                <label>Email*</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Email"
                       class="{{ $errors->has('email') ? 'input-error' : '' }}">
            </div>
            <div class="profil-field">
                <label>No.tlp*</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="No. Telepon"
                       class="{{ $errors->has('phone') ? 'input-error' : '' }}">
            </div>
            <div class="profil-field">
                <label>Tanggal lahir*</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}"
                       class="{{ $errors->has('birth_date') ? 'input-error' : '' }}">
            </div>
            <div class="profil-field">
                <label>Kelas & Jurusan*</label>
                <input type="text" name="kelas_jurusan" value="{{ old('kelas_jurusan', $user->kelas_jurusan) }}" placeholder="Contoh: XII PPLG 3"
                       class="{{ $errors->has('kelas_jurusan') ? 'input-error' : '' }}">
            </div>
        </div>

        {{-- Kolom kanan --}}
        <div class="profil-col">
            <div class="profil-field">
                <label>Nama Lengkap*</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Nama Lengkap"
                       class="{{ $errors->has('name') ? 'input-error' : '' }}">
            </div>
            <div class="profil-field">
                <label>Nomor Induk*</label>
                <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" placeholder="NIK / NIS"
                       class="{{ $errors->has('nik') ? 'input-error' : '' }}">
            </div>
            <div class="profil-field">
                <label>Jenis Kelamin*</label>
                <select name="gender" class="{{ $errors->has('gender') ? 'input-error' : '' }}">
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" {{ old('gender', $user->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('gender', $user->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div class="profil-field">
                <label>Alamat Lengkap*</label>
                <textarea name="address" rows="4" placeholder="Alamat lengkap"
                          class="{{ $errors->has('address') ? 'input-error' : '' }}">{{ old('address', $user->address) }}</textarea>
            </div>
        </div>

    </div>

    {{-- Tombol simpan --}}
    <div class="profil-save-row">
        <button type="submit" class="btn-simpan-profil">Simpan Perubahan</button>
    </div>

</form>

{{-- Form hapus foto (hidden) --}}
<form id="formHapusFoto" action="{{ route('anggota.profil.deletePhoto') }}" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('modal')
<script>
function previewPhoto(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const wrap = document.querySelector('.profil-photo-wrap');
        wrap.innerHTML = '<img src="' + e.target.result + '" alt="Preview" id="photoPreview">';
    };
    reader.readAsDataURL(input.files[0]);
}
function confirmHapusFoto() {
    if (confirm('Hapus foto profil?')) document.getElementById('formHapusFoto').submit();
}
</script>
@endsection
