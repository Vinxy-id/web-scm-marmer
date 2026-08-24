<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkOrder;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;
    protected User $admin;
    protected User $gudang;
    protected User $produksi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::create([
            'name' => 'Owner Utama',
            'email' => 'owner.test@cahayaonix.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'ikm_name' => 'UD Cahaya Onix',
            'is_active' => true,
        ]);

        $this->admin = User::create([
            'name' => 'Admin Pusat',
            'email' => 'admin.test@escm-marmer.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'ikm_name' => 'Pusat Klaster Tulungagung',
            'is_active' => true,
        ]);

        $this->gudang = User::create([
            'name' => 'Staf Gudang',
            'email' => 'gudang.test@cahayaonix.com',
            'password' => Hash::make('password123'),
            'role' => 'gudang',
            'ikm_name' => 'UD Cahaya Onix',
            'is_active' => true,
        ]);

        $this->produksi = User::create([
            'name' => 'Operator Bubut',
            'email' => 'produksi.test@cahayaonix.com',
            'password' => Hash::make('password123'),
            'role' => 'produksi',
            'ikm_name' => 'UD Cahaya Onix',
            'is_active' => true,
        ]);
    }

    public function test_owner_and_admin_can_view_user_management_page(): void
    {
        $response = $this->actingAs($this->owner)->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna');
        $response->assertSee('Owner Utama');

        $adminResponse = $this->actingAs($this->admin)->get(route('users.index'));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Admin Pusat');
    }

    public function test_staff_cannot_access_user_management_page(): void
    {
        $response = $this->actingAs($this->gudang)->get(route('users.index'));
        $response->assertStatus(403);

        $prodResponse = $this->actingAs($this->produksi)->get(route('users.index'));
        $prodResponse->assertStatus(403);
    }

    public function test_owner_can_create_new_user_with_valid_data(): void
    {
        $payload = [
            'name' => 'Ahmad QC Specialist',
            'email' => 'ahmad.qc@cahayaonix.com',
            'password' => 'password123',
            'role' => 'produksi',
            'phone' => '081234567895',
            'ikm_name' => 'UD Cahaya Onix',
            'is_active' => 1,
        ];

        $response = $this->actingAs($this->owner)->post(route('users.store'), $payload);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Ahmad QC Specialist',
            'email' => 'ahmad.qc@cahayaonix.com',
            'role' => 'produksi',
            'phone' => '081234567895',
            'is_active' => true,
        ]);

        $createdUser = User::where('email', 'ahmad.qc@cahayaonix.com')->first();
        $this->assertTrue(Hash::check('password123', $createdUser->password));
    }

    public function test_create_user_fails_if_email_is_duplicate(): void
    {
        $payload = [
            'name' => 'Duplikat User',
            'email' => 'gudang.test@cahayaonix.com', // Duplicate
            'password' => 'password123',
            'role' => 'gudang',
            'ikm_name' => 'UD Cahaya Onix',
        ];

        $response = $this->actingAs($this->owner)->post(route('users.store'), $payload);
        $response->assertSessionHasErrors('email');
    }

    public function test_owner_can_update_user_details_and_role(): void
    {
        $payload = [
            'name' => 'Budi Santoso Senior',
            'email' => 'gudang.test@cahayaonix.com',
            'role' => 'owner', // Promoted to owner
            'phone' => '089988776655',
            'ikm_name' => 'UD Cahaya Onix',
            'is_active' => 1,
        ];

        $response = $this->actingAs($this->owner)->put(route('users.update', $this->gudang->id), $payload);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->gudang->id,
            'name' => 'Budi Santoso Senior',
            'role' => 'owner',
            'phone' => '089988776655',
        ]);
    }

    public function test_owner_can_toggle_user_status(): void
    {
        $this->assertTrue($this->gudang->is_active);

        $response = $this->actingAs($this->owner)->post(route('users.toggle-status', $this->gudang->id));
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertFalse($this->gudang->fresh()->is_active);

        // Toggle back to active
        $response2 = $this->actingAs($this->owner)->post(route('users.toggle-status', $this->gudang->id));
        $response2->assertRedirect(route('users.index'));
        $this->assertTrue($this->gudang->fresh()->is_active);
    }

    public function test_owner_cannot_deactivate_or_delete_self(): void
    {
        $toggleResponse = $this->actingAs($this->owner)->post(route('users.toggle-status', $this->owner->id));
        $toggleResponse->assertSessionHas('error');
        $this->assertTrue($this->owner->fresh()->is_active);

        $deleteResponse = $this->actingAs($this->owner)->delete(route('users.destroy', $this->owner->id));
        $deleteResponse->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->owner->id]);
    }

    public function test_user_without_work_orders_can_be_deleted(): void
    {
        $dummyUser = User::create([
            'name' => 'Dummy Temp User',
            'email' => 'dummy@temp.local',
            'password' => Hash::make('password123'),
            'role' => 'distribusi',
            'ikm_name' => 'UD Cahaya Onix',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->owner)->delete(route('users.destroy', $dummyUser->id));
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $dummyUser->id]);
    }
}
