<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

        // Seed the roles
        $this->setupRoles();
    }

    /**
     * Setup roles for testing
     */
    private function setupRoles()
    {
        // Buat role admin
        $adminRole = Role::where('name', 'admin')->first();
        if (!$adminRole) {
            $adminRole = Role::create([
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Admin Role',
            ]);
        }

        // Buat role user
        $userRole = Role::where('name', 'user')->first();
        if (!$userRole) {
            $userRole = Role::create([
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Regular User Role',
            ]);
        }

        // Buat permission
        $viewDashboard = Permission::where('name', 'view-admin-dashboard')->first();
        if (!$viewDashboard) {
            $viewDashboard = Permission::create([
                'name' => 'view-admin-dashboard',
                'display_name' => 'View Admin Dashboard',
                'description' => 'Can view admin dashboard',
            ]);
        }

        // Sync permissions ke role admin
        if (!$adminRole->hasPermission('view-admin-dashboard')) {
            $adminRole->syncPermissions([$viewDashboard->id]);
        }
    }

    /**
     * Test: Admin can login and access dashboard
     *
     * @return void
     */
    public function test_admin_can_login_and_access_dashboard()
    {
        // Buat user admin
        $admin = User::create([
            'username' => 'admin_test_' . time(),
            'name' => 'admintest',
            'email' => 'admin_' . time() . '@test.com',
            'password' => Hash::make('password123'),
            'phone' => '08888888',
        ]);

        // Attach role admin ke user
        $administrator = Role::where('name', 'admin')->first();
        $admin->addRole($administrator);

        // Coba login sebagai admin
        $response = $this->post('/login', [
            'login' => $admin->username,
            'password' => 'password123',
        ]);

        // Verifikasi user terautentikasi
        $this->assertAuthenticated();

        // Verifikasi user memiliki role admin
        $this->assertTrue($admin->hasRole('admin'));

        // Verifikasi redirect ke dashboard admin
        $response->assertRedirect('/admin/dashboard');
    }

    /**
     * Test: Regular user cannot access admin dashboard
     *
     * @return void
     */
    public function test_regular_user_cannot_access_admin_dashboard()
    {
        // Buat regular user
        $user = User::create([
            'username' => 'user_test_' . time(),
            'name' => 'admintest',
            'email' => 'user_' . time() . '@test.com',
            'password' => Hash::make('password123'),
            'phone' => '08888888',
        ]);

        // Attach role user (bukan admin)
        $userRole = Role::where('name', 'user')->first(); // Ubah ini dari 'admin' ke 'user'
        $user->addRole($userRole);

        // Login sebagai regular user
        $this->post('/login', [
            'login' => $user->username,
            'password' => 'password123',
        ]);

        // Verifikasi user terautentikasi
        $this->assertAuthenticated();

        // Verifikasi user TIDAK memiliki role admin
        $this->assertFalse($user->hasRole('admin'));

        // Coba akses dashboard admin
        $response = $this->actingAs($user)->get('/admin/dashboard');

        // Seharusnya dapat response 403 (Forbidden)
        $response->assertStatus(403);
    }

    /**
     * Test: Username field is required
     *
     * @return void
     */
    public function test_username_is_required()
    {
        $response = $this->post('/login', [
            'login' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('login');
    }
}
