@extends('layouts.guest')
@section('title','Đăng nhập')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/toast.css') }}">
@endpush

@section('content')
<div class="auth-wrapper">
  {{-- CỘT TRÁI --}}
  <div class="auth-left">

    {{-- TOAST: đặt ngoài card-box để không bị "nhốt" trong khung --}}
    @if(session('ok'))
    <div class="toast-notification toast-success" id="loginToast">
      <span class="toast-icon">✔</span>
      <span>{{ session('ok') }}</span>
    </div>
    @endif

    {{-- Logo trường + tên trường (1 ảnh gộp) --}}
    <div class="brand-combo">
      <img src="{{ asset('assets/images/logo_truong.png') }}" alt="HCMUE Logo & Name">
    </div>

    {{-- Logo Đoàn & Hội --}}
    <div class="club">
      <img src="{{ asset('assets/images/logo_doan.png') }}" alt="Logo Đoàn">
      <img src="{{ asset('assets/images/logo_hoi.png') }}" alt="Logo Hội SV">
    </div>

    {{-- Tiêu đề hệ thống --}}
    <div class="title">
      HỆ THỐNG NGHIỆP VỤ<br>
      CÔNG TÁC RÈN LUYỆN SINH VIÊN
    </div>

    {{-- Khung đăng nhập --}}
    <div class="card-box">

      <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Tên đăng nhập</label>
          <input
            type="text"
            name="TenDangNhap"
            value="{{ old('TenDangNhap') }}"
            class="form-control @error('TenDangNhap') is-invalid @enderror"
            placeholder="Username"
            required>
          @error('TenDangNhap')
          <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label class="form-label">Mật khẩu</label>
          <input
            type="password"
            name="MatKhau"
            class="form-control @error('MatKhau') is-invalid @enderror"
            placeholder="Password"
            required>
          @error('MatKhau')
          <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror

          {{-- Nếu có lỗi chung (không thuộc 2 field) --}}
          @if ($errors->any() && !$errors->has('TenDangNhap') && !$errors->has('MatKhau'))
          <div class="invalid-feedback d-block mt-1">{{ $errors->first() }}</div>
          @endif
        </div>

        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>

        <div class="text-center mt-3 mb-1">
          <a href="{{ route('forgot.show') }}" class="forgot-link">Quên mật khẩu?</a>
        </div>
      </form>
    </div>

    <div class="footer-note">
      ©2025 Hệ thống QLRLKTSV. Developed by
      <a href="https://github.com/JWHYHSTS/LTPHP_LIVINGCELLS_25_26" target="_blank" class="dev-logo">
        <img src="{{ asset('assets/images/logo_dark.png') }}" alt="Living Cell Logo">
      </a>
    </div>
  </div>

  {{-- CỘT PHẢI --}}
  <div class="auth-right"></div>
</div>
@endsection

@push('scripts')
<script>
  (function() {
    const toasts = document.querySelectorAll('.sv-alert');
    if (!toasts.length) return;

    toasts.forEach(toast => {
      const closeBtn = toast.querySelector('.sv-toast-close');

      const hide = () => {
        toast.classList.add('toastOut');
        setTimeout(() => toast.remove(), 220);
      };

      if (closeBtn) closeBtn.addEventListener('click', hide);

      // tự ẩn sau 3.5 giây
      setTimeout(hide, 3500);
    });
  })();
</script>
<script>
  setTimeout(() => {
    const toast = document.getElementById('loginToast');
    if (toast) {
      toast.classList.add('toast-hide');
      setTimeout(() => toast.remove(), 500);
    }
  }, 3500);
</script>
@endpush