<div class="sidebar-title">Kepala Perpustakaan</div>
<nav class="sidebar-nav">
    <a href="{{ route('dashboard.kepala') }}" class="nav-item {{ request()->routeIs('dashboard.kepala') ? 'active' : '' }}">🏠 Beranda</a>
    <a href="{{ route('kepala.transaksi') }}" class="nav-item {{ request()->routeIs('kepala.transaksi*') ? 'active' : '' }}">🔄 Transaksi</a>
    <a href="{{ route('kepala.buku.index') }}" class="nav-item {{ request()->routeIs('kepala.buku*') ? 'active' : '' }}">📋 Daftar Buku</a>
    <a href="{{ route('kepala.kategori.index') }}" class="nav-item {{ request()->routeIs('kepala.kategori*') ? 'active' : '' }}">🏷️ Kategori Buku</a>
    <a href="{{ route('kepala.anggota.index') }}" class="nav-item {{ request()->routeIs('kepala.anggota*') ? 'active' : '' }}">👤 Daftar Anggota</a>
    <a href="{{ route('kepala.petugas.index') }}" class="nav-item {{ request()->routeIs('kepala.petugas*') ? 'active' : '' }}">🧑‍💼 Daftar Petugas</a>
    <a href="{{ route('kepala.laporan') }}" class="nav-item {{ request()->routeIs('kepala.laporan*') ? 'active' : '' }}">🖨️ Cetak Laporan</a>
</nav>
<form method="POST" action="{{ route('logout') }}" class="sidebar-logout">
    @csrf
    <button type="submit" class="btn-sidebar-logout">• Logout</button>
</form>
