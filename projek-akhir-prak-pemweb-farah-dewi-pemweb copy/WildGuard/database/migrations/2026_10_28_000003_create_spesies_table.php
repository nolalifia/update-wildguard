<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spesies', function (Blueprint $t) {
            $t->id('id_spesies');
            $t->string('nama', 100);
            $t->string('nama_latin', 150)->unique();
            $t->enum('tipe', ['flora', 'fauna']);
            $t->foreignId('id_status')
                ->constrained('status_perlindungan', 'id_status')
                ->cascadeOnUpdate()->restrictOnDelete();
            $t->string('habitat', 150)->nullable();
            $t->string('populasi', 80)->nullable();
            $t->string('ciri_khas', 150)->nullable();
            $t->string('ringkasan', 255)->nullable();
            $t->text('deskripsi')->nullable();
            $t->text('fakta')->nullable();
            $t->string('gambar_url', 500)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spesies');
    }
};
