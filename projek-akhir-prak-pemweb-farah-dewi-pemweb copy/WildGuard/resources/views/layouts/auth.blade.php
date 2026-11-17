<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'WildGuard') - WildGuard</title>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700;800&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/wildguard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/crud.css') }}">
</head>
<body class="halaman-dalam">
  @include('partials.navbar')

  <main class="auth-wrap">
    <div class="auth-card">
      @if (session('ok')) <div class="alert ok">{{ session('ok') }}</div> @endif
      @if (session('err')) <div class="alert err">{{ session('err') }}</div> @endif
      @yield('content')
    </div>
  </main>

  @include('partials.footer')
  <script src="{{ asset('js/nav.js') }}"></script>
</body>
</html>