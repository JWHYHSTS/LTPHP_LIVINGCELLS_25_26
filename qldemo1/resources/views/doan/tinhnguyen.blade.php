@extends('layouts.app')
@section('title','VP Đoàn Trường | Quản lý ngày tình nguyện')

@section('content')
<link rel="stylesheet" href="{{ asset('css/doan-tinhnguyen.css') }}">
<link rel="stylesheet" href="{{ asset('css/toast.css') }}">
{{-- Toast container (góc phải trên) --}}
<div id="toastStack"
     style="position:fixed; top:20px; right:28px;
            z-index:9999; display:flex; flex-direction:column; gap:12px;">
</div>
<div class="row">
  <main class="col-md-12">
    <h5>Quản lý ngày tình nguyện</h5>

    <div class="d-flex gap-2 mb-2">
      <form method="post" action="{{ route('doan.tinhnguyen.import') }}" enctype="multipart/form-data" class="d-flex gap-2">
        @csrf
        <input type="file" name="file" class="form-control" style="max-width:260px" accept=".xlsx,.xls,.csv" required>
        <button class="btn btn-soft-secondary btn-animate ripple">
  <i class="bi bi-cloud-upload me-1"></i> Upload file
</button>
      </form>
{{-- Nút tải Mẫu Excel --}}
      <a href="{{ route('doan.tinhnguyen.template') }}"
         class="btn btn-soft-success btn-animate ripple">
        <i class="bi bi-file-earmark-excel me-1"></i> Mẫu Excel
      </a>
      <button class="btn btn-soft-primary btn-animate ripple" data-bs-toggle="modal" data-bs-target="#addNTN">
  <i class="bi bi-plus-circle me-1"></i> Thêm
</button>

      <button id="btn-refresh" class="btn btn-soft-warning btn-animate ripple" type="button" onclick="showSaveMessage()">
  <i class="bi-check-circle"></i> Lưu
</button>

      <div class="ms-auto d-flex gap-2">
        <form method="GET" action="{{ route('doan.tinhnguyen.index') }}" class="d-flex gap-2">
          <input type="text" name="q" class="form-control" value="{{ $q }}" placeholder="Tìm MSSV / Họ tên / Hoạt động">
          <button class="btn btn-outline-primary btn-animate ripple">Tìm</button>
        </form>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>STT</th>
            <th>MSSV</th>
            <th>Họ và Tên</th>
            <th>Hoạt động đã tham gia</th>
            <th>Ngày tham gia</th>
            <th>Số ngày tình nguyện</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $i => $r)
          <tr>
            <td>{{ $data->firstItem() + $i }}</td>
            <td>{{ $r->MaSV }}</td>
            <td>{{ $r->HoTen }}</td>
            <td>{{ $r->TenHoatDong ?? '— Chưa có —' }}</td>
            <td>{{ $r->NgayThamGiaText ?? '—' }}</td>
            <td>{{ $r->SoNgayTN ?? '—' }}</td>
           <td>
@php
  $st = strtolower($r->TrangThaiDuyet ?? '');
  $color = match ($st) {
    'daduyet'    => 'success',
    'chuaduyet'  => 'warning',
    'tuchoi'     => 'danger',
    default      => 'secondary'
  };
@endphp
<span class="badge text-bg-{{ $color }} badge-tn">
  {{ $r->TrangThaiDuyet ?? '—' }}
</span>
</td>
            <td class="text-nowrap">
              @if($r->MaNTN)
              <button type="button"
                class="btn btn-sm btn-outline-primary btn-animate ripple me-1"
                data-bs-toggle="modal" data-bs-target="#editNTN"
                data-mantn="{{ $r->MaNTN }}"
                data-masv="{{ $r->MaSV }}"
                data-hoten="{{ $r->HoTen }}"
                data-tenhd="{{ $r->TenHoatDong }}"
                data-ngay="{{ $r->NgayThamGia }}"
                data-songay="{{ $r->SoNgayTN }}"
                data-trangthai="{{ $r->TrangThaiDuyet }}">
                Sửa
              </button>
              <form method="post" action="{{ route('doan.tinhnguyen.delete') }}" class="d-inline">
                @csrf
                <input type="hidden" name="MaNTN" value="{{ $r->MaNTN }}">
                <button class="btn btn-sm btn-outline-danger btn-animate ripple">Xóa</button>
              </form>
              @else
              <span class="text-muted">—</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center">Không có dữ liệu</td>
          </tr>
          @endforelse
        </tbody>
      </table>
      
  </main>
</div>
      {{ $data->links() }}
{{-- MODAL THÊM --}}
<div class="modal fade" id="addNTN" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('doan.tinhnguyen.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Thêm hoạt động TN</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label class="form-label">MSSV</label>
          <select class="form-select" name="MaSV" required>
            <option value="">-- Chọn MSSV --</option>
            @foreach($dsSV as $sv)
            <option value="{{ $sv->MaSV }}">{{ $sv->MaSV }} — {{ $sv->HoTen }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label">Tên hoạt động</label>
          <input class="form-control" name="TenHoatDong" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Ngày tham gia</label>
          <input type="date" class="form-control" name="NgayThamGia" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Số ngày TN</label>
          <input type="number" min="1" class="form-control" name="SoNgayTN" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Trạng thái</label>
          <select class="form-select" name="TrangThaiDuyet" required>
            <option value="ChuaDuyet">Chưa duyệt</option>
            <option value="DaDuyet">Đã duyệt</option>
            <option value="TuChoi">Từ chối</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

{{-- MODAL SỬA (MSSV READONLY) --}}
<div class="modal fade" id="editNTN" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('doan.tinhnguyen.update') }}">
      @csrf
      <input type="hidden" name="MaNTN" id="edit_mantn">
      <div class="modal-header">
        <h5 class="modal-title">Sửa hoạt động TN</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label class="form-label">MSSV</label>
          <input class="form-control" name="MaSV" id="edit_masv" readonly>
        </div>
        <div class="mb-2">
          <label class="form-label">Họ và Tên</label>
          <input class="form-control" id="edit_hoten" readonly>
        </div>
        <div class="mb-2">
          <label class="form-label">Tên hoạt động</label>
          <input class="form-control" name="TenHoatDong" id="edit_tenhd" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Ngày tham gia</label>
          <input type="date" class="form-control" name="NgayThamGia" id="edit_ngay" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Số ngày TN</label>
          <input type="number" min="1" class="form-control" name="SoNgayTN" id="edit_songay" required>
        </div>
        <div class="mb-2">
          <label class="form-label">Trạng thái</label>
          <select class="form-select" name="TrangThaiDuyet" id="edit_trangthai" required>
            <option value="ChuaDuyet">Chưa duyệt</option>
            <option value="DaDuyet">Đã duyệt</option>
            <option value="TuChoi">Từ chối</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Xoá --}}
<div class="modal fade" id="delNTN" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('doan.tinhnguyen.delete') }}">
      @csrf
      <input type="hidden" name="MaNTN" id="d_mantn">
      <div class="modal-header">
        <h5 class="modal-title">Xoá hoạt động</h5><button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Bạn chắc chắn muốn xoá hoạt động: <strong id="d_tenhd"></strong>?
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
        <button class="btn btn-danger">Xoá</button>
      </div>
    </form>
  </div>
</div>
<script id="toastPayload" type="application/json">
{!! json_encode([
  'success'    => session('success'),
  'error'      => session('error'),
  'firstError' => $errors->any() ? $errors->first() : null,
], JSON_UNESCAPED_UNICODE) !!}
</script>

@push('scripts')
<script>
  /* =========================
     TOAST SYSTEM
  ========================= */
  function showToast(type, message, title = 'Thông báo', duration = 2500) {
    const stack = document.getElementById('toastStack');
    if (!stack) return;

    const toast = document.createElement('div');
    const success = type === 'success';

    toast.className = `toast-notification sv-toast
      ${success ? 'toast-success sv-toast-success' : 'toast-error sv-toast-error'}`;

    toast.innerHTML = `
      <span class="toast-icon">${success ? '✅' : '❌'}</span>
      <div style="flex:1">
        <div class="sv-toast-title"><strong>${title}</strong></div>
        <div class="sv-toast-msg">${message ?? ''}</div>
      </div>
      <button class="sv-toast-close" style="border:none;background:none;font-size:18px">✕</button>
    `;

    toast.style.position = 'relative';
    stack.appendChild(toast);

    const close = () => {
      toast.classList.add('toast-hide');
      setTimeout(() => toast.remove(), 500);
    };

    toast.querySelector('.sv-toast-close').onclick = close;
    setTimeout(close, duration);
  }

  /* =========================
     NÚT LƯU
  ========================= */
  document.getElementById('btn-refresh')?.addEventListener('click', () => {
    showToast('success', 'Đã cập nhật thành công! Đang quay lại...', 'Thành công', 1500);
    setTimeout(() => {
      window.location.href = "{{ route('doan.tinhnguyen.index') }}";
    }, 1500);
  });

  /* =========================
     MODAL SỬA
  ========================= */
  document.getElementById('editNTN')?.addEventListener('show.bs.modal', e => {
    const b = e.relatedTarget;
    if (!b) return;

    const get = k => b.getAttribute('data-' + k) || '';

    document.getElementById('edit_mantn').value = get('mantn');
    document.getElementById('edit_masv').value  = get('masv');
    document.getElementById('edit_hoten').value = get('hoten');
    document.getElementById('edit_tenhd').value = get('tenhd');
    document.getElementById('edit_ngay').value  = get('ngay');
    document.getElementById('edit_songay').value = get('songay');
    document.getElementById('edit_trangthai').value = get('trangthai');
  });

  /* =========================
     MODAL XOÁ (nếu dùng)
  ========================= */
  document.getElementById('delNTN')?.addEventListener('show.bs.modal', e => {
    const b = e.relatedTarget;
    if (!b) return;

    document.getElementById('d_mantn').value = b.getAttribute('data-mantn') || '';
    document.getElementById('d_tenhd').textContent = b.getAttribute('data-tenhd') || '';
  });

  /* =========================
     TOAST TỪ SESSION / ERRORS
     (JS THUẦN -> KHÔNG BÁO ĐỎ)
  ========================= */
  const payloadEl = document.getElementById('toastPayload');
const payload = payloadEl ? JSON.parse(payloadEl.textContent || '{}') : {};

const toastSuccess = payload.success;
const toastError   = payload.error;
const firstError   = payload.firstError;

if (toastSuccess) showToast('success', toastSuccess, 'Thành công');
if (toastError)   showToast('error', toastError, 'Lỗi');
if (firstError)   showToast('error', firstError, 'Dữ liệu không hợp lệ');
  if (toastSuccess) {
    showToast('success', toastSuccess, 'Thành công');
  }
  if (toastError) {
    showToast('error', toastError, 'Lỗi');
  }
  if (firstError) {
    showToast('error', firstError, 'Dữ liệu không hợp lệ');
  }
</script>
@endpush
@endsection