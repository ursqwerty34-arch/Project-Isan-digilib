@extends('layouts.kepala')
@section('title', 'Tambah Buku')

@section('content')

<div class="buku-form-card">
    <form method="POST" action="{{ route('kepala.buku.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="buku-form-layout">

            <div class="buku-cover-side">
                <div class="cover-dropzone" id="dropzone">
                    <div class="dropzone-inner" id="dropzoneInner">
                        <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                            <path d="M32 44V20M32 20L24 28M32 20L40 28" stroke="#2f5d34" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M20 48C13.4 48 8 42.6 8 36C8 30.2 12.2 25.4 17.8 24.2C18.6 17.2 24.6 12 32 12C39.4 12 45.4 17.2 46.2 24.2C51.8 25.4 56 30.2 56 36C56 42.6 50.6 48 44 48" stroke="#2f5d34" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                        <div class="dropzone-text">Drop File Here<br><span>Or</span></div>
                        <label class="btn-upload-file">Upload File<input type="file" name="cover" id="coverInput" accept=".png,.jpg,.jpeg" style="display:none;"></label>
                        <div class="dropzone-hint">Hanya Mendukung File Berbentuk<br>Png, Jpg, Jpeg</div>
                    </div>
                    <img id="coverPreview" src="" alt="" style="display:none; width:100%; height:100%; object-fit:cover; border-radius:12px;">
                </div>
            </div>

            <div class="buku-fields-side">
                <div class="buku-field"><label>Kode Buku*</label><input type="text" name="kode_buku" value="{{ old('kode_buku') }}" required>@error('kode_buku')<span class="field-error">{{ $message }}</span>@enderror</div>
                <div class="buku-field"><label>Penulis*</label><input type="text" name="author" value="{{ old('author') }}" required>@error('author')<span class="field-error">{{ $message }}</span>@enderror</div>
                <div class="buku-field"><label>Tahun terbit*</label><input type="date" name="year" value="{{ old('year') }}" required>@error('year')<span class="field-error">{{ $message }}</span>@enderror</div>
                <div class="buku-field"><label>Judul Buku*</label><input type="text" name="title" value="{{ old('title') }}" required>@error('title')<span class="field-error">{{ $message }}</span>@enderror</div>
                <div class="buku-field"><label>Stok Buku*</label><input type="number" name="stock" value="{{ old('stock') }}" min="0" required>@error('stock')<span class="field-error">{{ $message }}</span>@enderror</div>
                <div class="buku-form-actions">
                    <a href="{{ route('kepala.buku.index') }}" class="btn-buku-kembali">Kembali</a>
                    <button type="submit" class="btn-buku-submit">Tambahkan Buku</button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
const coverInput = document.getElementById('coverInput');
const coverPreview = document.getElementById('coverPreview');
const dropzoneInner = document.getElementById('dropzoneInner');
coverInput.addEventListener('change', function() { if (this.files[0]) showPreview(this.files[0]); });
function showPreview(file) {
    const reader = new FileReader();
    reader.onload = e => { coverPreview.src = e.target.result; coverPreview.style.display = 'block'; dropzoneInner.style.display = 'none'; };
    reader.readAsDataURL(file);
}
</script>
@endsection
