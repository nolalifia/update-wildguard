<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id_laporan';
    public $timestamps = false; // hanya ada created_at (diisi default DB)
    protected $guarded = [];
    protected $casts = [
        'tanggal_kejadian' => 'date',
        'created_at' => 'datetime',
    ];

    public function jenis()
    {
        return $this->belongsTo(JenisTemuan::class, 'id_jenis', 'id_jenis');
    }

    public function spesies()
    {
        return $this->belongsTo(Spesies::class, 'id_spesies', 'id_spesies');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
