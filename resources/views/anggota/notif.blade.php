@extends('layouts.anggota')
@section('title', 'Pemberitahuan')

@section('content')

<div class="db-section-title" style="margin-bottom:20px;">🔔 Pemberitahuan</div>

<div style="display:flex;flex-direction:column;gap:10px;">
    @forelse($notifications as $notif)
    @php $isAlert = $notif->type === 'return_fine' || $notif->type === 'loan_rejected'; $data = $notif->data ?? []; @endphp
    <div class="notif-item {{ $isAlert ? 'notif-alert' : '' }}" style="{{ !$notif->is_read ? 'font-weight:600;' : '' }}">
        <div class="notif-icon">
            @if($isAlert)
                <svg width="32" height="32" viewBox="0 0 48 48" fill="none"><rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#ef5350" stroke-width="2.2"/><circle cx="18" cy="22" r="2.5" fill="#ef5350"/><circle cx="30" cy="22" r="2.5" fill="#ef5350"/><path d="M17 31 Q24 25 31 31" stroke="#ef5350" stroke-width="2.2" stroke-linecap="round" fill="none"/><line x1="24" y1="8" x2="24" y2="3" stroke="#ef5350" stroke-width="2" stroke-linecap="round"/><circle cx="24" cy="2" r="1.5" fill="#ef5350"/></svg>
            @else
                <svg width="32" height="32" viewBox="0 0 48 48" fill="none"><rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/><circle cx="18" cy="22" r="2.5" fill="#2f5d34"/><circle cx="30" cy="22" r="2.5" fill="#2f5d34"/><path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/><line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/><circle cx="24" cy="2" r="1.5" fill="#4caf50"/></svg>
            @endif
        </div>
        <div class="notif-message" style="{{ $isAlert ? 'color:#ef5350;' : 'color:#1e1e1e;' }}">{{ $notif->message }}</div>
        <button class="notif-detail-btn {{ $isAlert ? 'notif-detail-alert' : '' }}"
            data-id="{{ $notif->id }}"
            data-type="{{ $notif->type }}"
            data-message="{{ $notif->message }}"
            data-data="{{ json_encode($data) }}">Lihat Detail</button>
    </div>
    @empty
    <div style="text-align:center;color:#9ca3af;padding:40px;font-size:14px;">Belum ada pemberitahuan.</div>
    @endforelse
</div>

@endsection

@section('modal')
<div class="modal-overlay" id="modalNotif">
    <div class="modal-box" style="max-width:480px;text-align:left;">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
            <div id="modalNotifIcon"></div>
            <div style="font-size:16px;font-weight:700;color:#1e1e1e;">Halo, {{ Auth::user()->name }} 👋</div>
        </div>
        <div id="modalNotifBody" style="font-size:13px;color:#374151;line-height:1.8;"></div>
        <div class="modal-actions" style="justify-content:center;gap:16px;margin-top:24px;">
            <button class="btn-modal-batal" id="btnNotifKembali">Kembali</button>
            <button class="btn-modal-hapus" id="btnNotifMengerti">Mengerti</button>
        </div>
    </div>
</div>

<script>
const notifTemplates = {
    loan_approved: (d) => `
        <p>Selamat! Pengajuan peminjaman buku kamu telah disetujui oleh petugas perpustakaan.</p>
        <p style="margin-top:10px;color:#2f5d34;font-weight:700;line-height:2;">
            Judul buku: ${d.judul || '-'}<br>
            Tanggal jatuh tempo: ${d.due_date ? formatDate(d.due_date) : '-'}
        </p>
        <p style="margin-top:10px;">Silakan ambil buku di perpustakaan. Terima kasih!</p>
    `,
    loan_rejected: (d) => `
        <p>Mohon maaf, pengajuan peminjaman buku kamu tidak dapat disetujui.</p>
        <p style="margin-top:10px;color:#ef5350;font-weight:700;line-height:2;">
            Judul buku: ${d.judul || '-'}<br>
            Alasan: ${d.alasan || '-'}
        </p>
        <p style="margin-top:10px;">Silakan hubungi petugas perpustakaan untuk informasi lebih lanjut.</p>
    `,
    return_success: (d) => `
        <p>Pengembalian buku kamu telah berhasil dikonfirmasi oleh petugas perpustakaan.</p>
        <p style="margin-top:10px;color:#2f5d34;font-weight:700;line-height:2;">
            Judul buku: ${d.judul || '-'}<br>
            Tanggal pengembalian: ${d.return_date ? formatDate(d.return_date) : '-'}
        </p>
        <p style="margin-top:10px;">Terima kasih telah mengembalikan buku tepat waktu!</p>
    `,
    return_fine: (d) => `
        <p>Pengembalian buku yang Anda pinjam telah melewati batas waktu yang ditentukan. Sesuai ketentuan perpustakaan, keterlambatan dikenakan denda.</p>
        <p style="margin-top:10px;">Rincian keterlambatan:</p>
        <p style="color:#ef5350;font-weight:700;line-height:2;margin-top:4px;">
            Judul buku: ${d.judul || '-'}<br>
            Tanggal jatuh tempo: ${d.due_date ? formatDate(d.due_date) : '-'}<br>
            Tanggal pengembalian: ${d.return_date ? formatDate(d.return_date) : '-'}<br>
            Total denda: Rp${Number(d.total_denda || 0).toLocaleString('id-ID')}
        </p>
        <p style="margin-top:10px;">Mohon kesediaan Anda untuk melakukan pembayaran denda kepada petugas perpustakaan saat mengembalikan buku.<br>Terima kasih atas perhatian dan kerja samanya.</p>
    `,
    reminder: (d) => `
        <p>Ini adalah pengingat bahwa batas waktu pengembalian buku kamu adalah <strong>besok</strong>.</p>
        <p style="margin-top:10px;color:#2f5d34;font-weight:700;line-height:2;">
            Judul buku: ${d.judul || '-'}<br>
            Tanggal jatuh tempo: ${d.due_date ? formatDate(d.due_date) : '-'}
        </p>
        <p style="margin-top:10px;">Harap kembalikan buku tepat waktu untuk menghindari denda. Terima kasih!</p>
    `,
};

function formatDate(str) {
    if (!str) return '-';
    return new Date(str).toLocaleDateString('id-ID', {day:'2-digit',month:'2-digit',year:'numeric'});
}

const iconGreen = `<svg width="48" height="48" viewBox="0 0 48 48" fill="none"><rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2"/><circle cx="18" cy="22" r="2.5" fill="#2f5d34"/><circle cx="30" cy="22" r="2.5" fill="#2f5d34"/><path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none"/><line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round"/><circle cx="24" cy="2" r="1.5" fill="#4caf50"/></svg>`;
const iconRed = `<svg width="48" height="48" viewBox="0 0 48 48" fill="none"><rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#ef5350" stroke-width="2.2"/><circle cx="18" cy="22" r="2.5" fill="#ef5350"/><circle cx="30" cy="22" r="2.5" fill="#ef5350"/><path d="M17 31 Q24 25 31 31" stroke="#ef5350" stroke-width="2.2" stroke-linecap="round" fill="none"/><line x1="24" y1="8" x2="24" y2="3" stroke="#ef5350" stroke-width="2" stroke-linecap="round"/><circle cx="24" cy="2" r="1.5" fill="#ef5350"/></svg>`;

document.querySelectorAll('.notif-detail-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const type = btn.dataset.type;
        const data = JSON.parse(btn.dataset.data || '{}');
        const isAlert = type === 'return_fine' || type === 'loan_rejected';
        document.getElementById('modalNotifIcon').innerHTML = isAlert ? iconRed : iconGreen;
        const tpl = notifTemplates[type] || (() => `<p>${btn.dataset.message}</p>`);
        document.getElementById('modalNotifBody').innerHTML = tpl(data);
        document.getElementById('modalNotif').classList.add('active');
        fetch(`/anggota/notif/${btn.dataset.id}/read`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
    });
});

document.getElementById('btnNotifKembali').addEventListener('click', () => document.getElementById('modalNotif').classList.remove('active'));
document.getElementById('btnNotifMengerti').addEventListener('click', () => document.getElementById('modalNotif').classList.remove('active'));
</script>
@endsection
