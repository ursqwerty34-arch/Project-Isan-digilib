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
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                    <select id="selectKelas" class="{{ $errors->has('kelas_jurusan') ? 'input-error' : '' }}" data-cs data-cs-form>
                        <option value="">-- Kelas --</option>
                        @foreach(['X','XI','XII'] as $k)
                            <option value="{{ $k }}"
                                {{ str_starts_with(old('kelas_jurusan', $user->kelas_jurusan ?? ''), $k.' ') ? 'selected' : '' }}>
                                {{ $k }}
                            </option>
                        @endforeach
                    </select>
                    <select id="selectJurusan" class="{{ $errors->has('kelas_jurusan') ? 'input-error' : '' }}" data-cs data-cs-form>
                        <option value="">-- Jurusan --</option>
                        @foreach(['AKL 1','AKL 2','AKL 3','PPLG 1','PPLG 2','PPLG 3','TBSM 1','TBSM 2','TBSM 3','TKRO 1','TKRO 2','TKRO 3','APHP 1','APHP 2','APHP 3','APAT 1','APAT 2','APAT 3'] as $j)
                            @php $val = old('kelas_jurusan', $user->kelas_jurusan ?? ''); $parts = explode(' ', $val, 2); @endphp
                            <option value="{{ $j }}" {{ (isset($parts[1]) && $parts[1] === $j) ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="kelas_jurusan" id="inputKelasJurusan" value="{{ old('kelas_jurusan', $user->kelas_jurusan) }}">
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
                <label>NISN*</label>
                <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" placeholder="NIK / NIS"
                       class="{{ $errors->has('nik') ? 'input-error' : '' }}">
            </div>
            <div class="profil-field">
                <label>Jenis Kelamin*</label>
                <select name="gender" class="{{ $errors->has('gender') ? 'input-error' : '' }}" data-cs data-cs-form>
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

{{-- Form Ubah Password --}}
<div style="margin-top:32px;background:#fff;border-radius:16px;padding:28px 32px;box-shadow:0 2px 12px rgba(47,93,52,0.08);">
    <div class="db-section-title" style="font-size:15px;margin-bottom:20px;">🔒 Ubah Password</div>

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
        <form action="{{ route('anggota.profil.password') }}" method="POST">
            @csrf @method('PUT')
            <div style="display:flex;flex-direction:column;gap:16px;">
                <div class="profil-field">
                    <label>Password Baru*</label>
                    <input type="password" name="new_password" placeholder="Minimal 6 karakter">
                </div>
                <div class="profil-field">
                    <label>Konfirmasi Password Baru*</label>
                    <input type="password" name="new_password_confirmation" placeholder="Ulangi password baru">
                </div>
            </div>
            <div class="profil-save-row" style="margin-top:20px;">
                <button type="submit" class="btn-simpan-profil">Ubah Password</button>
            </div>
        </form>
    @endif
</div>

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

function syncKelasJurusan() {
    const kelas   = document.getElementById('selectKelas').value;
    const jurusan = document.getElementById('selectJurusan').value;
    document.getElementById('inputKelasJurusan').value = (kelas && jurusan) ? kelas + ' ' + jurusan : '';
}
document.getElementById('selectKelas').addEventListener('change', syncKelasJurusan);
document.getElementById('selectJurusan').addEventListener('change', syncKelasJurusan);
</script>
@endsection
