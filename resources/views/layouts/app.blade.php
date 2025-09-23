<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Disnaker')</title>
  @vite(['resources/css/dashboard.css'])
</head>
<body>
  <div class="container">
    <aside class="sidebar">@include('partials._sidebar')</aside>
    <main class="main-content">
      <h2>@yield('title', '')</h2>
      @yield('topbar')
      @yield('content')
    </main>
  </div>
</body>
</html>
