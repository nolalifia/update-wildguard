<?php

namespace App\Http\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

// Simpan / hapus lampiran laporan di public/uploads/laporan
trait SimpanLampiran
{
    protected function simpanLampiran(?UploadedFile $file): ?string
    {
        if (!$file) {
            return null;
        }
        $nama = Str::random(24) . '.' . $file->extension();
        $file->move(public_path('uploads/laporan'), $nama);
        return 'uploads/laporan/' . $nama;
    }

    protected function hapusLampiran(?string $path): void
    {
        if ($path && is_file(public_path($path))) {
            @unlink(public_path($path));
        }
    }
}
