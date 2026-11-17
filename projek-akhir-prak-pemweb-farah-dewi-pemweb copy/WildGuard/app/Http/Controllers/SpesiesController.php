<?php

namespace App\Http\Controllers;

use App\Http\Concerns\PesanValidasi;
use App\Models\Lokasi;
use App\Models\Spesies;
use App\Models\StatusPerlindungan;
use App\Support\Kembali;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SpesiesController extends Controller
{
    use PesanValidasi;

    private function rules(?Spesies $spesies = null): array
    {
        return [
            'nama' => ['required', 'max:100'],
            'nama_latin' => [
                'required', 'max:150',
                Rule::unique('spesies', 'nama_latin')->ignore($spesies?->id_spesies, 'id_spesies'),
            ],
            'tipe' => ['required', Rule::in(['flora', 'fauna'])],
            'id_status' => ['required', 'exists:status_perlindungan,id_status'],
            'habitat' => ['nullable', 'max:150'],
            'populasi' => ['nullable', 'max:80'],
            'ciri_khas' => ['nullable', 'max:150'],
            'ringkasan' => ['nullable', 'max:255'],
            'deskripsi' => ['nullable'],
            'fakta' => ['nullable'],
            'gambar_url' => ['nullable', 'max:500'],
            'lokasi' => ['nullable', 'array'],
            'lokasi.*' => ['exists:lokasi,id_lokasi'],
        ];
    }

    private function attrs(): array
    {
        return [
            'nama' => 'Nama', 'nama_latin' => 'Nama latin', 'tipe' => 'Tipe',
            'id_status' => 'Status perlindungan', 'habitat' => 'Habitat',
            'populasi' => 'Populasi', 'ciri_khas' => 'Ciri khas', 'ringkasan' => 'Ringkasan',
            'gambar_url' => 'URL gambar',
        ];
    }

    private function formData(Spesies $spesies): array
    {
        return [
            'row' => $spesies,
            'statuses' => StatusPerlindungan::orderBy('nama_status')->get(),
            'lokasis' => Lokasi::orderBy('nama_lokasi')->get(),
            'selected' => old('lokasi', $spesies->exists ? $spesies->lokasi->pluck('id_lokasi')->all() : []),
        ];
    }

    // READ: daftar + cari + filter
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $tipe = $request->query('tipe', '');

        $rows = Spesies::with(['status', 'lokasi'])
            ->when($q !== '', fn($qb) => $qb->where(
                fn($w) => $w->where('nama', 'like', "%$q%")->orWhere('nama_latin', 'like', "%$q%")
            ))
            ->when(in_array($tipe, ['flora', 'fauna'], true), fn($qb) => $qb->where('tipe', $tipe))
            ->orderBy('tipe')->orderBy('nama')
            ->get();

        return view('spesies.index', compact('rows', 'q', 'tipe'));
    }

    // READ: detail
    public function show(Spesies $spesies)
    {
        $spesies->load(['status', 'lokasi']);
        return view('spesies.show', ['row' => $spesies]);
    }

    // CREATE
    public function create(Request $request)
    {
        $tipe = $request->query('tipe') === 'flora' ? 'flora' : 'fauna';
        return view('spesies.form', $this->formData(new Spesies(['tipe' => $tipe])))
            ->with('kembali', $request->query('kembali', $request->query('tipe')));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->pesan(), $this->attrs());
        unset($data['kembali']);

        DB::transaction(function () use ($data) {
            $lokasi = $data['lokasi'] ?? [];
            unset($data['lokasi']);
            $spesies = Spesies::create($data);
            $spesies->lokasi()->sync($lokasi);
        });

        return redirect(Kembali::url($request->input('kembali'), route('spesies.index')))
            ->with('ok', 'Spesies berhasil ditambahkan.');
    }

    // UPDATE
    public function edit(Request $request, Spesies $spesies)
    {
        return view('spesies.form', $this->formData($spesies))
            ->with('kembali', $request->query('kembali'));
    }

    public function update(Request $request, Spesies $spesies)
    {
        $data = $request->validate($this->rules($spesies), $this->pesan(), $this->attrs());

        DB::transaction(function () use ($data, $spesies) {
            $lokasi = $data['lokasi'] ?? [];
            unset($data['lokasi']);
            $spesies->update($data);
            $spesies->lokasi()->sync($lokasi);
        });

        return redirect(Kembali::url($request->input('kembali'), route('spesies.index')))
            ->with('ok', 'Spesies berhasil diperbarui.');
    }

    // DELETE
    public function destroy(Request $request, Spesies $spesies)
    {
        $spesies->delete();
        return redirect(Kembali::url($request->input('kembali'), route('spesies.index')))
            ->with('ok', 'Spesies berhasil dihapus.');
    }
}
