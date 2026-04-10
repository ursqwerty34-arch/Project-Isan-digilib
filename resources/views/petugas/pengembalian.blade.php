@extends('layouts.petugas')
@section('title', 'Pengembalian')

@section('content')

    <div class="pengajuan-card">

        {{-- TABEL PENDING PENGEMBALIAN --}}
        <div class="pengajuan-section-title">Daftar pengembalian</div>
        <table class="pengajuan-table">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>tgl jatuh tempo</th>
                    <th>tgl kembali</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pending as $loan)
                    @php
                        $isLate = \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($loan->due_date));
                        $returnDate = \Carbon\Carbon::now()->format('d/m/Y');
                    @endphp
                    <tr>
                        <td>
                            <div class="cover-thumb">@if($loan->book->cover)<img
                            src="{{ asset('storage/' . $loan->book->cover) }}" alt="">@endif</div>
                        </td>
                        <td>{{ $loan->book->title ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                        <td>{{ $loan->user->name ?? '-' }}</td>
                        <td>{{ $loan->user->nik ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>
                        <td style="{{ $isLate ? 'color:#ef5350; font-weight:700;' : '' }}">{{ $returnDate }}</td>
                        <td>
                            @if($isLate)
                                <button class="btn-hitung-denda" data-id="{{ $loan->id }}"
                                    data-url="{{ route('petugas.pengembalian.hitung', $loan) }}"
                                    data-kirim="{{ route('petugas.pengembalian.kirim', $loan) }}">
                                    Hitung Denda
                                </button>
                            @else
                                <button class="btn-konfirmasi-kembali" data-id="{{ $loan->id }}"
                                    data-url="{{ route('petugas.pengembalian.konfirmasi', $loan) }}">
                                    Konfirmasi
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-row">Tidak ada pengembalian pending.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {!! $pending->render('layouts._pagination') !!}

        {{-- TABEL SUDAH DIKONFIRMASI --}}
        <div class="pengajuan-section-title" style="margin-top:32px;">
            Daftar pengembalian yang petugas - konfirmasi
        </div>
        <table class="pengajuan-table">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>NIK</th>
                    <th>tgl jatuh tempo</th>
                    <th>tgl kembali</th>
                    <th>Total Denda</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($confirmed as $ret)
                    <tr>
                        <td>
                            <div class="cover-thumb">@if($ret->loan->book->cover)<img
                            src="{{ asset('storage/' . $ret->loan->book->cover) }}" alt="">@endif</div>
                        </td>
                        <td>{{ $ret->loan->book->title ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($ret->loan->loan_date)->format('d/m/Y') }}</td>
                        <td>{{ $ret->loan->user->nik ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($ret->loan->due_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($ret->return_date)->format('d/m/Y') }}</td>
                        <td style="{{ $ret->fine > 0 ? 'color:#ef5350; font-weight:700;' : '' }}">
                            {{ $ret->fine > 0 ? number_format($ret->fine, 0, ',', '.') : '-' }}
                        </td>
                        <td>
                            @if($ret->fine_status === 'belum_lunas')
                                <button class="btn-lunas-denda" data-url="{{ route('petugas.pengembalian.lunas', $ret) }}">
                                    Tandai Lunas
                                </button>
                            @elseif($ret->fine_status === 'lunas')
                                <span style="color:#2f5d34; font-weight:700; font-size:13px;">Lunas</span>
                            @else
                                <span style="color:#2f5d34; font-size:13px;">Dikembalikan</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('petugas.pengembalian.detail', $ret) }}" class="btn-detail-icon"
                                title="Detail">&#128441;</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="empty-row">Belum ada konfirmasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {!! $confirmed->render('layouts._pagination') !!}

    </div>

    {{-- MODAL SUKSES KONFIRMASI (screenshot 2) --}}
    <div class="modal-overlay" id="modalSuksesKonfirmasi">
        <div class="modal-box" style="max-width:420px; text-align:center;">
            <div class="modal-mascot-circle" style="margin-bottom:20px;">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                    <rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2" />
                    <circle cx="18" cy="22" r="2.5" fill="#2f5d34" />
                    <circle cx="30" cy="22" r="2.5" fill="#2f5d34" />
                    <path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none" />
                    <line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round" />
                    <circle cx="24" cy="2" r="1.5" fill="#4caf50" />
                </svg>
                <span class="modal-mascot-dot"></span>
            </div>
            <div class="modal-title" style="color:#2f5d34; font-size:17px; margin-bottom:24px;">Pengembalian buku berhasil
                di<br>konfirmasi...</div>
            <div class="modal-actions" style="justify-content:center;">
                <button class="btn-modal-hapus" onclick="window.location.reload()">Kembali</button>
            </div>
        </div>
    </div>

    {{-- MODAL HITUNG DENDA (screenshot 3) --}}
    <div class="modal-overlay" id="modalHitungDenda">
        <div class="modal-box" style="max-width:520px; text-align:left;">
            <div class="modal-title" style="color:#2f5d34; font-size:18px; margin-bottom:16px;">Denda Keterlambatan</div>

            <div class="denda-info-grid"
                style="background:#f9fdf9; border-radius:10px; padding:14px 16px; margin-bottom:16px; font-size:13px;">
                <div style="display:grid; grid-template-columns:100px 1fr; gap:6px 0;">
                    <span style="color:#6b6b6b;">Judul Buku</span>
                    <span id="dendaJudul" style="font-weight:700; color:#1e1e1e;"></span>
                    <span style="color:#6b6b6b;">Peminjam</span>
                    <div style="display:flex; gap:16px; align-items:center;">
                        <span id="dendaPeminjam" style="font-weight:700; color:#1e1e1e;"></span>
                        <span style="color:#6b6b6b; font-size:11px;">&#128197; <span id="dendaTglPinjam"></span></span>
                    </div>
                    <span style="color:#6b6b6b;">Jatuh Tempo</span>
                    <div style="display:flex; gap:16px; align-items:center;">
                        <span style="color:#6b6b6b; font-size:11px;">&#128197; <span id="dendaTglJatuh"></span></span>
                        <span style="color:#ef5350; font-size:11px; font-weight:700;">&#128197; <span
                                id="dendaTglKembali"></span></span>
                    </div>
                </div>
                <div style="text-align:right; margin-top:8px; font-size:12px; color:#6b6b6b;">Total telat: <span
                        id="dendaTelat" style="font-weight:700; color:#1e1e1e;"></span> Hari</div>
            </div>

            <div style="background:#e8f5e9; border-radius:10px; padding:14px 16px; font-size:13px;">
                <div style="font-weight:700; color:#2f5d34; margin-bottom:10px;">Rincian Pembayaran</div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; align-items:start;">
                    <div>
                        <div style="color:#6b6b6b; font-size:11px; margin-bottom:4px;">Masukan Total Telat</div>
                        <div style="display:flex; align-items:center; gap:6px;">
                            <input type="number" id="inputTelat" min="0"
                                style="width:60px; padding:6px 8px; border:1.5px solid #a5d6a7; border-radius:6px; font-size:13px; outline:none;">
                            <span style="font-size:12px; color:#6b6b6b;">Hari</span>
                        </div>
                        <div style="color:#6b6b6b; font-size:11px; margin-top:8px; margin-bottom:4px;">Jumlah Bayar</div>
                        <div id="jumlahBayarDisplay"
                            style="padding:6px 10px; background:#fff; border:1.5px solid #a5d6a7; border-radius:6px; font-size:13px; color:#6b6b6b; min-width:100px;">
                            Rp. 0</div>
                        <div style="margin-top:8px; font-weight:700; font-size:13px;">Total Bayar <span
                                id="totalBayarDisplay" style="color:#1e1e1e;">Rp. 0</span></div>
                    </div>
                    <div style="font-size:12px; color:#6b6b6b; line-height:1.6; padding-top:20px;">
                        <span style="color:#6b6b6b;">Denda/Hari</span> &nbsp; <span
                            style="font-weight:700; color:#1e1e1e;">Rp. 5.000</span><br><br>
                        Denda di bayar ketika sedang<br>mengembalikan buku di<br>perpustakaan (cash only)
                    </div>
                </div>
            </div>

            <div class="modal-actions" style="margin-top:20px; justify-content:center; gap:16px;">
                <button class="btn-modal-batal" id="btnBatalDenda">Batal</button>
                <button class="btn-modal-hapus" id="btnKirimDenda">Kirim pemberitahuan kepada anggota</button>
            </div>
        </div>
    </div>

    {{-- MODAL SUKSES KIRIM DENDA (screenshot 4) --}}
    <div class="modal-overlay" id="modalSuksesKirim">
        <div class="modal-box" style="max-width:420px; text-align:center;">
            <div class="modal-mascot-circle" style="margin-bottom:20px;">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                    <rect x="6" y="8" width="36" height="32" rx="7" fill="white" stroke="#4caf50" stroke-width="2.2" />
                    <circle cx="18" cy="22" r="2.5" fill="#2f5d34" />
                    <circle cx="30" cy="22" r="2.5" fill="#2f5d34" />
                    <path d="M17 29 Q24 35 31 29" stroke="#2f5d34" stroke-width="2.2" stroke-linecap="round" fill="none" />
                    <line x1="24" y1="8" x2="24" y2="3" stroke="#4caf50" stroke-width="2" stroke-linecap="round" />
                    <circle cx="24" cy="2" r="1.5" fill="#4caf50" />
                </svg>
                <span class="modal-mascot-dot"></span>
            </div>
            <div class="modal-title" style="color:#2f5d34; font-size:17px; margin-bottom:24px;">Pemberitahuan sudah terkirim
            </div>
            <div class="modal-actions" style="justify-content:center;">
                <button class="btn-modal-hapus" onclick="window.location.reload()">Kembali</button>
            </div>
        </div>
    </div>

    <script>
        const DENDA_PER_HARI = 5000;
        let currentKirimUrl = null;
        let currentTglKembaliRaw = null;

        // ── Konfirmasi tanpa denda ──
        document.querySelectorAll('.btn-konfirmasi-kembali').forEach(btn => {
            btn.addEventListener('click', () => {
                fetch(btn.dataset.url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ return_date: new Date().toISOString().split('T')[0] })
                })
                    .then(r => r.json())
                    .then(d => { if (d.success) document.getElementById('modalSuksesKonfirmasi').classList.add('active'); });
            });
        });

        // ── Hitung denda ──
        document.querySelectorAll('.btn-hitung-denda').forEach(btn => {
            btn.addEventListener('click', () => {
                currentKirimUrl = btn.dataset.kirim;
                fetch(btn.dataset.url)
                    .then(r => r.json())
                    .then(d => {
                        document.getElementById('dendaJudul').textContent = d.judul;
                        document.getElementById('dendaPeminjam').textContent = d.peminjam;
                        document.getElementById('dendaTglPinjam').textContent = d.tgl_pinjam;
                        document.getElementById('dendaTglJatuh').textContent = d.tgl_jatuh;
                        document.getElementById('dendaTglKembali').textContent = d.tgl_kembali;
                        document.getElementById('dendaTelat').textContent = d.telat;
                        document.getElementById('inputTelat').value = d.telat;
                        currentTglKembaliRaw = d.tgl_kembali_raw;
                        hitungTotal(d.telat);
                        document.getElementById('modalHitungDenda').classList.add('active');
                    });
            });
        });

        document.getElementById('inputTelat').addEventListener('input', function () {
            hitungTotal(parseInt(this.value) || 0);
        });

        function hitungTotal(telat) {
            const total = telat * DENDA_PER_HARI;
            document.getElementById('jumlahBayarDisplay').textContent = 'Rp. ' + total.toLocaleString('id-ID');
            document.getElementById('totalBayarDisplay').textContent = 'Rp. ' + total.toLocaleString('id-ID');
        }

        document.getElementById('btnBatalDenda').addEventListener('click', () => {
            document.getElementById('modalHitungDenda').classList.remove('active');
        });

        document.getElementById('btnKirimDenda').addEventListener('click', () => {
            const telat = parseInt(document.getElementById('inputTelat').value) || 0;
            const total = telat * DENDA_PER_HARI;
            fetch(currentKirimUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ telat, total_denda: total, return_date: currentTglKembaliRaw })
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        document.getElementById('modalHitungDenda').classList.remove('active');
                        document.getElementById('modalSuksesKirim').classList.add('active');
                    }
                });
        });

        // ── Tandai lunas ──
        document.querySelectorAll('.btn-lunas-denda').forEach(btn => {
            btn.addEventListener('click', () => {
                fetch(btn.dataset.url, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                    .then(r => r.json())
                    .then(d => { if (d.success) window.location.reload(); });
            });
        });
    </script>

@endsection