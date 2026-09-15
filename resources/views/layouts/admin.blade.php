<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SATU RUMAH - Portal Verifikator Disperwaskim')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Favicon Resmi Disperwaskim / SATU RUMAH -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo-icon" style="background: linear-gradient(135deg, #B92216 0%, #8C140A 100%); box-shadow: 0 2px 8px rgba(185, 34, 22, 0.35);">
                    <!-- Lambang Instansi: Bangunan Pemerintahan / Perumahan Rakyat & Tunas -->
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Pilar/Struktur Pemerintahan & Atap Rumah -->
                        <path d="M12 2L2 8H22L12 2Z" fill="#F8B803"/>
                        <path d="M4 9V18H6V9H4ZM9 9V18H11V9H9ZM13 9V18H15V9H13ZM18 9V18H20V9H18Z" fill="#ffffff" fill-opacity="0.95"/>
                        <path d="M2 19V21H22V19H2Z" fill="#F8B803"/>
                        <!-- Daun/Pucuk Hijau Melambangkan Kawasan Permukiman Asri -->
                        <circle cx="12" cy="13.5" r="2" fill="#B7C688"/>
                    </svg>
                </div>
                <div class="sidebar-logo-text">
                    <span class="sidebar-logo-title">SATU RUMAH</span>
                    <span class="sidebar-logo-subtitle">DISPERWASKIM KOTA TASIKMALAYA</span>
                </div>
            </div>

            <div class="sidebar-menu-label">MENU UTAMA</div>

            <nav class="sidebar-nav">
                <!-- Metafora 1: Dashboard -> Rumah / Beranda Induk (HQ Home) -->
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </span>
                    Dashboard
                </a>

                <!-- Metafora 2: Antrean Pengajuan -> Baki Berkas Masuk Bertumpuk (Inbox Tray / Layered Queue) -->
                <a href="{{ route('pengajuan.index') }}" class="nav-item {{ request()->routeIs('pengajuan.index') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </span>
                    Antrean Pengajuan
                </a>

                <!-- Metafora 3: Pengajuan -> Papan Klip Verifikasi / Audit Berkas (Verification Clipboard Checklist) -->
                <a href="{{ route('pengajuan.show', 'SR-2025-0148') }}" class="nav-item {{ request()->routeIs('pengajuan.show') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </span>
                    Pengajuan
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile-summary">
                    <div class="avatar-circle">
                        {{ auth()->user()->avatar_initials ?? 'VD' }}
                    </div>
                    <span class="user-profile-name">{{ auth()->user()->name ?? 'Verifikator Disperwaskim' }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <!-- Metafora 5: Logout -> Pintu Terbuka dengan Arah Keluar (Exit Door) -->
                    <button type="submit" class="logout-btn">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <!-- Top Header Bar -->
            <header class="top-header">
                <div class="top-header-title">
                    Dinas Perumahan Rakyat dan Kawasan Permukiman (Disperwaskim) Kota Tasikmalaya
                </div>
                <div class="top-header-right">
                    <!-- Metafora 4: Notifikasi -> Lonceng Berdering (Ringing Chime Bell) -->
                    <a href="{{ route('notifikasi.index') }}" class="notification-bell-btn" title="Notifikasi Verifikasi">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span class="bell-dot"></span>
                        @endif
                    </a>
                    <div class="header-user-badge">
                        <div class="avatar-circle">
                            {{ auth()->user()->avatar_initials ?? 'VD' }}
                        </div>
                        <span class="header-user-name">{{ auth()->user()->name ?? 'Verifikator Disperwaskim' }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Flash Messages -->
            @if(session('success'))
                <div style="margin: 20px 32px 0 32px; padding: 14px 20px; background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; color: #16a34a; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: space-between;">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 16px; cursor: pointer; color: #16a34a;">&times;</button>
                </div>
            @endif
            @if(session('warning'))
                <div style="margin: 20px 32px 0 32px; padding: 14px 20px; background-color: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; color: #b45309; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: space-between;">
                    <span>{{ session('warning') }}</span>
                    <button onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 16px; cursor: pointer; color: #b45309;">&times;</button>
                </div>
            @endif

            <!-- Main Body Content -->
            <main class="content-body">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>