<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $itStaff;
    private User $regularUser;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'Admin']);
        $itStaffRole = Role::create(['name' => 'IT Staff', 'slug' => 'it-staff', 'description' => 'IT Staff']);
        $userRole = Role::create(['name' => 'User', 'slug' => 'user', 'description' => 'User']);

        $this->admin = User::factory()->create();
        $this->admin->roles()->attach($adminRole);

        $this->itStaff = User::factory()->create();
        $this->itStaff->roles()->attach($itStaffRole);

        $this->regularUser = User::factory()->create();
        $this->regularUser->roles()->attach($userRole);

        $this->category = Category::create([
            'name' => 'Laptop',
            'type' => 'asset',
            'description' => 'Laptop devices',
        ]);
    }

    public function test_regular_user_cannot_access_assets(): void
    {
        $response = $this->actingAs($this->regularUser)
            ->get(route('assets.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_assets(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('assets.index'));

        $response->assertStatus(200);
        $response->assertViewIs('assets.index');
    }

    public function test_it_staff_can_view_assets(): void
    {
        $response = $this->actingAs($this->itStaff)
            ->get(route('assets.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_asset(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('assets.store'), [
            'name' => 'ThinkPad X1 Carbon',
            'category_id' => $this->category->id,
            'brand' => 'Lenovo',
            'model' => 'X1 Carbon Gen 11',
            'serial_number' => 'LNV-X1C-TEST001',
            'status' => 'Active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assets', [
            'name' => 'ThinkPad X1 Carbon',
            'serial_number' => 'LNV-X1C-TEST001',
        ]);
    }

    public function test_asset_code_is_auto_generated(): void
    {
        $this->actingAs($this->admin)
            ->post(route('assets.store'), [
            'name' => 'Test Laptop',
            'category_id' => $this->category->id,
            'status' => 'Active',
        ]);

        $asset = Asset::first();
        $this->assertNotNull($asset->asset_code);
        $this->assertStringStartsWith('AST-', $asset->asset_code);
    }

    public function test_admin_can_update_asset(): void
    {
        $asset = Asset::create([
            'asset_code' => 'AST-LPT-0001',
            'name' => 'Old Name',
            'category_id' => $this->category->id,
            'status' => 'Active',
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('assets.update', $asset), [
            'name' => 'Updated Name',
            'status' => 'Maintenance',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'name' => 'Updated Name',
            'status' => 'Maintenance',
        ]);
    }

    public function test_admin_can_delete_asset(): void
    {
        $asset = Asset::create([
            'asset_code' => 'AST-LPT-0002',
            'name' => 'Delete me',
            'category_id' => $this->category->id,
            'status' => 'Retired',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('assets.destroy', $asset));

        $response->assertRedirect();
        $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
    }

    public function test_serial_number_must_be_unique(): void
    {
        Asset::create([
            'asset_code' => 'AST-LPT-0003',
            'name' => 'Existing Asset',
            'category_id' => $this->category->id,
            'serial_number' => 'DUPLICATE-SN',
            'status' => 'Active',
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('assets.store'), [
            'name' => 'New Asset',
            'category_id' => $this->category->id,
            'serial_number' => 'DUPLICATE-SN',
            'status' => 'Active',
        ]);

        $response->assertSessionHasErrors('serial_number');
    }
}
