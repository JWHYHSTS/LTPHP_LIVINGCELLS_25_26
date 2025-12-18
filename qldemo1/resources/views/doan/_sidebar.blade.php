<ul class="nav nav-pills flex-column gap-2">
  <li class="nav-item">
    <a href="{{ route('doan.khenthuong.index') }}"
      class="nav-link {{ request()->routeIs('doan.khenthuong.index') ? 'active' : 'text-white' }}">
      Danh sách khen thưởng SV
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('doan.tinhnguyen.index') }}"
      class="nav-link {{ request()->routeIs('doan.tinhnguyen.index') ? 'active' : 'text-white' }}">
      Quản lý ngày tình nguyện
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('doan.danhhieu.index') }}"
      class="nav-link {{ request()->routeIs('doan.danhhieu.index') ? 'active' : 'text-white' }}">
      Quản lý danh hiệu
    </a>
  </li>
<li class="nav-item">
    <a href="{{ route('doan.sukien.index') }}"
       class="nav-link {{ request()->routeIs('doan.sukien.*') ? 'active' : 'text-white' }}">
        <i class="bi bi-calendar-event"></i>
        <span>Quản lý sự kiện</span>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('doan.sukien.dangky.index') }}"
       class="nav-link {{ request()->routeIs('doan.sukien.dangky.*') ? 'active' : 'text-white' }}">
        <i class="bi bi-clipboard-check"></i>
        <span>Danh sách đăng ký sự kiện</span>
    </a>
</li>
</ul>