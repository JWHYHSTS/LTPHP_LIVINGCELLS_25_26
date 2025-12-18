@extends('layouts.app')
@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sinhvien-dadangky.css') }}">
@endpush

<div class="container-fluid" style="padding-left:10px;">
    <div class="sv-reg-page">

        <div class="sv-reg-header">
            <div>
                <div class="page-title">
                    <h1>Sự kiện đã đăng ký</h1>
                    <p>Theo dõi lịch tham gia và trạng thái điểm danh của bạn.</p>
                </div>
            </div>

            <a class="btn-sv btn-sv-ghost" href="{{ route('sv.sukien.index') }}">
                Quay lại danh sách
            </a>
        </div>

        <div class="sv-table-wrap table-responsive">
            <table class="table sv-table align-middle">
                <thead>
                    <tr>
                        <th style="width:60px;">STT</th>
                        <th>Sự kiện</th>
                        <th style="width:260px;">Thời gian</th>
                        <th>Địa điểm</th>
                        <th style="width:190px;">Đăng ký lúc</th>
                        <th style="width:170px;">Điểm danh</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($rows as $i => $r)
                    <tr>
                        <td class="sv-col-stt">{{ $i+1 }}</td>
                        <td>
                            <div style="font-weight:800; letter-spacing:-0.01em;">
                                {{ $r->TieuDe }}
                            </div>
                        </td>
                        <td class="text-muted">
                            {{ $r->ThoiGianBatDau }} → {{ $r->ThoiGianKetThuc }}
                        </td>
                        <td>{{ $r->DiaDiem }}</td>
                        <td class="text-muted">{{ $r->DangKyLuc }}</td>
                        <td>
                            @if($r->DaDiemDanh)
                            <span class="sv-chip sv-chip-success">Đã điểm danh</span>
                            <div class="sv-chip-sub">{{ $r->DiemDanhLuc ?? '' }}</div>
                            @else
                            <span class="sv-chip sv-chip-warning">Chưa điểm danh</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding:26px; color:var(--muted);">
                            Chưa đăng ký sự kiện nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection