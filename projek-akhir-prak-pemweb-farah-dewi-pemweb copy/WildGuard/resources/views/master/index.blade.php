@extends('layouts.app')
@section('title', $cfg['title'])

@section('content')
<div class="head">
  <h1>{{ $cfg['title'] }}</h1>
  <a class="btn" href="{{ route('master.create', $t) }}">+ Tambah</a>
</div>

<div class="card tbl">
<table>
  <thead>
    <tr>
      <th>#</th>
      @foreach ($cfg['fields'] as $f) <th>{{ $f[0] }}</th> @endforeach
      <th class="r">Aksi</th>
    </tr>
  </thead>
  <tbody>
  @forelse ($rows as $i => $r)
    @php $id = $r->{$cfg['pk']}; @endphp
    <tr>
      <td>{{ $i + 1 }}</td>
      @foreach ($cfg['fields'] as $name => $f) <td>{{ $r->$name ?? '-' }}</td> @endforeach
      <td class="r act">
        <a href="{{ route('master.edit', [$t, $id]) }}">Ubah</a>
        <form method="post" action="{{ route('master.destroy', [$t, $id]) }}" onsubmit="return confirm('Hapus data ini?')">
          @csrf @method('DELETE')
          <button class="link danger">Hapus</button>
        </form>
      </td>
    </tr>
  @empty
    <tr><td colspan="{{ count($cfg['fields']) + 2 }}" class="kosong">🍃 Belum ada data. Tambah dulu yuk!</td></tr>
  @endforelse
  </tbody>
</table>
</div>
@endsection
