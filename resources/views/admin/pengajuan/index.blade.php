@extends('layouts.portal')

@section('title', 'Antrean Pengajuan - SATU RUMAH Portal Verifikator Disperwaskim')

@section('content')
    <div class="hero">
        <div>
            <div class="hero-crumbs"><a href="{{ route('dashboard') }}" style="color:#fff;opacity:.85;">Dashboard</a>
                &nbsp;&gt;&nbsp; <b>Antrean Pengajuan</b></div>
            <div class="hero-title">Antrean Pengajuan Izin Perumahan</div>
            <div class="hero-sub">Seluruh berkas pengajuan izin perumahan yang masuk ke Disperwaskim Kota Tasikmalaya untuk
                verifikasi teknis, dokumen, dan lapangan.</div>
        </div>
        <div class="hero-meta">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Terakhir diperbarui: {{ date('d M Y, H.i') }} WIB
        </div>
    </div>

    <div class="kpi-grid">
        <div class="kpi">
            <div class="kpi-top">
                <span class="kpi-label">Total Pengajuan</span>
                <span class="kpi-icon"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                    </svg></span>
            </div>
            <div class="kpi-value">{{ $totalSemua }}</div>
            <div class="kpi-note">Seluruh periode tercatat</div>
        </div>
        <div class="kpi">
            <div class="kpi-top">
                <span class="kpi-label">Pengajuan Aktif</span>
                <span class="kpi-icon"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg></span>
            </div>
            <div class="kpi-value">{{ $totalAktif }}</div>
            <div class="kpi-note">Sedang berjalan dalam siklus</div>
        </div>
        <div class="kpi" style="background:var(--primary-tint);border-color:var(--primary-border);">
            <div class="kpi-top">
                <span class="kpi-label" style="color:var(--primary-dark);">Menunggu Verifikasi</span>
                <span class="kpi-icon" style="background:var(--primary);color:#fff;"><svg width="18" height="18" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg></span>
            </div>
            <div class="kpi-value" style="color:var(--primary-dark);">{{ $totalMenunggu }}</div>
            <div class="kpi-note" style="color:var(--primary-dark);">Perlu tindakan segera</div>
        </div>
        <div class="kpi">
            <div class="kpi-top">
                <span class="kpi-label">Perlu Perbaikan</span>
                <span class="kpi-icon"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg></span>
            </div>
            <div class="kpi-value">{{ $totalPerlu }}</div>
            <div class="kpi-note">Revisi belum direspon pemohon</div>
        </div>
    </div>

    <div class="card">
        <div class="card-head bordered">
            <div>
                <div class="card-title">Daftar Semua Pengajuan</div>
                <div class="card-sub">Menampilkan {{ $pengajuans->firstItem() ?? 0 }}–{{ $pengajuans->lastItem() ?? 0 }}
                    dari total {{ $pengajuans->total() }} berkas masuk</div>
            </div>
            <a href="{{ route('dashboard.export') }}" class="btn btn-outline">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Ekspor CSV
            </a>
        </div>

        <div style="padding: 20px 24px 0;">
            <form method="GET" action="{{ route('pengajuan.index') }}"
                style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                <div class="search" style="flex:1;min-width:260px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" class="input"
                        placeholder="Cari ID, nama pengembang, atau nama perumahan" value="{{ request('search') }}"
                        onchange="this.form.submit()">
                </div>
                <select name="tahap" class="select" onchange="this.form.submit()">
                    <option value="semua" {{ !request('tahap') || request('tahap') == 'semua' ? 'selected' : '' }}>Semua tahap
                    </option>
                    <option value="Dokumen" {{ request('tahap') == 'Dokumen' ? 'selected' : '' }}>Dokumen</option>
                    <option value="Verifikasi teknis" {{ request('tahap') == 'Verifikasi teknis' ? 'selected' : '' }}>
                        Verifikasi teknis</option>
                    <option value="Survey" {{ request('tahap') == 'Survey' ? 'selected' : '' }}>Survey</option>
                    <option value="Monitoring" {{ request('tahap') == 'Monitoring' ? 'selected' : '' }}>Monitoring</option>
                    <option value="Persetujuan" {{ request('tahap') == 'Persetujuan' ? 'selected' : '' }}>Persetujuan</option>
                </select>
                <select name="status" class="select" onchange="this.form.submit()">
                    <option value="semua" {{ !request('status') || request('status') == 'semua' ? 'selected' : '' }}>Semua
                        status</option>
                    <option value="Menunggu verifikasi" {{ request('status') == 'Menunggu verifikasi' ? 'selected' : '' }}>
                        Menunggu verifikasi</option>
                    <option value="Perlu perbaikan" {{ request('status') == 'Perlu perbaikan' ? 'selected' : '' }}>Perlu
                        perbaikan</option>
                    <option value="Terjadwal" {{ request('status') == 'Terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                    <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="Final" {{ request('status') == 'Final' ? 'selected' : '' }}>Final</option>
                    <option value="Siap disetujui" {{ request('status') == 'Siap disetujui' ? 'selected' : '' }}>Siap
                        disetujui</option>
                </select>
                @if(request('search') || (request('tahap') && request('tahap') !== 'semua') || (request('status') && request('status') !== 'semua'))
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-outline btn-sm">&times; Reset</a>
                @endif
            </form>
        </div>

        <div class="table-wrap" style="margin-top: 16px;">
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
                        <tr class="{{ $item->status == 'Perlu perbaikan' ? 'row-flag' : '' }}">
                            <td><span
                                    class="id-chip {{ $item->status == 'Perlu perbaikan' ? 'is-hot' : '' }}">{{ $item->id }}</span>
                            </td>
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
                            <td class="col-actions"><a href="{{ route('pengajuan.show', $item->id) }}"
                                    class="table-link">Buka</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 48px;" class="text-muted">
                                <div style="font-weight: 700; margin-bottom: 4px;">Tidak ada pengajuan ditemukan</div>
                                <div style="font-size: 13px;">Coba ubah atau hapus filter pencarian.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-foot">
            <div>Menampilkan {{ $pengajuans->firstItem() ?? 0 }}–{{ $pengajuans->lastItem() ?? 0 }} dari
                {{ $pengajuans->total() }} pengajuan</div>
            <div class="pagination">
                @if($pengajuans->onFirstPage())
                    <span class="page disabled">&lt;</span>
                @else
                    <a href="{{ $pengajuans->previousPageUrl() }}" class="page">&lt;</a>
                @endif

                @foreach($pengajuans->getUrlRange(1, min(5, $pengajuans->lastPage())) as $page => $url)
                    <a href="{{ $url }}" class="page {{ $page == $pengajuans->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach

                @if($pengajuans->lastPage() > 5)
                    @if($pengajuans->currentPage() < $pengajuans->lastPage() - 2)
                        <span class="page disabled">…</span>
                    @endif
                    @if($pengajuans->currentPage() < $pengajuans->lastPage() - 1)
                        <a href="{{ $pengajuans->url($pengajuans->lastPage()) }}" class="page">{{ $pengajuans->lastPage() }}</a>
                    @endif
                @endif

                @if($pengajuans->hasMorePages())
                    <a href="{{ $pengajuans->nextPageUrl() }}" class="page">&gt;</a>
                @else
                    <span class="page disabled">&gt;</span>
                @endif
            </div>
        </div>
    </div>
@endsection
