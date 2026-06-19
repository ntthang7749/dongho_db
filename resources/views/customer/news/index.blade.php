@extends('layouts.customer')
@section('title', 'Tin Tức & Bài Viết')

@push('styles')
<style>
    /* ===== PAGE HERO ===== */
    .news-hero {
        background: linear-gradient(135deg, #0a0a0f 0%, #111128 50%, #0a0a0f 100%);
        padding: 56px 0;
        border-bottom: 1px solid rgba(201,168,76,0.12);
        position: relative;
    }
    .news-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 15% 60%, rgba(201,168,76,0.09) 0%, transparent 55%),
            radial-gradient(ellipse at 85% 30%, rgba(99,60,150,0.07) 0%, transparent 50%);
        pointer-events: none;
    }
    .news-hero h1 {
        font-family: 'Playfair Display', serif;
    }
    .hero-label {
        letter-spacing: 2px;
        color: var(--gold);
        background: rgba(201,168,76,0.1);
        border: 1px solid rgba(201,168,76,0.25);
    }

    /* ===== FEATURED NEWS CARD ===== */
    .news-featured {
        transition: transform 0.35s cubic-bezier(.4,0,.2,1), box-shadow 0.35s;
        text-decoration: none;
        color: inherit;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .news-featured:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 36px rgba(0,0,0,0.08) !important;
        color: inherit;
    }
    .news-featured img {
        transition: transform 0.5s ease;
    }
    .news-featured:hover img { 
        transform: scale(1.04); 
    }
    .featured-overlay {
        background: linear-gradient(to top, rgba(10,10,15,0.5), transparent);
    }
    .featured-badge {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
        letter-spacing: 1px;
    }
    .btn-read-more {
        color: var(--gold-dark);
        border: 1.5px solid rgba(201,168,76,0.3);
        transition: all 0.25s;
        background: rgba(201,168,76,0.02);
    }
    .btn-read-more:hover {
        background: var(--gold);
        border-color: var(--gold);
        color: #0a0a0f !important;
        box-shadow: 0 4px 14px rgba(201,168,76,0.25);
    }

    /* ===== STANDARD NEWS CARD ===== */
    .news-card {
        transition: all 0.35s cubic-bezier(.4,0,.2,1);
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 36px rgba(0,0,0,0.08) !important;
    }
    .news-card img {
        transition: transform 0.5s ease;
    }
    .news-card:hover img { 
        transform: scale(1.05); 
    }
    .news-card-title {
        font-family: 'Playfair Display', serif;
        color: #1a1a2e;
        transition: color 0.2s;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .news-card-title:hover { 
        color: var(--gold-dark) !important; 
    }
    
    /* ===== SECTION DIVIDER ===== */
    .section-label-line {
        height: 1px;
        background: linear-gradient(90deg, rgba(201,168,76,0.3), transparent);
    }
    .section-label-text {
        letter-spacing: 2px;
        color: var(--gold-dark);
    }
</style>
@endpush

@section('content')

{{-- ════════════ PAGE HERO ════════════ --}}
<div class="news-hero overflow-hidden text-white">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="hero-label d-inline-flex align-items-center gap-2 small fw-bold text-uppercase rounded-pill px-3 py-1 mb-3">
                    <i class="bi bi-newspaper text-gold"></i> Góc Nhìn Đồng Hồ
                </div>
                <h1 class="text-white fw-bold mb-2">Góc Nhìn <span style="color:var(--gold);">Tuyệt Tác</span></h1>
                <p class="small mb-0 text-white-50" style="max-width: 540px;">Cập nhật các tin tức mới nhất, đánh giá chuyên sâu và xu hướng thời trang đồng hồ cao cấp từ các chuyên gia hàng đầu.</p>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <nav class="mt-4">
            <ol class="breadcrumb mb-0 bg-transparent p-0 small">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none" style="color:rgba(201,168,76,0.7);"><i class="bi bi-house me-1"></i>Trang chủ</a>
                </li>
                <li class="breadcrumb-item active text-white-50">Tin tức</li>
            </ol>
        </nav>
    </div>
</div>

{{-- ════════════ MAIN CONTENT ════════════ --}}
<div class="bg-light py-5" style="min-height:60vh;">
    <div class="container">

        @forelse($newsList as $index => $item)

            {{-- FEATURED: First item gets big card layout --}}
            @if($index === 0)
            <div class="section-label d-flex align-items-center gap-3 mb-4">
                <span class="section-label-text small fw-bold text-uppercase"><i class="bi bi-stars me-1 text-gold"></i> Bài Viết Nổi Bật</span>
                <div class="section-label-line flex-grow-1"></div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-lg-7">
                    <a href="{{ route('news.show', $item->slug) }}" class="news-featured card bg-white rounded-4 overflow-hidden d-flex flex-column h-100 shadow-sm border-0">
                        <div class="position-relative overflow-hidden flex-shrink-0 bg-dark">
                            <div class="ratio ratio-21x9">
                                @if($item->thumbnail)
                                    <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="{{ $item->title }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-dark text-white fs-1">📰</div>
                                @endif
                            </div>
                            @if($item->thumbnail)
                                <div class="featured-overlay position-absolute bottom-0 start-0 w-100 h-50" style="z-index: 1;"></div>
                            @endif
                            <span class="featured-badge position-absolute top-0 start-0 m-3 px-3 py-1.5 small fw-bold text-uppercase rounded-pill shadow-sm" style="z-index: 2;">⭐ Nổi Bật</span>
                        </div>
                        <div class="card-body p-4 flex-grow-1 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex gap-3 small text-muted mb-2">
                                    <span><i class="bi bi-person me-1 text-gold"></i>{{ $item->author->name ?? 'Admin' }}</span>
                                    <span><i class="bi bi-calendar3 me-1 text-gold"></i>{{ $item->created_at->format('d/m/Y') }}</span>
                                    <span><i class="bi bi-chat me-1 text-gold"></i>{{ $item->comments->where('status','approved')->count() }}</span>
                                </div>
                                <h2 class="featured-title fs-5 fw-bold text-dark mb-3" style="font-family:'Playfair Display', serif; line-height: 1.4;">{{ $item->title }}</h2>
                                @if($item->summary)
                                    <p class="small text-secondary mb-4" style="line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ $item->summary }}</p>
                                @endif
                            </div>
                            <span class="btn-read-more d-inline-flex align-items-center gap-2 small fw-semibold text-decoration-none rounded-pill px-4 py-2 w-auto me-auto">
                                Đọc bài viết <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </a>
                </div>

                {{-- 2nd & 3rd items in right column --}}
                <div class="col-lg-5 d-flex flex-column gap-4">
                    @if(isset($newsList[$index + 1]))
                    @php $side1 = $newsList[$index + 1]; @endphp
                    <a href="{{ route('news.show', $side1->slug) }}" class="news-featured card bg-white rounded-4 overflow-hidden d-flex flex-column h-100 shadow-sm border-0" style="flex:1;">
                        <div class="d-flex h-100 gap-0">
                            <div class="flex-shrink-0 overflow-hidden bg-dark" style="width:130px;">
                                @if($side1->thumbnail)
                                    <img src="{{ asset('storage/'.$side1->thumbnail) }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-dark text-white fs-3">📰</div>
                                @endif
                            </div>
                            <div class="card-body p-3 flex-grow-1 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="small text-muted mb-1">
                                        <span><i class="bi bi-calendar3 me-1 text-gold"></i>{{ $side1->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <h3 class="fs-6 fw-bold text-dark mb-2" style="font-family:'Playfair Display', serif; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">{{ $side1->title }}</h3>
                                </div>
                                <span class="btn-read-more d-inline-flex align-items-center gap-2 small fw-semibold text-decoration-none rounded-pill px-3 py-1.5 w-auto me-auto" style="font-size:0.75rem;">
                                    Đọc tiếp <i class="bi bi-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                    @endif

                    @if(isset($newsList[$index + 2]))
                    @php $side2 = $newsList[$index + 2]; @endphp
                    <a href="{{ route('news.show', $side2->slug) }}" class="news-featured card bg-white rounded-4 overflow-hidden d-flex flex-column h-100 shadow-sm border-0" style="flex:1;">
                        <div class="d-flex h-100 gap-0">
                            <div class="flex-shrink-0 overflow-hidden bg-dark" style="width:130px;">
                                @if($side2->thumbnail)
                                    <img src="{{ asset('storage/'.$side2->thumbnail) }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-dark text-white fs-3">📰</div>
                                @endif
                            </div>
                            <div class="card-body p-3 flex-grow-1 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="small text-muted mb-1">
                                        <span><i class="bi bi-calendar3 me-1 text-gold"></i>{{ $side2->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <h3 class="fs-6 fw-bold text-dark mb-2" style="font-family:'Playfair Display', serif; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">{{ $side2->title }}</h3>
                                </div>
                                <span class="btn-read-more d-inline-flex align-items-center gap-2 small fw-semibold text-decoration-none rounded-pill px-3 py-1.5 w-auto me-auto" style="font-size:0.75rem;">
                                    Đọc tiếp <i class="bi bi-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Divider before remaining --}}
            @if($newsList->count() > 3)
            <div class="section-label d-flex align-items-center gap-3 mb-4 mt-2">
                <span class="section-label-text small fw-bold text-uppercase"><i class="bi bi-grid me-1 text-gold"></i> Tất Cả Bài Viết</span>
                <div class="section-label-line flex-grow-1"></div>
            </div>
            <div class="row g-4">
            @endif

            @elseif($index >= 3)
            {{-- Items 4+ go into normal 3-col grid --}}
            <div class="col-md-4">
                <div class="news-card card bg-white rounded-4 overflow-hidden h-100 shadow-sm border-0 d-flex flex-column justify-content-between">
                    <div>
                        <div class="position-relative overflow-hidden bg-dark">
                            <div class="ratio ratio-16x9">
                                @if($item->thumbnail)
                                    <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="{{ $item->title }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white fs-2">📰</div>
                                @endif
                            </div>
                            <span class="badge position-absolute bottom-0 start-0 m-3 px-2 py-1.5 small fw-semibold bg-dark bg-opacity-75 border border-secondary border-opacity-20 text-white rounded-2" style="z-index: 2;">
                                <i class="bi bi-calendar3 me-1 text-gold"></i>{{ $item->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-2 small text-muted mb-2">
                                <i class="bi bi-person text-gold"></i>
                                <span>{{ $item->author->name ?? 'Admin' }}</span>
                                <span class="mx-1 opacity-25">|</span>
                                <i class="bi bi-chat text-gold"></i>
                                <span>{{ $item->comments->where('status','approved')->count() }}</span>
                            </div>
                            <a href="{{ route('news.show', $item->slug) }}" class="news-card-title fw-bold text-decoration-none fs-6 mb-2">
                                {{ $item->title }}
                            </a>
                            @if($item->summary)
                                <p class="small text-secondary mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">{{ Str::limit($item->summary, 100) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-4 pt-0">
                        <a href="{{ route('news.show', $item->slug) }}" class="btn-read-more d-inline-flex align-items-center gap-1.5 small fw-semibold text-decoration-none rounded-pill px-3 py-1.5">
                            Đọc tiếp <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif

        @empty
        <div class="empty-state text-center py-5 px-3 bg-white rounded-4 shadow-sm border border-light">
            <div class="d-inline-flex align-items-center justify-content-center bg-light text-gold rounded-4 mb-4 fs-2" style="width: 80px; height: 80px;">
                <i class="bi bi-journal-x"></i>
            </div>
            <h5 class="fw-bold mb-2" style="font-family:'Playfair Display',serif; color:#1a1a2e; font-size:1.3rem;">Chưa có bài viết nào</h5>
            <p class="small text-muted mb-4">Hệ thống đang cập nhật chuyên mục bài viết. Hãy quay lại sau nhé!</p>
            <a href="{{ route('home') }}" class="btn btn-dark border-0 px-4 py-2.5 rounded-3 fw-bold small text-white" style="background:#1a1a2e;">
                <i class="bi bi-house me-1"></i> Về trang chủ
            </a>
        </div>
        @endforelse

        {{-- Close the 3-col grid if it was opened --}}
        @if($newsList->count() > 3)
            </div>
        @endif

        {{-- ===== PAGINATION ===== --}}
        @if($newsList->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $newsList->links() }}
        </div>
        @endif

    </div>
</div>

@endsection