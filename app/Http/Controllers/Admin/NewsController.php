<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('author')->latest()->paginate(15);

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:25600',
        ]);

        $data = $request->except(['thumbnail', '_token']);
        $data['slug'] = Str::slug($request->title).'-'.Str::random(5);
        $data['user_id'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('news', 'public');
        }

        News::create($data);

        return redirect()->route('admin.news.index')
            ->with('success', '✅ Đã đăng bài viết!');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:25600',
        ]);

        $data = $request->except(['thumbnail', '_token', '_method']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('news', 'public');
        }

        $news->update($data);

        return redirect()->route('admin.news.index')
            ->with('success', '✅ Đã cập nhật bài viết!');
    }

    public function destroy(News $news)
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }
        $news->delete();

        return back()->with('success', '✅ Đã xoá bài viết!');
    }

    public function __construct(private GeminiService $gemini) {}

    // Thêm method
    public function suggestTitles(Request $request)
    {
        $request->validate(['content' => 'required|string|min:50']);

        $titles = $this->gemini->suggestNewsTitles($request->content, 5);

        return response()->json([
            'success' => true,
            'titles' => $titles,
        ]);
    }

    public function generateNews(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
        ], [
            'prompt.required' => 'Vui lòng nhập từ khóa hoặc chủ đề gợi ý.',
            'prompt.max' => 'Từ khóa quá dài, tối đa 1000 ký tự.',
        ]);

        $newsData = $this->gemini->generateFullNews($request->prompt);

        return response()->json([
            'success' => !empty($newsData) && !empty($newsData['title']),
            'news' => $newsData,
        ]);
    }
}

