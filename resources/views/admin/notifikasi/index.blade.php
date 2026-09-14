@extends('layouts.admin')

@section('title', 'Notifikasi - SATU RUMAH Portal Verifikator Disperwaskim')

@section('content')
<div style="margin-bottom: 24px; display: flex; align-items: flex-start; justify-content: space-between;">
    <div>
        <div class="breadcrumb" style="margin-bottom: 8px;">
            <span class="breadcrumb-current">Notifikasi</span>
        </div>
        <h1 class="page-title" style="font-size: 26px;">Notifikasi</h1>
        <p class="page-subtitle" style="font-size: 14px; margin-top: 4px;">{{ $unreadCount }} notifikasi belum dibaca.</p>
    </div>

    @if(isset($unreadCount) && $unreadCount > 0)
        <form action="{{ route('notifikasi.read_all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline" style="padding: 10px 20px; font-weight: 500; font-size: 14px; border-color: var(--border-color); color: var(--text-main);">
                Tandai semua dibaca
            </button>
        </form>
    @endif
</div>

<!-- Card Container for Notifications List -->
<div class="card" style="margin-bottom: 0;">
    <div class="card-body" style="padding: 0;">
        @forelse($notifikasis as $index => $n)
            <div style="padding: 24px 32px; border-bottom: {{ $loop->last ? 'none' : '1px solid var(--border-color)' }}; display: flex; align-items: center; justify-content: space-between; gap: 20px;">
                <div style="display: flex; align-items: flex-start; gap: 16px;">
                    <!-- Bullet Dot Indicator -->
                    <div style="margin-top: 6px;">
                        @if(!$n->is_read)
                            <span style="display: block; width: 10px; height: 10px; border-radius: 50%; background-color: #16a34a;"></span>
                        @else
                            <span style="display: block; width: 10px; height: 10px; border-radius: 50%; background-color: #cbd5e1;"></span>
                        @endif
                    </div>

                    <div>
                        <h2 style="font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 4px;">
                            {{ $n->judul }} &middot; {{ $n->pesan }}
                        </h2>
                        <div style="font-size: 13px; color: var(--text-muted);">
                            @if($index == 0)
                                Hari ini, 10.42 WIB
                            @else
                                12 Mei 2025, 14.20 WIB
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    @if(!$n->is_read)
                        <form action="{{ route('notifikasi.read', $n->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" style="background: none; border: none; font-size: 13px; font-weight: 600; color: #23416d; cursor: pointer; text-decoration: none;">
                                Tandai dibaca
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div style="padding: 40px; text-align: center; color: var(--text-muted); font-size: 14px;">
                Tidak ada notifikasi.
            </div>
        @endforelse
    </div>
</div>
@endsection
