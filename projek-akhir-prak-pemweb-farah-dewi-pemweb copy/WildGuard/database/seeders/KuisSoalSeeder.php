<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// Soal contoh untuk kuis. Admin bisa menambah, mengubah, atau menghapusnya
// lewat menu Kuis di panel admin.
// Jalankan sendiri: php artisan db:seed --class=KuisSoalSeeder
class KuisSoalSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $soal = [
            [
                'pertanyaan' => 'Apa tujuan SDG 15.7?',
                'opsi_a' => 'Mengakhiri perburuan dan perdagangan ilegal flora dan fauna dilindungi',
                'opsi_b' => 'Memperbanyak kebun binatang di kota besar',
                'opsi_c' => 'Membuka lahan hutan untuk perkebunan',
                'opsi_d' => null,
                'jawaban_benar' => 'a',
                'penjelasan' => 'SDG 15.7 menargetkan penghentian perburuan dan perdagangan ilegal spesies yang dilindungi.',
            ],
            [
                'pertanyaan' => 'Apa yang sebaiknya kamu lakukan jika menemukan penjualan satwa dilindungi di media sosial?',
                'opsi_a' => 'Membelinya supaya satwanya selamat',
                'opsi_b' => 'Mengabaikannya',
                'opsi_c' => 'Melaporkannya ke pihak berwenang atau lewat form laporan WildGuard',
                'opsi_d' => 'Membagikannya ke teman tanpa keterangan',
                'jawaban_benar' => 'c',
                'penjelasan' => 'Membeli justru menjaga permintaan tetap ada. Laporkan supaya bisa ditindaklanjuti.',
            ],
            [
                'pertanyaan' => 'Satwa dilindungi boleh dipelihara tanpa izin selama dirawat dengan baik.',
                'opsi_a' => 'Benar',
                'opsi_b' => 'Salah',
                'opsi_c' => null,
                'opsi_d' => null,
                'jawaban_benar' => 'b',
                'penjelasan' => 'Memelihara satwa dilindungi tanpa izin melanggar hukum, seberapa baik pun perawatannya.',
            ],
        ];

        foreach ($soal as $s) {
            DB::table('kuis_soal')->insert($s + [
                'gambar' => null,
                'aktif' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
