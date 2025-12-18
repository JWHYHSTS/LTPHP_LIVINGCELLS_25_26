@extends('layouts.app')
@section('content')

<div class="container-fluid" style="padding-left:260px;">
    <h3>Sự kiện tình nguyện</h3>

    <div class="mb-3">
        <a class="btn btn-outline-primary" href="{{ route('sv.sukien.dadangky') }}">Đã đăng ký</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        @forelse($events as $e)
            <div class="col-md-4 mb-3">
                <div class="card">
                    @if(isset($coverMap[$e->MaSK]))
                        <img src="{{ asset($coverMap[$e->MaSK]) }}" class="card-img-top"
                             style="height:170px;object-fit:cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $e->TieuDe }}</h5>
                        <div class="text-muted">{{ $e->ThoiGianBatDau }} → {{ $e->ThoiGianKetThuc }}</div>
                        <div class="mb-2">{{ $e->DiaDiem }}</div>

                        <form method="POST" action="{{ route('sv.sukien.dangky') }}">
                            @csrf
                            <input type="hidden" name="MaSK" value="{{ $e->MaSK }}">
                            <button class="btn btn-primary">Đăng ký</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-muted">Chưa có sự kiện đang mở.</div>
        @endforelse
    </div>
</div>
@endsection
