@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

{{-- Form Filter --}}
<div class="table-card" style="padding:28px 32px;margin-bottom:24px;">
    <div style="font-family:'Poppins-Bold','Segoe UI',sans-serif;font-size:16px;font-weight:700;color:#1e1e1e;margin-bottom:20px;">🖨️ Cetak Laporan</div>

    <form method="GET" action="{{ route($route) }}" id="formLaporan">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:16px;align-items:end;">

            <div class="form-group">
                <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Jenis Laporan</label>
                <select name="jenis" data-cs data-cs-form style="display:none">
                    <option value="peminjaman" {{ ($jenis ?? 'peminjaman') === 'peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                    <option value="pengembalian" {{ ($jenis ?? '') === 'pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                </select>
            </div>

            <div class="form-group">
                <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dari ?? '' }}"
                    style="width:100%;padding:11px 14px;border:1.5px solid #d1d5db;border-radius:10px;font-size:14px;outline:none;">
            </div>

            <div class="form-group">
                <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $sampai ?? '' }}"
                    style="width:100%;padding:11px 14px;border:1.5px solid #d1d5db;border-radius:10px;font-size:14px;outline:none;">
            </div>

            <div class="form-group">
                <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">Bulan (opsional)</label>
                <select name="bulan" data-cs data-cs-form style="display:none">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari'=>1,'Februari'=>2,'Maret'=>3,'April'=>4,'Mei'=>5,'Juni'=>6,'Juli'=>7,'Agustus'=>8,'September'=>9,'Oktober'=>10,'November'=>11,'Desember'=>12] as $nama => $num)
                        <option value="{{ $num }}" {{ ($bulan ?? '') == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div style="display:flex;gap:12px;margin-top:20px;">
            <button type="submit" class="btn-tambah">Tampilkan</button>
            @if($generated)
                <a href="{{ route($cetakRoute, ['dari'=>$dari,'sampai'=>$sampai,'bulan'=>$bulan,'jenis'=>$jenis]) }}"
                   target="_blank" class="btn-cetak-pdf">🖨️ Cetak / Print</a>
            @endif
        </div>
    </form>
</div>

{{-- Hasil --}}
@if($generated)
<div class="table-card">
    <div style="padding:16px 24px 0;font-family:'Poppins-Bold','Segoe UI',sans-serif;font-size:14px;font-weight:700;color:#2f5d34;">
        Laporan {{ ucfirst($jenis) }} — {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}
        @if($bulan)
            — {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan] }}
        @endif
        <span style="font-weight:400;color:#6b6b6b;font-size:13px;margin-left:8px;">({{ $data->count() }} data)</span>
    </div>

    <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
    @if($jenis === 'peminjaman')
    <table class="data-table" style="min-width:700px;">
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
                <td><span class="status-badge {{ $loan->pengajuan_status }}">{{ ucfirst($loan->pengajuan_status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7" class="empty-row">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
    @else
    <table class="data-table" style="min-width:700px;">
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
                    @if($ret->fine_status === 'lunas') <span class="status-badge disetujui">Lunas</span>
                    @elseif($ret->fine_status === 'belum_lunas') <span class="status-badge pending">Belum Lunas</span>
                    @else <span class="status-badge disetujui">Tidak Ada</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="empty-row">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
    @endif
    </div>
</div>
@endif
