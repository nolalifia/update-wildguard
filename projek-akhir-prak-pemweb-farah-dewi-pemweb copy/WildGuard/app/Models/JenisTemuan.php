<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisTemuan extends Model
{
    protected $table = 'jenis_temuan';
    protected $primaryKey = 'id_jenis';
    public $timestamps = false;
    protected $guarded = [];

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'id_jenis', 'id_jenis');
    }
}
