@extends('layouts.petugas')
@section('title', 'Beranda')

@section('content')

{{-- Stats ringkas --}}
<div class="kp-stats-row">
    <div class="kp-stat" style="border-left-color:#ffa726;">
        <div class="kp-stat-val" style="color:#e65100;">{{ $totalPending }}</div>
        <div class="kp-stat-lbl">Pengajuan Pending</div>
    </div>
    <div class="kp-stat">
        <div class="kp-stat-val">{{ $totalDipinjam }}</div>
        <div class="kp-stat-lbl">Sedang Dipinjam</div>
    </div>
    <div class="kp-stat" style="border-left-color:#78909c;">
        <div class="kp-stat-val" style="color:#37474f;">{{ $totalDikembalikan }}</div>
        <div class="kp-stat-lbl">Total Pengembalian</div>
    </div>
    <div class="kp-stat">
        <div class="kp-stat-val">{{ $totalBuku }}</div>
        <div class="kp-stat-lbl">Total Buku</div>
    </div>
    <div class="kp-stat">
        <div class="kp-stat-val">{{ $totalAnggota }}</div>
        <div class="kp-stat-lbl">Total Anggota</div>
    </div>
</div>

{{-- Baris 1: Line chart pengajuan lebar penuh --}}
<div class="chart-card" style="margin-top:20px;">
    <div class="chart-card-title">📋 Aktivitas Pengajuan Peminjaman — 12 Bulan Terakhir</div>
    <canvas id="chartPengajuan" height="80"></canvas>
</div>

{{-- Baris 2: Bar + Doughnut --}}
<div class="chart-grid-2" style="margin-top:20px;">
    <div class="chart-card">
        <div class="chart-card-title">🏆 Top 5 Buku Terpopuler</div>
        <canvas id="chartBar" height="160"></canvas>
    </div>
    <div class="chart-card">
        <div class="chart-card-title">💰 Status Denda Pengembalian</div>
        <div style="display:flex;align-items:center;justify-content:center;gap:28px;flex-wrap:wrap;padding-top:8px;">
            <div style="width:180px;"><canvas id="chartDenda"></canvas></div>
            <div id="dendaLegend" style="font-size:13px;display:flex;flex-direction:column;gap:10px;"></div>
        </div>
    </div>
</div>

{{-- Baris 3: Pengembalian per bulan --}}
<div class="chart-card" style="margin-top:20px;">
    <div class="chart-card-title">🔄 Pengembalian Buku — 12 Bulan Terakhir</div>
    <canvas id="chartKembali" height="70"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const G  = '#2f5d34', GL = '#4caf50', GP = '#a5d6a7';
const OR = '#ffa726', SL = '#78909c', RD = '#ef5350';

Chart.defaults.font.family = "'Segoe UI', Helvetica, sans-serif";
Chart.defaults.color = '#6b6b6b';

// 1. Line: Pengajuan (pending, disetujui, ditolak)
new Chart(document.getElementById('chartPengajuan'), {
    type: 'line',
    data: {
        labels: @json($bulanLabels),
        datasets: [
            { label: 'Pending',    data: @json($dataPending),   borderColor: OR, backgroundColor: 'rgba(255,167,38,0.08)', borderWidth: 2.5, pointBackgroundColor: OR, pointRadius: 4, tension: 0.4, fill: true },
            { label: 'Disetujui', data: @json($dataDisetujui), borderColor: GL, backgroundColor: 'rgba(76,175,80,0.08)',  borderWidth: 2.5, pointBackgroundColor: GL, pointRadius: 4, tension: 0.4, fill: true },
            { label: 'Ditolak',   data: @json($dataDitolak),   borderColor: RD, backgroundColor: 'rgba(239,83,80,0.06)',  borderWidth: 2.5, pointBackgroundColor: RD, pointRadius: 4, tension: 0.4, fill: true },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top', labels: { font: { size: 12 }, boxWidth: 14, padding: 16 } } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});

// 2. Bar: Top 5 buku
new Chart(document.getElementById('chartBar'), {
    type: 'bar',
    data: {
        labels: @json($topBuku->pluck('book.title')->map(fn($t) => strlen($t) > 22 ? substr($t,0,22).'…' : $t)),
        datasets: [{
            label: 'Dipinjam',
            data: @json($topBuku->pluck('total')),
            backgroundColor: [G, GL, GP, OR, SL],
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});

// 3. Doughnut: Status denda
const dLabels = @json(array_keys($dendaStats));
const dData   = @json(array_values($dendaStats));
const dColors = [GP, RD, GL];

new Chart(document.getElementById('chartDenda'), {
    type: 'doughnut',
    data: {
        labels: dLabels,
        datasets: [{ data: dData, backgroundColor: dColors, borderWidth: 2, borderColor: '#fff' }]
    },
    options: { responsive: true, cutout: '62%', plugins: { legend: { display: false } } }
});

const dLeg = document.getElementById('dendaLegend');
dLabels.forEach((l, i) => {
    dLeg.innerHTML += `<div style="display:flex;align-items:center;gap:8px;">
        <span style="width:12px;height:12px;border-radius:3px;background:${dColors[i]};flex-shrink:0;display:inline-block;"></span>
        <span style="color:#374151;">${l}: <strong>${dData[i]}</strong></span>
    </div>`;
});

// 4. Line: Pengembalian per bulan
new Chart(document.getElementById('chartKembali'), {
    type: 'line',
    data: {
        labels: @json($bulanLabels),
        datasets: [{
            label: 'Pengembalian',
            data: @json($dataKembali),
            borderColor: SL,
            backgroundColor: 'rgba(120,144,156,0.09)',
            borderWidth: 2.5,
            pointBackgroundColor: SL,
            pointRadius: 5,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
            x: { grid: { display: false }, ticks: { font: { size: 12 } } }
        }
    }
});
</script>

@endsection
