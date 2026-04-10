@extends('layouts.kepala')
@section('title', 'Beranda')

@section('content')

{{-- Stats ringkas --}}
<div class="kp-stats-row">
    <div class="kp-stat">
        <div class="kp-stat-val">{{ $totalAnggota }}</div>
        <div class="kp-stat-lbl">Anggota</div>
    </div>
    <div class="kp-stat">
        <div class="kp-stat-val">{{ $totalPetugas }}</div>
        <div class="kp-stat-lbl">Petugas</div>
    </div>
    <div class="kp-stat">
        <div class="kp-stat-val">{{ $totalBuku }}</div>
        <div class="kp-stat-lbl">Buku</div>
    </div>
    <div class="kp-stat">
        <div class="kp-stat-val">{{ $totalPeminjaman }}</div>
        <div class="kp-stat-lbl">Peminjaman</div>
    </div>
    <div class="kp-stat">
        <div class="kp-stat-val">{{ $totalPengembalian }}</div>
        <div class="kp-stat-lbl">Pengembalian</div>
    </div>
</div>

{{-- Baris 1: Line chart lebar penuh --}}
<div class="chart-card" style="margin-top:20px;">
    <div class="chart-card-title">📈 Peminjaman & Pengembalian — 12 Bulan Terakhir</div>
    <canvas id="chartLine" height="80"></canvas>
</div>

{{-- Baris 2: Bar + Doughnut --}}
<div class="chart-grid-2" style="margin-top:20px;">
    <div class="chart-card">
        <div class="chart-card-title">🏆 Top 5 Buku Terpopuler</div>
        <canvas id="chartBar" height="160"></canvas>
    </div>
    <div class="chart-card">
        <div class="chart-card-title">🔵 Status Peminjaman</div>
        <div style="display:flex;align-items:center;justify-content:center;gap:28px;flex-wrap:wrap;padding-top:8px;">
            <div style="width:180px;"><canvas id="chartPie"></canvas></div>
            <div id="pieLegend" style="font-size:13px;display:flex;flex-direction:column;gap:10px;"></div>
        </div>
    </div>
</div>

{{-- Baris 3: Anggota baru --}}
<div class="chart-card" style="margin-top:20px;">
    <div class="chart-card-title">👥 Pertumbuhan Anggota Baru — 6 Bulan Terakhir</div>
    <canvas id="chartAnggota" height="70"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const G  = '#2f5d34', GL = '#4caf50', GP = '#a5d6a7';
const OR = '#ffa726', SL = '#78909c', RD = '#ef5350';

Chart.defaults.font.family = "'Segoe UI', Helvetica, sans-serif";
Chart.defaults.color = '#6b6b6b';

// 1. Line
new Chart(document.getElementById('chartLine'), {
    type: 'line',
    data: {
        labels: @json($bulanLabels),
        datasets: [
            { label: 'Peminjaman',   data: @json($dataPinjam),  borderColor: GL, backgroundColor: 'rgba(76,175,80,0.08)',  borderWidth: 2.5, pointBackgroundColor: GL, pointRadius: 4, tension: 0.4, fill: true },
            { label: 'Pengembalian', data: @json($dataKembali), borderColor: OR, backgroundColor: 'rgba(255,167,38,0.07)', borderWidth: 2.5, pointBackgroundColor: OR, pointRadius: 4, tension: 0.4, fill: true }
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

// 2. Bar
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

// 3. Doughnut
const pLabels = @json(array_keys($statusPinjam));
const pData   = @json(array_values($statusPinjam));
const pColors = [OR, GL, GP, RD];

new Chart(document.getElementById('chartPie'), {
    type: 'doughnut',
    data: {
        labels: pLabels,
        datasets: [{ data: pData, backgroundColor: pColors, borderWidth: 2, borderColor: '#fff' }]
    },
    options: { responsive: true, cutout: '62%', plugins: { legend: { display: false } } }
});

const leg = document.getElementById('pieLegend');
pLabels.forEach((l, i) => {
    leg.innerHTML += `<div style="display:flex;align-items:center;gap:8px;">
        <span style="width:12px;height:12px;border-radius:3px;background:${pColors[i]};flex-shrink:0;display:inline-block;"></span>
        <span style="color:#374151;">${l}: <strong>${pData[i]}</strong></span>
    </div>`;
});

// 4. Anggota baru
new Chart(document.getElementById('chartAnggota'), {
    type: 'line',
    data: {
        labels: @json($anggotaLabels),
        datasets: [{
            label: 'Anggota Baru',
            data: @json($dataAnggota),
            borderColor: G,
            backgroundColor: 'rgba(47,93,52,0.09)',
            borderWidth: 2.5,
            pointBackgroundColor: G,
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
