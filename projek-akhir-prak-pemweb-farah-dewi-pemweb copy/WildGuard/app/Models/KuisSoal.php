<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisSoal extends Model
{
    protected $table = 'kuis_soal';
    protected $primaryKey = 'id_soal';

    protected $fillable = [
        'pertanyaan', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d',
        'jawaban_benar', 'penjelasan', 'gambar', 'aktif',
    ];

    protected $casts = ['aktif' => 'boolean'];

    public function scopeAktif($q)
    {
        return $q->where('aktif', true);
    }

    /** pilihan yang terisi, urut A-D */
    public function daftarOpsi(): array
    {
        return array_values(array_filter(
            [$this->opsi_a, $this->opsi_b, $this->opsi_c, $this->opsi_d],
            fn ($v) => $v !== null && $v !== ''
        ));
    }

    /** posisi jawaban benar (0 = A, 1 = B, ...) */
    public function indeksBenar(): int
    {
        return (int) array_search($this->jawaban_benar, ['a', 'b', 'c', 'd'], true);
    }
}