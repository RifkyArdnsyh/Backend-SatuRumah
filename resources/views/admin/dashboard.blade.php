@extends('layouts.admin')

@section('title', 'Dashboard - SATU RUMAH Portal Verifikator Disperwaskim')

@section('content')
<div style="margin-bottom: 24px; display: flex; align-items: flex-start; justify-content: space-between;">
    <div>
        <div class="breadcrumb" style="margin-bottom: 8px;">
            <span class="breadcrumb-current">Dashboard</span>
        </div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Ringkasan situasi pengajuan izin perumahan hari ini.</p>
    </div>
    <div style="font-size: 13px; color: var(--text-muted); text-align: right; padding-top: 24px;">
        Terakhir diperbarui: {{ date('d M Y, H.i') }} WIB
    </div>
</div>

{{-- 4 Stat Cards --}}
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

{{-- Tabel 5 Pengajuan Terbaru --}}
<div class="card" style="margin-top: 28px;">
    <div class="card-header">
        <div>
            <h2 class="card-title">5 Pengajuan Terbaru</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">
                Pengajuan yang baru masuk dan membutuhkan perhatian segera.
            </p>
        </div>
        <a href="{{ route('dashboard.export') }}" class="btn btn-outline">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Ekspor CSV
        </a>
    </div>

    <div class="card-body">
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
                            <td style="font-weight: 700; font-size: 12px; letter-spacing: 0.3px; color: var(--primary);">
                                {{ $item->id }}
                            </td>
                            <td>{{ $item->nama_pengembang }}</td>
                            <td style="font-weight: 600;">{{ $item->nama_perumahan }}</td>
                            <td>
                                {{-- Badge TAHAP — seragam dengan halaman /pengajuan --}}
                                <span style="font-size: 11px; padding: 3px 8px; background: #F0ECE4; border-radius: 6px; font-weight: 600; color: #5C4A32;">
                                    {{ $item->tahap }}
                                </span>
                            </td>
                            <td>
                                @if($item->status == 'Perlu perbaikan')
                                    <span class="badge badge-yellow">Perlu perbaikan</span>
                                @elseif($item->status == 'Terjadwal')
                                    <span class="badge badge-blue">Terjadwal</span>
                                @elseif($item->status == 'Menunggu verifikasi')
                                    <span class="badge badge-gray">Menunggu verifikasi</span>
                                @elseif(in_array($item->status, ['Final', 'Selesai', 'Siap disetujui']))
                                    <span class="badge badge-green">{{ $item->status }}</span>
                                @else
                                    <span class="badge badge-gray">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td style="color: var(--text-muted); font-size: 12px;">{{ $item->diajukan_pada }}</td>
                            <td style="text-align: right;">
                                <a href="{{ route('pengajuan.show', $item->id) }}" class="table-link">Buka</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 32px; color: var(--text-muted);">
                                Belum ada pengajuan yang masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Footer: info + Lihat Semua --}}
    <div class="pagination-wrapper" style="justify-content: space-between; align-items: center;">
        <div style="color: var(--text-muted); font-size: 13px;">
            Menampilkan <strong>{{ $pengajuans->count() }} pengajuan terbaru</strong>
            dari total <strong>{{ $pengajuans->total() }}</strong> yang masuk.
        </div>
        <a href="{{ route('pengajuan.index') }}"
           style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: var(--primary); text-decoration: none; padding: 8px 16px; border: 1.5px solid var(--primary); border-radius: 8px; transition: all 0.15s ease;"
           onmouseover="this.style.background='var(--primary)'; this.style.color='white';"
           onmouseout="this.style.background='transparent'; this.style.color='var(--primary)';">
            Lihat Semua Pengajuan
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</div>
@endsection
