<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') – Anggota</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}?v={{ filemtime(public_path('css/globals.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ filemtime(public_path('css/dashboard.css')) }}">
</head>
<body class="anggota-body">

{{-- TOP NAVBAR --}}
<header class="anggota-topnav">
    <div class="anggota-topnav-inner">
        {{-- Brand --}}
        <a href="{{ route('dashboard.anggota') }}" class="anggota-brand">
            <span class="anggota-brand-icon">📚</span>
            <span class="anggota-brand-text">DigiLib</span>
        </a>

        {{-- Nav Links (desktop) --}}
        <nav class="anggota-nav-links">
            <a href="{{ route('dashboard.anggota') }}" class="anggota-nav-link {{ request()->routeIs('dashboard.anggota') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('anggota.buku.index') }}" class="anggota-nav-link {{ request()->routeIs('anggota.buku*') ? 'active' : '' }}">Koleksi Buku</a>
            <a href="{{ route('anggota.transaksi') }}" class="anggota-nav-link {{ request()->routeIs('anggota.transaksi*') ? 'active' : '' }}">Transaksi</a>
            <a href="{{ route('anggota.notif') }}" class="anggota-nav-link {{ request()->routeIs('anggota.notif*') ? 'active' : '' }}">Notifikasi</a>
        </nav>

        {{-- Right: profil + logout --}}
        <div class="anggota-nav-right">
            <a href="{{ route('anggota.profil') }}" class="anggota-nav-profil {{ request()->routeIs('anggota.profil*') ? 'active' : '' }}">
                @if(Auth::user()->photo)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="foto">
                @else
                    <span class="anggota-nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                @endif
                <span>{{ explode(' ', Auth::user()->name)[0] }}</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="anggota-nav-logout">Logout</button>
            </form>
        </div>

        {{-- Hamburger (mobile) --}}
        <button class="anggota-hamburger" id="hamburgerBtn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

{{-- Mobile Drawer --}}
<div class="anggota-drawer-overlay" id="navOverlay"></div>
<div class="anggota-drawer" id="navDrawer">
    <div class="anggota-drawer-brand">📚 DigiLib</div>
    <nav class="anggota-drawer-nav">
        <a href="{{ route('dashboard.anggota') }}" class="anggota-drawer-link {{ request()->routeIs('dashboard.anggota') ? 'active' : '' }}">🏠 Beranda</a>
        <a href="{{ route('anggota.buku.index') }}" class="anggota-drawer-link {{ request()->routeIs('anggota.buku*') ? 'active' : '' }}">📖 Koleksi Buku</a>
        <a href="{{ route('anggota.transaksi') }}" class="anggota-drawer-link {{ request()->routeIs('anggota.transaksi*') ? 'active' : '' }}">🔄 Transaksi</a>
        <a href="{{ route('anggota.notif') }}" class="anggota-drawer-link {{ request()->routeIs('anggota.notif*') ? 'active' : '' }}">🔔 Notifikasi</a>
        <a href="{{ route('anggota.profil') }}" class="anggota-drawer-link {{ request()->routeIs('anggota.profil*') ? 'active' : '' }}">👤 Profil</a>
    </nav>
    <form method="POST" action="{{ route('logout') }}" style="padding:0 16px 24px;">
        @csrf
        <button type="submit" class="anggota-drawer-logout">Logout</button>
    </form>
</div>

{{-- PAGE CONTENT --}}
<main class="anggota-main">
    <div class="anggota-container">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
</main>

{{-- FOOTER --}}
<footer class="anggota-footer">
    <div class="anggota-footer-inner">
        <div class="anggota-footer-brand">
            <span>📚</span> DigiLib
        </div>
        <div class="anggota-footer-links">
            <a href="{{ route('dashboard.anggota') }}">Beranda</a>
            <a href="{{ route('anggota.buku.index') }}">Koleksi Buku</a>
            <a href="{{ route('anggota.transaksi') }}">Transaksi</a>
            <a href="{{ route('anggota.notif') }}">Notifikasi</a>
            <a href="{{ route('anggota.profil') }}">Profil</a>
        </div>
        <div class="anggota-footer-copy">&copy; {{ date('Y') }} DigiLib. Semua hak dilindungi.</div>
    </div>
</footer>

@yield('modal')

<script>
const btn = document.getElementById('hamburgerBtn');
const drawer = document.getElementById('navDrawer');
const overlay = document.getElementById('navOverlay');
if(btn){
    btn.addEventListener('click', () => { drawer.classList.toggle('open'); overlay.classList.toggle('open'); });
    overlay.addEventListener('click', () => { drawer.classList.remove('open'); overlay.classList.remove('open'); });
}
</script>

</body>
</html>
