@extends('layouts.app')
@section('title', $row->exists ? 'Ubah Usulan Artikel' : 'Usulkan Artikel')

@section('content')
<div class="head"><h1>{{ $row->exists ? 'Ubah Usulan' : 'Usulkan Artikel' }}</h1></div>

<form class="card form" method="post"
      action="{{ $row->exists ? route('artikel-saya.update', $row->id_artikel) : route('artikel-saya.store') }}">
  @csrf
  @if ($row->exists) @method('PUT') @endif

  <div class="field">
    <label>Judul</label>
    <input name="judul" value="{{ old('judul', $row->judul) }}">
    @error('judul') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="field">
    <label>Kategori</label>
    <select name="id_kategori">
      <option value="">-- pilih --</option>
      @foreach ($kategoris as $k)
        <option value="{{ $k->id_kategori }}" @selected((int) old('id_kategori', $row->id_kategori) === (int) $k->id_kategori)>{{ $k->nama_kategori }}</option>
      @endforeach
    </select>
    @error('id_kategori') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="field">
    <label>Isi Artikel <span class="muted small">(pisahkan paragraf dengan baris kosong)</span></label>
    <textarea name="isi" rows="9">{{ old('isi', $row->isi) }}</textarea>
    @error('isi') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="field">
    <label>URL Gambar <span class="muted small">(opsional)</span></label>
    <input name="gambar_url" value="{{ old('gambar_url', $row->gambar_url) }}">
    @error('gambar_url') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="actions">
    <button class="btn">{{ $row->exists ? 'Simpan' : 'Kirim Usulan' }}</button>
    <a class="btn ghost" href="{{ route('artikel-saya.index') }}">Batal</a>
  </div>
</form>
@endsection
