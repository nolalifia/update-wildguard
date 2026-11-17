<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artikel', function (Blueprint $t) {
            $t->id('id_artikel');
            $t->foreignId('id_kategori')
                ->constrained('kategori_artikel', 'id_kategori')
                ->cascadeOnUpdate()->restrictOnDelete();
            $t->string('judul', 200);
            $t->string('slug', 220)->unique();
            $t->text('isi');
            $t->date('tanggal');
            $t->string('gambar_url', 500)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
