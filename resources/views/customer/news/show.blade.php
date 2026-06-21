@extends('layouts.customer')
@section('title', $news->title)

@push('styles')
<style>
    /* ===== ARTICLE HERO ===== */
    .article-hero {
        background: linear-gradient(135deg, #0a0a0f, #111128);
        padding: 56px 0;
        position: relative;
    }
    .article-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 10% 80%, rgba(201,168,76,0.08) 0%, transparent 55%),
            radial-gradient(ellipse at 90% 20%, rgba(99,60,150,0.07) 0%, transparent 50%);
    }
    .article-hero h1 {
        font-family: 'Playfair Display', serif;
    }
    .hero-breadcrumb a { color: rgba(201,168,76,0.7); transition: color 0.2s; }
    .hero-breadcrumb a:hover { color: var(--gold); }
    .hero-breadcrumb-sep { color: rgba(201,168,76,0.25); }

    /* Category pill */
    .article-category-pill {
        background: rgba(201,168,76,0.12);
        border: 1px solid rgba(201,168,76,0.25);
        color: var(--gold);
        letter-spacing: 1.5px;
    }

    /* Meta bar */
    .meta-icon {
        width: 28px; height: 28px;
        background: rgba(201,168,76,0.1);
        border: 1px solid rgba(201,168,76,0.2);
        color: var(--gold);
    }

    /* ===== ARTICLE CONTENT ===== */
    .article-content {
        font-size: 1.05rem;
        line-height: 1.85;
        color: #2c2c2c;
    }
    .article-content blockquote {
        border-left: 3px solid var(--gold) !important;
        border-radius: 0 12px 12px 0;
        background: #fdfdfb;
    }

    /* Share bar */
    .share-btn {
        transition: all 0.2s;
    }
    .share-btn.fb { background: #e7f0ff; color: #1877f2; }
    .share-btn.fb:hover { background: #1877f2; color: #fff; }
    .share-btn.tw { background: #e7f7ff; color: #1da1f2; }
    .share-btn.tw:hover { background: #1da1f2; color: #fff; }
    .share-btn.cp { background: #f4f3ed; color: #7a6a3a; }
    .share-btn.cp:hover { background: var(--gold); color: #0a0a0f; }

    /* ===== COMMENTS ===== */
    .comments-count {
        width: 26px; height: 26px;
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
    }
    .comment-avatar {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
        box-shadow: 0 4px 10px rgba(201,168,76,0.2);
    }
    .comment-bubble {
        background: #f8f7f4;
    }
    .comment-textarea:focus {
        border-color: var(--gold) !important;
        box-shadow: 0 0 0 3px rgba(201,168,76,0.15) !important;
    }
    .btn-comment-submit {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
        transition: all 0.25s;
        box-shadow: 0 4px 14px rgba(201,168,76,0.25);
    }
    .btn-comment-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(201,168,76,0.4);
    }

    /* ===== SIDEBAR ===== */
    .sidebar-header {
        background: linear-gradient(135deg, #0d0d18, #1a1a35);
        border-bottom: 1px solid rgba(201,168,76,0.15);
        color: rgba(240,236,224,0.9);
    }
    .sidebar-item {
        transition: background 0.2s;
    }
    .sidebar-item:hover { 
        background: #fdfdfb; 
    }
    .sidebar-item-title {
        color: #1a1a2e;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s;
    }
    .sidebar-item:hover .sidebar-item-title { 
        color: var(--gold-dark) !important; 
    }

    /* Back button */
    .btn-back-news {
        color: rgba(240,236,224,0.65);
        transition: color 0.2s;
    }
    .btn-back-news:hover { 
        color: var(--gold) !important; 
    }
</style>
@endpush

@section('content')

{{-- ════════════ ARTICLE HERO ════════════ --}}
<div class="article-hero text-white">
    <div class="container position-relative">

        {{-- Back & Breadcrumb --}}
        <a href="{{ route('news.index') }}" class="btn-back-news d-inline-flex align-items-center gap-2 small fw-semibold text-decoration-none mb-3">
            <i class="bi bi-arrow-left"></i> Quay lại Tin Tức
        </a>

        <div class="hero-breadcrumb d-flex align-items-center gap-2 small mb-3">
            <a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a>
            <span class="hero-breadcrumb-sep"><i class="bi bi-chevron-right" style="font-size:0.6rem;"></i></span>
            <a href="{{ route('news.index') }}" class="text-decoration-none">Tin tức</a>
            <span class="hero-breadcrumb-sep"><i class="bi bi-chevron-right" style="font-size:0.6rem;"></i></span>
            <span class="text-white-50">{{ Str::limit($news->title, 40) }}</span>
        </div>

        {{-- Category --}}
        <div class="article-category-pill d-inline-flex align-items-center gap-2 small fw-bold text-uppercase rounded-pill px-3 py-1 mb-3">
            <i class="bi bi-newspaper"></i> Chuyên Đề
        </div>

        {{-- Title --}}
        <h1 class="article-hero-title text-white fw-bold mb-3 fs-3 fs-md-2">{{ $news->title }}</h1>

        {{-- Meta --}}
        <div class="d-flex align-items-center flex-wrap gap-3 py-2">
            <div class="d-flex align-items-center gap-2 small text-white-50">
                <div class="meta-icon d-flex align-items-center justify-content-center rounded-2 flex-shrink-0"><i class="bi bi-person-fill"></i></div>
                <span>{{ $news->author->name ?? 'Admin' }}</span>
            </div>
            <div class="d-flex align-items-center gap-2 small text-white-50">
                <div class="meta-icon d-flex align-items-center justify-content-center rounded-2 flex-shrink-0"><i class="bi bi-calendar3"></i></div>
                <span>{{ $news->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="d-flex align-items-center gap-2 small text-white-50">
                <div class="meta-icon d-flex align-items-center justify-content-center rounded-2 flex-shrink-0"><i class="bi bi-chat-fill"></i></div>
                <span>{{ $news->comments->where('status','approved')->count() }} bình luận</span>
            </div>
            <div class="d-flex align-items-center gap-2 small text-white-50">
                <div class="meta-icon d-flex align-items-center justify-content-center rounded-2 flex-shrink-0"><i class="bi bi-clock"></i></div>
                <span>{{ $news->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ════════════ MAIN LAYOUT ════════════ --}}
<div class="article-layout bg-light py-5">
    <div class="container">
        <div class="row g-4">

            {{-- ── ARTICLE CONTENT ── --}}
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 p-4 p-md-5 shadow-sm bg-white">

                    {{-- Main Thumbnail image --}}
                    @if($news->thumbnail)
                    <div class="position-relative overflow-hidden rounded-4 mb-4 shadow-sm ratio ratio-16x9 bg-dark" style="border: 1px solid rgba(201, 168, 76, 0.1);">
                        <img src="{{ asset('storage/'.$news->thumbnail) }}" alt="{{ $news->title }}" class="w-100 h-100 object-fit-cover">
                    </div>
                    @endif

                    {{-- Summary --}}
                    @if($news->summary)
                    <div class="border-start border-3 border-warning bg-light p-3 p-md-4 rounded-end-4 mb-4">
                        <p class="text-secondary small mb-0 fw-medium" style="line-height:1.7; font-style:italic;">
                            {{ $news->summary }}
                        </p>
                    </div>
                    @endif

                    {{-- Article body --}}
                    <div class="article-content mb-4 text-justify">
                        {!! nl2br(e($news->content)) !!}
                    </div>

                    {{-- Share bar --}}
                    <div class="share-bar d-flex align-items-center gap-2 py-3 border-top border-bottom flex-wrap my-4">
                        <span class="small fw-semibold text-uppercase text-muted me-2">Chia sẻ</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           target="_blank" class="share-btn fb d-inline-flex align-items-center gap-2 small fw-semibold rounded-3 border-0 px-3 py-2 text-decoration-none">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->title) }}"
                           target="_blank" class="share-btn tw d-inline-flex align-items-center gap-2 small fw-semibold rounded-3 border-0 px-3 py-2 text-decoration-none">
                            <i class="bi bi-twitter-x"></i> Twitter
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href); this.innerHTML='<i class=\'bi bi-check-lg\'></i> Đã sao chép!';"
                                class="share-btn cp d-inline-flex align-items-center gap-2 small fw-semibold rounded-3 border-0 px-3 py-2">
                            <i class="bi bi-link-45deg"></i> Sao chép link
                        </button>
                    </div>

                </div>

                {{-- ── COMMENTS SECTION ── --}}
                <div class="card border-0 rounded-4 p-4 p-md-5 shadow-sm mt-4 bg-white">
                    <div class="comments-section">

                        <div class="fs-5 fw-bold mb-4 d-flex align-items-center gap-2 text-dark" style="font-family: 'Playfair Display', serif;">
                            <i class="bi bi-chat-square-text text-gold"></i>
                            Bình Luận
                            <span class="comments-count d-inline-flex align-items-center justify-content-center rounded-2 fw-bold small">{{ $news->comments->where('status','approved')->count() }}</span>
                        </div>

                        {{-- Comment list --}}
                        @forelse($news->comments->where('status','approved') as $comment)
                        <div class="comment-item d-flex gap-3 mb-4">
                            <div class="comment-avatar d-flex align-items-center justify-content-center rounded-3 fw-bold shadow-sm flex-shrink-0">
                                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="comment-bubble p-3 border border-light rounded-3">
                                    <div class="fw-bold small text-dark mb-1 d-flex align-items-center gap-2">
                                        {{ $comment->user->name ?? 'Ẩn danh' }}
                                        @if($comment->sentiment === 'positive')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.65rem; padding: 2px 6px;">😊 Tích cực</span>
                                        @elseif($comment->sentiment === 'negative')
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.65rem; padding: 2px 6px;">😠 Tiêu cực</span>
                                        @endif
                                    </div>
                                    <div class="small text-muted mb-2" style="font-size: 0.75rem;">
                                        <i class="bi bi-clock me-1 text-gold"></i>
                                        {{ $comment->created_at->diffForHumans() }}
                                    </div>
                                    <p class="small text-secondary m-0" style="line-height: 1.5;">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-chat-dots fs-3 text-muted opacity-50 d-block mb-2"></i>
                            <span class="small">Chưa có bình luận nào. Hãy là người đầu tiên chia sẻ cảm xúc nhé!</span>
                        </div>
                        @endforelse

                        {{-- Comment Form --}}
                        @auth
                        <div class="border rounded-4 p-4 mt-4 bg-light bg-opacity-10 border-light" style="border: 1px solid rgba(201, 168, 76, 0.15) !important;">
                            <div class="fs-6 fw-bold mb-3 text-dark" style="font-family: 'Playfair Display', serif;">
                                <i class="bi bi-pencil-square me-2 text-gold"></i>Viết Bình Luận
                            </div>
                            <form method="POST" action="{{ route('comments.store') }}">
                                @csrf
                                <input type="hidden" name="news_id" value="{{ $news->id }}">
                                <div class="mb-3">
                                    <textarea name="content" rows="4" class="comment-textarea form-control p-3 border rounded-3 small bg-white text-dark"
                                              placeholder="Chia sẻ ý kiến của bạn về bài viết này..."
                                              required></textarea>
                                </div>
                                <button type="submit" class="btn-comment-submit btn px-4 py-2 border-0 fw-bold rounded-3">
                                    <i class="bi bi-send-fill me-1"></i> Gửi Bình Luận
                                </button>
                            </form>
                        </div>
                        @else
                        <div class="alert alert-warning border-0 rounded-4 p-3 d-flex align-items-center gap-3 bg-warning bg-opacity-10 text-dark small mt-4">
                            <i class="bi bi-shield-lock-fill text-warning fs-5 flex-shrink-0"></i>
                            <span>
                                Vui lòng <a href="{{ route('login') }}" class="text-gold-dark fw-bold text-decoration-none">đăng nhập</a> để tham gia thảo luận và gửi bình luận của bạn!
                            </span>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- ── SIDEBAR ── --}}
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 overflow-hidden shadow-sm position-sticky bg-white" style="top: 80px;">
                    <div class="sidebar-header p-3 bg-dark">
                        <p class="small fw-bold text-uppercase m-0 d-flex align-items-center gap-2">
                            <i class="bi bi-stars text-gold"></i> Bài Viết Mới Nhất
                        </p>
                    </div>
                    @foreach($recentNews as $item)
                    <a href="{{ route('news.show', $item->slug) }}" class="sidebar-item d-flex gap-3 p-3 border-bottom text-decoration-none">
                        @if($item->thumbnail)
                            <img src="{{ asset('storage/'.$item->thumbnail) }}"
                                 class="rounded-2 flex-shrink-0 object-fit-cover" style="width:64px; height:52px;" alt="{{ $item->title }}">
                        @else
                            <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0 text-white bg-dark fs-5" style="width:64px; height:52px;">📰</div>
                        @endif
                        <div style="flex:1; min-width:0;">
                            <div class="sidebar-item-title small fw-semibold mb-1">{{ $item->title }}</div>
                            <div class="sidebar-item-date small text-muted d-flex align-items-center gap-1" style="font-size:0.75rem;">
                                <i class="bi bi-calendar3 text-gold"></i>
                                {{ $item->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Back to list --}}
                <div class="mt-4 text-center">
                    <a href="{{ route('news.index') }}" class="btn btn-white border shadow-sm rounded-4 px-4 py-2.5 small fw-bold text-secondary text-decoration-none d-inline-flex align-items-center gap-2" style="background:#fff; border: 1.5px solid rgba(201, 168, 76, 0.25) !important; transition: all 0.25s;">
                        <i class="bi bi-grid-3x3-gap text-gold"></i> Xem tất cả bài viết
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection