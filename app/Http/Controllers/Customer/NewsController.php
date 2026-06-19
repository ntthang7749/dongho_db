<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Jobs\ModerateComment;
use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $newsList = News::where('is_active', true)
            ->with('author')->latest()->paginate(9);

        return view('customer.news.index', compact('newsList'));
    }

    public function show(string $slug)
    {
        $news = News::where('slug', $slug)
            ->where('is_active', true)
            ->with(['author', 'comments' => fn ($q) => $q->where('status', 'approved')->with('user')])
            ->firstOrFail();

        $recentNews = News::where('is_active', true)
            ->where('id', '!=', $news->id)
            ->latest()->take(5)->get();

        return view('customer.news.show', compact('news', 'recentNews'));
    }

    public function storeComment(Request $request)
    {
        $request->validate([
            'news_id' => 'required|exists:news,id',
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'news_id' => $request->news_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'status' => 'pending', // mặc định pending — Job sẽ tự duyệt nếu confidence đủ cao
            'is_spam' => false,
        ]);

        // AI moderation: chạy đồng bộ để có kết quả ngay (DEV).
        // Production: dùng dispatch() + queue:work cho non-blocking.
        ModerateComment::dispatchSync($comment);

        $comment->refresh();
        $message = match ($comment->status) {
            'approved' => '✅ Bình luận đã được đăng!',
            'rejected' => '⚠️ Bình luận bị từ chối do vi phạm nội dung.',
            default => '✅ Bình luận của bạn đang chờ duyệt!',
        };

        return back()->with('success', $message);
    }
}
