<ul class="nav nav-pills flex-column gap-2">

    <li class="nav-item">
        <a href="{{ route('sv.home') }}"
           class="nav-link d-flex align-items-center gap-2
           {{ request()->routeIs('sv.home') ? 'active' : 'text-white' }}">
            <i class="bi bi-person-lines-fill"></i>
            <span>Thông tin sinh viên</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('sv.sukien.index') }}"
           class="nav-link d-flex align-items-center gap-2
           {{ request()->routeIs('sv.sukien.*') ? 'active' : 'text-white' }}">
            <i class="bi bi-calendar-event"></i>
            <span>Sự kiện tham gia</span>
        </a>
    </li>

</ul>
