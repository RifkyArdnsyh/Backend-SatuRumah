<div class="card" style="box-shadow: none;">
    <div class="card-head bordered">
        <h2 class="card-title" style="font-size: 16px;">Riwayat aktivitas</h2>
    </div>
    <div class="card-body">
        <div class="timeline">
            @forelse($pengajuan->riwayat as $r)
                <div class="tl-item">
                    <span class="tl-dot">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    <div class="tl-card">
                        <div class="tl-head">{{ $r->tanggal }}</div>
                        <div class="tl-title" style="font-size: 14px;">{{ $r->deskripsi ?? $r->judul }}</div>
                    </div>
                </div>
            @empty
                <div class="tl-item">
                    <span class="tl-dot">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    <div class="tl-card">
                        <div class="tl-head">14 Mei 2025 &middot; 10.42</div>
                        <div class="tl-title" style="font-size: 14px;">Verifikator Disperwaskim mengirim permintaan
                            perbaikan untuk 3 dokumen.</div>
                    </div>
                </div>

                <div class="tl-item">
                    <span class="tl-dot">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    <div class="tl-card">
                        <div class="tl-head">13 Mei 2025 &middot; 15.10</div>
                        <div class="tl-title" style="font-size: 14px;">Pengembang mengunggah versi 2 KTP Direktur Utama.
                            Versi 1 tetap tersimpan.</div>
                    </div>
                </div>

                <div class="tl-item">
                    <span class="tl-dot">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    <div class="tl-card">
                        <div class="tl-head">08 Mei 2025 &middot; 09.21</div>
                        <div class="tl-title" style="font-size: 14px;">Pengajuan baru dibuat oleh PT Citra Tasik Mandiri.
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
