<?php

namespace App\Http\Controllers;

use App\Http\Concerns\PesanValidasi;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

// "Artikel Saya": user mengusulkan artikel. Status awal "menunggu"; baru tampil
// di situs setelah disetujui admin. User hanya bisa ubah/hapus usulannya sendiri
// selama masih menunggu.
class ArtikelSayaController extends Controller
{
    use PesanValidasi;

    private function rules(): array
    {
        return [
            'id_kategori' => ['required', 'exists:kategori_artikel,id_kategori'],
            'judul' => ['required', 'min:5', 'max:200'],
            'isi' => ['required', 'min:50'],
            'gambar_url' => ['nullable', 'url', 'max:500'],
        ];
    }

    private function attrs(): array
    {
        return ['id_kategori' => 'Kategori', 'judul' => 'Judul', 'isi' => 'Isi artikel', 'gambar_url' => 'URL gambar'];
    }

    private function milik(Artikel $artikel): Artikel
    {
        abort_unless($artikel->id_user === Auth::id(), 403, 'Ini bukan artikel Anda.');
        abort_unless($artikel->status === 'menunggu', 403, 'Artikel yang sudah terbit hanya bisa diubah admin.');
        return $artikel;
    }

    private function slugFor(string $judul, ?int $ignoreId = null): string
    {
        $slug = Str::slug($judul);
        $dipakai = Artikel::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id_artikel', '!=', $ignoreId))
            ->exists();
        return $dipakai ? '' : $slug;
    }

    public function index()
    {
        $rows = Artikel::with('kategori')->where('id_user', Auth::id())
            ->orderByDesc('tanggal')->orderByDesc('id_artikel')->get();
        return view('artikel_saya.index', compact('rows'));
    }

    public function create()
    {
        return view('artikel_saya.form', [
            'row' => new Artikel(), 'kategoris' => KategoriArtikel::orderBy('nama_kategori')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->pesan(), $this->attrs());
        $slug = $this->slugFor($data['judul']);
        if ($slug === '') {
            return back()->withInput()->withErrors(['judul' => 'Judul sudah dipakai artikel lain.']);
        }
        Artikel::create($data + [
            'slug' => $slug, 'tanggal' => now()->toDateString(),
            'status' => 'menunggu', 'id_user' => Auth::id(),
        ]);
        return redirect()->route('artikel-saya.index')
            ->with('ok', 'Usulan artikel terkirim. Artikel tampil di situs setelah disetujui admin.');
    }

    public function edit(Artikel $artikel)
    {
        return view('artikel_saya.form', [
            'row' => $this->milik($artikel), 'kategoris' => KategoriArtikel::orderBy('nama_kategori')->get(),
        ]);
    }

    public function update(Request $request, Artikel $artikel)
    {
        $this->milik($artikel);
        $data = $request->validate($this->rules(), $this->pesan(), $this->attrs());
        $slug = $this->slugFor($data['judul'], $artikel->id_artikel);
        if ($slug === '') {
            return back()->withInput()->withErrors(['judul' => 'Judul sudah dipakai artikel lain.']);
        }
        $artikel->update($data + ['slug' => $slug]);
        return redirect()->route('artikel-saya.index')->with('ok', 'Usulan artikel berhasil diperbarui.');
    }

    public function destroy(Artikel $artikel)
    {
        $this->milik($artikel)->delete();
        return redirect()->route('artikel-saya.index')->with('ok', 'Usulan artikel berhasil dihapus.');
    }
}
