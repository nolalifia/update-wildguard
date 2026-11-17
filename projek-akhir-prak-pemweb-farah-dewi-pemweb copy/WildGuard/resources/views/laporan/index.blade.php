@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
<div class="head">
  <h1>Laporan Temuan</h1>
</div>

<form class="filter" method="get" action="{{ route('laporan.index') }}">
  <select name="status" onchange="this.form.submit()">
    <option value="">Semua status</option>
    @foreach (['baru', 'diproses', 'selesai'] as $s)
      <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
    @endforeach
  </select>
</form>

<div class="card tbl">
<table>
  <thead>
    <tr><th>#</th><th>Tanggal</th><th>Lokasi</th><th>Jenis</th><th>Spesies</th><th>Status</th><th class="r">Aksi</th></tr>
  </thead>
  <tbody>
  @forelse ($rows as $i => $r)
    <tr>
      <td>{{ $i + 1 }}</td>
      <td class="nw">{{ $r->tanggal_kejadian->format('d M Y') }}</td>
      <td>{{ $r->lokasi_kejadian }}<br><span class="muted small">{{ \Illuminate\Support\Str::limit($r->deskripsi, 70) }}</span>
        @if ($r->lampiran) <br><a class="small" href="{{ asset($r->lampiran) }}" target="_blank">Lampiran</a> @endif</td>
      <td>{{ $r->jenis->nama_jenis }}</td>
      <td>{{ $r->spesies->nama ?? '-' }}</td>
      <td><span class="badge b-{{ $r->status_laporan }}">{{ $r->status_laporan }}</span></td>
      <td class="r act">
        <a href="{{ route('laporan.edit', $r) }}">Ubah status</a>
        <form method="post" action="{{ route('laporan.destroy', $r) }}" style="display:inline"
              onsubmit="return confirm('Hapus laporan ini secara permanen? Tindakan ini tidak bisa dibatalkan.')">
          @csrf
          @method('DELETE')
          <button type="submit" style="background:none;border:0;padding:0 0 0 12px;font:inherit;color:#B8336A;font-weight:700;cursor:pointer">Hapus</button>
        </form>
      </td>
    </tr>
  @empty
    <tr><td colspan="7" class="muted center">Belum ada laporan.</td></tr>
  @endforelse
  </tbody>
</table>
</div>
@endsection
