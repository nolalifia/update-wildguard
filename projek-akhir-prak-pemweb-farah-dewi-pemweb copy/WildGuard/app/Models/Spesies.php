<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spesies extends Model
{
    protected $table = 'spesies';
    protected $primaryKey = 'id_spesies';
    public $timestamps = false;
    protected $guarded = [];

    // N:1 -> status_perlindungan
    public function status()
    {
        return $this->belongsTo(StatusPerlindungan::class, 'id_status', 'id_status');
    }

    // M:N -> lokasi (lewat tabel spesies_lokasi)
    public function lokasi()
    {
        return $this->belongsToMany(Lokasi::class, 'spesies_lokasi', 'id_spesies', 'id_lokasi');
    }

    // 1:N -> laporan
    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'id_spesies', 'id_spesies');
    }
}
