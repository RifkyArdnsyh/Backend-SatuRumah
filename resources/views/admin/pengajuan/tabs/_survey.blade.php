<div style="margin-bottom: 24px;">
    <h2 class="page-title" style="font-size: 22px;">Penjadwalan Survey</h2>
    <p class="page-subtitle">{{ $pengajuan->id }} &middot; {{ $pengajuan->nama_perumahan }}</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start;">
    <!-- Form Jadwalkan Survey (Left) -->
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header">
            <h2 class="card-title">Jadwalkan survey</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('survey.store', $pengajuan->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Tanggal survey</label>
                    <input type="text" name="tanggal_survey" class="form-input" placeholder="22 Mei 2025" value="{{ $pengajuan->activeSurvey->tanggal_survey ?? '22 Mei 2025' }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Petugas Perwaskim</label>
                    <select name="petugas_nama" class="form-select" required>
                        <option value="Rahmat Hidayat, S.T." selected>Rahmat Hidayat, S.T.</option>
                        <option value="Bambang Triyono, S.T.">Bambang Triyono, S.T.</option>
                        <option value="Dewi Lestari, S.T.">Dewi Lestari, S.T.</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">Instruksi survey</label>
                    <textarea name="instruksi" class="form-textarea" required placeholder="Tinjau kesesuaian site plan, drainase, dan akses jalan kawasan.">{{ $pengajuan->activeSurvey->instruksi ?? 'Tinjau kesesuaian site plan, drainase, dan akses jalan kawasan.' }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Jadwalkan Survey
                </button>
            </form>
        </div>
    </div>

    <!-- Right Side: Active Schedule & History -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Jadwal Aktif Card -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <h2 class="card-title">Jadwal aktif</h2>
            </div>
            <div class="card-body">
                @if($pengajuan->activeSurvey)
                    <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                        <div style="width: 48px; height: 48px; background-color: #dcfce7; color: #16a34a; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main);">
                                    Kamis, {{ $pengajuan->activeSurvey->tanggal_survey }} &middot; {{ $pengajuan->activeSurvey->jam_survey }}
                                </h3>
                                <span class="badge badge-blue">Terjadwal</span>
                            </div>
                            <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">
                                {{ $pengajuan->activeSurvey->petugas_nama }} &middot; {{ $pengajuan->activeSurvey->petugas_role }}
                            </div>
                            <p style="font-size: 13px; color: var(--text-main); line-height: 1.5;">
                                {{ $pengajuan->activeSurvey->instruksi }}
                            </p>
                        </div>
                    </div>
                    <div style="border-top: 1px solid var(--border-color); padding-top: 14px;">
                        <a href="#jadwalkan-form" onclick="document.getElementsByName('tanggal_survey')[0].focus(); return false;" class="table-link" style="font-size: 13px;">
                            Jadwalkan ulang
                        </a>
                    </div>
                @else
                    <p style="color: var(--text-muted); font-size: 13px;">Belum ada jadwal survey aktif.</p>
                @endif
            </div>
        </div>

        <!-- Riwayat Jadwal Card -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <h2 class="card-title">Riwayat jadwal</h2>
            </div>
            <div class="card-body">
                @forelse($pengajuan->survey->where('status', '!=', 'Terjadwal') as $oldSurvey)
                    <div style="display: flex; gap: 12px; font-size: 13px;">
                        <span style="color: var(--text-light); font-size: 16px;">&bull;</span>
                        <div>
                            <div style="font-weight: 700; color: var(--text-main);">
                                Selasa, {{ $oldSurvey->tanggal_survey }} &middot; {{ $oldSurvey->jam_survey }}
                            </div>
                            <div style="color: var(--text-muted); font-size: 12px; margin-top: 2px;">
                                Dijadwalkan oleh Verifikator Disperwaskim &middot; diganti pada 14 Mei 2025
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="display: flex; gap: 12px; font-size: 13px;">
                        <span style="color: var(--text-light); font-size: 16px;">&bull;</span>
                        <div>
                            <div style="font-weight: 700; color: var(--text-main);">
                                Selasa, 20 Mei 2025 &middot; 09.00 WIB
                            </div>
                            <div style="color: var(--text-muted); font-size: 12px; margin-top: 2px;">
                                Dijadwalkan oleh Verifikator Disperwaskim &middot; diganti pada 14 Mei 2025
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
