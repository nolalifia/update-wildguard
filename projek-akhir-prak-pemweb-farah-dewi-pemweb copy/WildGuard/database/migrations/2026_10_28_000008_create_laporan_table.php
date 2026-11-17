<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $t) {
            $t->id('id_laporan');
            $t->foreignId('id_jenis')
                ->constrained('jenis_temuan', 'id_jenis')
                ->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('id_spesies')->nullable()
                ->constrained('spesies', 'id_spesies')
                ->cascadeOnUpdate()->nullOnDelete();
            // pelapor terdaftar (opsional); pengunjung biasa = NULL
            $t->foreignId('id_user')->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()->nullOnDelete();
            $t->string('lokasi_kejadian', 200);
            $t->date('tanggal_kejadian');
            $t->text('deskripsi');
            $t->string('lampiran', 255)->nullable();
            $t->enum('status_laporan', ['baru', 'diproses', 'selesai'])->default('baru');
            $t->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
