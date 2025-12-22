@extends('layouts.app')
@section('content')


<link rel="stylesheet" href="{{ asset('css/sinhvien-sukien-modern.css') }}">
<link rel="stylesheet" href="{{ asset('css/sv-event-modal-modern.css') }}">


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

    {{-- Toast --}}
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
        // Status
        $status = $e->TrangThai ?? '';

        // Cover URL (asset đầy đủ để JS dùng trực tiếp)
        $coverUrl = isset($coverMap[$e->MaSK])
        ? asset($coverMap[$e->MaSK])
        : asset('assets/default-event.jpg');

        // Nội dung
        $noiDung = $e->NoiDung ?? '';
        @endphp

        <div class="sv-col sv-event-item"
            data-title="{{ mb_strtolower($e->TieuDe ?? '') }}"
            data-place="{{ mb_strtolower($e->DiaDiem ?? '') }}"

            {{-- dữ liệu cho modal --}}
            data-mask="{{ $e->MaSK }}"
            data-mtitle="{{ e($e->TieuDe ?? '') }}"
            data-mtime="{{ e(($e->ThoiGianBatDau ?? '') . ' → ' . ($e->ThoiGianKetThuc ?? '')) }}"
            data-mplace="{{ e($e->DiaDiem ?? '') }}"
            data-mcontent="{{ e($noiDung) }}"
            data-mcover="{{ $coverUrl }}"
            data-mstatus="{{ e($status) }}"
            data-mregistered="{{ !empty($e->is_registered) ? 1 : 0 }}"
            data-mexpired="{{ !empty($e->is_expired) ? 1 : 0 }}"
            data-mfull="{{ !empty($e->is_full) ? 1 : 0 }}"
            data-mcanregister="{{ !empty($e->can_register) ? 1 : 0 }}"
            data-mcapacity="{{ e(($e->SoLuongToiDa ?? '—')) }}">
            <div class="sv-card">

                {{-- Vùng click để mở modal (KHÔNG ảnh hưởng nút đăng ký ở footer) --}}
                <div class="js-open-modal" style="cursor:pointer;">
                    <div class="sv-card-media">
                        <img src="{{ $coverUrl }}" alt="cover">

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

{{-- ===================== MODAL (FORM) XEM CHI TIẾT SỰ KIỆN ===================== --}}
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header">
                <h5 class="modal-title" id="mTitle">Chi tiết sự kiện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <div class="modal-body" style="padding:0;">
                <div style="height:260px; background:#f3f4f6;">
                    <img id="mCover" src="" alt="cover"
                        style="width:100%; height:100%; object-fit:cover; display:block;">
                </div>

                <div style="padding:16px 18px;">
                    <div id="mChips" style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:10px;"></div>

                    <div class="sv-meta" style="margin-bottom:12px;">
                        <span id="mTime"></span>
                        <span class="dot"></span>
                        <span id="mPlace"></span>
                        <span class="dot"></span>
                        <span id="mCapacity"></span>
                    </div>

                    <hr style="margin:12px 0;">

                    {{-- Nội dung dạng text: giữ xuống dòng bằng white-space:pre-line --}}
                    <div id="mContent" style="line-height:1.7; white-space:pre-line;"></div>
                </div>
            </div>

            <div class="modal-footer" style="justify-content:space-between;">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>

                {{-- Form đăng ký trong modal --}}
                <form id="mRegisterForm" method="POST" action="{{ route('sv.sukien.dangky') }}" style="margin:0;">
                    @csrf
                    <input type="hidden" name="MaSK" id="mMaSK" value="">
                    <button type="submit" class="btn btn-primary" id="mRegisterBtn">Đăng ký ngay</button>
                </form>

                <button type="button" class="btn btn-secondary" id="mDisabledBtn" disabled style="display:none;">
                    Không thể đăng ký
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===================== JS ===================== --}}
<script>
    // Search filter
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

    // Modal details
    (function() {
        const modalEl = document.getElementById('eventDetailModal');
        if (!modalEl) return;

        const mTitle = document.getElementById('mTitle');
        const mCover = document.getElementById('mCover');
        const mTime = document.getElementById('mTime');
        const mPlace = document.getElementById('mPlace');
        const mCapacity = document.getElementById('mCapacity');
        const mContent = document.getElementById('mContent');
        const mChips = document.getElementById('mChips');

        const mMaSK = document.getElementById('mMaSK');
        const mRegForm = document.getElementById('mRegisterForm');
        const mRegBtn = document.getElementById('mRegisterBtn');
        const mDisabled = document.getElementById('mDisabledBtn');

        function chip(html) {
            const span = document.createElement('span');
            span.innerHTML = html;
            return span.firstElementChild;
        }

        function buildStatusChip(status) {
            if (status === 'Open') return '<span class="sv-chip sv-chip-success">Open</span>';
            if (status === 'Draft') return '<span class="sv-chip sv-chip-neutral">Draft</span>';
            if (status === 'Closed') return '<span class="sv-chip sv-chip-neutral">Closed</span>';
            return `<span class="sv-chip sv-chip-neutral">${status || 'N/A'}</span>`;
        }

        document.querySelectorAll('.sv-event-item .js-open-modal').forEach(el => {
            el.addEventListener('click', function() {
                const card = this.closest('.sv-event-item');
                if (!card) return;

                const MaSK = card.dataset.mask || '';
                const title = card.dataset.mtitle || '';
                const time = card.dataset.mtime || '';
                const place = card.dataset.mplace || '';
                const content = card.dataset.mcontent || '';
                const cover = card.dataset.mcover || '';
                const status = card.dataset.mstatus || '';
                const capacity = card.dataset.mcapacity || '—';

                const registered = card.dataset.mregistered === '1';
                const expired = card.dataset.mexpired === '1';
                const full = card.dataset.mfull === '1';
                const canReg = card.dataset.mcanregister === '1';

                // Fill
                mTitle.textContent = title || 'Chi tiết sự kiện';
                mCover.src = cover;
                mTime.textContent = time;
                mPlace.textContent = place;
                mCapacity.textContent = 'SL tối đa: ' + capacity;
                mContent.textContent = new DOMParser().parseFromString(content, 'text/html').documentElement.textContent;

                // Chips
                mChips.innerHTML = '';
                mChips.appendChild(chip(buildStatusChip(status)));
                if (registered) mChips.appendChild(chip('<span class="sv-chip sv-chip-registered">Đã đăng ký</span>'));
                if (expired) mChips.appendChild(chip('<span class="sv-chip sv-chip-warning">Đã kết thúc</span>'));
                if (full) mChips.appendChild(chip('<span class="sv-chip sv-chip-danger">Hết chỗ</span>'));

                // Register controls
                mMaSK.value = MaSK;

                if (canReg) {
                    mRegForm.style.display = '';
                    mDisabled.style.display = 'none';
                    mRegBtn.textContent = 'Đăng ký ngay';
                } else {
                    mRegForm.style.display = 'none';
                    mDisabled.style.display = '';
                    let text = 'Không thể đăng ký';
                    if (registered) text = 'Đã đăng ký';
                    else if (expired) text = 'Sự kiện đã kết thúc';
                    else if (full) text = 'Sự kiện đã đủ chỗ';
                    else if (status !== 'Open') text = 'Sự kiện chưa mở';
                    mDisabled.textContent = text;
                }

                // Open modal (Bootstrap 5)
                if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                    console.error('Bootstrap Modal chưa được load. Hãy kiểm tra layouts.app đã include bootstrap.bundle.min.js chưa.');
                    return;
                }
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            });
        });
    })();
</script>

@endsection