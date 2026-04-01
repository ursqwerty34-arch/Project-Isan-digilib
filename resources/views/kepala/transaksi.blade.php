@extends('layouts.kepala')
@section('title', 'Transaksi')

@section('content')

{{-- Tab buttons --}}
<div style="display:flex; gap:8px; margin-bottom:24px;">
    <button class="btn-tab active" id="tabPeminjaman" onclick="switchTab('peminjaman')">Peminjaman</button>
    <button class="btn-tab" id="tabPengembalian" onclick="switchTab('pengembalian')">Pengembalian</button>
</div>

{{-- TAB PEMINJAMAN (screenshot 1) --}}
<div id="panelPeminjaman" class="pengajuan-card">
    <table class="pengajuan-table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>NIK</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Jatuh Tempo</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $loan)
            <tr>
                <td>
                    <div class="cover-thumb">
                        @if($loan->book->cover)
                            <img src="{{ asset('storage/'.$loan->book->cover) }}" alt="">
                        @endif
                    </div>
                </td>
                <td>{{ $loan->user->nik ?? '-' }}</td>
                <td>{{ $loan->user->name ?? '-' }}</td>
                <td>{{ $loan->book->title ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                <td>{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-' }}</td>
                <td>
                    @if($loan->pengajuan_status === 'ditolak')
                        <span class="status-badge" style="background:#ef5350; color:#fff;">Ditolak</span>
                    @else
                        <span class="status-badge disetujui">Dipinjam</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="empty-row">Belum ada data peminjaman.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- TAB PENGEMBALIAN (screenshot 2) --}}
<div id="panelPengembalian" class="pengajuan-card" style="display:none;">
    <table class="pengajuan-table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>NIK</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Total Denda</th>
                <th>Status Denda</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengembalian as $ret)
            <tr>
                <td>
                    <div class="cover-thumb">
                        @if($ret->loan->book->cover)
                            <img src="{{ asset('storage/'.$ret->loan->book->cover) }}" alt="">
                        @endif
                    </div>
                </td>
                <td>{{ $ret->loan->user->nik ?? '-' }}</td>
                <td>{{ $ret->loan->book->title ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($ret->loan->loan_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($ret->return_date)->format('d/m/Y') }}</td>
                <td style="{{ $ret->fine > 0 ? 'color:#ef5350; font-weight:700;' : '' }}">
                    {{ $ret->fine > 0 ? 'Rp. ' . number_format($ret->fine, 0, ',', '.') : '-' }}
                </td>
                <td>
                    @if($ret->fine_status === 'lunas')
                        <span style="color:#2f5d34; font-weight:600; font-size:13px;">Lunas</span>
                    @elseif($ret->fine_status === 'belum_lunas')
                        <span style="color:#ef5350; font-weight:600; font-size:13px;">Belum Lunas</span>
                    @else
                        <span style="color:#9ca3af; font-size:13px;">-</span>
                    @endif
                </td>
                <td>
                    <span class="status-badge disetujui">Dikembalikan</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="empty-row">Belum ada data pengembalian.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function switchTab(tab) {
    document.getElementById('panelPeminjaman').style.display  = tab === 'peminjaman'  ? 'block' : 'none';
    document.getElementById('panelPengembalian').style.display = tab === 'pengembalian' ? 'block' : 'none';
    document.getElementById('tabPeminjaman').classList.toggle('active',  tab === 'peminjaman');
    document.getElementById('tabPengembalian').classList.toggle('active', tab === 'pengembalian');
}
</script>

@endsection
