<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WorkOrder;
use App\Models\ProductionStep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionModuleFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;
    protected Product $product;
    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->owner = User::firstOrCreate(
            ['email' => 'owner@cahayaonix.com'],
            [
                'name' => 'Bapak Pangki',
                'password' => bcrypt('password'),
                'role' => 'owner',
                'ikm_name' => 'UD Cahaya Onix',
            ]
        );

        $this->product = Product::first();
        $this->customer = Customer::first();
    }

    public function test_kanban_board_renders_successfully(): void
    {
        $response = $this->actingAs($this->owner)->get('/production/kanban');

        $response->assertStatus(200);
        $response->assertSee('1. Antrian SPK');
        $response->assertSee('2. Potong Slep');
        $response->assertSee('3. Mesin Bubut');
        $response->assertSee('QC');
        $response->assertSee('5. Siap Kirim');
    }

    public function test_store_work_order_creates_standard_5_steps(): void
    {
        $payload = [
            'product_id' => $this->product->id,
            'customer_id' => $this->customer->id,
            'target_quantity' => 10,
            'priority' => 'high',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(5)->toDateString(),
            'notes' => 'Batch uji 10 unit',
        ];

        $response = $this->actingAs($this->owner)->post('/production/work-order', $payload);

        $response->assertRedirect(route('production.kanban'));

        $wo = WorkOrder::where('notes', 'Batch uji 10 unit')->first();
        $this->assertNotNull($wo);
        $this->assertStringStartsWith('SPK-', $wo->spk_number);
        $this->assertEquals(10, $wo->target_quantity);

        // Verify standard 5 production steps
        $steps = $wo->steps;
        $this->assertCount(5, $steps);
        $this->assertEquals('pembelahan_bongkahan', $steps[0]->step_name);
        $this->assertEquals('pemotongan_slep', $steps[1]->step_name);
        $this->assertEquals('pembubutan_bentuk', $steps[2]->step_name);
        $this->assertEquals('penghalusan_poles', $steps[3]->step_name);
        $this->assertEquals('inspeksi_qc', $steps[4]->step_name);
    }

    public function test_kanban_status_progression_and_single_stock_increment(): void
    {
        $initialStock = $this->product->ready_stock;

        $wo = WorkOrder::create([
            'spk_number' => 'SPK-TEST-999',
            'product_id' => $this->product->id,
            'customer_id' => $this->customer->id,
            'target_quantity' => 5,
            'completed_quantity' => 0,
            'scrap_quantity' => 1,
            'status' => 'scheduled',
            'priority' => 'normal',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
            'created_by' => $this->owner->id,
        ]);

        ProductionStep::create([
            'work_order_id' => $wo->id,
            'step_name' => 'pemotongan_slep',
            'sequence_order' => 1,
            'machine_number' => 'Mesin Slep',
            'input_qty' => 5,
            'status' => 'pending',
        ]);

        ProductionStep::create([
            'work_order_id' => $wo->id,
            'step_name' => 'pembubutan_bentuk',
            'sequence_order' => 2,
            'machine_number' => 'Mesin Bubut 1',
            'input_qty' => 5,
            'status' => 'pending',
        ]);

        // 1. Progress to Slep
        $this->actingAs($this->owner)->patch(route('production.work-order.update-status', $wo->id), [
            'status' => 'in_progress',
            'step' => 'slep',
        ]);
        $wo->refresh();
        $this->assertEquals('in_progress', $wo->status);
        $this->assertEquals('running', $wo->steps()->where('step_name', 'pemotongan_slep')->value('status'));

        // 2. Progress to Bubut
        $this->actingAs($this->owner)->patch(route('production.work-order.update-status', $wo->id), [
            'status' => 'in_progress',
            'step' => 'bubut',
        ]);
        $wo->refresh();
        $this->assertEquals('completed', $wo->steps()->where('step_name', 'pemotongan_slep')->value('status'));
        $this->assertEquals('running', $wo->steps()->where('step_name', 'pembubutan_bentuk')->value('status'));

        // 3. Progress to Completed
        $this->actingAs($this->owner)->patch(route('production.work-order.update-status', $wo->id), [
            'status' => 'completed',
        ]);
        $wo->refresh();
        $this->assertEquals('completed', $wo->status);
        $this->assertEquals(4, $wo->completed_quantity); // 5 target - 1 scrap

        // Verify ready_stock incremented exactly once by 4
        $this->product->refresh();
        $this->assertEquals($initialStock + 4, $this->product->ready_stock);

        // 4. Update again from WIP form (PRD-02 double increment prevention test)
        $this->actingAs($this->owner)->patch(route('production.work-order.update-wip', $wo->id), [
            'completed_quantity' => 4,
            'scrap_quantity' => 1,
            'status' => 'completed',
        ]);

        // Stock should NOT increment a second time
        $this->product->refresh();
        $this->assertEquals($initialStock + 4, $this->product->ready_stock);
    }

    public function test_wip_tracking_page_renders_table(): void
    {
        $response = $this->actingAs($this->owner)->get('/production/wip');

        $response->assertStatus(200);
        $response->assertSee('Tracking Barang dalam Proses (WIP)');
        $response->assertSee('Status 7 Unit Mesin Lantai Produksi');
        $response->assertSee('No. SPK');
        $response->assertSee('Stasiun Kerja');
    }
}
