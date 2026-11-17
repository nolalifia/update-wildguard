@extends('layouts.auth')
@section('title', 'Daftar')

@section('content')
<h1>Daftar Akun Pelapor</h1>
<p class="auth-sub">Dengan akun, Anda bisa melihat, mengubah, dan menghapus laporan yang pernah Anda kirim.</p>

<form method="post" action="{{ route('register') }}" class="auth-form">
  @csrf
  <div class="field">
    <label for="name">Nama</label>
    <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" minlength="3" maxlength="100" required autofocus>
    @error('name') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="field">
    <label for="email">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required>
    @error('email') <p class="error">{{ $message }}</p> @enderror
  </div>
  <div class="field">
    <label for="password">Password</label>
    <input id="password" type="password" name="password" autocomplete="new-password" maxlength="72" required>
    @foreach ($errors->get('password') as $pesan) <p class="error">{{ $pesan }}</p> @endforeach
    <ul class="pw-cek" id="pwCek" aria-live="polite">
      <li data-r="len">Minimal 8 karakter</li>
      <li data-r="up">Ada huruf besar (A-Z)</li>
      <li data-r="lo">Ada huruf kecil (a-z)</li>
      <li data-r="num">Ada angka (0-9)</li>
      <li data-r="sym">Ada simbol (! @ # $ %)</li>
      <li data-r="sp">Tanpa spasi</li>
      <li data-r="idn">Tidak sama dengan nama atau email</li>
    </ul>
  </div>
  <div class="field">
    <label for="password_confirmation">Ulangi password</label>
    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" maxlength="72" required>
    @error('password_confirmation') <p class="error">{{ $message }}</p> @enderror
    <p class="error" id="pwBeda" hidden>Konfirmasi password belum cocok.</p>
  </div>
  <button type="submit" class="btn-primary btn-block">Daftar</button>
</form>

<script>
(function () {
  var pw = document.getElementById('password'), pc = document.getElementById('password_confirmation');
  var nm = document.getElementById('name'), em = document.getElementById('email');
  var li = {}; document.querySelectorAll('#pwCek li').forEach(function (x) { li[x.dataset.r] = x; });
  var beda = document.getElementById('pwBeda');
  function set(k, ok) { li[k].classList.toggle('ok', ok); }
  function cek() {
    var v = pw.value, l = v.toLowerCase();
    set('len', v.length >= 8);
    set('up', /[A-Z]/.test(v)); set('lo', /[a-z]/.test(v)); set('num', /\d/.test(v));
    set('sym', /[^A-Za-z0-9]/.test(v)); set('sp', v.length > 0 && !/\s/.test(v));
    var e = em.value.toLowerCase(), eb = e.split('@')[0];
    set('idn', v.length > 0 && l !== nm.value.trim().toLowerCase() && l !== e && l !== eb);
    beda.hidden = !pc.value || pc.value === v;
  }
  [pw, pc, nm, em].forEach(function (x) { x.addEventListener('input', cek); }); cek();
})();
</script>

<p class="auth-alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
@endsection