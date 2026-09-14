<div class="card" style="margin-bottom: 0;">
    <div class="card-header">
        <h2 class="card-title" style="font-size: 16px;">Riwayat aktivitas</h2>
    </div>
    <div class="card-body" style="padding: 28px 32px;">
        <div style="display: flex; flex-direction: column; gap: 24px;">
            @forelse($pengajuan->riwayat as $r)
                <div style="display: flex; align-items: flex-start; gap: 16px;">
                    <!-- Bullet dot green -->
                    <div style="margin-top: 4px;">
                        <span style="display: block; width: 10px; height: 10px; border-radius: 50%; background-color: #5b8a82;"></span>
                    </div>

                    <div>
                        <div style="font-size: 13px; color: var(--text-muted); font-weight: 500; margin-bottom: 4px;">
                            {{ $r->tanggal }}
                        </div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text-main); line-height: 1.5;">
                            {{ $r->deskripsi ?? $r->judul }}
                        </div>
                    </div>
                </div>
            @empty
                <div style="display: flex; align-items: flex-start; gap: 16px;">
                    <div style="margin-top: 4px;">
                        <span style="display: block; width: 10px; height: 10px; border-radius: 50%; background-color: #5b8a82;"></span>
                    </div>
                    <div>
                        <div style="font-size: 13px; color: var(--text-muted); font-weight: 500; margin-bottom: 4px;">
                            14 Mei 2025 &middot; 10.42
                        </div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text-main); line-height: 1.5;">
                            Verifikator Disperwaskim mengirim permintaan perbaikan untuk 3 dokumen.
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: flex-start; gap: 16px;">
                    <div style="margin-top: 4px;">
                        <span style="display: block; width: 10px; height: 10px; border-radius: 50%; background-color: #5b8a82;"></span>
                    </div>
                    <div>
                        <div style="font-size: 13px; color: var(--text-muted); font-weight: 500; margin-bottom: 4px;">
                            13 Mei 2025 &middot; 15.10
                        </div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text-main); line-height: 1.5;">
                            Pengembang mengunggah versi 2 KTP Direktur Utama. Versi 1 tetap tersimpan.
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: flex-start; gap: 16px;">
                    <div style="margin-top: 4px;">
                        <span style="display: block; width: 10px; height: 10px; border-radius: 50%; background-color: #5b8a82;"></span>
                    </div>
                    <div>
                        <div style="font-size: 13px; color: var(--text-muted); font-weight: 500; margin-bottom: 4px;">
                            08 Mei 2025 &middot; 09.21
                        </div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text-main); line-height: 1.5;">
                            Pengajuan baru dibuat oleh PT Citra Tasik Mandiri.
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
