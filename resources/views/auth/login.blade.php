<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SATU RUMAH Portal Verifikator Disperwaskim</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
</head>

<body>
    <div class="login-shell">
        <div class="login-aside">
            <div class="login-brand">
                <div class="login-brand-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 10.5L12 3l9 7.5" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M5 9.5V20a1 1 0 001 1h3v-6h6v6h3a1 1 0 001-1V9.5" stroke="#ffffff" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div>
                    <div class="login-brand-title">SATU RUMAH</div>
                    <div class="login-brand-sub">Portal Verifikator Disperwaskim</div>
                </div>
            </div>

            <div>
                <h1 class="login-headline">Sistem Informasi Tata Ruang &amp; Verifikasi Site Plan Perumahan</h1>
                <p class="login-lead">Sentralisasi verifikasi berkas teknis, disposisi jadwal survey tim Perwaskim, dan
                    penerbitan rekomendasi Berita Acara secara digital, akuntabel, dan transparan.</p>
            </div>

            <div class="login-address">
                Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Tasikmalaya
                <small>Jl. Ir. H. Juanda No. 191, Tasikmalaya, Jawa Barat</small>
            </div>
        </div>

        <div class="login-panel">
            <div class="login-form">
                <h2>Selamat Datang di Konsol Verifikasi</h2>
                <p class="lead">Silakan masuk menggunakan akun kedinasan untuk mengakses antrean berkas dan penugasan
                    survey.</p>   

                @if($errors->any())
                    <div class="alert alert-danger" style="margin-bottom: 20px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label class="label">Email Dinas</label>
                        <div class="input-icon">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <input type="email" name="email" class="input" required autofocus
                                placeholder="verifikator@saturumah.go.id">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Kata Sandi</label>
                        <div class="input-icon">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6-2a6 6 0 10-10.472 4H4v3h2v3h3v-3h2.528A6 6 0 0018 9z" />
                            </svg>
                            <input type="password" name="password" id="password" class="input" required
                                placeholder="••••••••">
                            <button type="button" class="toggle-visibility" onclick="togglePw()">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="login-row">
                        <label class="check">
                            <input type="checkbox" name="remember" checked>
                            Ingat sesi login ini
                        </label>
                        <a href="#">Bantuan Akses?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        Masuk ke Portal Verifikator
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>
