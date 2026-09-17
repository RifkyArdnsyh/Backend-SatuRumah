@extends('layouts.admin')

@section('title', 'Detail Pengajuan - ' . $pengajuan->nama_perumahan)

@section('content')
<!-- Breadcrumbs -->
<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-separator">/</span>
    @if($activeTab == 'ringkasan')
        <span class="breadcrumb-current">Detail Pengajuan</span>
    @else
        <a href="{{ route('pengajuan.show', ['id' => $pengajuan->id, 'tab' => 'ringkasan']) }}">Detail Pengajuan</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current">{{ ucfirst($activeTab) }}</span>
    @endif
</div>

<!-- Header Card Banner -->
<div class="detail-header-card">
    <div class="detail-header-top">
        <div class="detail-title-group">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 14px; font-weight: 700; color: var(--text-muted);">{{ $pengajuan->id }}</span>
                @if($pengajuan->status == 'Perlu perbaikan')
                    <span class="badge badge-yellow">Perlu perbaikan</span>
                @elseif($pengajuan->status == 'Terjadwal')
                    <span class="badge badge-blue">Terjadwal</span>
                @elseif($pengajuan->status == 'Final' || $pengajuan->status == 'Siap disetujui')
                    <span class="badge badge-green">{{ $pengajuan->status }}</span>
                @else
                    <span class="badge badge-gray">{{ $pengajuan->status }}</span>
                @endif
            </div>
            <h1>{{ $pengajuan->nama_perumahan }}</h1>
            <div class="detail-subtitle">
                {{ $pengajuan->nama_pengembang }} &middot; Tahap: {{ $pengajuan->tahap }}
            </div>
        </div>

        <div>
            @if($pengajuan->tahap == 'Persetujuan' || $pengajuan->status == 'Final')
                <form action="{{ route('monitoring.action', $pengajuan->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="persetujuan">
                    <button type="submit" class="btn btn-primary">
                        Lanjutkan Persetujuan
                    </button>
                </form>
            @else
                <form action="{{ route('pengajuan.auto_verifikasi', $pengajuan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" title="Jalankan pemeriksaan dan verifikasi kelengkapan berkas">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Verifikasi Berkas
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Sub Navigation Tabs (Clean 4 Tabs: Ringkasan, Dokumen, Monitoring, Riwayat) -->
    <div class="tabs-nav" style="margin-bottom: 0;">
        <a href="{{ route('pengajuan.show', ['id' => $pengajuan->id, 'tab' => 'ringkasan']) }}" class="tab-item {{ $activeTab == 'ringkasan' ? 'active' : '' }}">
            Ringkasan
        </a>
        <a href="{{ route('pengajuan.show', ['id' => $pengajuan->id, 'tab' => 'dokumen']) }}" class="tab-item {{ $activeTab == 'dokumen' ? 'active' : '' }}">
            Dokumen
        </a>
        <a href="{{ route('pengajuan.show', ['id' => $pengajuan->id, 'tab' => 'monitoring']) }}" class="tab-item {{ $activeTab == 'monitoring' ? 'active' : '' }}">
            Survey & Monitoring
        </a>
        <a href="{{ route('pengajuan.show', ['id' => $pengajuan->id, 'tab' => 'riwayat']) }}" class="tab-item {{ $activeTab == 'riwayat' ? 'active' : '' }}">
            Riwayat
        </a>
    </div>
</div>

<!-- Tab Content Included Dynamically -->
<div>
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
@endsection
