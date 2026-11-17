@extends('layouts.app')
@section('title', 'Spesies')

@section('content')
<div class="head">
  <h1>Spesies</h1>
  <a class="btn" href="{{ route('spesies.create') }}">+ Tambah Spesies</a>
</div>

<form class="filter" method="get" action="{{ route('spesies.index') }}">
  <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama / nama latin...">
  <select name="tipe">
    <option value="">Semua tipe</option>
    <option value="flora" @selected($tipe === 'flora')>Flora</option>
    <option value="fauna" @selected($tipe === 'fauna')>Fauna</option>
  </select>
  <button class="btn ghost">Cari</button>
</form>

<div class="card tbl">
<table>
  <thead>
    <tr><th>#</th><th>Nama</th><th>Tipe</th><th>Status</th><th>Lokasi</th><th class="r">Aksi</th></tr>
  </thead>
  <tbody>
  @forelse ($rows as $i => $r)
    <tr>
      <td>{{ $i + 1 }}</td>
      <td><strong>{{ $r->nama }}</strong><br><em class="muted small">{{ $r->nama_latin }}</em></td>
      <td><span class="badge b-{{ $r->tipe }}">{{ $r->tipe }}</span></td>
      <td>{{ $r->status->nama_status }}</td>
      <td>{{ $r->lokasi->pluck('nama_lokasi')->implode(', ') ?: '-' }}</td>
      <td class="r act">
        <a href="{{ route('spesies.show', $r) }}">Detail</a>
        <a href="{{ route('spesies.edit', $r) }}">Ubah</a>
        <form method="post" action="{{ route('spesies.destroy', $r) }}" onsubmit="return confirm('Hapus spesies ini?')">
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
