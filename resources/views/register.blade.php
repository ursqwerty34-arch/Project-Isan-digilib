<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <title>Daftar Akun – DigiLib</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="login-page">

 
    <div class="login-mascot">
        <svg viewBox="0 0 160 160" xmlns="http://www.w3.org/2000/svg">
            <rect x="15" y="15" width="130" height="130" rx="22" fill="#ffffff" stroke="#2f5d34" stroke-width="5"/>
            <circle cx="58" cy="72" r="8" fill="#2f5d34"/>
            <circle cx="61" cy="69" r="3" fill="#ffffff"/>
            <circle cx="102" cy="72" r="8" fill="#2f5d34"/>
            <circle cx="105" cy="69" r="3" fill="#ffffff"/>
            <path d="M55,100 Q80,122 105,100" stroke="#2f5d34" stroke-width="4" fill="none" stroke-linecap="round"/>
            <ellipse cx="130" cy="18" rx="14" ry="8" fill="#4caf50" transform="rotate(-40 130 18)"/>
            <line x1="128" y1="22" x2="128" y2="10" stroke="#2f5d34" stroke-width="2"/>
        </svg>
    </div>

    <div class="login-deco-top">
        <div class="deco-circle yellow"></div>
        <div class="deco-circle pink"></div>
    </div>

    <div class="login-card">

        <div class="brand">
            <h1>Daftar Akun 📖</h1>
            <p>Buat akun untuk mulai membaca</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="form-group">
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Nama Lengkap"
                    required
                    autofocus
                />
            </div>

            <div class="form-group">
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Email"
                    required
                />
            </div>

            <div class="form-group">
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                />
            </div>

            <div class="form-group">
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Konfirmasi Password"
                    required
                />
            </div>

            <button type="submit" class="btn-login">Daftar Sekarang</button>
        </form>

        <p class="register-link">
            Sudah punya akun? <a href="{{ route('login') }}">Login yuk 😊</a>
        </p>

    </div>

   
    <div class="login-wave">
        <svg viewBox="0 0 1440 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path fill="#a5d6a7" fill-opacity="0.6" d="M0,60 C360,120 1080,0 1440,60 L1440,120 L0,120 Z"/>
            <path fill="#2f5d34" fill-opacity="0.8" d="M0,80 C400,20 1000,100 1440,70 L1440,120 L0,120 Z"/>
        </svg>
    </div>

</div>

</body>
</html>
