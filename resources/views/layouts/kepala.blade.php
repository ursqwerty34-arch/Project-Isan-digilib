<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>@yield('title', 'Dashboard') – Kepala Perpustakaan</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<nav class="mobile-navbar">
    <span class="mobile-navbar-title">Kepala Perpustakaan</span>
    <button class="hamburger-btn" id="hamburgerBtn" aria-label="Menu">
        <span></span><span></span><span></span>
    </button>
</nav>
<div class="mobile-nav-overlay" id="navOverlay"></div>
<div class="mobile-nav-drawer" id="navDrawer">
    @include('layouts._nav_kepala')
</div>

<div class="admin-layout">
    <aside class="sidebar">
        @include('layouts._nav_kepala')
    </aside>

    <main class="main-content">
        <div class="topbar">
            <span>Halo, Kepala Perpustakaan 👋</span>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>
</div>

@yield('modal')

<script>
const btn = document.getElementById('hamburgerBtn');
const drawer = document.getElementById('navDrawer');
const overlay = document.getElementById('navOverlay');
if(btn) {
    btn.addEventListener('click', () => { drawer.classList.toggle('open'); overlay.classList.toggle('open'); });
    overlay.addEventListener('click', () => { drawer.classList.remove('open'); overlay.classList.remove('open'); });
}
</script>

</body>
</html>
