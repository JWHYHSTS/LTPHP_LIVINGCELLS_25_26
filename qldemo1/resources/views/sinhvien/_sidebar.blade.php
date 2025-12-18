<ul class="nav nav-pills flex-column gap-2">
  <li class="nav-item">
    <a href="{{ route('sv.home') }}"
      class="nav-link {{ request()->routeIs('sv.home') ? 'active' : 'text-white' }}">
      Thông tin sinh viên
    </a>
  </li>
  <li class="nav-item">
  <a class="nav-link" href="{{ route('sv.sukien.index') }}">
    Sự kiện tình nguyện
  </a>
</li>

</ul>