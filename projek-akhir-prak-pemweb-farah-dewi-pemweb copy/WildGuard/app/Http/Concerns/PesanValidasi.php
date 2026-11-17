<?php

namespace App\Http\Concerns;

// Pesan validasi berbahasa Indonesia (dipakai semua controller CRUD)
trait PesanValidasi
{
    protected function pesan(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah terdaftar.',
            'email' => ':attribute harus berupa alamat email yang valid.',
            'url' => ':attribute harus berupa alamat URL yang valid.',
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'in' => ':attribute tidak valid.',
            'exists' => ':attribute tidak valid.',
            'date' => ':attribute bukan tanggal yang valid.',
            'regex' => ':attribute mengandung format atau karakter yang tidak diperbolehkan.',
            'not_regex' => ':attribute mengandung format atau karakter yang tidak diperbolehkan.',
            'before_or_equal' => ':attribute tidak boleh di masa depan.',
        ];
    }
}
