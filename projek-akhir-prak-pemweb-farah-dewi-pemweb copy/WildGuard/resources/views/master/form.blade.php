@extends('layouts.app')
@section('title', $cfg['title'])

@section('content')
@php $edit = $row !== null; @endphp
<div class="head"><h1>{{ $edit ? 'Ubah' : 'Tambah' }} {{ $cfg['title'] }}</h1></div>

<form class="card form narrow" method="post"
      action="{{ $edit ? route('master.update', [$t, $row->{$cfg['pk']}]) : route('master.store', $t) }}">
  @csrf
  @if ($edit) @method('PUT') @endif

  @foreach ($cfg['fields'] as $name => $f)
    <div class="field">
      <label>{{ $f[0] }}</label>
      @if ($f[1] === 'select')
        <select name="{{ $name }}">
          @foreach ($f[3] as $opt)
            <option @selected(old($name, $row->$name ?? '') === $opt)>{{ $opt }}</option>
          @endforeach
        </select>
      @else
        <input name="{{ $name }}" value="{{ old($name, $row->$name ?? '') }}">
      @endif
      @error($name) <p class="error">{{ $message }}</p> @enderror
    </div>
  @endforeach

  <div class="actions">
    <button class="btn">Simpan</button>
    <a class="btn ghost" href="{{ route('master.index', $t) }}">Batal</a>
  </div>
</form>
@endsection
