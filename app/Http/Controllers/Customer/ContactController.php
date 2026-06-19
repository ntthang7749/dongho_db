<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /** Hiển thị form liên hệ */
    public function index()
    {
        return view('customer.contact.index');
    }

    /** Lưu liên hệ vào DB */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'required|string|max:200',
            'type'    => 'required|in:feedback,report,question,other',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'name.required'    => 'Vui lòng nhập họ tên.',
            'email.required'   => 'Vui lòng nhập email.',
            'email.email'      => 'Email không hợp lệ.',
            'subject.required' => 'Vui lòng nhập tiêu đề.',
            'type.required'    => 'Vui lòng chọn loại liên hệ.',
            'message.required' => 'Vui lòng nhập nội dung.',
            'message.min'      => 'Nội dung phải có ít nhất 10 ký tự.',
        ]);

        Contact::create([
            'user_id' => auth()->id(),
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'subject' => $request->subject,
            'type'    => $request->type,
            'message' => $request->message,
            'status'  => 'new',
        ]);

        return redirect()->route('contact.index')
            ->with('success', '✅ Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
}
