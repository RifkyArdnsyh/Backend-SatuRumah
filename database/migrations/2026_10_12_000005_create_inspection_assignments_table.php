<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Disposisi / surat tugas inspeksi (hanya untuk pengajuan di tahap 4).
     * status: dijadwalkan | diterima | selesai | dialihkan
     */
    public function up(): void
    {
        Schema::create('inspection_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->cascadeOnDelete();
            $table->foreignId('officer_id')->constrained('users');
            $table->date('scheduled_date');
            $table->string('note', 500)->nullable();
            $table->string('status', 20)->default('dijadwalkan');
            $table->foreignId('reassigned_from_id')->nullable()
                ->constrained('inspection_assignments')->nullOnDelete();
            $table->text('reassign_reason')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['officer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_assignments');
    }
};
