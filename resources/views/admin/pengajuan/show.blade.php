@extends('layouts.portal')

@section('title', 'Detail Pengajuan - ' . $pengajuan->nama_perumahan)

@section('content')
    <div class="hero">
        <div>
            <div class="hero-crumbs">
                <a href="{{ route('dashboard') }}" style="color:#fff;opacity:.85;">Dashboard</a> &nbsp;&gt;&nbsp;
                @if($activeTab == 'ringkasan')
                    <b>Detail Pengajuan</b>
                @else
                    <a href="{{ route('pengajuan.show', ['id' => $pengajuan->id, 'tab' => 'ringkasan']) }}"
                        style="color:#fff;opacity:.85;">Detail Pengajuan</a> &nbsp;&gt;&nbsp;
                    <b>{{ $activeTab == 'monitoring' ? 'Survey & Monitoring' : ucfirst($activeTab) }}</b>
                @endif
            </div>
            <div class="hero-tags">
                <span class="hero-tag">{{ $pengajuan->id }}</span>
                @if($pengajuan->status == 'Perlu perbaikan')
                    <span class="hero-tag warning">⚠ Perlu Perbaikan</span>
                @endif
            </div>
            <div class="hero-title">{{ $pengajuan->nama_perumahan }}</div>
            <div class="hero-sub">{{ $pengajuan->nama_pengembang }} &middot; Tahap: {{ $pengajuan->tahap }}</div>
        </div>

        <div>
            @if($pengajuan->tahap == 'Persetujuan' || $pengajuan->status == 'Final')
                <form action="{{ route('monitoring.action', $pengajuan->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="persetujuan">
                    <button type="submit" class="btn btn-white">Lanjutkan Persetujuan</button>
                </form>
            @else
                <form action="{{ route('pengajuan.auto_verifikasi', $pengajuan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-white" title="Jalankan pemeriksaan dan verifikasi kelengkapan berkas">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Verifikasi Berkas
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="tabs">
            <a href="{{ route('pengajuan.show', ['id' => $pengajuan->id, 'tab' => 'ringkasan']) }}"
                class="tab {{ $activeTab == 'ringkasan' ? 'active' : '' }}">Ringkasan</a>
            <a href="{{ route('pengajuan.show', ['id' => $pengajuan->id, 'tab' => 'dokumen']) }}"
                class="tab {{ $activeTab == 'dokumen' ? 'active' : '' }}">
                Dokumen
                @if($pengajuan->dokumen->count()) <span class="tab-count">{{ $pengajuan->dokumen->count() }}</span> @endif
            </a>
            <a href="{{ route('pengajuan.show', ['id' => $pengajuan->id, 'tab' => 'monitoring']) }}"
                class="tab {{ $activeTab == 'monitoring' ? 'active' : '' }}">Survey &amp; Monitoring</a>
            <a href="{{ route('pengajuan.show', ['id' => $pengajuan->id, 'tab' => 'riwayat']) }}"
                class="tab {{ $activeTab == 'riwayat' ? 'active' : '' }}">Riwayat</a>
        </div>

        <div class="card-body">
            @if($activeTab == 'ringkasan')
                @include('admin.pengajuan.tabs._ringkasan')
            @elseif($activeTab == 'dokumen')
                @include('admin.pengajuan.tabs._dokumen')
            @elseif($activeTab == 'monitoring')
                @include('admin.pengajuan.tabs._monitoring')
            @elseif($activeTab == 'riwayat')
                @include('admin.pengajuan.tabs._riwayat')
            @endif
        </div>
    </div>
@endsection
