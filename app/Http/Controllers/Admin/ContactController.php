<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;
use App\Services\GeminiService;



class ContactController extends Controller
{
    /** Danh sách liên hệ */
    public function index(Request $request)
    {
        $status = $request->status;
        $type   = $request->type;

        $contacts = Contact::with('user')
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($type,   fn($q) => $q->where('type', $type))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'all'     => Contact::count(),
            'new'     => Contact::where('status', 'new')->count(),
            'read'    => Contact::where('status', 'read')->count(),
            'replied' => Contact::where('status', 'replied')->count(),
            'closed'  => Contact::where('status', 'closed')->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'counts', 'status', 'type'));
    }

    /** Chi tiết & đánh dấu đã đọc */
    public function show(Contact $contact)
    {
        if ($contact->status === 'new') {
            $contact->update(['status' => 'read']);
        }
        return view('admin.contacts.show', compact('contact'));
    }

    /** Phản hồi liên hệ */
    public function reply(Request $request, Contact $contact)
    {
        $request->validate([
            'admin_reply' => 'required|string|min:5|max:2000',
        ], [
            'admin_reply.required' => 'Vui lòng nhập nội dung phản hồi.',
            'admin_reply.min'      => 'Nội dung phải có ít nhất 5 ký tự.',
        ]);

        $contact->update([
            'admin_reply' => $request->admin_reply,
            'status'      => 'replied',
            'replied_at'  => now(),
        ]);

        ActivityLogger::log('Phản hồi liên hệ', 'Liên hệ', $contact->id, 'Đã phản hồi liên hệ của khách hàng: "' . $contact->name . '" (Email: ' . $contact->email . ', Tiêu đề: "' . $contact->subject . '")');


        return back()->with('success', '✅ Đã gửi phản hồi thành công!');
    }

    /** Đổi trạng thái */
    public function updateStatus(Request $request, Contact $contact)
    {
        $request->validate(['status' => 'required|in:new,read,replied,closed']);
        $contact->update(['status' => $request->status]);

        ActivityLogger::log('Cập nhật liên hệ', 'Liên hệ', $contact->id, 'Đã cập nhật trạng thái liên hệ của khách hàng "' . $contact->name . '" sang: ' . $request->status);

        return back()->with('success', 'Đã cập nhật trạng thái.');
    }

    /** Gợi ý phản hồi bằng AI */
    public function aiSuggest(int $id, GeminiService $gemini)
    {
        $contact = Contact::findOrFail($id);
        
        try {
            $suggestion = $gemini->suggestContactReply(
                $contact->name,
                $contact->subject ?? 'Không có tiêu đề',
                $contact->message ?? 'Không có nội dung',
                $contact->type ?? 'other'
            );
            
            ActivityLogger::log('AI gợi ý phản hồi', 'Liên hệ', $contact->id, 'AI đã gợi ý câu trả lời cho liên hệ của: "' . $contact->name . '" (Tiêu đề: "' . $contact->subject . '")');
            
            return response()->json([
                'success' => true,
                'suggestion' => $suggestion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi kết nối AI: ' . $e->getMessage()
            ]);
        }
    }

    /** Xoá liên hệ */
    public function destroy(Contact $contact)
    {
        ActivityLogger::log('Xóa liên hệ', 'Liên hệ', $contact->id, 'Đã xóa liên hệ của khách hàng: "' . $contact->name . '" (Email: ' . $contact->email . ')');

        $contact->delete();
        return back()->with('success', 'Đã xoá liên hệ.');
    }
}
