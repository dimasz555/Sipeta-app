<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Disable CSRF protection during tests
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Disable CSRF protection for tests
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    }

    /**
     * Test: User can login with correct credentials (username).
     *
     * @return void
     */
    public function test_user_can_login_with_correct_credentials()
    {
        // Create user with valid username and password
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'phone' => '08888888',
        ]);

        // Send login request with valid credentials
        $response = $this->post('/login', [
            'login' => 'testuser', // Valid username
            'password' => 'password123',
        ]);

        // Verify that the response is a successful login (expect status 200 or redirect to home)
        $response->assertStatus(302);  // Adjust based on your route behavior (could be redirect 302)
        $response->assertRedirect('/home'); // Adjust the redirection path after successful login
    }

    /**
     * Test: User cannot login with incorrect password.
     *
     * @return void
     */
    public function test_user_cannot_login_with_incorrect_password()
    {
        // Create user with valid username and password
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'phone' => '08888888',
        ]);

        // Send login request with incorrect password
        $response = $this->post('/login', [
            'login' => 'testuser', // Correct username
            'password' => 'wrongpassword', // Incorrect password
        ]);

        // Verify that the login failed and session has errors for password
        $response->assertStatus(302); // Redirect after failed login
        $response->assertSessionHasErrors('password'); // Check if the error for incorrect password is present
    }

    /**
     * Test: User cannot login with nonexistent username or phone.
     *
     * @return void
     */
    public function test_user_cannot_login_with_nonexistent_username_or_phone()
    {
        // Send login request with a non-existent username
        $response = $this->post('/login', [
            'login' => 'nonexistentuser', // Username not in the database
            'password' => 'password123',
        ]);

        // Verify that login fails and session has errors for login field
        $response->assertStatus(302); // Redirect after failed login
        $response->assertSessionHasErrors('login'); // Check if the error for username/phone not found is present
    }

    /**
     * Test: Username field is required.
     *
     * @return void
     */
    public function test_username_is_required()
    {
        // Send login request with empty username
        $response = $this->post('/login', [
            'login' => '', // Empty login field
            'password' => 'password123',
        ]);

        // Verify that the login failed and session has errors for login field
        $response->assertSessionHasErrors('login'); // Ensure error for missing login
    }

    /**
     * Test: Ensure Rate Limiting for failed login attempts.
     *
     * @return void
     */
    public function test_rate_limiting_for_failed_logins()
    {
        // Create user with valid username and password
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123'),
            'phone' => '08888888',
        ]);

        // Attempt to login 5 times with incorrect password
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/login', [
                'login' => 'testuser', // Correct username
                'password' => 'wrongpassword', // Incorrect password
            ]);
        }

        // Send 6th failed login attempt, which should trigger rate-limiting
        $response = $this->post('/login', [
            'login' => 'testuser', // Correct username
            'password' => 'wrongpassword', // Incorrect password
        ]);

        // Verify that rate-limiting error occurs and the login fails
        $response->assertSessionHasErrors('login'); // Check rate limiting error message
    }
}
