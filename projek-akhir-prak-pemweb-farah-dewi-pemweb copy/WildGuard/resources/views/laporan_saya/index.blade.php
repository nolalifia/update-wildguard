@extends('layouts.app')
@section('title', 'Laporan Saya')

@section('content')
<div class="head">
  <div>
    <h1>Laporan Saya</h1>
    <p class="muted">Halo, {{ auth()->user()->name }}. Laporan bisa diubah atau dihapus selama statusnya masih <b>baru</b>.</p>
  </div>
  <a class="btn" href="{{ route('home') }}#laporan">+ Kirim Laporan Baru</a>
</div>

<div class="card tbl">
  <table>
    <thead><tr><th>Tanggal Kejadian</th><th>Jenis</th><th>Lokasi</th><th>Status</th><th class="r">Aksi</th></tr></thead>
    <tbody>
    @forelse ($rows as $r)
      <tr>
        <td class="nw">{{ $r->tanggal_kejadian->format('d/m/Y') }}</td>
        <td>{{ $r->jenis->nama_jenis }}</td>
        <td>{{ $r->lokasi_kejadian }}<br><span class="muted small">{{ \Illuminate\Support\Str::limit($r->deskripsi, 80) }}</span></td>
        <td><span class="badge b-{{ $r->status_laporan }}">{{ $r->status_laporan }}</span></td>
        <td class="r act">
          @if ($r->status_laporan === 'baru')
            <a href="{{ route('laporan-saya.edit', $r->id_laporan) }}">Ubah</a>
            <form method="post" action="{{ route('laporan-saya.destroy', $r->id_laporan) }}"
                  onsubmit="return confirm('Hapus laporan ini?')">
              @csrf @method('DELETE')
              <button class="link danger">Hapus</button>
            </form>
          @else
            <span class="muted small">Sudah ditangani</span>
          @endif
        </td>
      </tr>
    @empty
      <tr><td colspan="5" class="kosong">Belum ada laporan. Kirim lewat form "Laporkan" saat Anda masuk, maka laporan otomatis tercatat di sini.</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection
