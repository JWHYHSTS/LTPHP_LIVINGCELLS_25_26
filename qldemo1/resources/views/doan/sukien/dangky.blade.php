@extends('layouts.app')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/title-section.css') }}">
<link rel="stylesheet" href="{{ asset('css/doan-sukien-dangky.css') }}">
@endpush

@php
    // chống lỗi Undefined variable nếu controller chưa truyền
    $events = $events ?? collect();

    // $rows nên là LengthAwarePaginator khi có paginate(),
    // nhưng vẫn fallback về collection để tránh crash
    $rows = $rows ?? collect();

    $MaSK = $MaSK ?? request('MaSK');
@endphp

<div class="container-fluid" style="padding-left:10px;">
  <div class="doan-dk-page">

    <h1 class="doan-dk-title">Danh sách đăng ký sự kiện</h1>
    <div class="doan-dk-subtitle">Chọn sự kiện, lọc sinh viên và thực hiện điểm danh nhanh.</div>

    {{-- Toast giống trang trước (auto hide) --}}
@if(session('success'))
  <div class="sv-toast sv-toast-success sv-alert" role="alert">
    <div>
      <p class="sv-toast-title">Thành công</p>
      <p class="sv-toast-msg">{{ session('success') }}</p>
    </div>
    <button type="button" class="sv-toast-close" aria-label="Close">×</button>
  </div>
@endif

@if(session('error'))
  <div class="sv-toast sv-toast-error sv-alert" role="alert">
    <div>
      <p class="sv-toast-title">Thất bại</p>
      <p class="sv-toast-msg">{{ session('error') }}</p>
    </div>
    <button type="button" class="sv-toast-close" aria-label="Close">×</button>
  </div>
@endif

    {{-- Filter --}}
    <form class="row g-2 doan-dk-toolbar mb-3" method="GET" action="{{ route('doan.sukien.dangky.index') }}">
      <div class="col-md-4">
        <select class="form-select" name="MaSK" required>
          <option value="">-- Chọn sự kiện --</option>
          @foreach($events as $e)
            <option value="{{ $e->MaSK }}" @selected((string)$MaSK === (string)$e->MaSK)>
              [{{ $e->MaSK }}] {{ $e->TieuDe }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-6">
        <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Tìm MSSV / Họ tên">
      </div>

      <div class="col-md-2 d-grid">
        <button class="btn-sv btn-sv-primary" type="submit">Lọc</button>
      </div>
    </form>

    {{-- Table --}}
    <div class="doan-dk-card table-responsive">
      <table class="table doan-dk-table align-middle">
        <thead>
          <tr>
            <th style="width:60px;">STT</th>
            <th style="width:150px;">MSSV</th>
            <th>Họ tên</th>
            <th style="width:140px;">Lớp</th>
            <th style="width:180px;">Đăng ký lúc</th>
            <th style="width:140px;">Trạng thái</th>
            <th style="width:220px;">Điểm danh</th>
            <th style="width:220px;">Thao tác</th>
          </tr>
        </thead>

        <tbody>
          @forelse($rows as $i => $r)
            @php
              $st = $r->TrangThaiDangKy ?? 'Registered';
              $checked = (int)($r->DaDiemDanh ?? 0) === 1;
              $maSkRow = $r->MaSK ?? $MaSK;

              // STT theo phân trang (nếu $rows là paginator)
              $stt = method_exists($rows, 'firstItem') && $rows->firstItem()
                   ? ($rows->firstItem() + $i)
                   : ($i + 1);
            @endphp

            <tr>
              <td>{{ $stt }}</td>
              <td class="fw-semibold">{{ $r->MaSV }}</td>
              <td class="fw-semibold">{{ $r->HoTen }}</td>
              <td>{{ $r->Lop }}</td>
              <td>{{ $r->DangKyLuc }}</td>

              {{-- Trạng thái đăng ký --}}
              <td>
                @if($st === 'Cancelled')
                  <span class="badge-sv badge-sv-muted">Cancelled</span>
                @else
                  <span class="badge-sv badge-sv-primary">Registered</span>
                @endif
              </td>

              {{-- Điểm danh (badge + time inline) --}}
              <td>
                <div class="dk-attend">
                  @if($checked)
                    <span class="badge-sv badge-sv-success">Đã điểm danh</span>
                    @if(!empty($r->DiemDanhLuc))
                      <span class="dk-attend-time">{{ $r->DiemDanhLuc }}</span>
                    @endif
                  @else
                    <span class="badge-sv badge-sv-warning">Chưa điểm danh</span>
                  @endif
                </div>
              </td>

              {{-- Thao tác --}}
              <td>
                @if($st === 'Cancelled')
                  <span class="doan-dk-muted">SV đã hủy</span>
                @else
                  <form class="d-inline" method="POST" action="{{ route('doan.sukien.diemdanh') }}">
                    @csrf
                    <input type="hidden" name="MaSK" value="{{ $maSkRow }}">
                    <input type="hidden" name="MaSV" value="{{ $r->MaSV }}">
                    <input type="hidden" name="action" value="{{ $checked ? 'checkout' : 'checkin' }}">

                    <button type="submit" class="btn-sv btn-sm {{ $checked ? 'btn-sv-danger' : 'btn-sv-success' }}">
                      {{ $checked ? 'Hủy điểm danh' : 'Điểm danh' }}
                    </button>
                  </form>
                @endif
              </td>
            </tr>

          @empty
            <tr>
              <td colspan="8" class="text-center doan-dk-muted">
                Chọn sự kiện để xem danh sách đăng ký
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>

      {{-- Pagination: phải đặt ngoài table/tbody --}}
      @if(method_exists($rows, 'hasPages') && $rows->hasPages())
        <div class="dk-pagination-wrap">
          {{ $rows->links() }}
        </div>
      @endif
    </div>

  </div>
</div>
<script>
  (function () {
    const toasts = document.querySelectorAll('.sv-alert');
    if (!toasts.length) return;

    toasts.forEach(toast => {
      const closeBtn = toast.querySelector('.sv-toast-close');

      const hide = () => {
        toast.classList.add('toastOut');
        setTimeout(() => toast.remove(), 220);
      };

      if (closeBtn) closeBtn.addEventListener('click', hide);

      setTimeout(hide, 3500);
    });
  })();
</script>
@endsection
