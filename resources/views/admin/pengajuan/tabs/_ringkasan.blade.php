<div class="grid-main-side">
    <div class="card" style="box-shadow:none;">
        <div class="card-head bordered">
            <div class="card-title" style="font-size:16px;">Informasi Perumahan</div>
        </div>
        <div class="card-body" style="padding: 8px 24px 20px;">
            <dl class="kv">
                <div class="kv-row">
                    <dt>Nama perumahan</dt>
                    <dd>{{ $pengajuan->nama_perumahan }}</dd>
                </div>
                <div class="kv-row">
                    <dt>Lokasi</dt>
                    <dd>{{ $pengajuan->lokasi }}</dd>
                </div>
                <div class="kv-row">
                    <dt>Jumlah unit</dt>
                    <dd>{{ $pengajuan->jumlah_unit }}</dd>
                </div>
                <div class="kv-row">
                    <dt>Luas kawasan</dt>
                    <dd>{{ $pengajuan->luas_kawasan }}</dd>
                </div>
                <div class="kv-row">
                    <dt>Penanggung jawab</dt>
                    <dd>{{ $pengajuan->penanggung_jawab }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="card" style="box-shadow:none;">
        <div class="card-head bordered">
            <div class="card-title" style="font-size:16px;">Status Pengajuan</div>
        </div>
        <div class="card-body">
            <div style="margin-bottom: 16px;">
                @if($pengajuan->status == 'Perlu perbaikan')
                    <span class="badge badge-warning badge-dot">Perlu perbaikan</span>
                @elseif($pengajuan->status == 'Terjadwal')
                    <span class="badge badge-info badge-dot">Terjadwal</span>
                @elseif(in_array($pengajuan->status, ['Final', 'Siap disetujui']))
                    <span class="badge badge-success badge-dot">{{ $pengajuan->status }}</span>
                @else
                    <span class="badge badge-dot">{{ $pengajuan->status }}</span>
                @endif
            </div>

            <p style="font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
                {{ $pengajuan->catatan_status ?? 'Belum ada catatan tambahan untuk pengajuan ini.' }}
            </p>

            <div class="text-muted" style="font-size: 12px; border-top: 1px solid var(--border); padding-top: 14px;">
                Diajukan pada {{ $pengajuan->diajukan_pada }}
            </div>
        </div>
    </div>
</div>
