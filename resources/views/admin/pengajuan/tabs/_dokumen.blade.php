<!-- Dokumen Perusahaan Card -->
<div class="card">
    <div class="card-head bordered">
        <h2 class="card-title">Dokumen Perusahaan - {{ $dokumenPerusahaan->count() }} persyaratan wajib</h2>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Persyaratan</th>
                    <th>Berkas Aktif</th>
                    <th>Tanggal &middot; Ukuran</th>
                    <th>Status</th>
                    <th class="col-actions">Aksi Berkas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dokumenPerusahaan as $doc)
                    <tr>
                        <td class="col-strong">{{ $doc->nama_persyaratan }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 28px; height: 28px; border-radius: 6px; background-color: #fee2e2; color: #b91c1c; display: flex; align-items: center; justify-content: center; flex-shrink: 0;"
                                    title="Berkas PDF">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="mono" style="font-size: 13px; font-weight: 500;">{{ $doc->nama_file }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $doc->ukuran_file }}</td>
                        <td>
                            @if($doc->status == 'Sesuai')
                                <span class="badge badge-success badge-dot">Sesuai</span>
                            @elseif($doc->status == 'Perlu perbaikan')
                                <span class="badge badge-warning badge-dot">Perlu perbaikan</span>
                            @else
                                <span class="badge badge-dot">Belum diperiksa</span>
                            @endif
                        </td>
                        <td class="col-actions">
                            <div style="display: inline-flex; gap: 6px;">
                                <button class="btn btn-outline btn-sm"
                                    onclick="openPdfViewer({{ json_encode($doc) }}, '{{ $pengajuan->nama_perumahan }}', '{{ $pengajuan->nama_pengembang }}')"
                                    title="Buka PDF Langsung">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Pratinjau
                                </button>
                                <button class="btn btn-outline btn-sm" onclick="openDocStatusModal({{ json_encode($doc) }})"
                                    title="Ubah Status / Catatan">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Dokumen Perumahan Card -->
<div class="card" style="margin-top: 20px;">
    <div class="card-head bordered">
        <h2 class="card-title">Dokumen Perumahan - {{ $dokumenPerumahan->count() }} persyaratan wajib</h2>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Persyaratan</th>
                    <th>Berkas Aktif</th>
                    <th>Tanggal &middot; Ukuran</th>
                    <th>Status</th>
                    <th class="col-actions">Aksi Berkas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dokumenPerumahan as $doc)
                    <tr>
                        <td class="col-strong">{{ $doc->nama_persyaratan }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 28px; height: 28px; border-radius: 6px; background-color: #fee2e2; color: #b91c1c; display: flex; align-items: center; justify-content: center; flex-shrink: 0;"
                                    title="Berkas PDF">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="mono" style="font-size: 13px; font-weight: 500;">{{ $doc->nama_file }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $doc->ukuran_file }}</td>
                        <td>
                            @if($doc->status == 'Sesuai')
                                <span class="badge badge-success badge-dot">Sesuai</span>
                            @elseif($doc->status == 'Perlu perbaikan')
                                <span class="badge badge-warning badge-dot">Perlu perbaikan</span>
                            @else
                                <span class="badge badge-dot">Belum diperiksa</span>
                            @endif
                        </td>
                        <td class="col-actions">
                            <div style="display: inline-flex; gap: 6px;">
                                <button class="btn btn-outline btn-sm"
                                    onclick="openPdfViewer({{ json_encode($doc) }}, '{{ $pengajuan->nama_perumahan }}', '{{ $pengajuan->nama_pengembang }}')"
                                    title="Buka PDF Langsung">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Pratinjau
                                </button>
                                <button class="btn btn-outline btn-sm" onclick="openDocStatusModal({{ json_encode($doc) }})"
                                    title="Ubah Status / Catatan">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Paket Dokumen Teknis Card -->
<div class="card" style="margin-top: 20px;">
    <div class="card-head bordered">
        <h2 class="card-title">Paket Dokumen Teknis</h2>
    </div>
    <div class="card-body">
        <div
            style="background-color: var(--bg-page); border: 1px solid var(--border); border-radius: 10px; padding: 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 24px;">
            <div>
                <div class="fw-700" style="font-size: 15px; margin-bottom: 4px;">
                    {{ $paketTeknis->nama_file ?? 'Paket_tekdok_GriyaMahardika.zip' }}
                </div>
                <div class="text-muted" style="font-size: 13px;">
                    Diunggah {{ $paketTeknis->ukuran_file ?? '08 Mei 2025 · 24.8 MB · berisi 7 berkas' }}
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-outline"
                    onclick="openPdfViewer({{ json_encode($paketTeknis ?? ['nama_persyaratan' => 'Paket Teknis Lengkap', 'nama_file' => 'Paket_tekdok_GriyaMahardika.pdf', 'ukuran_file' => '24.8 MB', 'status' => 'Sesuai', 'catatan' => '']) }}, '{{ $pengajuan->nama_perumahan }}', '{{ $pengajuan->nama_pengembang }}')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Lihat Dokumen
                </button>
                <button class="btn btn-primary" onclick="alert('Mengunduh paket teknis zip...');">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Unduh ZIP
                </button>
            </div>
        </div>

        <div>
            <div class="text-muted"
                style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">
                Kategori Teknis Opsional &middot; 21 Kategori
            </div>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px 24px; font-size: 13px;">
                <div>&bull; Struktur dan pondasi</div>
                <div>&bull; Sistem air bersih</div>
                <div>&bull; Sistem air limbah</div>
                <div>&bull; Drainase kawasan</div>
                <div>&bull; Jaringan jalan</div>
                <div>&bull; Penerangan jalan umum</div>
                <div>&bull; Ruang terbuka hijau</div>
                <div>&bull; Sistem proteksi kebakaran</div>
                <div>&bull; Utilitas listrik</div>
                <div>&bull; Telekomunikasi</div>
                <div>&bull; Pengelolaan sampah</div>
                <div>&bull; Aksesibilitas difabel</div>
                <div>&bull; Fasilitas sosial</div>
                <div>&bull; Fasilitas umum</div>
                <div>&bull; Peta kontur</div>
                <div>&bull; Kajian geoteknik</div>
                <div>&bull; Kajian hidrologi</div>
                <div>&bull; Rencana mitigasi bencana</div>
                <div>&bull; Rencana pengelolaan lingkungan</div>
                <div>&bull; Rencana pemeliharaan</div>
                <div>&bull; Kajian transportasi</div>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Native PDF Viewer Modal -->
<div id="pdfViewerModal" class="modal-backdrop" style="display: none;">
    <div class="pdf-modal-container">
        <!-- PDF Toolbar -->
        <div class="pdf-toolbar">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div
                    style="background-color: #dc2626; color: #ffffff; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 800;">
                    PDF</div>
                <div>
                    <div id="pdfViewerDocTitle" class="fw-700" style="font-size: 14px;">Dokumen Persyaratan</div>
                    <div id="pdfViewerDocSubtitle" class="text-muted" style="font-size: 11px;">berkas.pdf &middot; 1.2
                        MB</div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 16px;">
                <span class="chip">Halaman 1 dari 3</span>
                <button onclick="closePdfViewer()" class="modal-close" style="font-size: 22px;">&times;</button>
            </div>
        </div>

        <!-- PDF Rendered Viewport -->
        <div class="pdf-viewport">
            <div class="pdf-paper">
                <div class="pdf-watermark">SATU RUMAH DISPERWASKIM</div>

                <!-- Letterhead / Kop Resmi -->
                <div
                    style="border-bottom: 2px solid #0f172a; padding-bottom: 16px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div
                            style="font-size: 10px; font-weight: 800; letter-spacing: 1px; color: #64748b; text-transform: uppercase;">
                            PEMERINTAH KOTA TASIKMALAYA</div>
                        <div style="font-size: 14px; font-weight: 800; color: #0f172a;">DINAS PERUMAHAN RAKYAT DAN
                            KAWASAN PERMUKIMAN</div>
                        <div style="font-size: 11px; color: #64748b;">Sistem Administrasi Terpadu Urusan & Pengawasan
                            Perumahan (SATU RUMAH)</div>
                    </div>
                    <div style="text-align: right;">
                        <span id="pdfPaperBadge" class="badge badge-success badge-dot"
                            style="font-size: 12px;">Sesuai</span>
                    </div>
                </div>

                <!-- Document Main Body -->
                <div style="text-align: center; margin-bottom: 24px;">
                    <h2 id="pdfPaperTitle"
                        style="font-size: 18px; font-weight: 800; text-transform: uppercase; color: #0f172a; margin-bottom: 4px;">
                        NIB DAN IZIN USAHA PENGEMBANG
                    </h2>
                    <div style="font-size: 12px; color: #64748b;">Nomor Verifikasi: VER/SR-2026/09-0148/DISPERWASKIM
                    </div>
                </div>

                <!-- Metadata Box -->
                <div
                    style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 24px; font-size: 13px; display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <span style="color: #64748b; font-size: 11px; display: block;">NAMA PERUMAHAN</span>
                        <strong id="pdfPaperHousing">Griya Mangkubumi Asri</strong>
                    </div>
                    <div>
                        <span style="color: #64748b; font-size: 11px; display: block;">PENGEMBANG</span>
                        <strong id="pdfPaperDeveloper">PT Citra Tasik Mandiri</strong>
                    </div>
                    <div>
                        <span style="color: #64748b; font-size: 11px; display: block;">NAMA FILE BERKAS</span>
                        <span id="pdfPaperFilename" class="mono">nib_dan_izin_usaha.pdf</span>
                    </div>
                    <div>
                        <span style="color: #64748b; font-size: 11px; display: block;">TANGGAL UNGGAH & UKURAN</span>
                        <span id="pdfPaperMeta">08 Mei 2025 · 0.8 MB</span>
                    </div>
                </div>

                <!-- Document Content Simulation Lines -->
                <div style="font-size: 13px; color: #334155; line-height: 1.8; margin-bottom: 32px;">
                    <p style="margin-bottom: 12px;">
                        Dengan ini diterangkan bahwa berkas dokumen digital terlampir telah diunggah ke dalam portal
                        SATU RUMAH Disperwaskim Kota Tasikmalaya dan diverifikasi kesesuaian data izin operasional,
                        kesesuaian site plan teknis, dan kelengkapan perizinan berusaha berbasis risiko.
                    </p>
                    <p style="margin-bottom: 12px;">
                        Kesesuaian berkas ini menjadi dasar kelayakan administrasi perizinan sebelum dilanjutkan ke
                        tahap <strong>Survey dan Monitoring Lapangan</strong> oleh Tim Petugas Perwaskim Disperwaskim.
                    </p>
                    <div
                        style="border-left: 3px solid var(--primary); background-color: var(--primary-tint); padding: 10px 14px; border-radius: 4px; font-size: 12px;">
                        <strong>Catatan Verifikator:</strong> <span id="pdfPaperNotes">Berkas telah diperiksa dan
                            dinyatakan sah serta memenuhi standar Perda tata ruang.</span>
                    </div>
                </div>

                <!-- Footer Signatures Stamp -->
                <div
                    style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 48px; border-top: 1px dashed #cbd5e1; padding-top: 24px; flex-wrap: wrap; gap: 16px;">
                    <div style="font-size: 11px; color: #64748b;">
                        <div>ID Dokumen: <span class="mono">DOC-SR2026-09148-OK</span></div>
                    </div>
                    <div
                        style="text-align: center; border: 2px solid #16a34a; border-radius: 8px; padding: 8px 16px; color: #16a34a;">
                        <div style="font-size: 10px; font-weight: 800; letter-spacing: 0.5px;">DISPERWASKIM KOTA
                            TASIKMALAYA</div>
                        <div style="font-size: 14px; font-weight: 800;">DIVERIFIKASI DIGITAL</div>
                        <div style="font-size: 9px;">SISTEM SATU RUMAH</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PDF Footer Action Bar (Change status on the fly while viewing) -->
        <div class="pdf-footer-bar">
            <div class="text-muted" style="font-size: 13px;">
                Ubah status verifikasi dokumen ini secara langsung:
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <form id="pdfQuickSesuaiForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="status" value="Sesuai">
                    <button type="submit" class="btn btn-success btn-sm">
                        &check; Tandai Sesuai
                    </button>
                </form>

                <form id="pdfQuickPerluPerbaikanForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="status" value="Perlu perbaikan">
                    <input type="hidden" name="catatan" value="Dokumen memerlukan penyesuaian/pembaharuan scan berkas.">
                    <button type="submit" class="btn btn-soft btn-sm">
                        &times; Minta Perbaikan
                    </button>
                </form>

                <button type="button" class="btn btn-outline btn-sm" onclick="closePdfViewer()">
                    Tutup Viewer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dialog Update Status & Catatan Dokumen -->
<div id="docStatusModal" class="modal-backdrop" style="display: none;">
    <div class="modal">
        <div class="modal-head">
            <h3 class="modal-title" id="modalDocTitle">Verifikasi Dokumen</h3>
            <button onclick="closeDocStatusModal()" class="modal-close">&times;</button>
        </div>
        <form id="docStatusForm" method="POST" action="">
            @csrf
            <div class="modal-body">
                <div class="field">
                    <label class="label">Nama Persyaratan</label>
                    <input type="text" id="modalDocName" class="input" readonly>
                </div>
                <div class="field">
                    <label class="label">File Berkas</label>
                    <input type="text" id="modalDocFile" class="input" readonly>
                </div>
                <div class="field">
                    <label class="label">Status Verifikasi</label>
                    <select name="status" id="modalDocStatusSelect" class="select" required>
                        <option value="Sesuai">Sesuai (Diterima)</option>
                        <option value="Perlu perbaikan">Perlu Perbaikan</option>
                        <option value="Belum diperiksa">Belum Diperiksa</option>
                    </select>
                </div>
                <div class="field" style="margin-bottom: 0;">
                    <label class="label">Catatan Evaluasi Dokumen</label>
                    <textarea name="catatan" id="modalDocCatatan" class="textarea"
                        placeholder="Tuliskan catatan perbaikan jika dokumen belum sesuai..."></textarea>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn btn-outline" onclick="closeDocStatusModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Status Dokumen</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPdfViewer(doc, housingName, devName) {
        document.getElementById('pdfViewerDocTitle').innerText = doc.nama_persyaratan;
        document.getElementById('pdfViewerDocSubtitle').innerText = doc.nama_file + ' · ' + (doc.ukuran_file || '1.0 MB');

        document.getElementById('pdfPaperTitle').innerText = doc.nama_persyaratan;
        document.getElementById('pdfPaperHousing').innerText = housingName || 'Griya Mangkubumi Asri';
        document.getElementById('pdfPaperDeveloper').innerText = devName || 'PT Citra Tasik Mandiri';
        document.getElementById('pdfPaperFilename').innerText = doc.nama_file;
        document.getElementById('pdfPaperMeta').innerText = (doc.tanggal_unggah || '08 Mei 2025') + ' · ' + (doc.ukuran_file || '1.0 MB');
        document.getElementById('pdfPaperNotes').innerText = doc.catatan || 'Berkas telah sesuai dengan ketentuan teknis yang dipersyaratkan.';

        var badge = document.getElementById('pdfPaperBadge');
        if (doc.status === 'Sesuai') {
            badge.className = 'badge badge-success badge-dot';
            badge.innerText = 'Sesuai';
        } else if (doc.status === 'Perlu perbaikan') {
            badge.className = 'badge badge-warning badge-dot';
            badge.innerText = 'Perlu perbaikan';
        } else {
            badge.className = 'badge badge-dot';
            badge.innerText = 'Belum diperiksa';
        }

        var quickSesuai = document.getElementById('pdfQuickSesuaiForm');
        quickSesuai.action = '/dokumen/' + doc.id + '/status';

        var quickPerbaikan = document.getElementById('pdfQuickPerluPerbaikanForm');
        quickPerbaikan.action = '/dokumen/' + doc.id + '/status';

        document.getElementById('pdfViewerModal').style.display = 'flex';
    }

    function closePdfViewer() {
        document.getElementById('pdfViewerModal').style.display = 'none';
    }

    function openDocStatusModal(doc) {
        document.getElementById('modalDocTitle').innerText = 'Verifikasi ' + doc.nama_persyaratan;
        document.getElementById('modalDocName').value = doc.nama_persyaratan;
        document.getElementById('modalDocFile').value = doc.nama_file + ' (' + doc.ukuran_file + ')';
        document.getElementById('modalDocStatusSelect').value = doc.status;
        document.getElementById('modalDocCatatan').value = doc.catatan || '';

        var form = document.getElementById('docStatusForm');
        form.action = '/dokumen/' + doc.id + '/status';

        document.getElementById('docStatusModal').style.display = 'flex';
    }

    function closeDocStatusModal() {
        document.getElementById('docStatusModal').style.display = 'none';
    }
</script>
