<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPerlindungan extends Model
{
    protected $table = 'status_perlindungan';
    protected $primaryKey = 'id_status';
    public $timestamps = false;
    protected $guarded = [];

    public function spesies()
    {
        return $this->hasMany(Spesies::class, 'id_status', 'id_status');
    }
}
