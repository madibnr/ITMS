<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $itStaff;
    private User $manager;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'Admin']);
        $itStaffRole = Role::create(['name' => 'IT Staff', 'slug' => 'it-staff', 'description' => 'IT Staff']);
        $managerRole = Role::create(['name' => 'Manager', 'slug' => 'manager', 'description' => 'Manager']);
        $userRole = Role::create(['name' => 'User', 'slug' => 'user', 'description' => 'User']);

        $this->admin = User::factory()->create();
        $this->admin->roles()->attach($adminRole);

        $this->itStaff = User::factory()->create();
        $this->itStaff->roles()->attach($itStaffRole);

        $this->manager = User::factory()->create();
        $this->manager->roles()->attach($managerRole);

        $this->regularUser = User::factory()->create();
        $this->regularUser->roles()->attach($userRole);
    }

    // ─── User Management (admin only) ───────────────────

    public function test_admin_can_access_user_management(): void
    {
        $response = $this->actingAs($this->admin)->get(route('users.index'));
        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('users.index'));
        $response->assertStatus(403);
    }

    public function test_it_staff_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->itStaff)->get(route('users.index'));
        $response->assertStatus(403);
    }

    public function test_manager_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->manager)->get(route('users.index'));
        $response->assertStatus(403);
    }

    // ─── Reports (admin & manager) ──────────────────────

    public function test_admin_can_access_reports(): void
    {
        $response = $this->actingAs($this->admin)->get(route('reports.tickets'));
        $response->assertStatus(200);
    }

    public function test_manager_can_access_reports(): void
    {
        $response = $this->actingAs($this->manager)->get(route('reports.tickets'));
        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_reports(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('reports.tickets'));
        $response->assertStatus(403);
    }

    // ─── IT Operations (admin, it-staff, manager) ───────

    public function test_admin_can_access_change_requests(): void
    {
        $response = $this->actingAs($this->admin)->get(route('change-requests.index'));
        $response->assertStatus(200);
    }

    public function test_it_staff_can_access_change_requests(): void
    {
        $response = $this->actingAs($this->itStaff)->get(route('change-requests.index'));
        $response->assertStatus(200);
    }

    public function test_manager_can_access_change_requests(): void
    {
        $response = $this->actingAs($this->manager)->get(route('change-requests.index'));
        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_change_requests(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('change-requests.index'));
        $response->assertStatus(403);
    }

    // ─── Dashboard (any authenticated) ──────────────────

    public function test_any_authenticated_user_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->regularUser)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    // ─── hasRole / hasAnyRole helpers ────────────────────

    public function test_user_has_role(): void
    {
        $this->assertTrue($this->admin->hasRole('admin'));
        $this->assertFalse($this->admin->hasRole('user'));
    }

    public function test_user_has_any_role(): void
    {
        $this->assertTrue($this->itStaff->hasAnyRole(['admin', 'it-staff']));
        $this->assertFalse($this->regularUser->hasAnyRole(['admin', 'it-staff']));
    }
}
