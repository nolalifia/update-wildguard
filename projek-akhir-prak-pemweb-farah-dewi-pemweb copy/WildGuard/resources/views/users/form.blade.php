@extends('layouts.app')
@section('title', $row->exists ? 'Ubah Pengguna' : 'Tambah Pengguna')

@section('content')
<div class="head"><h1>{{ $row->exists ? 'Ubah' : 'Tambah' }} Pengguna</h1></div>

<form class="card form narrow" method="post"
      action="{{ $row->exists ? route('users.update', $row->id) : route('users.store') }}">
  @csrf
  @if ($row->exists) @method('PUT') @endif
  <div class="field">
    <label>Nama</label>
    <input name="name" value="{{ old('name', $row->name) }}">
    @error('name') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="field">
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email', $row->email) }}">
    @error('email') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="field">
    <label>Peran</label>
    <select name="role">
      @foreach (['user' => 'User (pelapor)', 'admin' => 'Admin'] as $v => $t)
        <option value="{{ $v }}" @selected(old('role', $roleDefault) === $v)>{{ $t }}</option>
      @endforeach
    </select>
    @error('role') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="field">
    <label>Password @if ($row->exists) <span class="muted small">(kosongkan kalau tidak diganti)</span> @endif</label>
    <input type="password" name="password" autocomplete="new-password">
    @error('password') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="field">
    <label>Ulangi Password</label>
    <input type="password" name="password_confirmation" autocomplete="new-password">
  </div>
  <div class="actions">
    <button class="btn">Simpan</button>
    <a class="btn ghost" href="{{ route('users.index') }}">Batal</a>
  </div>
</form>
@endsection
