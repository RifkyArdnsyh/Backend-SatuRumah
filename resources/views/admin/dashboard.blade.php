@extends('layouts.portal')

@section('title', 'Dashboard - SATU RUMAH Portal Verifikator Disperwaskim')

@section('content')
<div class="hero">
    <div>
        <div class="hero-crumbs">Portal Admin &nbsp;&gt;&nbsp; <b>Dashboard</b></div>
        <div class="hero-title">Dashboard Pengawasan</div>
        <div class="hero-sub">Ringkasan situasi pengajuan izin perumahan dan verifikasi teknis hari ini.</div>
    </div>
    <div class="hero-meta">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Terakhir diperbarui: {{ date('d M Y, H.i') }} WIB
    </div>
</div>

@if($stats['perlu_tindak_lanjut'] > 0)
<div class="alert alert-warning" style="align-items: center;">
    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    <div style="flex:1;">
        <div class="alert-title">{{ $stats['perlu_tindak_lanjut'] }} Berkas Membutuhkan Tindak Lanjut</div>
        <div>Terdapat pengajuan dengan revisi atau verifikasi yang belum direspon.</div>
    </div>
    <a href="{{ route('pengajuan.index') }}" class="btn btn-sm" style="background:#B45309;color:#fff;white-space:nowrap;">Tinjau Antrean</a>
</div>
@endif

<div class="kpi-grid">
    <div class="kpi is-primary">
        <div class="kpi-top">
            <span class="kpi-label">Pengajuan Baru</span>
            <span class="kpi-icon" style="background:rgba(255,255,255,.2);color:#fff;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </span>
        </div>
        <div class="kpi-value">{{ $stats['pengajuan_baru'] }}<span class="kpi-unit">Berkas</span></div>
        <div class="kpi-note">+4 sejak kemarin</div>
    </div>
    <div class="kpi">
        <div class="kpi-top">
            <span class="kpi-label">Verifikasi Teknis</span>
            <span class="kpi-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
        </div>
        <div class="kpi-value">{{ $stats['verifikasi_teknis'] }}<span class="kpi-unit">Proses</span></div>
        <div class="kpi-note">Butuh pemeriksaan</div>
    </div>
    <div class="kpi">
        <div class="kpi-top">
            <span class="kpi-label">Survey Terjadwal</span>
            <span class="kpi-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
        </div>
        <div class="kpi-value">{{ $stats['survey_terjadwal'] }}<span class="kpi-unit">Lokasi</span></div>
        <div class="kpi-note">Dalam 14 hari ke depan</div>
    </div>
    <div class="kpi">
        <div class="kpi-top">
            <span class="kpi-label">Perlu Tindak Lanjut</span>
            <span class="kpi-icon" style="background:var(--primary-tint);color:var(--primary);">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
        </div>
        <div class="kpi-value">{{ $stats['perlu_tindak_lanjut'] }}<span class="kpi-unit">Tertunda</span></div>
        <div class="kpi-note">Revisi belum dijawab</div>
    </div>
</div>

<div class="card">
    <div class="card-head bordered">
        <div>
            <div class="card-title">5 Pengajuan Terbaru</div>
            <div class="card-sub">Pengajuan yang baru masuk dan membutuhkan perhatian segera.</div>
        </div>
        <a href="{{ route('dashboard.export') }}" class="btn btn-outline">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Ekspor CSV
        </a>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID Pengajuan</th>
                    <th>Nama Pengembang</th>
                    <th>Nama Perumahan</th>
                    <th>Tahap</th>
                    <th>Status</th>
                    <th>Terakhir Diperbarui</th>
                    <th class="col-actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $item)
                    <tr>
                        <td><span class="id-chip">{{ $item->id }}</span></td>
                        <td>{{ $item->nama_pengembang }}</td>
                        <td class="col-strong">{{ $item->nama_perumahan }}</td>
                        <td><span class="chip">{{ $item->tahap }}</span></td>
                        <td>
                            @if($item->status == 'Perlu perbaikan')
                                <span class="badge badge-warning badge-dot">Perlu perbaikan</span>
                            @elseif($item->status == 'Terjadwal')
                                <span class="badge badge-info badge-dot">Terjadwal</span>
                            @elseif($item->status == 'Menunggu verifikasi')
                                <span class="badge badge-dot">Menunggu verifikasi</span>
                            @elseif(in_array($item->status, ['Final', 'Selesai', 'Siap disetujui']))
                                <span class="badge badge-success badge-dot">{{ $item->status }}</span>
                            @else
                                <span class="badge badge-dot">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td class="text-muted" style="font-size: 12px;">{{ $item->diajukan_pada }}</td>
                        <td class="col-actions"><a href="{{ route('pengajuan.show', $item->id) }}" class="table-link">Buka</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 32px;" class="text-muted">Belum ada pengajuan yang masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-foot">
        <div>Menampilkan <strong class="text-primary">{{ $pengajuans->count() }}</strong> pengajuan terbaru dari total <strong>{{ $pengajuans->total() }}</strong> yang masuk.</div>
        <a href="{{ route('pengajuan.index') }}" class="btn btn-soft btn-sm">
            Lihat Semua Antrean Pengajuan
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>
@endsection
