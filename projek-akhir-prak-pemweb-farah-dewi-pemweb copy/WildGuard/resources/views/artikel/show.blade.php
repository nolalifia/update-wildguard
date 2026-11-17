@extends('layouts.app')
@section('title', $row->judul)

@section('content')
<div class="head">
  <h1>{{ $row->judul }}</h1>
  <a class="btn ghost" href="{{ route('artikel.index') }}">&larr; Kembali</a>
</div>
<div class="card detail one">
  @if ($row->gambar_url)
    <img src="{{ $row->gambar_url }}" alt="" referrerpolicy="no-referrer">
  @endif
  <p class="muted">
    <span class="badge">{{ $row->kategori->nama_kategori }}</span>
    {{ $row->tanggal->format('d F Y') }}
  </p>
  <p>{!! nl2br(e($row->isi)) !!}</p>
</div>
@endsection
