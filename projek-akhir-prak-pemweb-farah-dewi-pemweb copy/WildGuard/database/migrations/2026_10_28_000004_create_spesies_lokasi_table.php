<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel penghubung relasi many-to-many spesies <-> lokasi
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spesies_lokasi', function (Blueprint $t) {
            $t->foreignId('id_spesies')
                ->constrained('spesies', 'id_spesies')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $t->foreignId('id_lokasi')
                ->constrained('lokasi', 'id_lokasi')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $t->primary(['id_spesies', 'id_lokasi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spesies_lokasi');
    }
};
