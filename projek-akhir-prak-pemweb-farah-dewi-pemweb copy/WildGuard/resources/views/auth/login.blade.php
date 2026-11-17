@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
<h1>Masuk</h1>
<p class="auth-sub">Login hanya untuk admin dan pelapor terdaftar. Membaca informasi dan mengirim laporan tidak perlu login.</p>

<form method="post" action="{{ route('login') }}" class="auth-form">
  @csrf
  <div class="field">
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
    @error('email') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="field">
    <label for="password">Password</label>
    <input id="password" type="password" name="password" autocomplete="current-password" required>
    @error('password') <p class="error">{{ $message }}</p> @enderror
  </div>
  <label class="chk"><input type="checkbox" name="ingat" value="1"> Ingat saya</label>
  <button type="submit" class="btn-primary btn-block">Masuk</button>
</form>

<p class="auth-alt">Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai pelapor</a></p>
@endsection
