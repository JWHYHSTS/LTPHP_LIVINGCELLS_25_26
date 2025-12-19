@extends('layouts.app')
@section('title','Thông tin phòng/ban')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="{{ asset('css/toast.css') }}">
<script src="{{ asset('js/toast.js') }}"></script>

<h4 class="page-title">Thông tin phòng/ban (Admin / CTCT-HSSV / Khảo thí / Đoàn Trường)</h4>

<div class="admin-toolbar card mb-3">
  <div class="card-body py-2 d-flex flex-wrap gap-2 align-items-center">
    <form class="ms-auto d-flex" method="get">
      <input class="form-control me-2" name="q" value="{{ $q ?? '' }}"
        placeholder="Tìm theo tên đăng nhập / email / vai trò / MaTK...">
      <button class="btn btn-outline-primary"><i class="bi bi-search"></i> Tìm</button>
    </form>
  </div>
</div>

{{-- (Tuỳ chọn) giữ box lỗi đỏ để dễ xem chi tiết --}}
@if($errors->any())
<div class="alert alert-danger">
  <div class="fw-semibold mb-1">Có lỗi nhập liệu:</div>
  <ul class="mb-0">
    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
  </ul>
</div>
@endif

<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:80px">STT</th>
        <th style="width:90px">MaTK</th>
        <th>Tên đăng nhập</th>
        <th>Email</th>
        <th style="width:120px">Vai trò</th>
        <th style="width:260px">Thông tin hiện có</th>
        <th style="width:130px">Thao tác</th>
      </tr>
    </thead>

    <tbody>
      @forelse($data as $i => $r)
      @php
      $profileText = 'Chưa có';

      if($r->VaiTro === 'Admin' && $r->adminProfile){
      $profileText = 'MaAdmin: '.$r->adminProfile->MaAdmin;
      }

      if($r->VaiTro === 'CTCTHSSV' && $r->ctctProfile){
      $profileText = 'MaCTCT: '.$r->ctctProfile->MaCTCT
      .' | '.$r->ctctProfile->TenPhong
      .' | '.$r->ctctProfile->NguoiQL;
      }

      if($r->VaiTro === 'KhaoThi' && $r->khaoThiProfile){
      $profileText = 'MaPKT: '.$r->khaoThiProfile->MaPKT
      .' | '.$r->khaoThiProfile->TenPhong
      .' | '.$r->khaoThiProfile->NguoiQL;
      }

      if($r->VaiTro === 'DoanTruong' && $r->doanProfile){
      $profileText = 'MaDT: '.$r->doanProfile->MaDT
      .' | '.$r->doanProfile->TenDT
      .' | '.$r->doanProfile->NguoiQL;
      }
      @endphp

      <tr>
        <td>{{ $data->firstItem() + $i }}</td>
        <td>{{ $r->MaTK }}</td>
        <td>{{ $r->TenDangNhap }}</td>
        <td>{{ $r->Email }}</td>
        <td>
          <span class="badge rounded-pill text-white badge-role {{ strtolower($r->VaiTro) }}">
            {{ $r->VaiTro }}
          </span>
        </td>
        <td>{{ $profileText }}</td>
        <td>
          <button type="button"
            class="btn btn-sm btn-outline-primary btn-animate ripple"
            data-bs-toggle="modal"
            data-bs-target="#modalProfile"

            data-matk="{{ $r->MaTK }}"
            data-vaitro="{{ $r->VaiTro }}"

            data-maadmin="{{ optional($r->adminProfile)->MaAdmin }}"

            data-mactct="{{ optional($r->ctctProfile)->MaCTCT }}"
            data-tenphong-ctct="{{ optional($r->ctctProfile)->TenPhong }}"
            data-nguoiql-ctct="{{ optional($r->ctctProfile)->NguoiQL }}"

            data-mapkt="{{ optional($r->khaoThiProfile)->MaPKT }}"
            data-tenphong-kt="{{ optional($r->khaoThiProfile)->TenPhong }}"
            data-nguoiql-kt="{{ optional($r->khaoThiProfile)->NguoiQL }}"

            data-madt="{{ optional($r->doanProfile)->MaDT }}"
            data-tendt="{{ optional($r->doanProfile)->TenDT }}"
            data-nguoiql-dt="{{ optional($r->doanProfile)->NguoiQL }}">
            Nhập/Sửa
          </button>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center">Không có dữ liệu</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $data->links() }}

{{-- Modal nhập/sửa thông tin phòng ban --}}
<div class="modal fade" id="modalProfile" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="{{ route('admin.staff.upsert') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Nhập thông tin phòng/ban</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" name="MaTK" id="pfMaTK">
        <input type="hidden" name="VaiTro" id="pfVaiTro">

        {{-- Admin --}}
        <div class="role-block d-none" data-role="Admin">
          <div class="mb-2">
            <label class="form-label">MaAdmin</label>
            <input class="form-control" name="MaAdmin" placeholder="VD: AD001">
          </div>
          <div class="text-muted small">Bảng Admin chỉ cần MaAdmin gắn với MaTK.</div>
        </div>

        {{-- CTCTHSSV --}}
        <div class="role-block d-none" data-role="CTCTHSSV">
          <div class="mb-2">
            <label class="form-label">MaCTCT</label>
            <input class="form-control" name="MaCTCT" placeholder="VD: CT001">
          </div>
          <div class="mb-2">
            <label class="form-label">Tên phòng</label>
            <input class="form-control" name="TenPhong" placeholder="VD: Phòng CTCT-HSSV">
          </div>
          <div class="mb-2">
            <label class="form-label">Người quản lý</label>
            <input class="form-control" name="NguoiQL" placeholder="VD: Nguyễn Văn A">
          </div>
        </div>

        {{-- KhaoThi --}}
        <div class="role-block d-none" data-role="KhaoThi">
          <div class="mb-2">
            <label class="form-label">MaPKT</label>
            <input class="form-control" name="MaPKT" placeholder="VD: KT001">
          </div>
          <div class="mb-2">
            <label class="form-label">Tên phòng</label>
            <input class="form-control" name="TenPhong" placeholder="VD: Phòng Khảo thí">
          </div>
          <div class="mb-2">
            <label class="form-label">Người quản lý</label>
            <input class="form-control" name="NguoiQL" placeholder="VD: Nguyễn Văn B">
          </div>
        </div>

        {{-- DoanTruong --}}
        <div class="role-block d-none" data-role="DoanTruong">
          <div class="mb-2">
            <label class="form-label">MaDT</label>
            <input class="form-control" name="MaDT" placeholder="VD: DT001">
          </div>
          <div class="mb-2">
            <label class="form-label">Tên Đoàn trường</label>
            <input class="form-control" name="TenDT" placeholder="VD: Văn phòng Đoàn Trường">
          </div>
          <div class="mb-2">
            <label class="form-label">Người quản lý</label>
            <input class="form-control" name="NguoiQL" placeholder="VD: Nguyễn Văn C">
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-animate ripple">Lưu</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
{{-- Toast JS --}}
<script src="{{ asset('js/toast.js') }}"></script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const modalEl = document.getElementById('modalProfile');
    if (!modalEl) return;

    const form = modalEl.querySelector('form');
    const hMaTK = document.getElementById('pfMaTK');
    const hVaiTro = document.getElementById('pfVaiTro');

    function disableAllRoleInputs() {
      modalEl.querySelectorAll('.role-block').forEach(block => {
        block.classList.add('d-none');
        block.querySelectorAll('input').forEach(inp => {
          inp.value = '';
          inp.disabled = true; // không submit input không thuộc role
        });
      });
    }

    function enableRole(role) {
      const block = modalEl.querySelector(`.role-block[data-role="${role}"]`);
      if (!block) return;
      block.classList.remove('d-none');
      block.querySelectorAll('input').forEach(inp => inp.disabled = false);
    }

    modalEl.addEventListener('show.bs.modal', (ev) => {
      const btn = ev.relatedTarget;
      if (!btn) return;

      const matk = btn.getAttribute('data-matk') || '';
      const vaitro = btn.getAttribute('data-vaitro') || '';

      hMaTK.value = matk;
      hVaiTro.value = vaitro;

      disableAllRoleInputs();
      enableRole(vaitro);

      if (vaitro === 'Admin') {
        form.querySelector('input[name="MaAdmin"]').value = btn.getAttribute('data-maadmin') || '';
      }

      if (vaitro === 'CTCTHSSV') {
        form.querySelector('input[name="MaCTCT"]').value = btn.getAttribute('data-mactct') || '';
        form.querySelector('input[name="TenPhong"]').value = btn.getAttribute('data-tenphong-ctct') || '';
        form.querySelector('input[name="NguoiQL"]').value = btn.getAttribute('data-nguoiql-ctct') || '';
      }

      if (vaitro === 'KhaoThi') {
        form.querySelector('input[name="MaPKT"]').value = btn.getAttribute('data-mapkt') || '';
        form.querySelector('input[name="TenPhong"]').value = btn.getAttribute('data-tenphong-kt') || '';
        form.querySelector('input[name="NguoiQL"]').value = btn.getAttribute('data-nguoiql-kt') || '';
      }

      if (vaitro === 'DoanTruong') {
        form.querySelector('input[name="MaDT"]').value = btn.getAttribute('data-madt') || '';
        form.querySelector('input[name="TenDT"]').value = btn.getAttribute('data-tendt') || '';
        form.querySelector('input[name="NguoiQL"]').value = btn.getAttribute('data-nguoiql-dt') || '';
      }
    });

    form.addEventListener('submit', (e) => {
      if (!hMaTK.value || !hVaiTro.value) {
        e.preventDefault();
        alert('Không lấy được MaTK/VaiTro. Đóng modal và bấm Nhập/Sửa lại.');
      }
    });

    // ===== Toast from session/errors =====
    const ok = JSON.parse('{!! json_encode(session('
      ok ')) !!}');
    if (ok) {
      window.SVToast?.toast('success', 'Thành công', ok);
    }

    const errs = JSON.parse('{!! json_encode($errors->all()) !!}');
    if (Array.isArray(errs) && errs.length) {
      window.SVToast?.toast('error', 'Có lỗi nhập liệu', errs.join('\n'), 4200);
    }
  });
</script>
@endpush

@endsection