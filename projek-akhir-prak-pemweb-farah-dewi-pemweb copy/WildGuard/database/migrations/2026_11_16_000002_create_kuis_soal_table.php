<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuis_soal', function (Blueprint $t) {
            $t->id('id_soal');
            $t->string('pertanyaan', 300);
            $t->string('opsi_a', 200);
            $t->string('opsi_b', 200);
            $t->string('opsi_c', 200)->nullable();
            $t->string('opsi_d', 200)->nullable();
            $t->char('jawaban_benar', 1);          // a, b, c, atau d
            $t->text('penjelasan')->nullable();
            $t->string('gambar', 255)->nullable(); // URL gambar
            $t->boolean('aktif')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis_soal');
    }
};