@extends('layouts.petugas')
@section('title', 'Edit Buku')

@section('content')

<div class="buku-form-card">
    <form method="POST" action="{{ route('petugas.buku.update', $book) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="buku-form-layout">

            {{-- Kiri: Cover --}}
            <div class="buku-cover-side">
                <div class="cover-dropzone" id="dropzone" style="{{ $book->cover ? 'border:none;' : '' }}">
                    @if($book->cover)
                        <img id="coverPreview" src="{{ asset('storage/' . $book->cover) }}" alt=""
                             style="width:100%; height:100%; object-fit:cover; border-radius:12px;">
                        <div id="dropzoneInner" style="display:none;">
                    @else
                        <img id="coverPreview" src="" alt="" style="display:none; width:100%; height:100%; object-fit:cover; border-radius:12px;">
                        <div id="dropzoneInner">
                    @endif
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M32 44V20M32 20L24 28M32 20L40 28" stroke="#2f5d34" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M20 48C13.4 48 8 42.6 8 36C8 30.2 12.2 25.4 17.8 24.2C18.6 17.2 24.6 12 32 12C39.4 12 45.4 17.2 46.2 24.2C51.8 25.4 56 30.2 56 36C56 42.6 50.6 48 44 48" stroke="#2f5d34" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                            <div class="dropzone-text">Drop File Here<br><span>Or</span></div>
                            <label class="btn-upload-file">
                                Upload File
                                <input type="file" name="cover" id="coverInput" accept=".png,.jpg,.jpeg" style="display:none;">
                            </label>
                        </div>
                </div>
                <label class="btn-change-cover">
                    Change Cover
                    <input type="file" name="cover" id="coverInputChange" accept=".png,.jpg,.jpeg" style="display:none;">
                </label>
                <div class="dropzone-hint" style="text-align:center; margin-top:6px;">Hanya Mendukung File Berbentuk<br>Png, Jpg, Jpeg</div>
            </div>

            {{-- Kanan: Form Fields --}}
            <div class="buku-fields-side">
                <div class="buku-field">
                    <label>Kode Buku*</label>
                    <input type="text" name="kode_buku" value="{{ old('kode_buku', $book->kode_buku) }}" required>
                    @error('kode_buku')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="buku-field">
                    <label>Penulis*</label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}" required>
                    @error('author')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="buku-field">
                    <label>Tahun terbit*</label>
                    <div class="input-date-wrap">
                        <input type="date" name="year" value="{{ old('year', $book->year ? $book->year . '-01-01' : '') }}" required>
                    </div>
                    @error('year')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="buku-field">
                    <label>Judul Buku*</label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required>
                    @error('title')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="buku-field">
                    <label>Penerbit</label>
                    <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" placeholder="Penerbit">
                </div>
                <div class="buku-field">
                    <label>Stok Buku*</label>
                    <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" min="0" required>
                    @error('stock')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="buku-form-actions">
                    <a href="{{ route('petugas.buku.index') }}" class="btn-buku-kembali">Kembali</a>
                    <button type="submit" class="btn-buku-submit">Edit Buku</button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
function handleFileChange(input) {
    if (input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('coverPreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
            const inner = document.getElementById('dropzoneInner');
            if (inner) inner.style.display = 'none';
            document.getElementById('dropzone').style.border = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

const ci = document.getElementById('coverInput');
const cic = document.getElementById('coverInputChange');
if (ci) ci.addEventListener('change', function() { handleFileChange(this); });
if (cic) cic.addEventListener('change', function() {
    handleFileChange(this);
    // sync ke input cover utama jika ada
    if (ci) {
        const dt = new DataTransfer();
        dt.items.add(this.files[0]);
        ci.files = dt.files;
    }
});
</script>

@endsection
