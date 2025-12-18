@extends('layouts.app')
@section('content')


<div class="container-fluid" style="padding-left:260px;">
    <h3>Sự kiện đã đăng ký</h3>

    <div class="mb-3">
        <a class="btn btn-outline-secondary" href="{{ route('sv.sukien.index') }}">Quay lại danh sách</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th style="width:60px;">STT</th>
                <th>Sự kiện</th>
                <th style="width:210px;">Thời gian</th>
                <th>Địa điểm</th>
                <th style="width:180px;">Đăng ký lúc</th>
                <th style="width:140px;">Điểm danh</th>
            </tr>
            </thead>
            <tbody>
            @forelse($rows as $i => $r)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $r->TieuDe }}</td>
                    <td>{{ $r->ThoiGianBatDau }} → {{ $r->ThoiGianKetThuc }}</td>
                    <td>{{ $r->DiaDiem }}</td>
                    <td>{{ $r->DangKyLuc }}</td>
                    <td>
                        @if($r->DaDiemDanh)
                            <span class="badge bg-success">Đã điểm danh</span>
                            <div class="text-muted" style="font-size:12px;">{{ $r->DiemDanhLuc ?? '' }}</div>
                        @else
                            <span class="badge bg-warning text-dark">Chưa điểm danh</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Chưa đăng ký sự kiện nào.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
