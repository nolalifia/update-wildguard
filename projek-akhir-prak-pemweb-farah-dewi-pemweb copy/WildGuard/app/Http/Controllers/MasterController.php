<?php

namespace App\Http\Controllers;

use App\Http\Concerns\PesanValidasi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

// CRUD untuk 4 tabel master dengan satu controller (dikonfigurasi per tabel)
class MasterController extends Controller
{
    use PesanValidasi;

    private function config(string $type): array
    {
        $all = [
            'status' => [
                'table' => 'status_perlindungan', 'pk' => 'id_status', 'title' => 'Status Perlindungan',
                'order' => 'nama_status',
                'fields' => [
                    'nama_status' => ['Nama Status', 'text', ['required', 'max:60']],
                    'sumber' => ['Sumber', 'select', ['required', Rule::in(['Nasional', 'IUCN', 'CITES'])], ['Nasional', 'IUCN', 'CITES']],
                    'keterangan' => ['Keterangan', 'text', ['nullable', 'max:255']],
                ],
            ],
            'lokasi' => [
                'table' => 'lokasi', 'pk' => 'id_lokasi', 'title' => 'Lokasi Sebaran', 'order' => 'nama_lokasi',
                'fields' => [
                    'nama_lokasi' => ['Nama Lokasi', 'text', ['required', 'max:100']],
                    'pulau' => ['Pulau', 'text', ['required', 'max:60']],
                ],
            ],
            'kategori' => [
                'table' => 'kategori_artikel', 'pk' => 'id_kategori', 'title' => 'Kategori Artikel',
                'order' => 'nama_kategori',
                'fields' => ['nama_kategori' => ['Nama Kategori', 'text', ['required', 'max:60']]],
            ],
            'jenis' => [
                'table' => 'jenis_temuan', 'pk' => 'id_jenis', 'title' => 'Jenis Temuan', 'order' => 'id_jenis',
                'fields' => ['nama_jenis' => ['Nama Jenis Temuan', 'text', ['required', 'max:100']]],
            ],
        ];
        abort_unless(isset($all[$type]), 404);
        return $all[$type];
    }

    // aturan validasi dari config; kolom pertama harus unik
    private function rules(array $cfg, ?int $ignoreId = null): array
    {
        $rules = [];
        $first = array_key_first($cfg['fields']);
        foreach ($cfg['fields'] as $name => $f) {
            $rules[$name] = $f[2];
            if ($name === $first) {
                $rules[$name][] = Rule::unique($cfg['table'], $name)->ignore($ignoreId, $cfg['pk']);
            }
        }
        return $rules;
    }

    private function attrs(array $cfg): array
    {
        return array_map(fn($f) => $f[0], $cfg['fields']);
    }

    public function index(string $type)
    {
        $cfg = $this->config($type);
        $rows = DB::table($cfg['table'])->orderBy($cfg['order'])->get();
        return view('master.index', ['t' => $type, 'cfg' => $cfg, 'rows' => $rows]);
    }

    public function create(string $type)
    {
        return view('master.form', ['t' => $type, 'cfg' => $this->config($type), 'row' => null]);
    }

    public function store(Request $request, string $type)
    {
        $cfg = $this->config($type);
        $data = $request->validate($this->rules($cfg), $this->pesan(), $this->attrs($cfg));
        DB::table($cfg['table'])->insert($data);
        return redirect()->route('master.index', $type)->with('ok', $cfg['title'] . ' berhasil ditambahkan.');
    }

    public function edit(string $type, int $id)
    {
        $cfg = $this->config($type);
        $row = DB::table($cfg['table'])->where($cfg['pk'], $id)->first();
        abort_unless($row, 404);
        return view('master.form', ['t' => $type, 'cfg' => $cfg, 'row' => $row]);
    }

    public function update(Request $request, string $type, int $id)
    {
        $cfg = $this->config($type);
        $data = $request->validate($this->rules($cfg, $id), $this->pesan(), $this->attrs($cfg));
        DB::table($cfg['table'])->where($cfg['pk'], $id)->update($data);
        return redirect()->route('master.index', $type)->with('ok', $cfg['title'] . ' berhasil diperbarui.');
    }

    public function destroy(string $type, int $id)
    {
        $cfg = $this->config($type);
        try {
            DB::table($cfg['table'])->where($cfg['pk'], $id)->delete();
        } catch (QueryException $e) {
            // FK RESTRICT: data masih dipakai tabel lain
            return redirect()->route('master.index', $type)
                ->with('err', 'Data tidak bisa dihapus karena masih dipakai oleh data lain.');
        }
        return redirect()->route('master.index', $type)->with('ok', 'Data berhasil dihapus.');
    }
}
