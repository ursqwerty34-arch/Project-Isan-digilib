<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta name="description" content="DigiLib - Temukan buku seru, belajar santai, dan jelajahi ilmu baru" />
    <title>DigiLib</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>

<div class="landing-page">

    {{-- Bukit hijau background --}}
    <div class="hills">
        <div class="hill hill-1"></div>
        <div class="hill hill-2"></div>
        <div class="hill hill-3"></div>
    </div>

    {{-- Dekorasi lingkaran kiri --}}
    <div class="deco-left">
        <div class="deco-circle-left pink"></div>
    </div>

    {{-- Dekorasi lingkaran kanan atas --}}
    <div class="deco-right">
        <div class="deco-dot yellow"></div>
        <div class="deco-dot green"></div>
        <div class="deco-dot pink-sm"></div>
    </div>

    {{-- Mascot buku --}}
    <div class="mascot">
        <svg viewBox="0 0 160 160" xmlns="http://www.w3.org/2000/svg">
            <!-- Tubuh buku -->
            <rect x="10" y="10" width="135" height="135" rx="24" fill="#ffffff" stroke="#2f5d34" stroke-width="6"/>
            <!-- Mata kiri -->
            <circle cx="55" cy="72" r="7" fill="#2f5d34"/>
            <circle cx="57" cy="70" r="2.5" fill="#ffffff"/>
            <!-- Mata kanan -->
            <circle cx="100" cy="72" r="7" fill="#2f5d34"/>
            <circle cx="102" cy="70" r="2.5" fill="#ffffff"/>
            <!-- Senyum -->
            <path d="M52,98 Q77,118 103,98" stroke="#2f5d34" stroke-width="4.5" fill="none" stroke-linecap="round"/>
            <!-- Daun kecil -->
            <ellipse cx="138" cy="22" rx="13" ry="7" fill="#4caf50" transform="rotate(-35 138 22)"/>
            <line x1="136" y1="26" x2="134" y2="14" stroke="#2f5d34" stroke-width="2.5" stroke-linecap="round"/>
        </svg>
    </div>

    {{-- Card utama --}}
    <div class="welcome-card">
        <h1>Selamat Datang 👋</h1>
        <h2>DigiLib</h2>
        <p>Temukan buku seru, belajar santai, dan jelajahi ilmu baru</p>

        <a href="{{ route('register') }}" class="btn-mulai">Mulai Membaca</a>

        <p class="login-hint">
            Sudah punya akun? <a href="{{ route('login') }}">Login yuk 😊</a>
        </p>
    </div>

    {{-- Footer --}}
    <footer class="landing-footer">
        <p>Belajar lebih menyenangkan bersama DigiLib 🌿📚</p>
        <p>© {{ date('Y') }} DigiLib • Dibuat untuk pembelajaran</p>
    </footer>

</div>

</body>
</html>
