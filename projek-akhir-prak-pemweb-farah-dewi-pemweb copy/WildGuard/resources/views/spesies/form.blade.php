@extends('layouts.app')
@section('title', $row->exists ? 'Ubah Spesies' : 'Tambah Spesies')

@section('content')
<div class="head"><h1>{{ $row->exists ? 'Ubah' : 'Tambah' }} Spesies</h1></div>

<form class="card form" method="post"
      action="{{ $row->exists ? route('spesies.update', $row) : route('spesies.store') }}">
  @csrf
  <input type="hidden" name="kembali" value="{{ old('kembali', $kembali ?? '') }}">
  @if ($row->exists) @method('PUT') @endif

  <div class="g2">
    <div class="field">
      <label>Nama</label>
      <input name="nama" value="{{ old('nama', $row->nama) }}">
      @error('nama') <p class="error">{{ $message }}</p> @enderror
    </div>
    <div class="field">
      <label>Nama Latin</label>
      <input name="nama_latin" value="{{ old('nama_latin', $row->nama_latin) }}">
      @error('nama_latin') <p class="error">{{ $message }}</p> @enderror
    </div>
    <div class="field">
      <label>Tipe</label>
      <select name="tipe">
        @foreach (['flora', 'fauna'] as $t)
          <option value="{{ $t }}" @selected(old('tipe', $row->tipe) === $t)>{{ ucfirst($t) }}</option>
        @endforeach
      </select>
      @error('tipe') <p class="error">{{ $message }}</p> @enderror
    </div>
    <div class="field">
      <label>Status Perlindungan</label>
      <select name="id_status">
        <option value="">-- pilih --</option>
        @foreach ($statuses as $s)
          <option value="{{ $s->id_status }}" @selected((int) old('id_status', $row->id_status) === (int) $s->id_status)>
            {{ $s->nama_status }} ({{ $s->sumber }})
          </option>
        @endforeach
      </select>
      @error('id_status') <p class="error">{{ $message }}</p> @enderror
    </div>
    <div class="field">
      <label>Habitat</label>
      <input name="habitat" value="{{ old('habitat', $row->habitat) }}">
      @error('habitat') <p class="error">{{ $message }}</p> @enderror
    </div>
    <div class="field">
      <label>Populasi</label>
      <input name="populasi" value="{{ old('populasi', $row->populasi) }}" placeholder="mis. Sekitar 3.000 ekor">
      @error('populasi') <p class="error">{{ $message }}</p> @enderror
    </div>
  </div>

  <div class="field">
    <label>Lokasi Sebaran <span class="muted small">(boleh lebih dari satu)</span></label>
    <div class="checks">
      @foreach ($lokasis as $l)
        <label class="chk">
          <input type="checkbox" name="lokasi[]" value="{{ $l->id_lokasi }}"
                 @checked(in_array((int) $l->id_lokasi, array_map('intval', $selected), true))>
          {{ $l->nama_lokasi }}
        </label>
      @endforeach
    </div>
  </div>

  <div class="g2">
    <div class="field">
      <label>Ciri Khas <span class="muted small">(untuk flora)</span></label>
      <input name="ciri_khas" value="{{ old('ciri_khas', $row->ciri_khas) }}">
      @error('ciri_khas') <p class="error">{{ $message }}</p> @enderror
    </div>
    <div class="field">
      <label>Ringkasan <span class="muted small">(teks singkat di kartu)</span></label>
      <input name="ringkasan" value="{{ old('ringkasan', $row->ringkasan) }}">
      @error('ringkasan') <p class="error">{{ $message }}</p> @enderror
    </div>
  </div>

  <div class="field">
    <label>Deskripsi</label>
    <textarea name="deskripsi" rows="4">{{ old('deskripsi', $row->deskripsi) }}</textarea>
  </div>
  <div class="field">
    <label>Tahukah Kamu?</label>
    <textarea name="fakta" rows="2">{{ old('fakta', $row->fakta) }}</textarea>
  </div>
  <div class="field">
    <label>URL Gambar</label>
    <input name="gambar_url" value="{{ old('gambar_url', $row->gambar_url) }}">
    @error('gambar_url') <p class="error">{{ $message }}</p> @enderror
  </div>

  <div class="actions">
    <button class="btn">Simpan</button>
    <a class="btn ghost" href="{{ \App\Support\Kembali::url(old('kembali', $kembali ?? ''), route('spesies.index')) }}">Batal</a>
  </div>
</form>
@endsection
