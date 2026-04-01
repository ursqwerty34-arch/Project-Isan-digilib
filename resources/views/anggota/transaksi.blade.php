@extends('layouts.anggota')
@section('title', 'Transaksi')

@section('content')

<div class="db-section-title" style="margin-bottom:20px;">🔄 Riwayat Transaksi</div>

<div class="ang-table-card">
    <div class="ang-table-section-title">Aktif</div>
    <div class="ang-table-wrap">
    <table class="ang-table">
        <thead><tr>
            <th>Cover</th><th>Judul Buku</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Status</th><th>Aksi</th>
        </tr></thead>
        <tbody>
            @forelse($aktif as $loan)
            <tr>
                <td><div class="cover-thumb">@if($loan->book->cover)<img src="{{ asset('storage/'.$loan->book->cover) }}" alt="">@endif</div></td>
                <td>{{ $loan->book->title ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                <td>{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-' }}</td>
                <td>
                    @if($loan->pengajuan_status === 'pending')
                        <span class="status-badge pending">Pending</span>
                    @else
                        <span class="status-badge disetujui">Dipinjam</span>
                    @endif
                </td>
                <td>
                    @if($loan->pengajuan_status !== 'pending')
                        @if($loan->return_requested)
                            <span class="btn-menunggu">Menunggu</span>
                        @else
                            <button class="btn-kembalikan" data-id="{{ $loan->id }}" data-url="{{ route('anggota.transaksi.kembalikan', $loan) }}">Kembalikan</button>
                        @endif
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="empty-row">Tidak ada transaksi aktif.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

<div class="ang-table-card" style="margin-top:24px;">
    <div class="ang-table-section-title">Dikembalikan</div>
    <div class="ang-table-wrap">
    <table class="ang-table">
        <thead><tr>
            <th>Cover</th><th>Judul Buku</th><th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Status</th>
        </tr></thead>
        <tbody>
            @forelse($dikembalikan as $loan)
            <tr>
                <td><div class="cover-thumb">@if($loan->book->cover)<img src="{{ asset('storage/'.$loan->book->cover) }}" alt="">@endif</div></td>
                <td>{{ $loan->book->title ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                <td>{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-' }}</td>
                <td><span class="status-badge dikembalikan">Dikembalikan</span></td>
            </tr>
            @empty
            <tr><td colspan="5" class="empty-row">Belum ada pengembalian.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@endsection

@section('modal')
{{-- MODAL KONFIRMASI KEMBALIKAN (screenshot 2) --}}
<div class="modal-overlay" id="modalKembalikan">
    <div class="modal-box" style="max-width:400px; text-align:center;">
        <div class="modal-mascot-circle" style="margin-bottom:16px;">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                <rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/>
                <circle cx="18" cy="22" r="2.5" fill="#2f5d34"/>
                <circle cx="30" cy="22" r="2.5" fill="#2f5d34"/>
                <path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                <line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/>
                <circle cx="24" cy="2" r="1.5" fill="#4caf50"/>
            </svg>
            <span class="modal-mascot-dot"></span>
        </div>
        <div class="modal-title" style="font-size:16px; margin-bottom:24px;">Ingin kembalikan Buku Ini?</div>
        <div class="modal-actions" style="justify-content:center; gap:16px;">
            <button class="btn-modal-batal" id="btnNantiKembalikan">Nanti saja</button>
            <button class="btn-modal-hapus" id="btnYaKembalikan">Ya</button>
        </div>
    </div>
</div>

{{-- MODAL SUKSES KEMBALIKAN (screenshot 3) --}}
<div class="modal-overlay" id="modalSuksesKembalikan">
    <div class="modal-box" style="max-width:420px; text-align:center;">
        <div class="modal-mascot-circle" style="margin-bottom:20px;">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                <rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/>
                <circle cx="18" cy="22" r="2.5" fill="#2f5d34"/>
                <circle cx="30" cy="22" r="2.5" fill="#2f5d34"/>
                <path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/>
                <line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/>
                <circle cx="24" cy="2" r="1.5" fill="#4caf50"/>
            </svg>
            <span class="modal-mascot-dot"></span>
        </div>
        <div class="modal-title" style="color:#2f5d34; font-size:16px; margin-bottom:24px;">
            Pengajuan pengembalian buku telah<br>terkirim silahkan tunggu konfirmasi...
        </div>
        <div class="modal-actions" style="justify-content:center;">
            <button class="btn-modal-hapus" id="btnMengertiKembalikan">Mengerti</button>
        </div>
    </div>
</div>

<script>
let currentKembalikanUrl = null;

document.querySelectorAll('.btn-kembalikan').forEach(btn => {
    btn.addEventListener('click', () => {
        currentKembalikanUrl = btn.dataset.url;
        document.getElementById('modalKembalikan').classList.add('active');
    });
});

document.getElementById('btnNantiKembalikan').addEventListener('click', () => {
    document.getElementById('modalKembalikan').classList.remove('active');
});

document.getElementById('btnYaKembalikan').addEventListener('click', () => {
    fetch(currentKembalikanUrl, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            document.getElementById('modalKembalikan').classList.remove('active');
            document.getElementById('modalSuksesKembalikan').classList.add('active');
        }
    });
});

document.getElementById('btnMengertiKembalikan').addEventListener('click', () => {
    window.location.reload();
});
</script>
@endsection
