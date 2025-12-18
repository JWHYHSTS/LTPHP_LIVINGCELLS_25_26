@extends('layouts.app')
@section('content')

<style>
    .modal { z-index: 2000 !important; }
    .modal-backdrop { z-index: 1990 !important; }

    /* nếu sidebar bạn có class khác thì đổi lại cho đúng */
    .sidebar, .doan-sidebar { z-index: 1000 !important; }
</style>

<div class="container-fluid" style="padding-left:260px;">
    <h3>Quản lý sự kiện</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form class="d-flex mb-3" method="GET" action="{{ route('doan.sukien.index') }}">
        <input class="form-control me-2" name="q" value="{{ $q ?? '' }}" placeholder="Tìm tiêu đề/địa điểm">
        <button class="btn btn-outline-primary" type="submit">Tìm</button>

        <button type="button" class="btn btn-primary ms-2"
                data-bs-toggle="modal" data-bs-target="#modalCreate">
            Thêm
        </button>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th style="width:60px;">STT</th>
                <th>Tiêu đề</th>
                <th style="width:210px;">Thời gian</th>
                <th>Địa điểm</th>
                <th style="width:120px;">SL tối đa</th>
                <th style="width:120px;">Trạng thái</th>
                <th style="width:120px;">Ảnh</th>
                <th style="width:260px;">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @forelse($events as $i => $e)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>
                        <div class="fw-bold">{{ $e->TieuDe }}</div>
                        <div class="text-muted"
                             style="max-width:520px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $e->NoiDung }}
                        </div>
                    </td>
                    <td>
                        <div>{{ $e->ThoiGianBatDau }}</div>
                        <div class="text-muted">{{ $e->ThoiGianKetThuc }}</div>
                    </td>
                    <td>{{ $e->DiaDiem }}</td>
                    <td>{{ $e->SoLuongToiDa ?? 'Không giới hạn' }}</td>
                    <td>
                        @php
                            $badge = match($e->TrangThai) {
                                'Open' => 'bg-success',
                                'Closed' => 'bg-secondary',
                                'Draft' => 'bg-warning text-dark',
                                'Cancelled' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badge }}">{{ $e->TrangThai }}</span>
                    </td>
                    <td>
                        @if(!empty($imageMap[$e->MaSK]))
                            <img src="{{ asset($imageMap[$e->MaSK]) }}"
                                 style="width:90px;height:60px;object-fit:cover;border-radius:6px;">
                        @else
                            <span class="text-muted">Không có</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $e->MaSK }}">
                            Sửa
                        </button>

                        <form class="d-inline" method="POST"
                              action="{{ route('doan.sukien.delete') }}"
                              onsubmit="return confirm('Xóa sự kiện này?');">
                            @csrf
                            <input type="hidden" name="MaSK" value="{{ $e->MaSK }}">
                            <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                        </form>

                        <form class="d-inline" method="POST" action="{{ route('doan.sukien.toggle') }}">
                            @csrf
                            <input type="hidden" name="MaSK" value="{{ $e->MaSK }}">
                            <button class="btn btn-sm btn-outline-success" type="submit">
                                {{ $e->TrangThai === 'Open' ? 'Đóng' : 'Mở' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">Chưa có sự kiện</td></tr>
            @endforelse
            </tbody>
        </table>
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
                    <div class="mb-2">
                        <label class="form-label">Tiêu đề</label>
                        <input class="form-control" name="TieuDe" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Nội dung</label>
                        <textarea class="form-control" name="NoiDung" rows="4" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Thời gian bắt đầu</label>
                            <input type="datetime-local" class="form-control" name="ThoiGianBatDau" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Thời gian kết thúc</label>
                            <input type="datetime-local" class="form-control" name="ThoiGianKetThuc" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Địa điểm</label>
                            <input class="form-control" name="DiaDiem" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Số lượng tối đa</label>
                            <input type="number" class="form-control" name="SoLuongToiDa" min="1">
                            <small class="text-muted">Để trống = không giới hạn</small>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="TrangThai" required>
                            @foreach(['Draft','Open','Closed','Cancelled'] as $st)
                                <option value="{{ $st }}" @selected($st==='Open')>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Upload ảnh (nhiều ảnh)</label>
                        <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Đóng</button>
                    <button class="btn btn-primary" type="submit">Thêm</button>
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
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Đóng</button>
                        <button class="btn btn-primary" type="submit">Lưu</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endforeach

@endsection
