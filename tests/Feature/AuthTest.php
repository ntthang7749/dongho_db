<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Otp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Fake Mail to prevent actual emails from being sent
        Mail::fake();
    }

    /**
     * Test login screen rendering
     */
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Test successful customer login
     */
    public function test_customer_can_login_with_correct_credentials(): void
    {
        $user = User::create([
            'name' => 'Customer User',
            'username' => 'customer_test',
            'email' => 'customer@test.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'login' => 'customer@test.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('home'));
    }

    /**
     * Test successful admin login
     */
    public function test_admin_can_login_with_correct_credentials(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'username' => 'admin_test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'login' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Test login failure with incorrect credentials
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        User::create([
            'name' => 'Customer User',
            'username' => 'customer_test',
            'email' => 'customer@test.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'login' => 'customer@test.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('error');
    }

    /**
     * Test login failure when account is deactivated
     */
    public function test_deactivated_user_cannot_login(): void
    {
        User::create([
            'name' => 'Blocked User',
            'username' => 'blocked_test',
            'email' => 'blocked@test.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'login' => 'blocked@test.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('error');
    }

    /**
     * Test registration flow (step 1 & step 2)
     */
    public function test_registration_flow_succeeds_with_otp_verification(): void
    {
        // Step 1: Submit registration details
        $response1 = $this->post('/register', [
            'name' => 'New User',
            'username' => 'new_user',
            'email' => 'newuser@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response1->assertRedirect(route('auth.otp.register.form'));
        $this->assertDatabaseHas('otps', [
            'email' => 'newuser@test.com',
            'type' => 'register',
        ]);

        // Retrieve the generated OTP from the database
        $otpRecord = Otp::where('email', 'newuser@test.com')->where('type', 'register')->first();
        $this->assertNotNull($otpRecord);

        // Step 2: Verify incorrect OTP
        $response2 = $this->post('/register/verify-otp', [
            'otp' => '000000',
        ]);
        $response2->assertSessionHas('error');

        // Step 3: Verify correct OTP
        $response3 = $this->post('/register/verify-otp', [
            'otp' => $otpRecord->otp,
        ]);

        $response3->assertRedirect(route('home'));
        $this->assertDatabaseHas('users', [
            'username' => 'new_user',
            'email' => 'newuser@test.com',
        ]);

        $user = User::where('email', 'newuser@test.com')->first();
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test forgot password flow (step 1, step 2 & step 3)
     */
    public function test_forgot_password_flow_succeeds_with_otp_and_password_update(): void
    {
        $user = User::create([
            'name' => 'Password User',
            'username' => 'pwd_user',
            'email' => 'pwd@test.com',
            'password' => Hash::make('oldpassword'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        // Step 1: Request OTP
        $response1 = $this->post('/forgot-password', [
            'email' => 'pwd@test.com',
        ]);

        $response1->assertRedirect(route('auth.otp.reset.form'));
        $this->assertDatabaseHas('otps', [
            'email' => 'pwd@test.com',
            'type' => 'reset_password',
        ]);

        $otpRecord = Otp::where('email', 'pwd@test.com')->where('type', 'reset_password')->first();
        $this->assertNotNull($otpRecord);

        // Step 2: Verify OTP
        $response2 = $this->post('/forgot-password/verify-otp', [
            'otp' => $otpRecord->otp,
        ]);

        $response2->assertRedirect(route('auth.new-password.form'));

        // Step 3: Update Password
        $response3 = $this->post('/reset-password', [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response3->assertRedirect(route('login'));

        // Verify password is updated by attempting login
        $responseLogin = $this->post('/login', [
            'login' => 'pwd@test.com',
            'password' => 'newpassword123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test logout functionality
     */
    public function test_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'Logout User',
            'username' => 'logout_test',
            'email' => 'logout@test.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
