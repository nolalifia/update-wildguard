<?php

namespace App\Support;

// Setelah simpan/hapus dari situs publik, admin dikembalikan ke bagian situs
// tempat ia menekan tombol (flora / fauna / artikel). Selain itu: URL bawaan.
class Kembali
{
    private const PETA = [
        'flora' => '#flora',
        'fauna' => '#fauna',
        'artikel' => '#edukasi',
        'laporan' => '#kelola-laporan',
    ];

    public static function url(?string $kembali, string $default): string
    {
        return isset(self::PETA[$kembali ?? ''])
            ? route('home') . self::PETA[$kembali]
            : $default;
    }
}
