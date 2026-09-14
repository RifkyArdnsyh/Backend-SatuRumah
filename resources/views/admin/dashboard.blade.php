@extends('layouts.admin')

@section('title', 'Dashboard - SATU RUMAH Portal Verifikator Disperwaskim')

@section('content')
<div style="margin-bottom: 24px; display: flex; align-items: flex-start; justify-content: space-between;">
    <div>
        <div class="breadcrumb" style="margin-bottom: 8px;">
            <span class="breadcrumb-current">Dashboard</span>
        </div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Ringkasan pengajuan izin perumahan yang perlu ditangani.</p>
    </div>
    <div style="font-size: 13px; color: var(--text-muted); text-align: right; padding-top: 24px;">
        Terakhir diperbarui: {{ date('d M Y, H.i') }} WIB
    </div>
</div>

<!-- 4 Stat Cards Grid -->
<div class="stat-cards-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $stats['pengajuan_baru'] }}</div>
        <div class="stat-label">Pengajuan baru</div>
        <div class="stat-subtext">+4 sejak kemarin</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['verifikasi_teknis'] }}</div>
        <div class="stat-label">Verifikasi teknis</div>
        <div class="stat-subtext">Butuh pemeriksaan</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['survey_terjadwal'] }}</div>
        <div class="stat-label">Survey terjadwal</div>
        <div class="stat-subtext">Dalam 14 hari ke depan</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['perlu_tindak_lanjut'] }}</div>
        <div class="stat-label">Perlu tindak lanjut</div>
        <div class="stat-subtext">Revisi belum dijawab</div>
    </div>
</div>

<!-- Antrean Pengajuan Card -->
<div class="card" id="antrean-pengajuan">
    <div class="card-header">
        <div>
            <h2 class="card-title">Antrean Pengajuan</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Menampilkan pengajuan aktif yang tersedia untuk Verifikator Disperwaskim.</p>
        </div>
        <a href="{{ route('dashboard.export') }}" class="btn btn-outline">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Ekspor
        </a>
    </div>

    <div class="card-body">
        <!-- Controls Filter Bar -->
        <form method="GET" action="{{ route('dashboard') }}" class="controls-bar">
            <div class="search-input-wrapper">
                <span class="search-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" class="input-control" placeholder="Cari ID, pengembang, atau perumahan" value="{{ request('search') }}" onchange="this.form.submit()">
            </div>

            <div style="display: flex; gap: 12px;">
                <select name="tahap" class="select-control" onchange="this.form.submit()">
                    <option value="semua" {{ request('tahap') == 'semua' ? 'selected' : '' }}>Semua tahap</option>
                    <option value="Dokumen" {{ request('tahap') == 'Dokumen' ? 'selected' : '' }}>Dokumen</option>
                    <option value="Verifikasi teknis" {{ request('tahap') == 'Verifikasi teknis' ? 'selected' : '' }}>Verifikasi teknis</option>
                    <option value="Survey" {{ request('tahap') == 'Survey' ? 'selected' : '' }}>Survey</option>
                    <option value="Monitoring" {{ request('tahap') == 'Monitoring' ? 'selected' : '' }}>Monitoring</option>
                    <option value="Persetujuan" {{ request('tahap') == 'Persetujuan' ? 'selected' : '' }}>Persetujuan</option>
                </select>

                <select name="status" class="select-control" onchange="this.form.submit()">
                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua status</option>
                    <option value="Menunggu verifikasi" {{ request('status') == 'Menunggu verifikasi' ? 'selected' : '' }}>Menunggu verifikasi</option>
                    <option value="Perlu perbaikan" {{ request('status') == 'Perlu perbaikan' ? 'selected' : '' }}>Perlu perbaikan</option>
                    <option value="Terjadwal" {{ request('status') == 'Terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Final" {{ request('status') == 'Final' ? 'selected' : '' }}>Final</option>
                    <option value="Siap disetujui" {{ request('status') == 'Siap disetujui' ? 'selected' : '' }}>Siap disetujui</option>
                </select>
            </div>
        </form>

        <!-- Data Table -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID PENGAJUAN</th>
                        <th>NAMA PENGEMBANG</th>
                        <th>NAMA PERUMAHAN</th>
                        <th>TAHAP</th>
                        <th>STATUS</th>
                        <th>TERAKHIR DIPERBARUI</th>
                        <th style="text-align: right;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuans as $item)
                        <tr>
                            <td style="font-weight: 700;">{{ $item->id }}</td>
                            <td>{{ $item->nama_pengembang }}</td>
                            <td style="font-weight: 600;">{{ $item->nama_perumahan }}</td>
                            <td>{{ $item->tahap }}</td>
                            <td>
                                @if($item->status == 'Perlu perbaikan')
                                    <span class="badge badge-yellow">Perlu perbaikan</span>
                                @elseif($item->status == 'Terjadwal')
                                    <span class="badge badge-blue">Terjadwal</span>
                                @elseif($item->status == 'Menunggu verifikasi')
                                    <span class="badge badge-gray">Menunggu verifikasi</span>
                                @elseif($item->status == 'Final')
                                    <span class="badge badge-green">Final</span>
                                @elseif($item->status == 'Siap disetujui')
                                    <span class="badge badge-green">Siap disetujui</span>
                                @else
                                    <span class="badge badge-gray">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td style="color: var(--text-muted);">{{ $item->diajukan_pada }}</td>
                            <td style="text-align: right;">
                                <a href="{{ route('pengajuan.show', $item->id) }}" class="table-link">Buka</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 32px; color: var(--text-muted);">
                                Tidak ada pengajuan yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination Footer -->
    <div class="pagination-wrapper">
        <div>
            Menampilkan {{ $pengajuans->firstItem() ?? 0 }}-{{ $pengajuans->lastItem() ?? 0 }} dari {{ $pengajuans->total() }} pengajuan
        </div>
        <div class="pagination-pages">
            @if($pengajuans->onFirstPage())
                <span class="page-btn btn-disabled">&lt;</span>
            @else
                <a href="{{ $pengajuans->previousPageUrl() }}" class="page-btn">&lt;</a>
            @endif

            @foreach($pengajuans->getUrlRange(1, min(3, $pengajuans->lastPage())) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $page == $pengajuans->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach

            @if($pengajuans->hasMorePages())
                <a href="{{ $pengajuans->nextPageUrl() }}" class="page-btn">&gt;</a>
            @else
                <span class="page-btn btn-disabled">&gt;</span>
            @endif
        </div>
    </div>
</div>
@endsection
