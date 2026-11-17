@extends('layouts.app')
@section('title', 'Ubah Status Laporan')

@section('content')
<div class="head"><h1>Ubah Status Laporan</h1></div>

<form class="card form" method="post" action="{{ route('laporan.update', $row) }}">
  @csrf
  @method('PUT')
  <input type="hidden" name="kembali" value="{{ old('kembali', $kembali ?? '') }}">

  <p class="muted small">Isi laporan hanya bisa dibaca. Admin hanya dapat mengubah status penanganannya.</p>

  <div class="g2">
    <div class="field">
      <label>Jenis Temuan</label>
      <input value="{{ $row->jenis->nama_jenis ?? '-' }}" readonly>
    </div>
    <div class="field">
      <label>Spesies terkait</label>
      <input value="{{ $row->spesies->nama ?? 'Tidak diketahui' }}" readonly>
    </div>
    <div class="field">
      <label>Lokasi Kejadian</label>
      <input value="{{ $row->lokasi_kejadian }}" readonly>
    </div>
    <div class="field">
      <label>Tanggal Kejadian</label>
      <input value="{{ $row->tanggal_kejadian?->format('d/m/Y') }}" readonly>
    </div>
  </div>
  <div class="field">
    <label>Deskripsi</label>
    <textarea rows="5" readonly>{{ $row->deskripsi }}</textarea>
  </div>
  @if ($row->lampiran)
    <p class="small"><a href="{{ asset($row->lampiran) }}" target="_blank" rel="noopener">Lihat lampiran</a></p>
  @endif

  <div class="field">
    <label for="status_laporan">Status Laporan</label>
    <select id="status_laporan" name="status_laporan">
      @foreach (['baru', 'diproses', 'selesai'] as $s)
        <option value="{{ $s }}" @selected(old('status_laporan', $row->status_laporan ?? 'baru') === $s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    @error('status_laporan') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="actions">
    <button class="btn">Simpan status</button>
    <a class="btn ghost" href="{{ \App\Support\Kembali::url(old('kembali', $kembali ?? ''), route('laporan.index')) }}">Batal</a>
  </div>
</form>
@endsection
