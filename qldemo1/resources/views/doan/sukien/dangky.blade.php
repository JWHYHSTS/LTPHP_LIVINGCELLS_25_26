@extends('layouts.app')
@section('content')

@php
    // chống lỗi Undefined variable nếu controller chưa truyền
    $events = $events ?? collect();
    $rows   = $rows ?? collect();
    $MaSK   = $MaSK ?? request('MaSK');
@endphp

<div class="container-fluid" style="padding-left:260px;">
    <h3>Danh sách đăng ký sự kiện</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form class="row g-2 mb-3" method="GET" action="{{ route('doan.sukien.dangky.index') }}">
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
            <button class="btn btn-outline-primary">Lọc</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th style="width:60px;">STT</th>
                <th style="width:150px;">MSSV</th>
                <th>Họ tên</th>
                <th style="width:140px;">Lớp</th>
                <th style="width:180px;">Đăng ký lúc</th>
                <th style="width:140px;">Trạng thái</th>
                <th style="width:140px;">Điểm danh</th>
                <th style="width:220px;">Thao tác</th>
            </tr>
            </thead>

            <tbody>
            @forelse($rows as $i => $r)
    @php
        $st = $r->TrangThaiDangKy ?? 'Registered';
        $checked = (int)($r->DaDiemDanh ?? 0) === 1;
        $maSkRow = $r->MaSK ?? $MaSK;   // ƯU TIÊN MaSK từ row (đúng nhất)
    @endphp

    <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $r->MaSV }}</td>
        <td>{{ $r->HoTen }}</td>
        <td>{{ $r->Lop }}</td>
        <td>{{ $r->DangKyLuc }}</td>

        {{-- Trạng thái đăng ký --}}
        <td>
            @if($st === 'Cancelled')
                <span class="badge bg-secondary">Cancelled</span>
            @else
                <span class="badge bg-primary">Registered</span>
            @endif
        </td>

        {{-- Điểm danh --}}
        <td>
            @if($checked)
                <span class="badge bg-success">Đã điểm danh</span>
                @if(!empty($r->DiemDanhLuc))
                    <div class="text-muted" style="font-size:12px;">
                        {{ $r->DiemDanhLuc }}
                    </div>
                @endif
            @else
                <span class="badge bg-warning text-dark">Chưa điểm danh</span>
            @endif
        </td>

        {{-- Thao tác điểm danh --}}
        <td>
            @if($st === 'Cancelled')
                <span class="text-muted">SV đã hủy</span>
            @else
                <form class="d-inline" method="POST" action="{{ route('doan.sukien.diemdanh') }}">
                    @csrf
                    <input type="hidden" name="MaSK" value="{{ $maSkRow }}">
                    <input type="hidden" name="MaSV" value="{{ $r->MaSV }}">
                    <input type="hidden" name="action" value="{{ $checked ? 'checkout' : 'checkin' }}">
                    <button class="btn btn-sm {{ $checked ? 'btn-outline-danger' : 'btn-outline-success' }}">
                        {{ $checked ? 'Hủy điểm danh' : 'Điểm danh' }}
                    </button>
                </form>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center text-muted">
            Chọn sự kiện để xem danh sách đăng ký
        </td>
    </tr>
@endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
