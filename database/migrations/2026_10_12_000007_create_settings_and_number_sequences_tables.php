<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * settings: angka yang bisa diubah Administrator tanpa deploy (SLA, batas upload).
     * number_sequences: penomoran berurutan per tahun (SR-TAHUN-NNNN, BA-MON/TAHUN/NNNN).
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->string('value');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('number_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('sequence_key', 50)->unique(); // contoh: SR-2026, BA-MON-2026
            $table->unsignedInteger('last_value')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('number_sequences');
        Schema::dropIfExists('settings');
    }
};
