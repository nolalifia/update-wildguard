<?php

namespace App\Http\Controllers;

use App\Http\Concerns\PesanValidasi;
use App\Models\Artikel;
use App\Models\KategoriArtikel;
use App\Support\Kembali;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    use PesanValidasi;

    private function rules(): array
    {
        return [
            'id_kategori' => ['required', 'exists:kategori_artikel,id_kategori'],
            'judul' => ['required', 'max:200'],
            'isi' => ['required'],
            'tanggal' => ['required', 'date'],
            'gambar_url' => ['nullable', 'max:500'],
        ];
    }

    private function attrs(): array
    {
        return [
            'id_kategori' => 'Kategori', 'judul' => 'Judul', 'isi' => 'Isi artikel',
            'tanggal' => 'Tanggal', 'gambar_url' => 'URL gambar',
        ];
    }

    private function formData(Artikel $artikel): array
    {
        return ['row' => $artikel, 'kategoris' => KategoriArtikel::orderBy('nama_kategori')->get()];
    }

    // slug dari judul; ditolak kalau sudah dipakai artikel lain
    private function slugFor(string $judul, ?int $ignoreId = null): string
    {
        $slug = Str::slug($judul);
        $dipakai = Artikel::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id_artikel', '!=', $ignoreId))
            ->exists();
        return $dipakai ? '' : $slug;
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $rows = Artikel::with(['kategori', 'user'])
            ->when($q !== '', fn($qb) => $qb->where('judul', 'like', "%$q%"))
            ->orderByRaw("status = 'menunggu' DESC") // usulan yang menunggu tampil paling atas
            ->orderByDesc('tanggal')->get();
        return view('artikel.index', compact('rows', 'q'));
    }

    public function show(Artikel $artikel)
    {
        $artikel->load('kategori');
        return view('artikel.show', ['row' => $artikel]);
    }

    public function create(Request $request)
    {
        return view('artikel.form', $this->formData(new Artikel(['tanggal' => now()])))
            ->with('kembali', $request->query('kembali'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->pesan(), $this->attrs());
        $slug = $this->slugFor($data['judul']);
        if ($slug === '') {
            return back()->withInput()->withErrors(['judul' => 'Judul sudah dipakai artikel lain.']);
        }
        Artikel::create($data + ['slug' => $slug, 'status' => 'terbit', 'id_user' => Auth::id()]);
        return redirect(Kembali::url($request->input('kembali'), route('artikel.index')))
            ->with('ok', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Request $request, Artikel $artikel)
    {
        return view('artikel.form', $this->formData($artikel))
            ->with('kembali', $request->query('kembali'));
    }

    public function update(Request $request, Artikel $artikel)
    {
        $data = $request->validate($this->rules(), $this->pesan(), $this->attrs());
        $slug = $this->slugFor($data['judul'], $artikel->id_artikel);
        if ($slug === '') {
            return back()->withInput()->withErrors(['judul' => 'Judul sudah dipakai artikel lain.']);
        }
        $artikel->update($data + ['slug' => $slug]);
        return redirect(Kembali::url($request->input('kembali'), route('artikel.index')))
            ->with('ok', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Request $request, Artikel $artikel)
    {
        $artikel->delete();
        return redirect(Kembali::url($request->input('kembali'), route('artikel.index')))
            ->with('ok', 'Artikel berhasil dihapus.');
    }

    // admin menyetujui usulan artikel -> tampil di situs
    public function setujui(Request $request, Artikel $artikel)
    {
        $artikel->update(['status' => 'terbit']);
        return redirect(Kembali::url($request->input('kembali'), route('artikel.index')))
            ->with('ok', 'Artikel disetujui dan sudah tampil di situs.');
    }
}
