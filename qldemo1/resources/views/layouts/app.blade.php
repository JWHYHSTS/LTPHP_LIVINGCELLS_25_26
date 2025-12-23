<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- ✅ BẮT BUỘC: để fetch lấy CSRF --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title','QLSV')</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- CSS custom --}}
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sinhvien-title.css') }}">
  <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
  
{{-- AI chatbox --}}
<link rel="stylesheet" href="{{ asset('css/ai.css') }}">
  @stack('styles')

  <style>
    .app-wrap { min-height: 100vh; }

    @media (max-width: 991.98px) {
      .sidebar { position: fixed; z-index: 1030; transform: translateX(-100%); transition: .2s; border-radius: 0; }
      .sidebar.show { transform: none; }
      .sidebar-backdrop { position: fixed; inset: 0; background: rgba(0, 0, 0, .35); display: none; z-index: 1029; }
      .sidebar-backdrop.show { display: block; }
      .sidebar-toggler { position: fixed; top: 12px; left: 12px; z-index: 1031; }
    }

    @media (min-width: 992px) {
      .app-wrap { align-items: flex-start; }
      .sidebar { position: sticky; top: 0; height: 100vh; overflow-y: auto; flex: 0 0 260px; }
    }

    :root {
      --sidebar-bg: #164B71;
      --sidebar-hover: #1d5a8d;
      --sidebar-active: #164B71;
    }

    .sidebar nav .nav-link {
      display: flex; align-items: center; gap: 8px;
      white-space: nowrap; overflow: hidden;
      border-radius: 8px;
      color: #fff; background: transparent;
      transition: background .2s ease, color .2s ease;
    }

    .sidebar nav .nav-link:hover { background: var(--sidebar-hover); }
    .sidebar nav .nav-link.active { background: var(--sidebar-active); font-weight: 600; }

    .sidebar nav .nav-link span {
      display: inline-block;
      overflow: hidden;
      white-space: nowrap;
      max-width: 220px;
      opacity: 1;
      transition: max-width .25s ease, opacity .25s ease;
    }
    body.sidebar-collapsed .sidebar nav .nav-link span { max-width: 0; opacity: 0; }

    .sidebar nav .nav-link i {
      flex: 0 0 22px;
      display: grid;
      place-items: center;
      font-size: 1.05rem;
    }

    body.sidebar-collapsed .sidebar form .btn .btn-text { max-width: 0; opacity: 0; }
    .sidebar form .btn .btn-text {
      display: inline-block;
      white-space: nowrap;
      overflow: hidden;
      max-width: 220px;
      opacity: 1;
      transition: max-width .25s ease, opacity .25s ease;
      will-change: max-width, opacity;
    }
  </style>
</head>

<body class="sidebar-collapsed">
  <button class="btn btn-dark d-lg-none sidebar-toggler">☰</button>
  <div class="sidebar-backdrop d-lg-none"></div>

  {{-- Toast container global --}}
  <div id="toastStack"
       style="position:fixed; top:20px; right:28px; z-index:9999; display:flex; flex-direction:column; gap:12px;">
  </div>

  <div class="d-flex app-wrap">
    <aside class="sidebar text-white p-3 d-flex flex-column" id="appSidebar">
      <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Thu gọn/mở rộng sidebar">
        <span class="dot"></span><span class="dot"></span><span class="dot"></span>
      </button>

      <div class="brand text-center mb-3">
        <a href="https://hcmue.edu.vn/">
          <img src="{{ asset('assets/images/logo_truong.png') }}" alt="Logo" class="logo">
        </a>
      </div>

      @php $u = session('user'); @endphp
      <div class="user-info text-center mb-2">
        <i class="bi bi-person fs-2 d-block mb-1"></i>
        <div class="fw-semibold">{{ $u['name'] ?? 'Administrator' }}</div>
        <div class="opacity-75 small">{{ $u['role'] ?? 'null' }}</div>
        <div class="opacity-75 small">{{ $u['email'] ?? 'null' }}</div>
      </div>

      <nav class="mb-auto w-100 mt-2">
        @if (request()->is('admin*'))
          @include('admin._sidebar')
        @elseif (request()->is('sinhvien*'))
          @include('sinhvien._sidebar')
        @elseif (request()->is('ctct*'))
          @include('ctct._sidebar')
        @elseif (request()->is('khaothi*'))
          @include('khaothi._sidebar')
        @elseif (request()->is('doantruong*'))
          @include('doan._sidebar')
        @endif
      </nav>

      <form method="post" action="{{ route('logout') }}" class="mt-3 w-100">
        @csrf
        <button class="btn btn-light w-100 fw-semibold d-flex align-items-center justify-content-center gap-2">
          <i class="bi bi-box-arrow-right"></i>
          <span class="btn-text">Đăng xuất</span>
        </button>
      </form>
    </aside>

    <main class="flex-grow-1 d-flex flex-column">
      <header class="bg-light border-bottom text-center py-2">
        <div class="small text-uppercase fw-semibold" style="letter-spacing:.3px">
          HỆ THỐNG NGHIỆP VỤ CÔNG TÁC RÈN LUYỆN SINH VIÊN
        </div>
        <div class="small text-uppercase text-secondary" style="letter-spacing:.3px">
          TRƯỜNG ĐẠI HỌC SƯ PHẠM THÀNH PHỐ HỒ CHÍ MINH
        </div>
      </header>

      <section class="p-4 flex-grow-1">
        @yield('content')
      </section>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const sidebar = document.querySelector('.sidebar');
    const backdrop = document.querySelector('.sidebar-backdrop');
    const btnMobile = document.querySelector('.sidebar-toggler');

    function toggleSidebar(show) {
      const add = typeof show === 'boolean' ? show : !sidebar.classList.contains('show');
      sidebar.classList.toggle('show', add);
      backdrop.classList.toggle('show', add);
      document.body.style.overflow = add ? 'hidden' : '';
    }

    btnMobile?.addEventListener('click', (e) => { e.stopPropagation(); toggleSidebar(); });
    backdrop?.addEventListener('click', () => toggleSidebar(false));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') toggleSidebar(false); });
  </script>

  <script src="{{ asset('js/toast.js') }}"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const ok = @json(session('ok'));
      if (ok) window.SVToast?.toast('success', 'Thành công', ok);

      const errs = @json($errors->all());
      if (Array.isArray(errs) && errs.length) {
        window.SVToast?.toast('error', 'Có lỗi nhập liệu', errs.join('\n'), 4500);
      }
    });
  </script>

  @stack('scripts')

  <script>
    (function() {
      const KEY = 'sidebar-collapsed';
      if (localStorage.getItem(KEY) === '1') document.body.classList.add('sidebar-collapsed');

      const toggleBtn = document.getElementById('sidebarToggle');
      if (!toggleBtn) return;

      const toggle = () => {
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem(KEY, document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
      };

      toggleBtn.addEventListener('click', toggle);
      toggleBtn.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); }
      });
    })();
  </script>

  <script>
    (function() {
      const ICON_MAP = [
        { k: 'danh sách tài khoản', icon: 'bi-people' },
        { k: 'sinh viên', icon: 'bi-mortarboard' },
        { k: 'danh sách sinh viên', icon: 'bi-people' },
        { k: 'điểm rèn luyện', icon: 'bi-clipboard-check' },
        { k: 'điểm học tập', icon: 'bi-mortarboard-fill' },
        { k: 'khen thưởng', icon: 'bi-trophy' },
        { k: 'danh hiệu', icon: 'bi-award' },
        { k: 'ngày tình nguyện', icon: 'bi-calendar-heart' },
        { k: 'quản trị', icon: 'bi-gear' },
        { k: 'báo cáo', icon: 'bi-file-earmark-bar-graph' },
        { k: 'thống kê', icon: 'bi-bar-chart' },
      ];

      const HREF_MAP = [
        { test: /khaothi\/sinh-vien|khaothi\/sinhvien/i, icon: 'bi-people' },
        { test: /khaothi\/quan-ly-diem|khaothi\/diem|khaothi\/hoc-tap/i, icon: 'bi-mortarboard-fill' },
        { test: /doan(truong)?\/khen/i, icon: 'bi-trophy' },
        { test: /doan(truong)?\/danh-hieu|danhhieu/i, icon: 'bi-award' },
        { test: /doan(truong)?\/tinh-nguyen|ngaytn|volunteer/i, icon: 'bi-calendar-heart' },
      ];

      function pickIcon(linkEl) {
        const href = (linkEl.getAttribute('href') || '').toLowerCase();
        const text = (linkEl.textContent || '').toLowerCase().trim();
        for (const r of HREF_MAP) if (r.test.test(href)) return r.icon;
        for (const r of ICON_MAP) if (text.includes(r.k)) return r.icon;
        return 'bi-dot';
      }

      function injectIcons() {
        document.querySelectorAll('.sidebar nav a.nav-link').forEach(a => {
          if (a.querySelector('i')) return;
          const i = document.createElement('i');
          i.className = 'bi ' + pickIcon(a);
          a.prepend(i);
        });
      }

      if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', injectIcons);
      else injectIcons();
    })();
  </script>

  {{-- ✅ CHỈ NHÚNG AI 1 LẦN, NGAY TRƯỚC </body> --}}
  <x-ai-event-chatbox />

</body>

</html>
