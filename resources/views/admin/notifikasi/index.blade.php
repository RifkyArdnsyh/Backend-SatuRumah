@extends('layouts.portal')

@section('title', 'Notifikasi - SATU RUMAH Portal Verifikator Disperwaskim')

@section('content')
    <div class="hero">
        <div>
            <div class="hero-crumbs"><b>Notifikasi</b></div>
            <div class="hero-title">Pusat Notifikasi</div>
            <div class="hero-sub">{{ $unreadCount }} notifikasi belum dibaca.</div>
        </div>
        @if(isset($unreadCount) && $unreadCount > 0)
            <form action="{{ route('notifikasi.read_all') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-white">Tandai Semua Dibaca</button>
            </form>
        @endif
    </div>

    <div class="notif-list">
        @forelse($notifikasis as $index => $n)
            <div class="notif {{ $n->is_read ? 'read' : 'unread' }}">
                <div class="notif-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <div class="notif-body">
                    <div class="notif-meta">
                        @if(!$n->is_read)
                            <span class="badge badge-danger badge-dot">Belum Dibaca</span>
                        @endif
                    </div>
                    <div class="notif-text">{{ $n->judul }} &middot; {{ $n->pesan }}</div>
                    <div class="notif-time">
                        @if($index == 0)
                            Hari ini, 10.42 WIB
                        @else
                            12 Mei 2025, 14.20 WIB
                        @endif
                    </div>
                </div>
                <div class="notif-actions">
                    @if(!$n->is_read)
                        <form action="{{ route('notifikasi.read', $n->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="link-muted" style="font-weight: 700;">Tandai dibaca</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body" style="text-align: center;" class="text-muted">
                    Tidak ada notifikasi.
                </div>
            </div>
        @endforelse
    </div>
@endsection
