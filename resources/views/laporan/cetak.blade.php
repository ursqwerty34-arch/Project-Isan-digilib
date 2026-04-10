<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan {{ ucfirst($jenis) }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; color: #1e1e1e; padding: 32px; }
        .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #2f5d34; padding-bottom: 16px; }
        .header h1 { font-size: 18px; font-weight: 700; color: #2f5d34; }
        .header p { font-size: 13px; color: #6b6b6b; margin-top: 4px; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 12px; color: #374151; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        thead tr { background: #e8f5e9; }
        th { padding: 10px 12px; text-align: left; font-weight: 600; color: #2f5d34; border: 1px solid #c8e6c9; }
        td { padding: 9px 12px; border: 1px solid #e0e0e0; vertical-align: middle; }
        tbody tr:nth-child(even) { background: #f9fdf9; }
        .footer { margin-top: 32px; display: flex; justify-content: flex-end; }
        .ttd { text-align: center; }
        .ttd p { font-size: 12px; color: #374151; }
        .ttd .garis { margin-top: 56px; border-top: 1px solid #1e1e1e; width: 160px; margin-left: auto; margin-right: auto; }
        .badge { padding: 2px 8px; border-radius: 50px; font-size: 11px; font-weight: 600; }
        .badge-green { background: #e8f5e9; color: #2f5d34; }
        .badge-red { background: #fef2f2; color: #c62828; }
        .badge-yellow { background: #fff8e1; color: #f57c00; }
        @media print {
            body { padding: 16px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>📚 DigiLib — Laporan {{ ucfirst($jenis) }} Buku</h1>
    <p>
        Periode: {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}
        @if($bulan)
            — Bulan: {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan] }}
        @endif
    </p>
</div>

<div class="meta">
    <span>Total Data: <strong>{{ $data->count() }}</strong></span>
    <span>Dicetak: {{ now()->format('d M Y, H:i') }}</span>
</div>

@if($jenis === 'peminjaman')
<table>
    <thead><tr>
        <th>No</th><th>Nama Anggota</th><th>NISN</th><th>Judul Buku</th>
        <th>Tgl Pinjam</th><th>Jatuh Tempo</th><th>Status</th>
    </tr></thead>
    <tbody>
        @forelse($data as $i => $loan)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $loan->user->name ?? '-' }}</td>
            <td>{{ $loan->user->nik ?? '-' }}</td>
            <td>{{ $loan->book->title ?? '-' }}</td>
            <td>{{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') : '-' }}</td>
            <td>{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-' }}</td>
            <td>
                @if($loan->pengajuan_status === 'disetujui')
                    <span class="badge badge-green">Disetujui</span>
                @elseif($loan->pengajuan_status === 'ditolak')
                    <span class="badge badge-red">Ditolak</span>
                @else
                    <span class="badge badge-yellow">{{ ucfirst($loan->pengajuan_status) }}</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:20px;color:#9ca3af;">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>
@else
<table>
    <thead><tr>
        <th>No</th><th>Nama Anggota</th><th>NISN</th><th>Judul Buku</th>
        <th>Tgl Kembali</th><th>Denda</th><th>Status Denda</th>
    </tr></thead>
    <tbody>
        @forelse($data as $i => $ret)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $ret->loan->user->name ?? '-' }}</td>
            <td>{{ $ret->loan->user->nik ?? '-' }}</td>
            <td>{{ $ret->loan->book->title ?? '-' }}</td>
            <td>{{ $ret->return_date ? \Carbon\Carbon::parse($ret->return_date)->format('d/m/Y') : '-' }}</td>
            <td>Rp{{ number_format($ret->fine ?? 0, 0, ',', '.') }}</td>
            <td>
                @if($ret->fine_status === 'lunas') <span class="badge badge-green">Lunas</span>
                @elseif($ret->fine_status === 'belum_lunas') <span class="badge badge-red">Belum Lunas</span>
                @else <span class="badge badge-green">Tidak Ada</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:20px;color:#9ca3af;">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>
@endif

<div class="footer">
    <div class="ttd">
        <p>Mengetahui,</p>
        <p>Kepala Perpustakaan</p>
        <div class="garis"></div>
        <p>( ________________________ )</p>
    </div>
</div>

<script>window.onload = () => window.print();</script>
</body>
</html>
