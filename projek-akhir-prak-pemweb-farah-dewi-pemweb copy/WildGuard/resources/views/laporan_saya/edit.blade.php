@extends('layouts.app')
@section('title', 'Ubah Laporan')

@section('content')
<div class="head"><h1>Ubah Laporan</h1></div>

<form class="card form" method="post" enctype="multipart/form-data" action="{{ route('laporan-saya.update', $row->id_laporan) }}">
  @csrf @method('PUT')
  <input type="hidden" name="kembali" value="{{ old('kembali', $kembali ?? '') }}">
  <div class="g2">
    <div class="field">
      <label>Jenis Temuan</label>
      <select name="id_jenis">
        @foreach ($jenis as $j)
          <option value="{{ $j->id_jenis }}" @selected((int) old('id_jenis', $row->id_jenis) === (int) $j->id_jenis)>{{ $j->nama_jenis }}</option>
        @endforeach
      </select>
      @error('id_jenis') <p class="error">{{ $message }}</p> @enderror
    </div>
    <div class="field">
      <label>Tanggal Kejadian</label>
      <input type="date" name="tanggal_kejadian" max="{{ now()->format('Y-m-d') }}"
             value="{{ old('tanggal_kejadian', $row->tanggal_kejadian->format('Y-m-d')) }}">
      @error('tanggal_kejadian') <p class="error">{{ $message }}</p> @enderror
    </div>
  </div>
  <div class="field">
    <label>Lokasi Kejadian</label>
    <input name="lokasi_kejadian" value="{{ old('lokasi_kejadian', $row->lokasi_kejadian) }}">
    @error('lokasi_kejadian') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="field">
    <label>Deskripsi</label>
    <textarea name="deskripsi" rows="5">{{ old('deskripsi', $row->deskripsi) }}</textarea>
    @error('deskripsi') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="field">
    <label>Lampiran <span class="muted small">(kosongkan kalau tidak diganti; jpg/png/webp/pdf, maks 5 MB)</span></label>
    @if ($row->lampiran)
      <p class="small">Saat ini: <a href="{{ asset($row->lampiran) }}" target="_blank">lihat lampiran</a></p>
    @endif
    <input type="file" name="lampiran" accept="image/*,.pdf">
    @error('lampiran') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="actions">
    <button class="btn">Simpan</button>
    <a class="btn ghost" href="{{ \App\Support\Kembali::url(old('kembali', $kembali ?? ''), route('laporan-saya.index')) }}">Batal</a>
  </div>
</form>
@endsection
