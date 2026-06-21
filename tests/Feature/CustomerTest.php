<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\News;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Services\GeminiService;
use App\Services\VietQRService;
use App\Services\VNPayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected Product $product;
    protected Coupon $coupon;
    protected News $news;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Mock GeminiService
        $this->mock(GeminiService::class, function ($mock) {
            $mock->shouldReceive('processReview')->andReturn([
                'sentiment' => 'positive',
                'sentiment_score' => 0.9,
                'is_spam' => false,
                'reason' => 'Good review',
                'status' => 'approved',
                'confidence' => 0.95,
            ]);
            $mock->shouldReceive('processComment')->andReturn([
                'sentiment' => 'neutral',
                'sentiment_score' => 0.5,
                'is_spam' => false,
                'reason' => 'Comment is clean',
                'status' => 'approved',
                'confidence' => 0.95,
            ]);
            $mock->shouldReceive('summarizeReviews')->andReturn('Good product overall.');
        });

        // 2. Mock VNPayService
        $this->mock(VNPayService::class, function ($mock) {
            $mock->shouldReceive('createPaymentUrl')->andReturn('https://sandbox.vnpayment.vn/paymentv2/vpcpay.html?mock=true');
        });

        // 3. Mock VietQRService
        $this->mock(VietQRService::class, function ($mock) {
            $mock->shouldReceive('getQRImageUrl')->andReturn('https://img.vietqr.io/image/mock.png');
            $mock->shouldReceive('getBankName')->andReturn('Mock Bank');
            $mock->shouldReceive('getAccountNo')->andReturn('123456789');
            $mock->shouldReceive('getAccountName')->andReturn('MOCK MERCHANT');
        });

        // 4. Set up database records
        $this->customer = User::create([
            'name' => 'Nguyen Van A',
            'username' => 'customer_a',
            'email' => 'customer_a@test.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $category = Category::create([
            'name' => 'Dong Ho Nam',
            'slug' => 'dong-ho-nam',
            'is_active' => true,
        ]);

        $brand = Brand::create([
            'name' => 'Casio',
            'slug' => 'casio',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'name' => 'Casio Classic Watch',
            'slug' => 'casio-classic-watch',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'description' => 'A wonderful durable classic watch.',
            'price' => 1500000,
            'sale_price' => 1200000,
            'stock' => 10,
            'thumbnail' => 'products/casio.jpg',
            'sku' => 'CASIO-01',
            'is_active' => true,
        ]);

        $this->coupon = Coupon::create([
            'code' => 'TEST50',
            'name' => 'Giam 50k',
            'type' => 'fixed',
            'value' => 50000,
            'min_order' => 100000,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDays(5),
            'usage_limit' => 100,
            'usage_count' => 0,
            'is_active' => true,
        ]);

        $this->news = News::create([
            'title' => 'Hot watch trends 2026',
            'slug' => 'hot-watch-trends-2026',
            'summary' => 'Summary of trends.',
            'content' => 'Content of watch trends.',
            'user_id' => $this->customer->id, // Use simple user for author
            'is_active' => true,
        ]);
    }

    /**
     * Test public pages
     */
    public function test_home_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_suggestions_page_can_be_rendered(): void
    {
        $response = $this->get('/san-pham/goi-y');
        $response->assertStatus(200);
    }

    public function test_product_detail_page_can_be_rendered(): void
    {
        $response = $this->get('/san-pham/' . $this->product->slug);
        $response->assertStatus(200);
    }

    public function test_product_search_works(): void
    {
        $response = $this->get('/san-pham/search?q=Casio');
        $response->assertStatus(200);
        $response->assertSee('Casio Classic Watch');
    }

    /**
     * Test product comparison
     */
    public function test_product_comparison_actions(): void
    {
        // 1. Add to compare
        $response1 = $this->post('/san-pham/so-sanh/them', [
            'product_id' => $this->product->id
        ]);
        $response1->assertJson(['success' => true]);
        $this->assertEquals([$this->product->id], session('compare_products'));

        // 2. View compare page
        $response2 = $this->get('/san-pham/so-sanh');
        $response2->assertStatus(200);

        // 3. Remove from compare
        $response3 = $this->post('/san-pham/so-sanh/xoa', [
            'product_id' => $this->product->id
        ]);
        $response3->assertJson(['success' => true]);
        $this->assertEmpty(session('compare_products'));

        // 4. Clear compare
        session(['compare_products' => [$this->product->id]]);
        $response4 = $this->post('/san-pham/so-sanh/xoa-het');
        $response4->assertJson(['success' => true]);
        $this->assertNull(session('compare_products'));
    }

    /**
     * Test cart and coupon operations (authenticated)
     */
    public function test_cart_and_coupon_operations(): void
    {
        // 1. Add to cart
        $responseAdd = $this->actingAs($this->customer)->postJson('/gio-hang/them', [
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);
        $responseAdd->assertJson(['success' => true]);

        $this->assertArrayHasKey($this->product->id, session('cart'));
        $this->assertEquals(2, session('cart')[$this->product->id]['quantity']);

        // 2. View cart page
        $responseView = $this->actingAs($this->customer)->get('/gio-hang');
        $responseView->assertStatus(200);

        // 3. Apply coupon
        $responseCoupon = $this->actingAs($this->customer)->post('/gio-hang/ap-coupon', [
            'code' => 'TEST50'
        ]);
        $responseCoupon->assertRedirect();
        $this->assertNotNull(session('coupon'));
        $this->assertEquals('TEST50', session('coupon')['code']);

        // 4. Remove coupon
        $responseRemoveCoupon = $this->actingAs($this->customer)->post('/gio-hang/xoa-coupon');
        $responseRemoveCoupon->assertRedirect();
        $this->assertNull(session('coupon'));

        // 5. Update cart item quantity
        $responseUpdate = $this->actingAs($this->customer)->post('/gio-hang/cap-nhat', [
            'product_id' => $this->product->id,
            'quantity' => 3
        ]);
        $responseUpdate->assertRedirect();
        $this->assertEquals(3, session('cart')[$this->product->id]['quantity']);

        // 6. Remove item from cart
        $responseRemoveItem = $this->actingAs($this->customer)->post('/gio-hang/xoa/' . $this->product->id);
        $responseRemoveItem->assertRedirect();
        $this->assertEmpty(session('cart'));
    }

    /**
     * Test checkout and order placement (authenticated)
     */
    public function test_checkout_and_place_order_cod(): void
    {
        // Add item to cart first
        session(['cart' => [
            $this->product->id => [
                'product_id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->sale_price,
                'thumbnail' => $this->product->thumbnail,
                'slug' => $this->product->slug,
                'quantity' => 2,
                'stock' => $this->product->stock,
            ]
        ]]);

        // Checkout view
        $responseCheckout = $this->actingAs($this->customer)->get('/dat-hang/thanh-toan');
        $responseCheckout->assertStatus(200);

        // Place COD order
        $responsePlace = $this->actingAs($this->customer)->post('/dat-hang/dat', [
            'receiver_name' => 'Nguyen Van A',
            'receiver_phone' => '0987654321',
            'receiver_address' => '123 Test Street, HCM',
            'payment_method' => 'cod',
            'note' => 'Deliver in afternoon',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'receiver_name' => 'Nguyen Van A',
            'payment_method' => 'cod',
            'total' => 2400000,
        ]);

        $order = Order::where('user_id', $this->customer->id)->first();
        $responsePlace->assertRedirect(route('orders.success', $order->order_code));
        $this->assertNull(session('cart')); // Cart is cleared

        // View success page
        $responseSuccess = $this->actingAs($this->customer)->get('/dat-hang/thanh-cong/' . $order->order_code);
        $responseSuccess->assertStatus(200);

        // View history
        $responseHistory = $this->actingAs($this->customer)->get('/dat-hang/lich-su');
        $responseHistory->assertStatus(200);

        // View order details
        $responseDetail = $this->actingAs($this->customer)->get('/dat-hang/chi-tiet/' . $order->order_code);
        $responseDetail->assertStatus(200);

        // Cancel order
        $responseCancel = $this->actingAs($this->customer)->post('/dat-hang/huy/' . $order->id);
        $responseCancel->assertRedirect();
        $this->assertEquals('cancelled', $order->fresh()->status);
    }

    public function test_checkout_and_place_order_vnpay(): void
    {
        session(['cart' => [
            $this->product->id => [
                'product_id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->sale_price,
                'thumbnail' => $this->product->thumbnail,
                'slug' => $this->product->slug,
                'quantity' => 1,
                'stock' => $this->product->stock,
            ]
        ]]);

        // Place VNPay order
        $responsePlace = $this->actingAs($this->customer)->post('/dat-hang/dat', [
            'receiver_name' => 'Nguyen Van A',
            'receiver_phone' => '0987654321',
            'receiver_address' => '123 Test Street, HCM',
            'payment_method' => 'vnpay',
        ]);

        // Redirects to mock VNPay payment url
        $responsePlace->assertRedirectContains('sandbox.vnpayment.vn');
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'payment_method' => 'vnpay',
            'total' => 1200000,
        ]);
    }

    /**
     * Test wishlist toggle and view
     */
    public function test_wishlist_functionality(): void
    {
        // Add to wishlist
        $response1 = $this->actingAs($this->customer)->post('/yeu-thich/toggle/' . $this->product->id, [], [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);
        $response1->assertJson(['success' => true]);
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id
        ]);

        // View wishlist page
        $response2 = $this->actingAs($this->customer)->get('/yeu-thich');
        $response2->assertStatus(200);

        // Remove from wishlist (toggle again)
        $response3 = $this->actingAs($this->customer)->post('/yeu-thich/toggle/' . $this->product->id, [], [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);
        $response3->assertJson(['success' => true]);
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id
        ]);
    }

    /**
     * Test reviews
     */
    public function test_submit_product_review(): void
    {
        // Submitting review without buying -> fails
        $responseFail = $this->actingAs($this->customer)->post('/danh-gia', [
            'product_id' => $this->product->id,
            'rating' => 5,
            'comment' => 'Beautiful watch!'
        ]);
        $responseFail->assertRedirect()->assertSessionHas('error');

        // Setup delivered order for the customer
        $order = Order::create([
            'order_code' => 'DH2026MOCK',
            'user_id' => $this->customer->id,
            'receiver_name' => 'Nguyen Van A',
            'receiver_phone' => '0987654321',
            'receiver_address' => '123 Test Street, HCM',
            'subtotal' => 1200000,
            'discount' => 0,
            'total' => 1200000,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'status' => 'delivered',
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_image' => $this->product->thumbnail,
            'price' => 1200000,
            'quantity' => 1,
            'subtotal' => 1200000,
        ]);

        // Now submit review -> succeeds
        $responseSuccess = $this->actingAs($this->customer)->post('/danh-gia', [
            'product_id' => $this->product->id,
            'rating' => 5,
            'comment' => 'Beautiful watch!'
        ]);

        $responseSuccess->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'rating' => 5,
            'comment' => 'Beautiful watch!',
        ]);
    }

    /**
     * Test comments on news
     */
    public function test_submit_news_comment(): void
    {
        $response = $this->actingAs($this->customer)->post('/binh-luan', [
            'news_id' => $this->news->id,
            'content' => 'Very informative article!'
        ]);

        $response->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('comments', [
            'user_id' => $this->customer->id,
            'news_id' => $this->news->id,
            'content' => 'Very informative article!',
            'status' => 'approved', // AI approved it
        ]);
    }

    /**
     * Test contact form
     */
    public function test_submit_contact_form(): void
    {
        $response = $this->post('/lien-he', [
            'name' => 'Visitor A',
            'email' => 'visitor@test.com',
            'phone' => '0987111222',
            'subject' => 'Question about warranty',
            'type' => 'question',
            'message' => 'How long is the warranty for Casio watch?'
        ]);

        $response->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseHas('contacts', [
            'name' => 'Visitor A',
            'email' => 'visitor@test.com',
            'subject' => 'Question about warranty',
        ]);
    }
}
