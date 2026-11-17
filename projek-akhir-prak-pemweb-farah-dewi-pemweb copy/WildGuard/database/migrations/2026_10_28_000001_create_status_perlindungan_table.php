<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_perlindungan', function (Blueprint $t) {
            $t->id('id_status');
            $t->string('nama_status', 60)->unique();
            $t->enum('sumber', ['Nasional', 'IUCN', 'CITES']);
            $t->string('keterangan', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_perlindungan');
    }
};
