<?php

use App\Http\Controllers\Admin\AdminChatbotController;
use App\Http\Controllers\Admin\ActivityLogController;

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AI\ChatbotController;
use App\Http\Controllers\AI\ImageRecognitionController;
use App\Http\Controllers\AI\ProductAIController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\ContactController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\NewsController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\WishlistController;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// ==========================================
// AUTH — CHƯA ĐĂNG NHẬP
// ==========================================
Route::middleware('guest')->group(function () {

    // --- ĐĂNG KÝ ---
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'sendOtp'])
        ->middleware('throttle:public-submissions')
        ->name('register.send-otp');

    // OTP xác nhận đăng ký
    Route::get('/register/verify-otp', [RegisterController::class, 'showOtpForm'])->name('auth.otp.register.form');
    Route::post('/register/verify-otp', [RegisterController::class, 'verifyOtp'])
        ->middleware('throttle:public-submissions')
        ->name('auth.otp.register.verify');
    Route::post('/register/resend-otp', [RegisterController::class, 'resendOtp'])
        ->middleware('throttle:public-submissions')
        ->name('auth.otp.register.resend');

    // --- ĐĂNG NHẬP ---
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1'); // Rate limit 5 lần/phút

    // --- QUÊN MẬT KHẨU ---
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
        ->middleware('throttle:public-submissions')
        ->name('password.email');

    // OTP xác nhận reset password
    Route::get('/forgot-password/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('auth.otp.reset.form');
    Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])
        ->middleware('throttle:public-submissions')
        ->name('auth.otp.reset.verify');
    Route::post('/forgot-password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])
        ->middleware('throttle:public-submissions')
        ->name('auth.otp.reset.resend');

    // Đặt mật khẩu mới
    Route::get('/reset-password', [ForgotPasswordController::class, 'showNewPasswordForm'])->name('auth.new-password.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'updatePassword'])
        ->middleware('throttle:public-submissions')
        ->name('auth.new-password.update');

    // --- GOOGLE LOGIN ---
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
});

// ==========================================
// AUTH — ĐÃ ĐĂNG NHẬP
// ==========================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// ==========================================
// ADMIN ROUTES
// ==========================================
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/revenue-filter', [DashboardController::class, 'revenueFilter'])->name('dashboard.revenue-filter');
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');


        // Sản phẩm
        Route::get('/products/{id}/invoice', [AdminProductController::class, 'invoice'])->name('products.invoice');
        Route::resource('products', AdminProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('brands', BrandController::class);

        // Đơn hàng
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        // Người dùng
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{id}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');
        Route::post('/users/{id}/role', [UserController::class, 'changeRole'])->name('users.role');

        // Đánh giá
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{id}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{id}/ai-check', [AdminReviewController::class, 'aiCheck'])
            ->middleware('throttle:ai-operations')
            ->name('reviews.ai-check');
        Route::post('/reviews/bulk-ai-check', [AdminReviewController::class, 'bulkAiCheck'])
            ->middleware('throttle:ai-operations')
            ->name('reviews.bulk-ai-check');
        Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        // Banner
        Route::resource('banners', BannerController::class);

        // Coupon
        Route::resource('coupons', CouponController::class);

        // Tin tức
        Route::resource('news', AdminNewsController::class);

        // Bình luận
        Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
        Route::post('/comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve');
        Route::post('/comments/{id}/ai-check', [CommentController::class, 'aiCheck'])
            ->middleware('throttle:ai-operations')
            ->name('comments.ai-check');
        Route::post('/comments/bulk-ai-check', [CommentController::class, 'bulkAiCheck'])
            ->middleware('throttle:ai-operations')
            ->name('comments.bulk-ai-check');
        Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

        // Liên hệ
        Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
        Route::post('/contacts/{contact}/reply', [AdminContactController::class, 'reply'])->name('contacts.reply');
        Route::post('/contacts/{contact}/ai-suggest', [AdminContactController::class, 'aiSuggest'])
            ->middleware('throttle:ai-operations')
            ->name('contacts.ai-suggest');
        Route::post('/contacts/{contact}/status', [AdminContactController::class, 'updateStatus'])->name('contacts.status');
        Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

        // Trong group admin(Xóa ảnh)
        Route::delete('/product-images/{id}', function ($id) {
            $img = ProductImage::findOrFail($id);
            Storage::disk('public')->delete($img->image);
            $img->delete();

            return response()->json(['success' => true]);
        })->name('admin.product-images.destroy');

        Route::post('/orders/{id}/confirm-qr', [AdminOrderController::class, 'confirmQRPayment'])->name('orders.confirm-qr');

        // AI routes cho admin
        Route::post('/ai/generate-description', [ProductAIController::class, 'generateDescription'])
            ->middleware('throttle:ai-operations')
            ->name('ai.description');
        Route::post('/ai/suggest-titles', [AdminNewsController::class, 'suggestTitles'])
            ->middleware('throttle:ai-operations')
            ->name('ai.titles');
        Route::post('/ai/generate-news', [AdminNewsController::class, 'generateNews'])
            ->middleware('throttle:ai-operations')
            ->name('ai.generate-news');
        Route::post('/ai/chat', [AdminChatbotController::class, 'chat'])
            ->middleware('throttle:ai-operations')
            ->name('ai.chat');
    });


// ==========================================
// CUSTOMER — PUBLIC (không cần đăng nhập)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sản phẩm
Route::prefix('san-pham')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/goi-y', [ProductController::class, 'suggestions'])->name('suggestions');
    // QUAN TRỌNG: /search phải đứng TRƯỚC /{slug}, nếu không slug sẽ nuốt "search".
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    
    // So sánh sản phẩm (Cần đặt TRƯỚC /{slug})
    Route::get('/so-sanh', [ProductController::class, 'compare'])->name('compare');
    Route::post('/so-sanh/them', [ProductController::class, 'addToCompare'])->name('compare.add');
    Route::post('/so-sanh/xoa', [ProductController::class, 'removeFromCompare'])->name('compare.remove');
    Route::post('/so-sanh/xoa-het', [ProductController::class, 'clearCompare'])->name('compare.clear');

    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
});

// Tin tức
Route::prefix('tin-tuc')->name('news.')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('index');
    Route::get('/{slug}', [NewsController::class, 'show'])->name('show');
});

// Liên hệ (public — không cần đăng nhập)
Route::prefix('lien-he')->name('contact.')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::post('/', [ContactController::class, 'store'])
        ->middleware('throttle:public-submissions')
        ->name('store');
});

// Hỗ trợ khách hàng (public — không cần đăng nhập)
Route::prefix('ho-tro')->name('support.')->group(function () {
    Route::view('/doi-tra', 'customer.support.returns')->name('returns');
    Route::view('/bao-hanh', 'customer.support.warranty')->name('warranty');
    Route::view('/huong-dan-mua-hang', 'customer.support.buying_guide')->name('buying_guide');
    Route::view('/faq', 'customer.support.faq')->name('faq');
});

// ==========================================
// CUSTOMER — PRIVATE (cần đăng nhập)
// ==========================================
Route::middleware('auth')->group(function () {

    // Giỏ hàng
    Route::prefix('gio-hang')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/them', [CartController::class, 'add'])->name('add');
        Route::post('/mua-ngay', [CartController::class, 'buyNow'])->name('buy-now');
        Route::post('/cap-nhat', [CartController::class, 'update'])->name('update');
        Route::post('/xoa/{id}', [CartController::class, 'remove'])->name('remove');
        Route::post('/ap-coupon', [CartController::class, 'applyCoupon'])->name('coupon');
        Route::post('/xoa-coupon', [CartController::class, 'removeCoupon'])->name('remove-coupon');
    });

    // Đặt hàng
    Route::prefix('dat-hang')->name('orders.')->group(function () {
        Route::get('/thanh-toan', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/dat', [OrderController::class, 'place'])->name('place');
        Route::get('/thanh-cong/{code}', [OrderController::class, 'success'])->name('success');
        Route::get('/lich-su', [OrderController::class, 'history'])->name('history');
        Route::get('/chi-tiet/{code}', [OrderController::class, 'detail'])->name('detail');
        Route::get('/invoice/{code}', [OrderController::class, 'invoice'])->name('invoice');
        Route::post('/huy/{id}', [OrderController::class, 'cancel'])->name('cancel');

        Route::get('/qr/{code}', [OrderController::class, 'showQR'])->name('qr');
        Route::get('/qr/{code}/status', [OrderController::class, 'checkPaymentStatus'])->name('qr.status');

        Route::post('/thanh-toan-lai/{code}', [OrderController::class, 'repay'])->name('repay');
        Route::post('/doi-phuong-thuc/{code}', [OrderController::class, 'changePaymentMethod'])->name('change-payment');
    });

    // VNPay callback
    Route::get('/vnpay/return', [OrderController::class, 'vnpayReturn'])->name('vnpay.return');

    // Admin xác nhận QR (trong group admin)
    Route::post('/orders/{id}/confirm-qr', [AdminOrderController::class, 'confirmQRPayment'])->name('orders.confirm-qr');

    // Tài khoản
    Route::prefix('tai-khoan')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/cap-nhat', [ProfileController::class, 'update'])->name('update');
        Route::post('/doi-mat-khau', [ProfileController::class, 'changePassword'])->name('password');
    });

    // Yêu thích
    Route::prefix('yeu-thich')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/toggle/{id}', [WishlistController::class, 'toggle'])->name('toggle');
    });

    // Đánh giá
    Route::post('/danh-gia', [ReviewController::class, 'store'])
        ->middleware('throttle:public-submissions')
        ->name('reviews.store');

    // Bình luận tin tức
    Route::post('/binh-luan', [NewsController::class, 'storeComment'])
        ->middleware('throttle:public-submissions')
        ->name('comments.store');
});

// ══════════════════════════════════════════
// AI ROUTES
// ══════════════════════════════════════════
Route::prefix('ai')->name('ai.')->middleware('throttle:ai-operations')->group(function () {

    // Chatbot (public)
    Route::post('/chat', [ChatbotController::class, 'chat'])->name('chat');
    Route::post('/suggest-products', [ChatbotController::class, 'suggestProducts'])->name('suggest');

    // Tìm kiếm thông minh (public)
    Route::get('/search', [ProductAIController::class, 'smartSearch'])->name('search');

    // Nhận diện ảnh (public)
    Route::get('/nhan-dien', [ImageRecognitionController::class, 'index'])->name('image.form');
    Route::post('/nhan-dien', [ImageRecognitionController::class, 'recognize'])->name('image.recognize');

    // Gợi ý cá nhân (cần login)
    Route::middleware('auth')->group(function () {
        Route::get('/goi-y', [ProductAIController::class, 'getPersonalizedSuggestions'])->name('personalized');
    });
});
