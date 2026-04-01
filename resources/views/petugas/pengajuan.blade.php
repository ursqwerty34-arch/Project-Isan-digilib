@extends('layouts.petugas')
@section('title', 'Pengajuan')

@section('content')

<div class="pengajuan-card">

    {{-- TABEL PENDING --}}
    <div class="pengajuan-section-title">Daftar pengajuan - pending</div>
    <table class="pengajuan-table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pending as $loan)
            <tr>
                <td>
                    <div class="cover-thumb">
                        @if($loan->book->cover)
                            <img src="{{ asset('storage/' . $loan->book->cover) }}" alt="">
                        @endif
                    </div>
                </td>
                <td>{{ $loan->book->title ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                <td>{{ $loan->user->name ?? '-' }}</td>
                <td>{{ $loan->user->nik ?? '-' }}</td>
                <td>
                    <div class="aksi-group">
                        <button class="btn-tolak"
                            data-id="{{ $loan->id }}"
                            data-buku="{{ $loan->book->title ?? '' }}"
                            data-tgl="{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}">
                            Tolak
                        </button>
                        <button type="button" class="btn-pinjamkan"
                            data-id="{{ $loan->id }}">
                            Pinjamkan
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="empty-row">Tidak ada pengajuan pending.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- TABEL DIKONFIRMASI --}}
    <div class="pengajuan-section-title" style="margin-top:32px;">
        Daftar pengajuan yang petugas - {{ Auth::user()->name }} konfirmasi
    </div>
    <table class="pengajuan-table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            @forelse($confirmed as $loan)
            <tr>
                <td>
                    <div class="cover-thumb">
                        @if($loan->book->cover)
                            <img src="{{ asset('storage/' . $loan->book->cover) }}" alt="">
                        @endif
                    </div>
                </td>
                <td>{{ $loan->book->title ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                <td>{{ $loan->user->name ?? '-' }}</td>
                <td>{{ $loan->user->nik ?? '-' }}</td>
                <td>{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-' }}</td>
                <td>
                    <span class="status-badge {{ $loan->pengajuan_status }}">
                        {{ ucfirst($loan->pengajuan_status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('petugas.pengajuan.detail', $loan) }}" class="btn-detail-icon" title="Detail">&#128441;</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="empty-row">Belum ada konfirmasi.</td></tr>
            @endforelse
        </tbody>
    </table>

</div>

{{-- MODAL KONFIRMASI PINJAMKAN (screenshot 1) --}}
<div class="modal-overlay" id="modalPinjamkan">
    <div class="modal-box" style="max-width:420px; text-align:center;">
        <div class="modal-mascot-circle" style="margin-bottom:16px;">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/>
                <circle cx="18" cy="22" r="2.5" fill="#2f5d34"/>
                <circle cx="30" cy="22" r="2.5" fill="#2f5d34"/>
                <path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                <line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/>
                <circle cx="24" cy="2" r="1.5" fill="#4caf50"/>
            </svg>
            <span class="modal-mascot-dot"></span>
        </div>
        <div class="modal-title" style="font-size:17px; margin-bottom:16px;">Konfirmasi Peminjaman Buku Ini?</div>
        <div style="text-align:left; margin-bottom:4px;">
            <label style="font-size:12px; color:#6b6b6b;">Atur Tanggal Jatuh Tempo</label>
        </div>
        <div style="position:relative; margin-bottom:4px;">
            <input type="date" id="inputDueDate" style="width:100%; padding:11px 44px 11px 14px; border:1.5px solid #d1d5db; border-radius:10px; font-size:14px; outline:none; box-sizing:border-box;">
        </div>
        <div id="dueDateError" style="font-size:11px; color:#ef5350; text-align:left; margin-bottom:16px; display:none;">*field ini wajib di isi</div>
        <div class="modal-actions" style="justify-content:center; gap:16px;">
            <button class="btn-modal-batal" id="btnBatalPinjamkan">Nanti saja</button>
            <button class="btn-modal-hapus" id="btnYaPinjamkan">Ya, Pinjamkan</button>
        </div>
    </div>
</div>

{{-- MODAL SUKSES PINJAMKAN (screenshot 2) --}}
<div class="modal-overlay" id="modalSuksesPinjamkan">
    <div class="modal-box" style="max-width:420px; text-align:center;">
        <div class="modal-mascot-circle" style="margin-bottom:20px;">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/>
                <circle cx="18" cy="22" r="2.5" fill="#2f5d34"/>
                <circle cx="30" cy="22" r="2.5" fill="#2f5d34"/>
                <path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                <line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/>
                <circle cx="24" cy="2" r="1.5" fill="#4caf50"/>
            </svg>
            <span class="modal-mascot-dot"></span>
        </div>
        <div class="modal-title" style="color:#2f5d34; font-size:17px; margin-bottom:24px;">
            Peminjaman buku berhasil di<br>konfirmasi...
        </div>
        <div class="modal-actions" style="justify-content:center;">
            <button class="btn-modal-hapus" id="btnKembaliPinjamkan">Kembali</button>
        </div>
    </div>
</div>

{{-- MODAL TOLAK (screenshot 2) --}}
<div class="modal-overlay" id="modalTolak">
    <div class="modal-box" style="max-width:480px;">
        <div class="modal-mascot-circle" style="margin-bottom:16px;">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/>
                <circle cx="18" cy="22" r="2.5" fill="#2f5d34"/>
                <circle cx="30" cy="22" r="2.5" fill="#2f5d34"/>
                <path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                <line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/>
                <circle cx="24" cy="2" r="1.5" fill="#4caf50"/>
            </svg>
            <span class="modal-mascot-dot"></span>
        </div>

        <div class="modal-title" style="font-size:16px; margin-bottom:12px;">Konfirmasi Tolak Peminjaman Buku Ini?</div>

        <div style="font-size:12px; color:#6b6b6b; margin-bottom:8px;">
            Pilih template*
            <span class="template-btn active" data-template="stok">Stok Habis</span>
            <span class="template-btn" data-template="batas">Batas Pinjam</span>
            <span class="template-btn" data-template="data">Data Anggota</span>
        </div>

        <div style="font-size:12px; color:#374151; margin-bottom:8px;">Rincian Alasan</div>
        <textarea id="rejectionReason" rows="6" style="width:100%; padding:12px; border:1.5px solid #d1d5db; border-radius:10px; font-size:13px; font-family:inherit; resize:vertical; outline:none; box-sizing:border-box;"></textarea>

        <div class="modal-actions" style="margin-top:20px; justify-content:center; gap:16px;">
            <button class="btn-modal-batal" id="btnNantiSaja">Nanti saja</button>
            <button class="btn-modal-hapus" id="btnKonfirmasiTolak">Konfirmasi</button>
        </div>
    </div>
</div>

{{-- MODAL SUKSES TOLAK (screenshot 3 via JS) --}}
<div class="modal-overlay" id="modalSuksesTolakJS">
    <div class="modal-box" style="max-width:420px; text-align:center;">
        <div class="modal-mascot-circle" style="margin-bottom:20px;">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/>
                <circle cx="18" cy="22" r="2.5" fill="#2f5d34"/>
                <circle cx="30" cy="22" r="2.5" fill="#2f5d34"/>
                <path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                <line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/>
                <circle cx="24" cy="2" r="1.5" fill="#4caf50"/>
            </svg>
            <span class="modal-mascot-dot"></span>
        </div>
        <div class="modal-title" style="color:#1e1e1e; font-size:17px; margin-bottom:24px;">
            Penolakan Peminjaman buku berhasil<br>di konfirmasi...
        </div>
        <div class="modal-actions" style="justify-content:center;">
            <button class="btn-modal-profil" id="btnKembali">Kembali</button>
        </div>
    </div>
</div>

<script>
// ── Pinjamkan modal ──
let pinjamkanLoanId = null;

document.querySelectorAll('.btn-pinjamkan').forEach(btn => {
    btn.addEventListener('click', () => {
        pinjamkanLoanId = btn.dataset.id;
        document.getElementById('inputDueDate').value = '';
        document.getElementById('dueDateError').style.display = 'none';
        document.getElementById('modalPinjamkan').classList.add('active');
    });
});

document.getElementById('btnBatalPinjamkan').addEventListener('click', () => {
    document.getElementById('modalPinjamkan').classList.remove('active');
});

document.getElementById('btnYaPinjamkan').addEventListener('click', () => {
    const dueDate = document.getElementById('inputDueDate').value;
    if (!dueDate) {
        document.getElementById('dueDateError').style.display = 'block';
        return;
    }
    fetch(`/petugas/pengajuan/${pinjamkanLoanId}/pinjamkan`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ due_date: dueDate })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modalPinjamkan').classList.remove('active');
            document.getElementById('modalSuksesPinjamkan').classList.add('active');
        }
    });
});

document.getElementById('btnKembaliPinjamkan').addEventListener('click', () => {
    window.location.reload();
});

// ── Tolak modal ──
const templates = {
    stok: `Permintaan peminjaman buku tidak dapat disetujui karena seluruh stok buku saat ini sedang habis atau sedang dipinjam oleh pengguna lain.\nRincian:\n• Judul buku: {nama_buku}\n• Status stok: Tidak tersedia\n• Tanggal pengajuan: {tgl_pengajuan}`,
    batas: `Permintaan peminjaman buku tidak dapat disetujui karena anggota telah mencapai batas maksimal peminjaman buku.\nRincian:\n• Judul buku: {nama_buku}\n• Tanggal pengajuan: {tgl_pengajuan}`,
    data: `Permintaan peminjaman buku tidak dapat disetujui karena data anggota belum lengkap atau tidak valid.\nRincian:\n• Judul buku: {nama_buku}\n• Tanggal pengajuan: {tgl_pengajuan}`,
};

let currentLoanId = null;
let currentBuku   = '';
let currentTgl    = '';

// Buka modal tolak
document.querySelectorAll('.btn-tolak').forEach(btn => {
    btn.addEventListener('click', () => {
        currentLoanId = btn.dataset.id;
        currentBuku   = btn.dataset.buku;
        currentTgl    = btn.dataset.tgl;
        setTemplate('stok');
        document.querySelectorAll('.template-btn').forEach(t => t.classList.remove('active'));
        document.querySelector('[data-template="stok"]').classList.add('active');
        document.getElementById('modalTolak').classList.add('active');
    });
});

// Pilih template
document.querySelectorAll('.template-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.template-btn').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        setTemplate(btn.dataset.template);
    });
});

function setTemplate(key) {
    document.getElementById('rejectionReason').value = templates[key]
        .replace(/{nama_buku}/g, currentBuku)
        .replace(/{tgl_pengajuan}/g, currentTgl);
}

// Tutup modal tolak
document.getElementById('btnNantiSaja').addEventListener('click', () => {
    document.getElementById('modalTolak').classList.remove('active');
});

// Konfirmasi tolak → AJAX
document.getElementById('btnKonfirmasiTolak').addEventListener('click', () => {
    const reason = document.getElementById('rejectionReason').value.trim();
    if (!reason) return;

    fetch(`/petugas/pengajuan/${currentLoanId}/tolak`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') 
                ? document.querySelector('meta[name="csrf-token"]').content 
                : '{{ csrf_token() }}'
        },
        body: JSON.stringify({ rejection_reason: reason })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('modalTolak').classList.remove('active');
            document.getElementById('modalSuksesTolakJS').classList.add('active');
        }
    });
});

// Kembali dari modal sukses → reload
document.getElementById('btnKembali').addEventListener('click', () => {
    window.location.reload();
});
</script>

@endsection
