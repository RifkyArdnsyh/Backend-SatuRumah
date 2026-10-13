<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Pengajuan persetujuan site plan.
     * stage: 1 Pengajuan, 2 Verifikasi Administrasi, 3 Verifikasi Teknis,
     *        4 Survey Lapangan, 5 Persetujuan, 6 Selesai.
     * sub_status: draft | dalam_proses | perlu_perbaikan | selesai_verifikasi.
     * Aturan "satu pengajuan aktif per akun" (BR-007) dijaga di aplikasi.
     */
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20)->nullable()->unique(); // SR-2026-0001, terbit saat Submit
            $table->foreignId('developer_id')->constrained('users');

            // Isian manual Step 1 (nullable karena draft)
            $table->string('npwp', 20)->nullable();
            $table->decimal('luas_lahan', 14, 2)->nullable();
            $table->unsignedInteger('jumlah_unit')->nullable();
            $table->string('tipe_perumahan', 30)->nullable(); // subsidi | komersil | subsidi_komersil

            // Status dan tahap
            $table->unsignedTinyInteger('stage')->default(1);
            $table->string('sub_status', 30)->default('draft');
            $table->unsignedTinyInteger('current_step')->default(1); // posisi wizard draft (1-5)

            // Waktu dan SLA
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('stage_entered_at')->nullable();
            $table->timestamp('sla_paused_at')->nullable();
            $table->unsignedBigInteger('sla_paused_seconds')->default(0);
            $table->timestamp('correction_deadline_at')->nullable(); // batas perbaikan 14 hari (BR-042)

            // Keputusan Persetujuan (BR-038)
            $table->string('decision_number', 100)->nullable();
            $table->date('decision_date')->nullable();
            $table->string('decision_file_path')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->unsignedInteger('version')->default(1); // kunci konflik (ERR-015)
            $table->timestamps();

            $table->index(['stage', 'sub_status']);
            $table->index('developer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
