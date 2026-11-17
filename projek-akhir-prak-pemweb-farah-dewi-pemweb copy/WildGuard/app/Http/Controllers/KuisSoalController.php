<?php

namespace App\Http\Controllers;

use App\Models\KuisSoal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class KuisSoalController extends Controller
{
    public function index()
    {
        return view('admin.kuis.index', [
            'soalList'    => KuisSoal::orderByDesc('id_soal')->get(),
            'jumlahAktif' => KuisSoal::aktif()->count(),
        ]);
    }

    public function create()
    {
        return view('admin.kuis.form', [
            'soal' => new KuisSoal(['aktif' => true, 'jawaban_benar' => 'a']),
        ]);
    }

    public function store(Request $request)
    {
        KuisSoal::create($this->validasi($request));
        return redirect()->route('kuis-soal.index')->with('ok', 'Soal berhasil ditambahkan.');
    }

    public function edit(KuisSoal $soal)
    {
        return view('admin.kuis.form', compact('soal'));
    }

    public function update(Request $request, KuisSoal $soal)
    {
        $soal->update($this->validasi($request));
        return redirect()->route('kuis-soal.index')->with('ok', 'Soal berhasil diperbarui.');
    }

    public function destroy(KuisSoal $soal)
    {
        $soal->delete();
        return redirect()->route('kuis-soal.index')->with('ok', 'Soal dihapus.');
    }

    public function toggle(KuisSoal $soal)
    {
        $soal->update(['aktif' => ! $soal->aktif]);
        return back()->with('ok', $soal->aktif ? 'Soal diaktifkan.' : 'Soal dinonaktifkan.');
    }

    private function validasi(Request $r): array
    {
        $d = $r->validate([
            'pertanyaan'    => ['required', 'string', 'max:300'],
            'opsi_a'        => ['required', 'string', 'max:200'],
            'opsi_b'        => ['required', 'string', 'max:200'],
            'opsi_c'        => ['nullable', 'string', 'max:200'],
            'opsi_d'        => ['nullable', 'string', 'max:200'],
            'jawaban_benar' => ['required', 'in:a,b,c,d'],
            'penjelasan'    => ['nullable', 'string', 'max:1000'],
            'gambar'        => ['nullable', 'url', 'max:255'],
        ], [
            'pertanyaan.required' => 'Pertanyaan wajib diisi.',
            'opsi_a.required'     => 'Pilihan A wajib diisi.',
            'opsi_b.required'     => 'Pilihan B wajib diisi.',
            'jawaban_benar.required' => 'Tandai salah satu pilihan sebagai jawaban benar.',
            'gambar.url'          => 'Gambar harus berupa alamat URL yang valid (diawali https://).',
        ]);

        if (empty($d['opsi_'.$d['jawaban_benar']])) {
            throw ValidationException::withMessages([
                'jawaban_benar' => 'Jawaban benar harus menunjuk ke pilihan yang terisi.',
            ]);
        }
        if (! empty($d['opsi_d']) && empty($d['opsi_c'])) {
            throw ValidationException::withMessages([
                'opsi_c' => 'Isi pilihan C dulu sebelum mengisi D.',
            ]);
        }

        $d['aktif'] = $r->boolean('aktif');
        return $d;
    }
}