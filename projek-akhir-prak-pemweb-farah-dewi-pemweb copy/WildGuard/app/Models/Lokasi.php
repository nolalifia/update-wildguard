<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'lokasi';
    protected $primaryKey = 'id_lokasi';
    public $timestamps = false;
    protected $guarded = [];

    public function spesies()
    {
        return $this->belongsToMany(Spesies::class, 'spesies_lokasi', 'id_lokasi', 'id_spesies');
    }
}
