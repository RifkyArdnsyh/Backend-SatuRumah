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
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">Tanggal survey</label>
                        <input type="date" name="tanggal_survey" class="form-input" value="{{ $pengajuan->activeSurvey ? (date('Y-m-d', strtotime($pengajuan->activeSurvey->tanggal_survey)) != '1970-01-01' ? date('Y-m-d', strtotime($pengajuan->activeSurvey->tanggal_survey)) : date('Y-m-d')) : date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jam pelaksanaan</label>
                        <input type="text" name="jam_survey" class="form-input" placeholder="09.00 WIB" value="{{ $pengajuan->activeSurvey->jam_survey ?? '09.00 WIB' }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Petugas Perwaskim</label>
                    <select name="petugas_nama" class="form-select" required>
                        <option value="Rahmat Hidayat, S.T." {{ ($pengajuan->activeSurvey->petugas_nama ?? '') == 'Rahmat Hidayat, S.T.' ? 'selected' : '' }}>Rahmat Hidayat, S.T.</option>
                        <option value="Bambang Triyono, S.T." {{ ($pengajuan->activeSurvey->petugas_nama ?? '') == 'Bambang Triyono, S.T.' ? 'selected' : '' }}>Bambang Triyono, S.T.</option>
                        <option value="Dewi Lestari, S.T." {{ ($pengajuan->activeSurvey->petugas_nama ?? '') == 'Dewi Lestari, S.T.' ? 'selected' : '' }}>Dewi Lestari, S.T.</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">Instruksi survey</label>
                    <textarea name="instruksi" class="form-textarea" required placeholder="Tinjau kesesuaian site plan, drainase, dan akses jalan kawasan.">{{ $pengajuan->activeSurvey->instruksi ?? 'Tinjau kesesuaian site plan, drainase, akses jalan, dan fasilitas umum di lokasi perumahan.' }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Simpan & Konfirmasi Jadwal Survey
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
                    @php
                        $tglRaw = $pengajuan->activeSurvey->tanggal_survey;
                        try {
                            $carbonDate = \Carbon\Carbon::parse($tglRaw)->locale('id');
                            $formattedDayDate = $carbonDate->translatedFormat('l, d F Y');
                        } catch (\Exception $e) {
                            $formattedDayDate = $tglRaw;
                        }
                    @endphp
                    <div style="display: flex; gap: 16px; margin-bottom: 20px;">
                        <div style="width: 48px; height: 48px; background-color: #dcfce7; color: #16a34a; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                                <h3 style="font-size: 15px; font-weight: 700; color: var(--text-main);">
                                    {{ $formattedDayDate }} &middot; {{ $pengajuan->activeSurvey->jam_survey }}
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
                @php
                    $pastSurveys = $pengajuan->survey->where('status', '!=', 'Terjadwal');
                @endphp
                @forelse($pastSurveys as $oldSurvey)
                    @php
                        try {
                            $oldFormatted = \Carbon\Carbon::parse($oldSurvey->tanggal_survey)->locale('id')->translatedFormat('l, d F Y');
                        } catch (\Exception $e) {
                            $oldFormatted = $oldSurvey->tanggal_survey;
                        }
                    @endphp
                    <div style="display: flex; gap: 12px; font-size: 13px; margin-bottom: {{ $loop->last ? '0' : '14px' }};">
                        <span style="color: var(--text-light); font-size: 16px;">&bull;</span>
                        <div>
                            <div style="font-weight: 700; color: var(--text-main);">
                                {{ $oldFormatted }} &middot; {{ $oldSurvey->jam_survey }}
                            </div>
                            <div style="color: var(--text-muted); font-size: 12px; margin-top: 2px;">
                                Dijadwalkan untuk {{ $oldSurvey->petugas_nama }} &middot; diganti pada {{ $oldSurvey->updated_at ? $oldSurvey->updated_at->locale('id')->translatedFormat('d F Y') : '14 Mei 2025' }}
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
