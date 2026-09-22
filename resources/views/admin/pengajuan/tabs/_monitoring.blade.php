@php
    $monitoring = $pengajuan->monitoring;
    $isFinal = ($monitoring && $monitoring->status_monitoring === 'Final');
@endphp

<!-- SECTION 1: PENJADWALAN & SURVEY LAPANGAN -->
<div style="margin-bottom: 32px;">
    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h2 class="page-title" style="font-size: 20px;">1. Penjadwalan & Survey Lapangan</h2>
            <p class="page-subtitle">Pemeriksaan fisik lokasi perumahan oleh tim Petugas Perwaskim Disperwaskim.</p>
        </div>
        <div>
            @if($pengajuan->activeSurvey)
                <span class="badge badge-blue" style="font-size: 13px; padding: 6px 12px;">Survey Terjadwal</span>
            @else
                <span class="badge badge-gray" style="font-size: 13px; padding: 6px 12px;">Belum Terjadwal</span>
            @endif
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start;">
        <!-- Form Jadwalkan Survey (Left) -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <h3 class="card-title">Jadwalkan survey</h3>
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
                    <h3 class="card-title">Jadwal aktif</h3>
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
                                    <h4 style="font-size: 15px; font-weight: 700; color: var(--text-main);">
                                        {{ $formattedDayDate }} &middot; {{ $pengajuan->activeSurvey->jam_survey }}
                                    </h4>
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
                            <a href="#" onclick="document.getElementsByName('tanggal_survey')[0].focus(); return false;" class="table-link" style="font-size: 13px;">
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
                    <h3 class="card-title">Riwayat jadwal survey</h3>
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
</div>

<hr style="border: 0; border-top: 1px solid var(--border-color); margin: 32px 0;">

<!-- SECTION 2: HASIL MONITORING LAPANGAN -->
<div>
    <div style="margin-bottom: 20px;">
        <h2 class="page-title" style="font-size: 20px;">2. Hasil Monitoring & Evaluasi</h2>
        <p class="page-subtitle">{{ $pengajuan->id }} &middot; {{ $pengajuan->nama_perumahan }}</p>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
        <!-- Left Column: Ringkasan, Temuan, Foto Bukti -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Ringkasan Monitoring Card -->
            <div class="card" style="margin-bottom: 0;">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan monitoring</h3>
                </div>
                <div class="card-body" style="padding: 0;">
                    <table class="table" style="font-size: 13px;">
                        <tbody>
                            <tr>
                                <td style="width: 220px; color: var(--text-muted); border-bottom: 1px solid var(--border-color); padding: 14px 24px;">Status monitoring</td>
                                <td style="font-weight: 700; color: var(--text-main); border-bottom: 1px solid var(--border-color); padding: 14px 24px;">
                                    @if($isFinal)
                                        <span class="badge badge-green">Final</span>
                                    @else
                                        <span class="badge badge-gray">Draft</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted); border-bottom: 1px solid var(--border-color); padding: 14px 24px;">Tanggal survey</td>
                                <td style="font-weight: 700; color: var(--text-main); border-bottom: 1px solid var(--border-color); padding: 14px 24px;">
                                    @php
                                        $tglMonitoring = $pengajuan->activeSurvey ? $pengajuan->activeSurvey->tanggal_survey : ($monitoring->tanggal_survey ?? '2025-05-22');
                                        try {
                                            $formattedTglMon = \Carbon\Carbon::parse($tglMonitoring)->locale('id')->translatedFormat('d F Y');
                                        } catch (\Exception $e) {
                                            $formattedTglMon = $tglMonitoring;
                                        }
                                        $jamMon = $pengajuan->activeSurvey ? ' · ' . $pengajuan->activeSurvey->jam_survey : '';
                                    @endphp
                                    {{ $formattedTglMon }}{{ $jamMon }}
                                </td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted); border-bottom: 1px solid var(--border-color); padding: 14px 24px;">Petugas Perwaskim</td>
                                <td style="font-weight: 700; color: var(--text-main); border-bottom: 1px solid var(--border-color); padding: 14px 24px;">
                                    {{ $pengajuan->activeSurvey->petugas_nama ?? ($monitoring->petugas_nama ?? 'Rahmat Hidayat, S.T.') }}
                                </td>
                            </tr>
                            <tr>
                                <td style="color: var(--text-muted); padding: 14px 24px;">Hasil</td>
                                <td style="font-weight: 700; color: var(--text-main); padding: 14px 24px;">
                                    {{ $monitoring->hasil ?? 'Perlu Evaluasi' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Temuan dan Kesimpulan Card -->
            <div class="card" style="margin-bottom: 0;">
                <div class="card-header">
                    <h3 class="card-title">Temuan dan kesimpulan</h3>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">TEMUAN</div>
                        <p style="font-size: 14px; color: var(--text-main); line-height: 1.6;">
                            {{ $monitoring->temuan ?? 'Saluran drainase sisi timur belum sesuai detail rencana. Akses kendaraan pemadam perlu penegasan pada area tikungan blok C.' }}
                        </p>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">KESIMPULAN</div>
                        <p style="font-size: 14px; color: var(--text-main); line-height: 1.6;">
                            {{ $monitoring->kesimpulan ?? 'Kawasan dapat dilanjutkan setelah perbaikan desain drainase dan penyampaian revisi gambar teknis.' }}
                        </p>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <div style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">KESEPAKATAN</div>
                        <p style="font-size: 14px; color: var(--text-main); line-height: 1.6;">
                            {{ $monitoring->kesepakatan ?? 'Pengembang akan mengunggah revisi dalam 7 hari kerja.' }}
                        </p>
                    </div>

                    <!-- Alert Box -->
                    <div class="alert-box">
                        <div class="alert-title">RENCANA TINDAK LANJUT WAJIB</div>
                        <div class="alert-text">
                            {{ $monitoring->rencana_tindak_lanjut ?? 'Perbarui gambar drainase dan lengkapi simulasi manuver kendaraan pemadam sebelum persetujuan dilanjutkan.' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Foto Bukti Card -->
            <div class="card" style="margin-bottom: 0;">
                <div class="card-header">
                    <h3 class="card-title">Foto bukti survey lapangan</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div style="height: 120px; background-color: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-muted);" onclick="alert('Pratinjau foto Drainase Sisi Timur');">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </div>
                            <span style="font-size: 12px; color: var(--text-muted); text-align: center;">Drainase sisi timur</span>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div style="height: 120px; background-color: #e7dfd5; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-muted);" onclick="alert('Pratinjau foto Akses Blok C');">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </div>
                            <span style="font-size: 12px; color: var(--text-muted); text-align: center;">Akses blok C</span>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div style="height: 120px; background-color: #dbe5e7; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-muted);" onclick="alert('Pratinjau foto Ruang Terbuka Hijau');">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </div>
                            <span style="font-size: 12px; color: var(--text-muted); text-align: center;">Ruang terbuka hijau</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Tindakan Card -->
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <h3 class="card-title">Tindakan</h3>
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 16px;">
                <form action="{{ route('monitoring.action', $pengajuan->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="perbaikan">
                    <button type="submit" class="btn btn-outline" style="width: 100%; padding: 12px;">
                        Kembali ke Perbaikan
                    </button>
                </form>

                <form action="{{ route('monitoring.action', $pengajuan->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="persetujuan">
                    @if($isFinal)
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                            Lanjutkan Persetujuan
                        </button>
                    @else
                        <button type="button" class="btn btn-primary btn-disabled" style="width: 100%; padding: 12px; background-color: #cbd5e1; border-color: #cbd5e1;" disabled>
                            Lanjutkan Persetujuan
                        </button>
                    @endif
                </form>

                <p style="font-size: 12px; color: var(--text-muted); text-align: center; line-height: 1.5; margin-top: 4px;">
                    Persetujuan hanya dapat dilanjutkan setelah monitoring berstatus <strong>Final</strong>.
                </p>
            </div>
        </div>
    </div>
</div>
