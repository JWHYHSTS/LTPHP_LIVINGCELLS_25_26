@extends('layouts.app')
@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/title-section.css') }}">
<link rel="stylesheet" href="{{ asset('css/doan-sukien.css') }}">
@endpush
<style>
    .modal {
        z-index: 2000 !important;
    }

    .modal-backdrop {
        z-index: 1990 !important;
    }

    /* nếu sidebar bạn có class khác thì đổi lại cho đúng */
    .sidebar,
    .doan-sidebar {
        z-index: 1000 !important;
    }
</style>

<div class="container-fluid" style="padding-left:10px;">
    <div class="doan-sukien-page">

        <div class="doan-sukien-header">
            <div>
                <h1 class="doan-sukien-title">Quản lý sự kiện</h1>
                <div class="doan-sukien-subtitle">Tạo, cập nhật và đóng/mở các sự kiện tình nguyện.</div>
            </div>
        </div>

        {{-- Toast success/error (modern + auto hide) --}}
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

        <form class="doan-sukien-toolbar" method="GET" action="{{ route('doan.sukien.index') }}">
            <input class="doan-sukien-search" name="q" value="{{ $q ?? '' }}" placeholder="Tìm tiêu đề / địa điểm...">
            <button class="btn btn-sv btn-sv-ghost" type="submit">Tìm</button>

            <button type="button" class="btn btn-sv btn-sv-primary"
                data-bs-toggle="modal" data-bs-target="#modalCreate">
                Thêm
            </button>
        </form>

        <div class="doan-sukien-card table-responsive">
            <table class="table doan-sukien-table align-middle">
                <thead>
                    <tr>
                        <th style="width:60px;">STT</th>
                        <th style>Tiêu đề</th>
                        <th style="width:230px;">Thời gian</th>
                        <th style="width:350px;">Địa điểm</th>
                        <th style="width:200px;">SL tối đa</th>
                        <th style="width:120px;">Trạng thái</th>
                        <th style="width:130px;">Ảnh</th>
                        <th style="width:280px;">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($events as $i => $e)
                    <tr>
                        <td>{{ $events->firstItem() + $i }}</td>

                        <td>
                            <div class="fw-bold">{{ $e->TieuDe }}</div>
                            <div class="doan-sukien-muted" style="max-width:520px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $e->NoiDung }}
                            </div>
                        </td>

                        <td>
                            <div class="fw-semibold">{{ $e->ThoiGianBatDau }}</div>
                            <div class="doan-sukien-muted">{{ $e->ThoiGianKetThuc }}</div>
                        </td>

                        <td class="event-location">
                            {{ $e->DiaDiem }}
                        </td>
                        <td>{{ $e->SoLuongToiDa ?? 'Không giới hạn' }}</td>

                        <td>
                            @php
                            $cls = match($e->TrangThai) {
                            'Open' => 'badge-open',
                            'Closed' => 'badge-closed',
                            'Draft' => 'badge-draft',
                            'Cancelled' => 'badge-cancel',
                            default => 'badge-closed'
                            };
                            @endphp
                            <span class="badge-status {{ $cls }}">{{ $e->TrangThai }}</span>
                        </td>

                        <td>
                            @if(!empty($imageMap[$e->MaSK]))
                            @php
                            $rel = $imageMap[$e->MaSK];
                            $abs = public_path($rel);
                            $v = file_exists($abs) ? filemtime($abs) : time();
                            @endphp

                            <img class="thumb" src="{{ asset($rel) }}?v={{ $v }}" alt="Ảnh sự kiện">
                            @else
                            <span class="doan-sukien-muted">Không có</span>
                            @endif
                        </td>

                        <td class="text-nowrap">
                            <button class="btn btn-sv btn-sv-ghost btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $e->MaSK }}">
                                Sửa
                            </button>

                            <form class="d-inline" method="POST"
                                action="{{ route('doan.sukien.delete') }}"
                                onsubmit="return confirm('Xóa sự kiện này?');">
                                @csrf
                                <input type="hidden" name="MaSK" value="{{ $e->MaSK }}">
                                <button class="btn btn-sv btn-sv-danger btn-sm" type="submit">Xóa</button>
                            </form>

                            <form class="d-inline" method="POST" action="{{ route('doan.sukien.toggle') }}">
                                @csrf
                                <input type="hidden" name="MaSK" value="{{ $e->MaSK }}">
                                @php
                                $toggleLabel = match($e->TrangThai) {
                                'Open' => 'Đóng',
                                'Closed' => 'Mở',
                                'Draft' => 'Mở',
                                'Cancelled' => 'Đã huỷ',
                                default => 'Mở',
                                };
                                $disabledToggle = ($e->TrangThai === 'Cancelled');
                                @endphp

                                <button class="btn btn-sv btn-sv-success btn-sm" type="submit" @disabled($disabledToggle)>
                                    {{ $toggleLabel }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center doan-sukien-muted">Chưa có sự kiện</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @if($events->hasPages())
            <div class="d-flex justify-content-end mt-3 pe-2">
                {{ $events->links('vendor.pagination.doan-sukien') }}
            </div>
            @endif
        </div>

    </div>
</div>

{{-- ===========================
    MODAL THÊM (Create)
=========================== --}}
<div class="modal fade" id="modalCreate" tabindex="-1"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('doan.sukien.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Thêm sự kiện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Tiêu đề</label>
                        <input class="form-control" name="TieuDe" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nội dung</label>
                        <textarea class="form-control" name="NoiDung" rows="4" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thời gian bắt đầu</label>
                            <input type="datetime-local" class="form-control" name="ThoiGianBatDau" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thời gian kết thúc</label>
                            <input type="datetime-local" class="form-control" name="ThoiGianKetThuc" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Địa điểm</label>
                            <input class="form-control" name="DiaDiem" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số lượng tối đa</label>
                            <input type="number" class="form-control" name="SoLuongToiDa" min="1">
                            <small class="text-muted">Để trống = không giới hạn</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="TrangThai" required>
                            @foreach(['Draft','Open','Closed','Cancelled'] as $st)
                            <option value="{{ $st }}" @selected($st==='Open' )>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Upload ảnh (nhiều ảnh)</label>
                        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                        <small class="text-muted">Chọn nhiều ảnh để hiển thị cho sự kiện.</small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-sv btn-sv-ghost" data-bs-dismiss="modal" type="button">Đóng</button>
                    <button class="btn btn-sv btn-sv-primary" type="submit">Thêm</button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ===========================
    MODAL SỬA (Edit) - ĐƯA RA NGOÀI TABLE
=========================== --}}
@foreach($events as $e)
<div class="modal fade" id="modalEdit{{ $e->MaSK }}" tabindex="-1"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Sửa sự kiện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('doan.sukien.update') }}"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="MaSK" value="{{ $e->MaSK }}">

                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Tiêu đề</label>
                        <input class="form-control" name="TieuDe" value="{{ $e->TieuDe }}" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Nội dung</label>
                        <textarea class="form-control" name="NoiDung" rows="4" required>{{ $e->NoiDung }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Thời gian bắt đầu</label>
                            <input type="datetime-local" class="form-control" name="ThoiGianBatDau"
                                value="{{ \Carbon\Carbon::parse($e->ThoiGianBatDau)->format('Y-m-d\TH:i') }}"
                                required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Thời gian kết thúc</label>
                            <input type="datetime-local" class="form-control" name="ThoiGianKetThuc"
                                value="{{ \Carbon\Carbon::parse($e->ThoiGianKetThuc)->format('Y-m-d\TH:i') }}"
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Địa điểm</label>
                            <input class="form-control" name="DiaDiem" value="{{ $e->DiaDiem }}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Số lượng tối đa</label>
                            <input type="number" class="form-control" name="SoLuongToiDa" min="1"
                                value="{{ $e->SoLuongToiDa }}">
                            <small class="text-muted">Để trống = không giới hạn</small>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="TrangThai" required>
                            @foreach(['Draft','Open','Closed','Cancelled'] as $st)
                            <option value="{{ $st }}" @selected($e->TrangThai === $st)>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Upload thêm ảnh (nhiều ảnh)</label>
                        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                        <small class="text-muted">Ảnh sẽ được thêm vào danh sách ảnh của sự kiện.</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sv btn-sv-ghost" data-bs-dismiss="modal" type="button">Đóng</button>
                    <button class="btn btn-sv btn-sv-primary" type="submit">Lưu</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach
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

            // tự biến mất sau 3.5 giây   
            setTimeout(hide, 3500);
        });
    })();
</script>
@endsection