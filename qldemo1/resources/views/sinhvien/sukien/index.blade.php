@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('css/sinhvien-sukien-modern.css') }}">

<div class="sv-events-page">

    <div class="sv-events-header">
        <div class="page-title">
            <h1>Sự kiện</h1>
            <p>Đăng ký tham gia và theo dõi trạng thái điểm danh.</p>
        </div>

        <div class="sv-header-actions">
            <a class="sv-btn" href="{{ route('sv.sukien.dadangky') }}">
                Đã đăng ký
            </a>
        </div>
    </div>

@if(session('success'))
<div id="toast-success" class="toast-notification toast-success">
    <span class="toast-icon">✔</span>
    <span class="toast-message">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div id="toast-error" class="toast-notification toast-error">
    <span class="toast-icon">✖</span>
    <span class="toast-message">{{ session('error') }}</span>
</div>
@endif

    <div class="sv-toolbar">
        <div class="sv-search">
            <span class="sv-search-ico">
                <!-- icon search (inline svg) -->
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2" />
                    <path d="M16.5 16.5 21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </span>
            <input id="svEventSearch" type="text" placeholder="Tìm theo tiêu đề / địa điểm...">
        </div>
    </div>

    <div class="sv-grid" id="svEventGrid">
        @forelse($events as $e)

        @php
        // Controller của bạn đã map:
        // $e->is_registered, $e->is_expired, $e->is_full, $e->can_register
        $status = $e->TrangThai ?? '';
        @endphp

        <div class="sv-col sv-event-item"
            data-title="{{ mb_strtolower($e->TieuDe ?? '') }}"
            data-place="{{ mb_strtolower($e->DiaDiem ?? '') }}">

            <div class="sv-card">

                <div class="sv-card-media">
                    @if(isset($coverMap[$e->MaSK]))
                    <img src="{{ asset($coverMap[$e->MaSK]) }}" alt="cover">
                    @else
                    <img src="{{ asset('assets/default-event.jpg') }}" alt="cover">
                    @endif

                    <div class="sv-chip-row">
                        @if(($status) === 'Open')
                        <span class="sv-chip sv-chip-success">Open</span>
                        @elseif(($status) === 'Draft')
                        <span class="sv-chip sv-chip-neutral">Draft</span>
                        @elseif(($status) === 'Closed')
                        <span class="sv-chip sv-chip-neutral">Closed</span>
                        @else
                        <span class="sv-chip sv-chip-neutral">{{ $status ?: 'N/A' }}</span>
                        @endif

                        @if(!empty($e->is_registered))
                        <span class="sv-chip sv-chip-registered">Đã đăng ký</span>
                        @endif

                        @if(!empty($e->is_expired))
                        <span class="sv-chip sv-chip-warning">Đã kết thúc</span>
                        @endif

                        @if(!empty($e->is_full))
                        <span class="sv-chip sv-chip-danger">Hết chỗ</span>
                        @endif
                    </div>
                </div>

                <div class="sv-card-body">
                    <h5 class="sv-card-title">{{ $e->TieuDe }}</h5>

                    <div class="sv-meta">
                        <span>{{ $e->ThoiGianBatDau }} → {{ $e->ThoiGianKetThuc }}</span>
                        <span class="dot"></span>
                        <span>{{ $e->DiaDiem }}</span>
                    </div>
                </div>

                <div class="sv-card-footer">
                    @if(!empty($e->can_register))
                    <form method="POST" action="{{ route('sv.sukien.dangky') }}" style="flex:1;">
                        @csrf
                        <input type="hidden" name="MaSK" value="{{ $e->MaSK }}">
                        <button class="sv-cta sv-cta-primary" type="submit">
                            Đăng ký ngay
                        </button>
                    </form>
                    @else
                    @php
                    $disabledText = 'Không thể đăng ký';
                    if (!empty($e->is_registered)) $disabledText = 'Đã đăng ký';
                    else if (!empty($e->is_expired)) $disabledText = 'Sự kiện đã kết thúc';
                    else if (!empty($e->is_full)) $disabledText = 'Sự kiện đã đủ chỗ';
                    else if (($status ?? '') !== 'Open') $disabledText = 'Sự kiện chưa mở';
                    @endphp

                    <button class="sv-cta sv-cta-disabled" type="button" disabled>
                        {{ $disabledText }}
                    </button>
                    @endif
                </div>

            </div>
        </div>

        @empty
        <div class="sv-muted-box">
            Chưa có sự kiện phù hợp để hiển thị.
        </div>
        @endforelse
    </div>
</div>

<script>
    (function() {
        const input = document.getElementById('svEventSearch');
        const grid = document.getElementById('svEventGrid');
        if (!input || !grid) return;

        input.addEventListener('input', function() {
            const q = (this.value || '').trim().toLowerCase();
            const items = grid.querySelectorAll('.sv-event-item');
            items.forEach(it => {
                const title = it.getAttribute('data-title') || '';
                const place = it.getAttribute('data-place') || '';
                const ok = !q || title.includes(q) || place.includes(q);
                it.style.display = ok ? '' : 'none';
            });
        });
    })();
</script>

@endsection