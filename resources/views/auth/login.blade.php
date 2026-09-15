<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SATU RUMAH Portal Verifikator Disperwaskim</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        body {
            background-color: #231710;
            background-image: radial-gradient(circle at 50% 20%, rgba(185, 34, 22, 0.15), transparent 70%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(229, 224, 216, 0.6);
        }
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-logo {
            width: 56px;
            height: 56px;
            background: #B92216;
            color: #ffffff;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 22px;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(185, 34, 22, 0.3);
        }
        .login-title {
            font-size: 22px;
            font-weight: 800;
            color: #2B1D14;
        }
        .login-subtitle {
            font-size: 13px;
            color: #736458;
            margin-top: 4px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">SR</div>
            <h1 class="login-title">SATU RUMAH</h1>
            <p class="login-subtitle">PORTAL VERIFIKATOR DISPERWASKIM</p>
            <p style="font-size: 11px; color: #9e8e82; margin-top: 2px;">Pemerintah Kota Tasikmalaya</p>
        </div>

        @if($errors->any())
            <div style="margin-bottom: 20px; padding: 12px 16px; background-color: #fff8eb; border: 1px solid #fef08a; border-radius: 8px; color: #b45309; font-size: 13px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label" style="display: block; font-size: 13px; font-weight: 600; color: #2B1D14; margin-bottom: 6px;">Email Verifikator</label>
                <input type="email" name="email" class="input-control" value="admin@saturumah.go.id" required autofocus placeholder="verifikator@saturumah.go.id" style="width: 100%; padding: 10px 14px; border: 1px solid #e5e0d8; border-radius: 8px; font-size: 13px;">
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label" style="display: block; font-size: 13px; font-weight: 600; color: #2B1D14; margin-bottom: 6px;">Password</label>
                <input type="password" name="password" class="input-control" value="password" required placeholder="••••••••" style="width: 100%; padding: 10px 14px; border: 1px solid #e5e0d8; border-radius: 8px; font-size: 13px;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px; font-weight: 700; background-color: #B92216; color: #ffffff; border: none; border-radius: 8px; cursor: pointer;">
                Masuk ke Portal Verifikator
            </button>
        </form>
    </div>
</body>
</html>
