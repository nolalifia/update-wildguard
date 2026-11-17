<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi', function (Blueprint $t) {
            $t->id('id_lokasi');
            $t->string('nama_lokasi', 100)->unique();
            $t->string('pulau', 60);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};
