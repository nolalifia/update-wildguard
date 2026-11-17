<?php

namespace App\Http\Controllers;

use App\Http\Concerns\PesanValidasi;
use App\Http\Concerns\SimpanLampiran;
use App\Models\Artikel;
use App\Models\JenisTemuan;
use App\Models\KategoriArtikel;
use App\Models\KuisSoal;
use App\Models\Laporan;
use App\Models\Spesies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// Halaman publik WildGuard: tampilan sama dengan proyek Pemrograman Web,
// tetapi isinya dibaca dari database.
class HomeController extends Controller
{
    use PesanValidasi, SimpanLampiran;

    public function index()
    {
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';
        $spesies = Spesies::with(['status', 'lokasi'])->orderBy('id_spesies')->get();
        $artikel = Artikel::terbit()->with('kategori')->orderByDesc('tanggal')->get();

        // pita nama latin: selang-seling flora dan fauna
        $flora = $spesies->where('tipe', 'flora')->values();
        $fauna = $spesies->where('tipe', 'fauna')->values();
        $ticker = collect();
        for ($i = 0; $i < max($flora->count(), $fauna->count()); $i++) {
            if (isset($flora[$i])) $ticker->push($flora[$i]);
            if (isset($fauna[$i])) $ticker->push($fauna[$i]);
        }

        $wg = [
            'admin' => $isAdmin,
            'flora' => $flora->map(fn($s) => $this->spesiesToArray($s, $isAdmin))->all(),
            'fauna' => $fauna->map(fn($s) => $this->spesiesToArray($s, $isAdmin))->all(),
            // isi artikel dipecah per paragraf (dipisah baris kosong)
            'artikel' => $artikel->mapWithKeys(fn($a) => [
                $a->slug => preg_split('/\R\s*\R/', trim($a->isi)),
            ])->all(),
            // soal kuis buatan admin (hanya yang aktif); kosong = kuis pakai soal acak dari spesies
            'kuis' => KuisSoal::aktif()->orderBy('id_soal')->get()->map(fn($k) => [
                'soal'       => $k->pertanyaan,
                'opsi'       => $k->daftarOpsi(),
                'benar'      => $k->indeksBenar(),
                'penjelasan' => $k->penjelasan ?? '',
                'img'        => $k->gambar ?? '',
            ])->all(),
        ];

        return view('welcome', [
            'jFlora' => $flora->count(),
            'jFauna' => $fauna->count(),
            'ticker' => $ticker,
            'artikel' => $artikel,
            'kategori' => KategoriArtikel::orderBy('id_kategori')->get(),
            'jenisTemuan' => JenisTemuan::orderBy('id_jenis')->get(),
            'wg' => $wg,
            'isAdmin' => $isAdmin,
            // admin: semua laporan; user: laporan miliknya; pengunjung: tidak ada
            'daftarLaporan' => Auth::check()
                ? Laporan::with(['jenis', 'spesies'])
                    ->when(!$isAdmin, fn($q) => $q->where('id_user', Auth::id()))
                    ->orderByDesc('created_at')->take(50)->get()
                : collect(),
        ]);
    }

    // bentuk data yang dibaca script.js (kartu + modal detail)
    private function spesiesToArray(Spesies $s, bool $isAdmin = false): array
    {
        $info = [
            $s->tipe === 'fauna' ? 'Status IUCN' : 'Status' => $s->status->nama_status,
            'Lokasi' => $s->lokasi->pluck('nama_lokasi')->implode(', ') ?: null,
            'Habitat' => $s->habitat,
            ($s->tipe === 'fauna' ? 'Populasi' : 'Ciri Khas') => $s->tipe === 'fauna' ? $s->populasi : $s->ciri_khas,
        ];

        // tombol Ubah/Hapus hanya dikirim ke admin
        $admin = $isAdmin ? [
            'tipe' => $s->tipe,
            'edit_url' => route('spesies.edit', ['spesies' => $s->id_spesies, 'kembali' => $s->tipe]),
            'hapus_url' => route('spesies.destroy', $s->id_spesies),
        ] : [];

        return $admin + [
            'id' => 'sp-' . $s->id_spesies,
            'nama' => $s->nama,
            'latin' => $s->nama_latin,
            'status' => $s->status->nama_status,
            'img' => $s->gambar_url,
            'short' => $s->ringkasan ?? Str::limit((string) $s->deskripsi, 110),
            'info' => array_filter($info, fn($v) => $v !== null && $v !== ''),
            'desc' => $s->deskripsi ?? '-',
            'tip' => $s->fakta ?? '-',
        ];
    }

    // form "Laporkan Temuan" di halaman publik -> tabel laporan
    public function kirimLaporan(Request $request)
    {
        $data = $request->validate([
            'latitude'  => ['nullable', 'numeric', 'between:-90,90', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180', 'required_with:latitude'],
            'jenis' => ['required', 'exists:jenis_temuan,id_jenis'],
            'lokasi' => ['required', 'min:3', 'max:200'],
            'waktu' => ['required', 'date', 'before_or_equal:today'],
            'deskripsi' => ['required', 'min:20'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ], $this->pesan(), [
            'jenis' => 'Jenis temuan', 'lokasi' => 'Lokasi kejadian', 'waktu' => 'Waktu kejadian',
            'deskripsi' => 'Deskripsi', 'lampiran' => 'Lampiran',
        ]);

        $path = $this->simpanLampiran($request->file('lampiran'));

        Laporan::create([
            'id_user' => Auth::id(), // NULL kalau pengunjung belum login
            'id_jenis' => $data['jenis'],
            'lokasi_kejadian' => $data['lokasi'],
            'tanggal_kejadian' => $data['waktu'],
            'deskripsi' => $data['deskripsi'],
            'lampiran' => $path,
            'status_laporan' => 'baru',
            'latitude' => $data['latitude'] ?? null,    // <-- tambah
            'longitude' => $data['longitude'] ?? null,  // <-- tambah
        ]);

        return redirect(route('home') . '#laporan')->with('laporan_ok', true);
    }
    
    // data titik untuk peta publik (koordinat dibulatkan di server demi keamanan pelapor)
    public function sebaran()
    {
        return Laporan::with('jenis')
            ->whereNotNull('latitude')->whereNotNull('longitude')
            ->where('status_laporan', '!=', 'baru')
            ->latest()->limit(500)->get()
            ->map(fn ($l) => [
                'lat'     => round($l->latitude, 2),
                'lng'     => round($l->longitude, 2),
                'jenis'   => $l->jenis->nama_jenis ?? 'Temuan',
                'tanggal' => $l->tanggal_kejadian->locale('id')->translatedFormat('F Y'),
                'status'  => $l->status_laporan,
            ]);
    }
}
