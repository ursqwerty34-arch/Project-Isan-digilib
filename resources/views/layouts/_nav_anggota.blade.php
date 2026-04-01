<div class="sidebar-brand">
    <span class="sidebar-icon">📚</span>
    <span class="sidebar-brand-text">Anggota</span>
</div>
<nav class="sidebar-nav">
    <a href="{{ route('dashboard.anggota') }}" class="nav-item {{ request()->routeIs('dashboard.anggota') ? 'active' : '' }}">🏠 Beranda</a>
    <a href="{{ route('anggota.buku.index') }}" class="nav-item {{ request()->routeIs('anggota.buku*') ? 'active' : '' }}">📖 Buku</a>
    <a href="{{ route('anggota.transaksi') }}" class="nav-item {{ request()->routeIs('anggota.transaksi*') ? 'active' : '' }}">🔄 Transaksi</a>
    <a href="{{ route('anggota.notif') }}" class="nav-item {{ request()->routeIs('anggota.notif*') ? 'active' : '' }}">🔔 Pemberitahuan</a>
    <a href="{{ route('anggota.profil') }}" class="nav-item {{ request()->routeIs('anggota.profil*') ? 'active' : '' }}">👤 Profil</a>
</nav>
<form method="POST" action="{{ route('logout') }}" class="sidebar-logout">
    @csrf
    <button type="submit" class="btn-sidebar-logout">• Logout</button>
</form>
