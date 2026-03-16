<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $regularUser;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'Admin']);
        $userRole = Role::create(['name' => 'User', 'slug' => 'user', 'description' => 'User']);
        Role::create(['name' => 'IT Staff', 'slug' => 'it-staff', 'description' => 'IT Staff']);

        // Create users
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach($adminRole);

        $this->regularUser = User::factory()->create();
        $this->regularUser->roles()->attach($userRole);

        // Create category
        $this->category = Category::create([
            'name' => 'Hardware',
            'type' => 'ticket',
            'description' => 'Hardware issues',
        ]);
    }

    public function test_guest_cannot_access_tickets(): void
    {
        $response = $this->get(route('tickets.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_tickets(): void
    {
        $response = $this->actingAs($this->regularUser)
            ->get(route('tickets.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tickets.index');
    }

    public function test_user_can_create_ticket(): void
    {
        $response = $this->actingAs($this->regularUser)
            ->post(route('tickets.store'), [
            'title' => 'Laptop tidak bisa booting',
            'description' => 'Blue screen saat startup.',
            'category_id' => $this->category->id,
            'priority' => 'High',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tickets', [
            'title' => 'Laptop tidak bisa booting',
            'user_id' => $this->regularUser->id,
            'status' => 'Open',
        ]);
    }

    public function test_ticket_number_is_auto_generated(): void
    {
        $this->actingAs($this->regularUser)
            ->post(route('tickets.store'), [
            'title' => 'Test auto number',
            'description' => 'Testing.',
            'category_id' => $this->category->id,
            'priority' => 'Medium',
        ]);

        $ticket = Ticket::first();
        $this->assertNotNull($ticket->ticket_number);
        $this->assertStringStartsWith('TKT-', $ticket->ticket_number);
    }

    public function test_sla_deadline_is_auto_calculated(): void
    {
        $this->actingAs($this->regularUser)
            ->post(route('tickets.store'), [
            'title' => 'Critical issue',
            'description' => 'Server down.',
            'category_id' => $this->category->id,
            'priority' => 'Critical',
        ]);

        $ticket = Ticket::first();
        $this->assertNotNull($ticket->sla_deadline);
    }

    public function test_user_can_view_own_ticket(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TKT-TEST-0001',
            'title' => 'Test ticket',
            'description' => 'Test description',
            'category_id' => $this->category->id,
            'priority' => 'Medium',
            'status' => 'Open',
            'user_id' => $this->regularUser->id,
            'sla_deadline' => now()->addHours(24),
        ]);

        $response = $this->actingAs($this->regularUser)
            ->get(route('tickets.show', $ticket));

        $response->assertStatus(200);
        $response->assertViewIs('tickets.show');
    }

    public function test_admin_can_update_ticket(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TKT-TEST-0002',
            'title' => 'Ticket to update',
            'description' => 'Original description',
            'category_id' => $this->category->id,
            'priority' => 'Low',
            'status' => 'Open',
            'user_id' => $this->regularUser->id,
            'sla_deadline' => now()->addHours(48),
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('tickets.update', $ticket), [
            'title' => 'Updated ticket title',
            'description' => 'Updated description',
            'category_id' => $this->category->id,
            'priority' => 'High',
            'status' => 'In Progress',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'title' => 'Updated ticket title',
        ]);
    }

    public function test_admin_can_assign_ticket(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TKT-TEST-0003',
            'title' => 'Ticket to assign',
            'description' => 'Needs assignee.',
            'category_id' => $this->category->id,
            'priority' => 'Medium',
            'status' => 'Open',
            'user_id' => $this->regularUser->id,
            'sla_deadline' => now()->addHours(24),
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('tickets.assign', $ticket), [
            'assigned_to' => $this->admin->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'assigned_to' => $this->admin->id,
            'status' => 'In Progress',
        ]);
    }

    public function test_admin_can_resolve_ticket(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TKT-TEST-0004',
            'title' => 'Ticket to resolve',
            'description' => 'Fix me.',
            'category_id' => $this->category->id,
            'priority' => 'Medium',
            'status' => 'In Progress',
            'user_id' => $this->regularUser->id,
            'assigned_to' => $this->admin->id,
            'sla_deadline' => now()->addHours(24),
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('tickets.resolve', $ticket), [
            'resolution_note' => 'Replaced hard drive.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'Resolved',
            'resolution_note' => 'Replaced hard drive.',
        ]);
    }

    public function test_admin_can_close_ticket(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TKT-TEST-0005',
            'title' => 'Ticket to close',
            'description' => 'Done.',
            'category_id' => $this->category->id,
            'priority' => 'Low',
            'status' => 'Resolved',
            'user_id' => $this->regularUser->id,
            'assigned_to' => $this->admin->id,
            'sla_deadline' => now()->addHours(48),
            'resolved_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('tickets.close', $ticket));

        $response->assertRedirect();
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'Closed',
        ]);
    }

    public function test_user_can_add_comment(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TKT-TEST-0006',
            'title' => 'Ticket with comment',
            'description' => 'Comment me.',
            'category_id' => $this->category->id,
            'priority' => 'Medium',
            'status' => 'Open',
            'user_id' => $this->regularUser->id,
            'sla_deadline' => now()->addHours(24),
        ]);

        $response = $this->actingAs($this->regularUser)
            ->post(route('tickets.comments.store', $ticket), [
            'body' => 'This is a test comment.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ticket_comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $this->regularUser->id,
            'body' => 'This is a test comment.',
        ]);
    }

    public function test_admin_can_delete_ticket(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TKT-TEST-0007',
            'title' => 'Ticket to delete',
            'description' => 'Remove me.',
            'category_id' => $this->category->id,
            'priority' => 'Low',
            'status' => 'Open',
            'user_id' => $this->regularUser->id,
            'sla_deadline' => now()->addHours(48),
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('tickets.destroy', $ticket));

        $response->assertRedirect();
        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    public function test_store_validation_fails_without_required_fields(): void
    {
        $response = $this->actingAs($this->regularUser)
            ->post(route('tickets.store'), []);

        $response->assertSessionHasErrors(['title', 'description', 'category_id', 'priority']);
    }
}
