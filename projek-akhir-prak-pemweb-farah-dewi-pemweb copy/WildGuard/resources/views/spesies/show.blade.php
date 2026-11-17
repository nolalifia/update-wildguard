@extends('layouts.app')
@section('title', $row->nama)

@section('content')
<div class="head">
  <h1>{{ $row->nama }}</h1>
  <a class="btn ghost" href="{{ route('spesies.index') }}">&larr; Kembali</a>
</div>

<div class="card detail">
  @if ($row->gambar_url)
    <img src="{{ $row->gambar_url }}" alt="{{ $row->nama }}" referrerpolicy="no-referrer">
  @endif
  <div>
    <p><em>{{ $row->nama_latin }}</em></p>
    <p>
      <span class="badge b-{{ $row->tipe }}">{{ $row->tipe }}</span>
      <span class="badge">{{ $row->status->nama_status }} · {{ $row->status->sumber }}</span>
    </p>
    <dl class="kv">
      <dt>Habitat</dt><dd>{{ $row->habitat ?? '-' }}</dd>
      <dt>Populasi</dt><dd>{{ $row->populasi ?? '-' }}</dd>
      <dt>Ciri khas</dt><dd>{{ $row->ciri_khas ?? '-' }}</dd>
      <dt>Lokasi</dt><dd>{{ $row->lokasi->pluck('nama_lokasi')->implode(', ') ?: '-' }}</dd>
    </dl>
    <h3>Deskripsi</h3>
    <p>{!! nl2br(e($row->deskripsi ?? '-')) !!}</p>
    @if ($row->fakta)
      <div class="tip"><strong>Tahukah Kamu?</strong><br>{{ $row->fakta }}</div>
    @endif
  </div>
</div>
@endsection
