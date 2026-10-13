<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * 9 data akun pengembang yang diisi Administrator saat penerbitan akun.
     * (SRS REQ-F-002, FSD BR-008)
     */
    public function up(): void
    {
        Schema::create('developer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nib', 13)->unique();
            $table->string('nama_perumahan', 150);
            $table->string('nama_pt', 150);
            $table->string('nama_direktur', 150);
            $table->text('alamat_perumahan');
            $table->text('alamat_pt');
            $table->string('no_hp_direktur', 20);
            $table->string('email_pt');
            // site_plan_baru | perubahan | pengembangan
            $table->string('tipe_pengajuan', 30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developer_profiles');
    }
};
