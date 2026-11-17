@extends('layouts.app')
@section('title', 'Artikel')

@section('content')
<div class="head">
  <h1>Artikel</h1>
  <a class="btn" href="{{ route('artikel.create') }}">+ Tambah Artikel</a>
</div>

<form class="filter" method="get" action="{{ route('artikel.index') }}">
  <input type="search" name="q" value="{{ $q }}" placeholder="Cari judul artikel...">
  <button class="btn ghost">Cari</button>
</form>

<div class="card tbl">
<table>
  <thead><tr><th>#</th><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Status</th><th class="r">Aksi</th></tr></thead>
  <tbody>
  @forelse ($rows as $i => $r)
    <tr>
      <td>{{ $i + 1 }}</td>
      <td><strong>{{ $r->judul }}</strong></td>
      <td><span class="badge">{{ $r->kategori->nama_kategori }}</span></td>
      <td class="nw">{{ $r->tanggal->format('d M Y') }}</td>
      <td><span class="badge {{ $r->status === 'menunggu' ? 'b-baru' : 'b-selesai' }}">{{ $r->status }}</span>@if ($r->user && $r->user->role !== 'admin') <span class="muted small">usulan user</span> @endif</td>
      <td class="r act">
        @if ($r->status === 'menunggu')
          <form method="post" action="{{ route('artikel.setujui', $r) }}">@csrf <button class="link">Setujui</button></form>
        @endif
        <a href="{{ route('artikel.show', $r) }}">Baca</a>
        <a href="{{ route('artikel.edit', $r) }}">Ubah</a>
        <form method="post" action="{{ route('artikel.destroy', $r) }}" onsubmit="return confirm('Hapus artikel ini?')">
          @csrf @method('DELETE')
          <button class="link danger">Hapus</button>
        </form>
      </td>
    </tr>
  @empty
    <tr><td colspan="6" class="kosong">🔍 Hmm, nggak ketemu. Coba kata kunci lain!</td></tr>
  @endforelse
  </tbody>
</table>
</div>
@endsection
