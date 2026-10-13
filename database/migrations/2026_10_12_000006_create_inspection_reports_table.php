<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Laporan monitoring lapangan + Berita Acara.
     * assignment_id / submission_id boleh kosong (monitoring mandiri, REQ-F-038).
     * status: draft | menunggu_sinkronisasi | terbit
     * Laporan terbit tidak boleh diubah; koreksi = laporan baru (previous_report_id).
     */
    public function up(): void
    {
        Schema::create('inspection_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number', 30)->nullable()->unique(); // BA-MON/2026/0001
            $table->foreignId('assignment_id')->nullable()
                ->constrained('inspection_assignments')->nullOnDelete();
            $table->foreignId('submission_id')->nullable()
                ->constrained('submissions')->nullOnDelete();
            $table->foreignId('officer_id')->constrained('users');
            $table->foreignId('previous_report_id')->nullable()
                ->constrained('inspection_reports')->nullOnDelete();

            // Langkah 1
            $table->date('monitoring_date');
            $table->string('nama_perumahan', 150);
            $table->string('lokasi', 250)->nullable();
            $table->json('temuan')->nullable();       // daftar butir, maks 20
            $table->json('kesimpulan')->nullable();
            // Langkah 2
            $table->json('kesepakatan')->nullable();
            $table->json('rtl')->nullable();
            $table->json('tim_pelaksana')->nullable(); // [{nama, jabatan}], maks 10
            $table->json('pihak_ditemui')->nullable();

            $table->string('status', 30)->default('draft');
            $table->decimal('submit_latitude', 10, 7)->nullable();
            $table->decimal('submit_longitude', 10, 7)->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('published_at')->nullable();

            $table->string('pdf_path')->nullable();
            $table->string('docx_path')->nullable();
            $table->string('verification_token', 64)->nullable()->unique(); // untuk QR code
            $table->timestamps();

            $table->index(['officer_id', 'status']);
            $table->index('submission_id');
        });

        // Foto dokumentasi (maks 15 per laporan, JPG/PNG, maks 10 MB)
        Schema::create('inspection_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('inspection_reports')->cascadeOnDelete();
            $table->unsignedTinyInteger('sort_order')->default(1);
            $table->string('storage_path');
            $table->string('caption', 150)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();   // null = "Tanpa lokasi"
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('captured_at')->nullable();
            $table->unsignedBigInteger('size_bytes');
            $table->timestamps();

            $table->index(['report_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_photos');
        Schema::dropIfExists('inspection_reports');
    }
};
