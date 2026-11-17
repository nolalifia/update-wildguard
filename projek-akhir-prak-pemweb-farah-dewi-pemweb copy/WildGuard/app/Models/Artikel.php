<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $table = 'artikel';
    protected $primaryKey = 'id_artikel';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['tanggal' => 'date'];

    public function kategori()
    {
        return $this->belongsTo(KategoriArtikel::class, 'id_kategori', 'id_kategori');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // hanya artikel yang sudah disetujui admin yang tampil di situs publik
    public function scopeTerbit($q)
    {
        return $q->where('status', 'terbit');
    }
}
