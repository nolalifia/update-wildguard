<?php

namespace App\Http\Controllers;

use App\Http\Concerns\PesanValidasi;
use App\Http\Concerns\SimpanLampiran;
use App\Models\JenisTemuan;
use App\Models\Laporan;
use App\Support\Kembali;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// "Laporan Saya": pelapor terdaftar melihat, mengubah, dan menghapus laporannya sendiri.
// Ubah/hapus hanya boleh selama status masih "baru" (belum ditangani admin).
class LaporanSayaController extends Controller
{
    use PesanValidasi, SimpanLampiran;

    private function milik(Laporan $laporan): Laporan
    {
        abort_unless($laporan->id_user === Auth::id(), 403, 'Ini bukan laporan Anda.');
        return $laporan;
    }

    private function bolehUbah(Laporan $laporan): void
    {
        abort_unless($laporan->status_laporan === 'baru', 403, 'Laporan yang sudah diproses tidak bisa diubah.');
    }

    public function index()
    {
        $rows = Laporan::with(['jenis', 'spesies'])
            ->where('id_user', Auth::id())
            ->orderByDesc('created_at')->get();
        return view('laporan_saya.index', compact('rows'));
    }

    public function edit(Request $request, Laporan $laporan)
    {
        $this->bolehUbah($this->milik($laporan));
        return view('laporan_saya.edit', [
            'kembali' => $request->query('kembali'),
            'row' => $laporan,
            'jenis' => JenisTemuan::orderBy('id_jenis')->get(),
        ]);
    }

    public function update(Request $request, Laporan $laporan)
    {
        $this->bolehUbah($this->milik($laporan));

        $data = $request->validate([
            'id_jenis' => ['required', 'exists:jenis_temuan,id_jenis'],
            'lokasi_kejadian' => ['required', 'min:3', 'max:200'],
            'tanggal_kejadian' => ['required', 'date', 'before_or_equal:today'],
            'deskripsi' => ['required', 'min:20'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ], $this->pesan(), [
            'id_jenis' => 'Jenis temuan', 'lokasi_kejadian' => 'Lokasi kejadian',
            'tanggal_kejadian' => 'Tanggal kejadian', 'deskripsi' => 'Deskripsi', 'lampiran' => 'Lampiran',
        ]);

        if ($request->hasFile('lampiran')) {
            $this->hapusLampiran($laporan->lampiran);
            $data['lampiran'] = $this->simpanLampiran($request->file('lampiran'));
        } else {
            unset($data['lampiran']);
        }

        $laporan->update($data);
        return redirect(Kembali::url($request->input('kembali'), route('laporan-saya.index')))
            ->with('ok', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Request $request, Laporan $laporan)
    {
        $this->bolehUbah($this->milik($laporan));
        $this->hapusLampiran($laporan->lampiran);
        $laporan->delete();
        return redirect(Kembali::url($request->input('kembali'), route('laporan-saya.index')))
            ->with('ok', 'Laporan berhasil dihapus.');
    }
}
