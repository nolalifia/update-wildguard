@extends('layouts.app')
@section('title', 'Pengguna')

@section('content')
<div class="head">
  <h1>Pengguna</h1>
  <a class="btn" href="{{ route('users.create') }}">+ Tambah Pengguna</a>
</div>

<div class="card tbl">
  <table>
    <thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Laporan</th><th class="r">Aksi</th></tr></thead>
    <tbody>
    @foreach ($rows as $r)
      <tr>
        <td>{{ $r->name }} @if ($r->id === auth()->id()) <span class="muted small">(Anda)</span> @endif</td>
        <td>{{ $r->email }}</td>
        <td><span class="badge {{ $r->role === 'admin' ? 'b-fauna' : 'b-flora' }}">{{ $r->role }}</span></td>
        <td>{{ $jumlah[$r->id] ?? 0 }}</td>
        <td class="r act">
          <a href="{{ route('users.edit', $r->id) }}">Ubah</a>
          @if ($r->id !== auth()->id())
            <form method="post" action="{{ route('users.destroy', $r->id) }}"
                  onsubmit="return confirm('Hapus pengguna ini? Laporannya tetap tersimpan.')">
              @csrf @method('DELETE')
              <button class="link danger">Hapus</button>
            </form>
          @endif
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection
