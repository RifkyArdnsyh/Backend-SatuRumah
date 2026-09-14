<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SATU RUMAH Portal Verifikator Disperwaskim</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        body {
            background-color: #11223f;
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
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
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
        }
        .login-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
        }
        .login-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">SR</div>
            <h1 class="login-title">SATU RUMAH</h1>
            <p class="login-subtitle">PORTAL VERIFIKATOR DISPERWASKIM</p>
            <p style="font-size: 11px; color: #94a3b8; margin-top: 2px;">Pemerintah Kota Tasikmalaya</p>
        </div>

        @if($errors->any())
            <div style="margin-bottom: 20px; padding: 12px 16px; background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 8px; color: #dc2626; font-size: 13px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Admin</label>
                <input type="email" name="email" class="form-input" value="admin@saturumah.go.id" required autofocus placeholder="admin@saturumah.go.id">
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" value="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 14px;">
                Masuk ke Panel Admin
            </button>
        </form>
    </div>
</body>
</html>
