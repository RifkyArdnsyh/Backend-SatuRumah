<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Berkas per slot (18 slot: S2-01..05, S3-01..11, S4-01..02).
     * - Slot "Belum Diunggah" = belum ada baris untuk slot itu.
     * - Slot banyak-berkas (S3-05, S4-01, S4-02) punya beberapa baris is_current = true;
     *   keputusan verifikator berlaku untuk semua berkas aktif pada slot tersebut.
     * - Berkas lama yang diganti tidak dihapus: is_current = false (arsip versi, REQ-F-029).
     * status: menunggu_telaah | lolos | perlu_perbaikan
     * decision_type: revisi | tolak (hanya terisi bila status perlu_perbaikan)
     */
    public function up(): void
    {
        Schema::create('submission_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->cascadeOnDelete();
            $table->string('slot_code', 5);
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);

            $table->string('original_name');
            $table->string('stored_name');          // uuid, bukan nama asli (BR-022)
            $table->string('storage_path');         // disk privat, tanpa URL publik
            $table->string('file_type', 10);        // pdf | dwg
            $table->unsignedBigInteger('size_bytes');
            $table->string('checksum', 64)->nullable(); // sha256

            $table->string('status', 30)->default('menunggu_telaah');
            $table->string('decision_type', 10)->nullable();
            $table->text('review_note')->nullable();

            // Draft Evaluasi: belum terlihat pengembang sampai "Kirim Hasil Verifikasi" (BR-030)
            $table->string('draft_status', 30)->nullable();
            $table->string('draft_decision_type', 10)->nullable();
            $table->text('draft_note')->nullable();

            $table->foreignId('uploaded_by')->constrained('users');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('replaced_at')->nullable();
            $table->timestamps();

            $table->index(['submission_id', 'slot_code', 'is_current']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_files');
    }
};
