<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\JenisTemuan;
use App\Models\Laporan;
use App\Models\Spesies;
use App\Models\User;

// Dashboard panel admin (/admin)
class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'jFlora' => Spesies::where('tipe', 'flora')->count(),
            'jFauna' => Spesies::where('tipe', 'fauna')->count(),
            'jArtikel' => Artikel::count(),
            'jLaporan' => Laporan::count(),
            'jUser' => User::count(),
            'perJenis' => JenisTemuan::withCount('laporan')->orderByDesc('laporan_count')->get(),
            'terbaru' => Laporan::with('jenis')->orderByDesc('created_at')->take(5)->get(),
        ]);
    }
}
