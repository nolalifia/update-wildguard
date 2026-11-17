@extends('layouts.app')
@section('title', 'Kelola Kuis')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="kz-bar">
  <div>
    <h1>Kelola Kuis</h1>
    <p>Soal yang aktif akan muncul di kuis halaman utama.</p>
  </div>
  <a class="kz-btn kz-btn-solid" href="{{ route('kuis-soal.create') }}">+ Tambah Soal</a>
</div>

<p class="kz-info">
  <strong>{{ $jumlahAktif }}</strong> soal aktif dari {{ $soalList->count() }} soal.
  @if ($jumlahAktif === 0)
    Belum ada soal aktif, jadi kuis di halaman utama memakai soal acak dari data spesies.
  @endif
</p>

<div class="card tbl kz-scroll">
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Soal</th>
      <th>Jawaban benar</th>
      <th>Status</th>
      <th class="r">Aksi</th>
    </tr>
  </thead>
  <tbody>
  @forelse ($soalList as $i => $s)
    @php $h = strtoupper($s->jawaban_benar); @endphp
    <tr>
      <td>{{ $i + 1 }}</td>
      <td>
        <div class="kz-soal-cell">
          @if ($s->gambar)
            <img class="kz-thumb" src="{{ $s->gambar }}" alt="" loading="lazy" onerror="this.remove()">
          @endif
          <span>{{ $s->pertanyaan }}</span>
        </div>
      </td>
      <td><span class="kz-jawab" aria-hidden="true">{{ $h }}</span>{{ $s->{'opsi_'.$s->jawaban_benar} }}</td>
      <td>
        @if ($s->aktif)
          <span class="badge">Aktif</span>
        @else
          <span class="badge kz-badge-off">Nonaktif</span>
        @endif
      </td>
      <td class="r">
        <div class="kz-aksi">
          <form method="post" action="{{ route('kuis-soal.toggle', $s) }}">
            @csrf @method('PATCH')
            <button class="kz-btn kz-btn-sm">{{ $s->aktif ? 'Nonaktifkan' : 'Aktifkan' }}</button>
          </form>
          <a class="kz-btn kz-btn-sm" href="{{ route('kuis-soal.edit', $s) }}">Ubah</a>
          <form method="post" action="{{ route('kuis-soal.destroy', $s) }}" onsubmit="return confirm('Hapus soal ini?')">
            @csrf @method('DELETE')
            <button class="kz-btn kz-btn-sm kz-btn-danger">Hapus</button>
          </form>
        </div>
      </td>
    </tr>
  @empty
    <tr><td colspan="5" class="kosong">Belum ada soal. Tambah dulu yuk!</td></tr>
  @endforelse
  </tbody>
</table>
</div>
@endsection
