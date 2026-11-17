<?php

namespace App\Http\Controllers;

use App\Http\Concerns\PesanValidasi;
use App\Models\Laporan;
use App\Support\Kembali;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// Laporan di panel admin: hanya melihat daftar dan mengubah status.
// Laporan dibuat oleh pelapor lewat formulir publik, dan dihapus/diubah isinya
// oleh pelapor itu sendiri lewat "Laporan Saya".
class LaporanController extends Controller
{
    use PesanValidasi;

    public function index(Request $request)
    {
        $status = $request->query('status', '');
        $rows = Laporan::with(['jenis', 'spesies'])
            ->when(in_array($status, ['baru', 'diproses', 'selesai'], true),
                fn($q) => $q->where('status_laporan', $status))
            ->orderByDesc('created_at')->get();
        return view('laporan.index', compact('rows', 'status'));
    }

    public function edit(Request $request, Laporan $laporan)
    {
        $laporan->load(['jenis', 'spesies']);
        return view('laporan.form', ['row' => $laporan])
            ->with('kembali', $request->query('kembali'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        // Hanya kolom status yang boleh diubah oleh admin
        $data = $request->validate([
            'status_laporan' => ['required', Rule::in(['baru', 'diproses', 'selesai'])],
        ], $this->pesan(), ['status_laporan' => 'Status laporan']);

        $laporan->update(['status_laporan' => $data['status_laporan']]);

        return redirect(Kembali::url($request->input('kembali'), route('laporan.index')))
            ->with('ok', 'Status laporan berhasil diperbarui.');
    }

    public function destroy(Laporan $laporan)
    {
        // hapus file lampiran juga supaya tidak jadi sampah di server
        if ($laporan->lampiran) {
            \Illuminate\Support\Facades\File::delete(public_path($laporan->lampiran));
        }

        $laporan->delete();

        return back()->with('ok', 'Laporan berhasil dihapus.');
    }
}