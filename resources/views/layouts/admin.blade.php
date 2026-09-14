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
    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo-icon">SR</div>
                <div class="sidebar-logo-text">
                    <span class="sidebar-logo-title">SATU RUMAH</span>
                    <span class="sidebar-logo-subtitle">PORTAL VERIFIKATOR DISPERWASKIM</span>
                </div>
            </div>

            <div class="sidebar-menu-label">MENU UTAMA</div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </span>
                    Dashboard
                </a>

                <a href="{{ route('dashboard') }}#antrean-pengajuan" class="nav-item">
                    <span class="icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                    Antrean Pengajuan
                </a>

                <a href="{{ route('pengajuan.show', 'SR-2025-0148') }}" class="nav-item {{ request()->routeIs('pengajuan.show') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </span>
                    Pengajuan
                </a>

                <a href="{{ route('notifikasi.index') }}" class="nav-item {{ request()->routeIs('notifikasi.index') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </span>
                    Notifikasi
                    @if(isset($unreadCount) && $unreadCount > 0)
                        <span class="nav-badge">{{ $unreadCount }}</span>
                    @endif
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
                    <button type="submit" class="logout-btn">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
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
                    <a href="{{ route('notifikasi.index') }}" class="notification-bell-btn">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
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
