<div class="sidebar-title">Petugas</div>
<nav class="sidebar-nav">
    <a href="{{ route('dashboard.petugas') }}" class="nav-item {{ request()->routeIs('dashboard.petugas') ? 'active' : '' }}">🏠 Beranda</a>
    <a href="{{ route('petugas.pengajuan') }}" class="nav-item {{ request()->routeIs('petugas.pengajuan*') ? 'active' : '' }}">🔄 Pengajuan</a>
    <a href="{{ route('petugas.pengembalian') }}" class="nav-item {{ request()->routeIs('petugas.pengembalian*') ? 'active' : '' }}">💰 Pengembalian</a>
    <a href="{{ route('petugas.buku.index') }}" class="nav-item {{ request()->routeIs('petugas.buku*') ? 'active' : '' }}">📋 Daftar Buku</a>
    <a href="{{ route('petugas.laporan') }}" class="nav-item {{ request()->routeIs('petugas.laporan*') ? 'active' : '' }}">🖨️ Cetak Laporan</a>
    <a href="{{ route('petugas.profil') }}" class="nav-item {{ request()->routeIs('petugas.profil*') ? 'active' : '' }}">👤 Profil</a>
</nav>
<form method="POST" action="{{ route('logout') }}" class="sidebar-logout">
    @csrf
    <button type="submit" class="btn-sidebar-logout">• Logout</button>
</form>
