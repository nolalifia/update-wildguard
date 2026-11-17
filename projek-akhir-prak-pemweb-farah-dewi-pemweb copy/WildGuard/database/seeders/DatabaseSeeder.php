<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Data flora/fauna/artikel diambil dari data asli di script.js proyek WildGuard
// (disimpan di database/seeders/data/wildguard.json)
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // akun demo (ganti passwordnya setelah presentasi)
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Admin WildGuard', 'email' => 'admin@wildguard.test',
             'password' => Hash::make('admin12345'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Pelapor Demo', 'email' => 'user@wildguard.test',
             'password' => Hash::make('user12345'), 'role' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $data = json_decode(file_get_contents(database_path('seeders/data/wildguard.json')), true);

        DB::table('status_perlindungan')->insert([
            ['id_status' => 1, 'nama_status' => 'Dilindungi', 'sumber' => 'Nasional', 'keterangan' => 'Dilindungi undang-undang Indonesia'],
            ['id_status' => 2, 'nama_status' => 'Critically Endangered', 'sumber' => 'IUCN', 'keterangan' => 'Sangat terancam punah di alam liar'],
            ['id_status' => 3, 'nama_status' => 'Endangered', 'sumber' => 'IUCN', 'keterangan' => 'Terancam punah'],
        ]);

        DB::table('lokasi')->insert([
            ['id_lokasi' => 1, 'nama_lokasi' => 'Hutan hujan Sumatera', 'pulau' => 'Sumatera'],
            ['id_lokasi' => 2, 'nama_lokasi' => 'Hutan hujan Kalimantan', 'pulau' => 'Kalimantan'],
            ['id_lokasi' => 3, 'nama_lokasi' => 'Nusa Tenggara Timur', 'pulau' => 'Nusa Tenggara'],
            ['id_lokasi' => 4, 'nama_lokasi' => 'Taman Nasional Ujung Kulon', 'pulau' => 'Jawa'],
            ['id_lokasi' => 5, 'nama_lokasi' => 'Taman Nasional Komodo', 'pulau' => 'Nusa Tenggara'],
        ]);

        // spesies + relasi M:N ke lokasi
        foreach ($data['spesies'] as $i => $s) {
            $lokasi = $s['lokasi'];
            unset($s['lokasi']);
            $id = $i + 1;
            DB::table('spesies')->insert(['id_spesies' => $id] + $s);
            foreach ($lokasi as $idLokasi) {
                DB::table('spesies_lokasi')->insert(['id_spesies' => $id, 'id_lokasi' => $idLokasi]);
            }
        }

        DB::table('kategori_artikel')->insert([
            ['id_kategori' => 1, 'nama_kategori' => 'Edukasi'],
            ['id_kategori' => 2, 'nama_kategori' => 'Lingkungan'],
            ['id_kategori' => 3, 'nama_kategori' => 'Tips'],
        ]);

        DB::table('artikel')->insert($data['artikel']);

        DB::table('jenis_temuan')->insert([
            ['id_jenis' => 1, 'nama_jenis' => 'Perdagangan satwa hidup'],
            ['id_jenis' => 2, 'nama_jenis' => 'Bagian tubuh satwa (kulit, tanduk, gading, dll.)'],
            ['id_jenis' => 3, 'nama_jenis' => 'Tumbuhan atau anggrek langka'],
            ['id_jenis' => 4, 'nama_jenis' => 'Penjualan lewat media sosial atau marketplace'],
            ['id_jenis' => 5, 'nama_jenis' => 'Lainnya'],
        ]);

        // contoh laporan (data dummy untuk demo)
        DB::table('laporan')->insert([
            [
                'id_user' => 2,
                'id_jenis' => 4, 'id_spesies' => 3, 'lokasi_kejadian' => 'Marketplace online, penjual di Samarinda',
                'tanggal_kejadian' => '2026-10-20', 'status_laporan' => 'baru',
                'deskripsi' => 'Akun penjual menawarkan anggrek hitam dalam jumlah banyak tanpa dokumen izin.',
            ],
            [
                'id_user' => null,
                'id_jenis' => 1, 'id_spesies' => 5, 'lokasi_kejadian' => 'Pasar hewan, Medan',
                'tanggal_kejadian' => '2026-10-12', 'status_laporan' => 'diproses',
                'deskripsi' => 'Terlihat anak orangutan dijual di kandang kecil oleh seorang penjual.',
            ],
        ]);

        // soal contoh kuis (bisa diubah admin lewat menu Kuis)
        $this->call(KuisSoalSeeder::class);
    }
}
