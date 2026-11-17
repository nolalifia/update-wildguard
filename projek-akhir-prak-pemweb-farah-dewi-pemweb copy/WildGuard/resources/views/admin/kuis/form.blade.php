@extends('layouts.app')
@section('title', $soal->exists ? 'Ubah Soal Kuis' : 'Tambah Soal Kuis')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

@php
  // setelah validasi gagal, centang "aktif" mengikuti isian sebelumnya
  $aktif = session()->hasOldInput() ? (bool) old('aktif') : (bool) $soal->aktif;
  $benar = old('jawaban_benar', $soal->jawaban_benar ?: 'a');
@endphp

<div class="kz-bar">
  <div>
    <h1>{{ $soal->exists ? 'Ubah' : 'Tambah' }} Soal Kuis</h1>
    <p>Isi minimal dua pilihan, lalu tandai satu jawaban yang benar.</p>
  </div>
</div>

@if ($errors->any())
  <div class="kz-alert kz-alert-err" role="alert">
    Soal belum bisa disimpan:
    <ul>
      @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
@endif

<div class="card kz-form-card">
<form class="kz-form" method="post"
      action="{{ $soal->exists ? route('kuis-soal.update', $soal) : route('kuis-soal.store') }}">
  @csrf
  @if ($soal->exists) @method('PUT') @endif

  <div class="kz-field">
    <label class="kz-label" for="pertanyaan">Pertanyaan</label>
    <textarea id="pertanyaan" name="pertanyaan" rows="3" maxlength="300" required>{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
  </div>

  <fieldset class="kz-fieldset">
    <legend class="kz-label">Pilihan jawaban</legend>
    <p class="kz-hint">Klik huruf di kiri untuk menandai jawaban yang benar. Pilihan C dan D boleh dikosongkan.</p>
    <div class="kz-opsi-grid">
      @foreach (['a', 'b', 'c', 'd'] as $k)
        <div class="kz-opsi">
          <label class="kz-radio" title="Jadikan {{ strtoupper($k) }} jawaban benar">
            <input type="radio" name="jawaban_benar" value="{{ $k }}" @checked($benar === $k)>
            <span>{{ strtoupper($k) }}</span>
          </label>
          <input type="text" name="opsi_{{ $k }}" maxlength="200"
                 value="{{ old('opsi_'.$k, $soal->{'opsi_'.$k}) }}"
                 placeholder="Pilihan {{ strtoupper($k) }}{{ in_array($k, ['a', 'b']) ? '' : ' (opsional)' }}"
                 aria-label="Pilihan {{ strtoupper($k) }}" @required(in_array($k, ['a', 'b']))>
        </div>
      @endforeach
    </div>
  </fieldset>

  <div class="kz-field">
    <label class="kz-label" for="penjelasan">Penjelasan <span class="kz-opsional">(opsional)</span></label>
    <textarea id="penjelasan" name="penjelasan" rows="3" maxlength="1000">{{ old('penjelasan', $soal->penjelasan) }}</textarea>
    <p class="kz-hint">Ditampilkan setelah pemain menjawab.</p>
  </div>

  <div class="kz-field">
    <label class="kz-label" for="gambar">URL gambar <span class="kz-opsional">(opsional)</span></label>
    <input type="url" id="gambar" name="gambar" maxlength="255" placeholder="https://..."
           value="{{ old('gambar', $soal->gambar) }}">
  </div>

  <label class="kz-cek">
    <input type="checkbox" name="aktif" value="1" @checked($aktif)>
    Aktif (tampil di kuis)
  </label>

  <div class="kz-form-aksi">
    <button class="kz-btn kz-btn-solid">Simpan</button>
    <a class="kz-btn" href="{{ route('kuis-soal.index') }}">Batal</a>
  </div>
</form>
</div>
@endsection
