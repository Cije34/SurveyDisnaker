<div class="brand">Disnaker</div>

<div class="user-info">
  <p class="user-name">Thomas</p>
  <p class="user-email">thomas@gmail.com</p>
</div>

<nav class="menu">
  <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
  <a href="{{ route('jadwal') }}"    class="{{ request()->routeIs('jadwal') ? 'active' : '' }}">Jadwal</a>
  <a href="{{ route('survey') }}"    class="{{ request()->routeIs('survey') ? 'active' : '' }}">Survey</a>
</nav>

<button class="logout" type="button">Log out</button>
