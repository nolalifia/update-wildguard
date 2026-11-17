<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_temuan', function (Blueprint $t) {
            $t->id('id_jenis');
            $t->string('nama_jenis', 100)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_temuan');
    }
};
