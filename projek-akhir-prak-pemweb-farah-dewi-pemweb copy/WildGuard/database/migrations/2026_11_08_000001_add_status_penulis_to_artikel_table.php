<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikel', function (Blueprint $t) {
            // usulan artikel dari user: menunggu persetujuan admin, baru tampil di situs kalau "terbit"
            $t->enum('status', ['menunggu', 'terbit'])->default('terbit')->after('gambar_url');
            $t->foreignId('id_user')->nullable()->after('status')
                ->constrained('users')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('artikel', function (Blueprint $t) {
            $t->dropConstrainedForeignId('id_user');
            $t->dropColumn('status');
        });
    }
};
