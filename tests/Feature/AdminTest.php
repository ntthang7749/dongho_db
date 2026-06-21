<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\News;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Services\GeminiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected Category $category;
    protected Brand $brand;
    protected Product $product;
    protected Order $order;
    protected Review $review;
    protected Comment $comment;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock GeminiService
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
                'sentiment' => 'positive',
                'sentiment_score' => 0.8,
                'is_spam' => false,
                'reason' => 'Good comment',
                'status' => 'approved',
                'confidence' => 0.95,
            ]);
            $mock->shouldReceive('summarizeReviews')->andReturn('Good product overall.');
        });

        // Set up Admin & Customer
        $this->admin = User::create([
            'name' => 'Super Admin',
            'username' => 'admin_test',
            'email' => 'admin@test.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->customer = User::create([
            'name' => 'Customer User',
            'username' => 'customer_test',
            'email' => 'customer@test.com',
            'password' => Hash::make('Customer@123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Dong Ho Thuy Sy',
            'slug' => 'dong-ho-thuy-sy',
            'is_active' => true,
        ]);

        $this->brand = Brand::create([
            'name' => 'Tissot',
            'slug' => 'tissot',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'name' => 'Tissot Gentleman',
            'slug' => 'tissot-gentleman',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'price' => 25000000,
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->order = Order::create([
            'order_code' => 'DH2026ADMIN',
            'user_id' => $this->customer->id,
            'receiver_name' => 'Customer User',
            'receiver_phone' => '0987654321',
            'receiver_address' => '123 Main Street',
            'subtotal' => 25000000,
            'discount' => 0,
            'total' => 25000000,
            'payment_method' => 'cod',
            'status' => 'pending',
        ]);

        $this->review = Review::create([
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'rating' => 5,
            'comment' => 'Excellent watch!',
            'status' => 'pending',
        ]);

        $news = News::create([
            'title' => 'Tissot gentleman news',
            'slug' => 'tissot-gentleman-news',
            'summary' => 'Swiss watch trend',
            'content' => 'Content...',
            'user_id' => $this->admin->id,
            'is_active' => true,
        ]);

        $this->comment = Comment::create([
            'news_id' => $news->id,
            'user_id' => $this->customer->id,
            'content' => 'I love Swiss watches.',
            'status' => 'pending',
        ]);
    }

    /**
     * Test admin dashboard authorization
     */
    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->customer)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Test user management
     */
    public function test_admin_can_toggle_user_active_status(): void
    {
        $this->assertTrue($this->customer->is_active);

        $response = $this->actingAs($this->admin)->post('/admin/users/' . $this->customer->id . '/toggle');
        $response->assertRedirect();
        
        $this->assertFalse($this->customer->fresh()->is_active);
    }

    public function test_admin_cannot_toggle_their_own_active_status(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/users/' . $this->admin->id . '/toggle');
        $response->assertSessionHas('error');
        $this->assertTrue($this->admin->fresh()->is_active);
    }

    public function test_admin_can_change_user_role(): void
    {
        $this->assertEquals('customer', $this->customer->role);

        $response = $this->actingAs($this->admin)->post('/admin/users/' . $this->customer->id . '/role', [
            'role' => 'admin'
        ]);
        $response->assertRedirect();
        $this->assertEquals('admin', $this->customer->fresh()->role);
    }

    /**
     * Test order management
     */
    public function test_admin_can_view_order_details(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/orders/' . $this->order->id);
        $response->assertStatus(200);
        $response->assertSee('DH2026ADMIN');
    }

    public function test_admin_can_update_order_status(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/orders/' . $this->order->id . '/status', [
            'status' => 'confirmed'
        ]);
        $response->assertRedirect();
        $this->assertEquals('confirmed', $this->order->fresh()->status);
    }

    /**
     * Test review moderation
     */
    public function test_admin_can_approve_review(): void
    {
        $this->assertEquals('pending', $this->review->status);

        $response = $this->actingAs($this->admin)->post('/admin/reviews/' . $this->review->id . '/approve');
        $response->assertRedirect();
        $this->assertEquals('approved', $this->review->fresh()->status);
    }

    public function test_admin_can_trigger_ai_check_for_review(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/reviews/' . $this->review->id . '/ai-check');
        $response->assertJson(['success' => true]);
        $this->assertEquals('approved', $this->review->fresh()->status);
    }

    /**
     * Test comment moderation
     */
    public function test_admin_can_approve_comment(): void
    {
        $this->assertEquals('pending', $this->comment->status);

        $response = $this->actingAs($this->admin)->post('/admin/comments/' . $this->comment->id . '/approve');
        $response->assertRedirect();
        $this->assertEquals('approved', $this->comment->fresh()->status);
    }

    public function test_admin_can_trigger_ai_check_for_comment(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/comments/' . $this->comment->id . '/ai-check');
        $response->assertJson(['success' => true]);
        $this->assertEquals('approved', $this->comment->fresh()->status);
    }

    /**
     * Test catalog CRUD (simulated)
     */
    public function test_admin_can_create_brand(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/brands', [
            'name' => 'Rolex',
            'slug' => 'rolex',
            'description' => 'Luxury brand',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('brands', ['name' => 'Rolex']);
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'name' => 'Dong Ho Co',
            'slug' => 'dong-ho-co',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Dong Ho Co']);
    }
}
