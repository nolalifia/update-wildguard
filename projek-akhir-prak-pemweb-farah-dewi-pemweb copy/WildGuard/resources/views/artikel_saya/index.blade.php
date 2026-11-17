@extends('layouts.app')
@section('title', 'Artikel Saya')

@section('content')
<div class="head">
  <div>
    <h1>Artikel Saya</h1>
    <p class="muted">Usulan artikel tampil di situs setelah disetujui admin. Selama <b>menunggu</b>, masih bisa diubah atau dihapus.</p>
  </div>
  <a class="btn" href="{{ route('artikel-saya.create') }}">+ Usulkan Artikel</a>
</div>

<div class="card tbl">
  <table>
    <thead><tr><th>Judul</th><th>Kategori</th><th>Tanggal</th><th>Status</th><th class="r">Aksi</th></tr></thead>
    <tbody>
    @forelse ($rows as $r)
      <tr>
        <td><strong>{{ $r->judul }}</strong></td>
        <td><span class="badge">{{ $r->kategori->nama_kategori }}</span></td>
        <td class="nw">{{ $r->tanggal->format('d/m/Y') }}</td>
        <td><span class="badge {{ $r->status === 'menunggu' ? 'b-baru' : 'b-selesai' }}">{{ $r->status }}</span></td>
        <td class="r act">
          @if ($r->status === 'menunggu')
            <a href="{{ route('artikel-saya.edit', $r->id_artikel) }}">Ubah</a>
            <form method="post" action="{{ route('artikel-saya.destroy', $r->id_artikel) }}" onsubmit="return confirm('Hapus usulan ini?')">
              @csrf @method('DELETE')
              <button class="link danger">Hapus</button>
            </form>
          @else
            <span class="muted small">Sudah terbit</span>
          @endif
        </td>
      </tr>
    @empty
      <tr><td colspan="5" class="kosong">Belum ada usulan artikel. Yuk menulis!</td></tr>
    @endforelse
    </tbody>
  </table>
</div>
@endsection
