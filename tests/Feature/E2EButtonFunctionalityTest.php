<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Material;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WorkOrder;
use App\Models\Shipment;

class E2EButtonFunctionalityTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        if (Customer::count() === 0) {
            $this->seed(\Database\Seeders\DatabaseSeeder::class);
        }

        $this->user = User::first() ?? User::factory()->create();
    }

    public function test_guest_can_access_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_access_all_module_views()
    {
        $pages = [
            '/dashboard',
            '/supply-chain-flow',
            '/materials',
            '/production/kanban',
            '/production/wip',
            '/qc',
            '/waste',
            '/distribution',
            '/forecasting',
            '/reports',
        ];

        foreach ($pages as $page) {
            $response = $this->actingAs($this->user)->get($page);
            $response->assertStatus(200);
        }
    }

    public function test_material_crud_and_transaction_buttons_work()
    {
        // 1. Create Material Button
        $createResponse = $this->actingAs($this->user)->post(route('materials.store'), [
            'material_code' => 'MAT-TEST-' . time(),
            'name' => 'Marmer Uji Tombol',
            'type' => 'marmer',
            'grade' => 'grade_a_super',
            'dimension_info' => '100x100x100 cm',
            'unit' => 'blok',
            'current_stock' => 15.00,
            'minimum_stock' => 5.00,
            'unit_cost' => 500000.00,
        ]);
        $createResponse->assertRedirect(route('materials.index'));

        $material = Material::where('name', 'Marmer Uji Tombol')->first();
        $this->assertNotNull($material);

        // 2. Update Material Button
        $updateResponse = $this->actingAs($this->user)->put(route('materials.update', $material->id), [
            'name' => 'Marmer Uji Tombol (Updated)',
            'type' => 'marmer',
            'grade' => 'grade_a_super',
            'minimum_stock' => 8.00,
            'unit_cost' => 550000.00,
        ]);
        $updateResponse->assertRedirect(route('materials.index'));

        // 3. Mutasi Stok Button
        $txResponse = $this->actingAs($this->user)->post(route('materials.transaction'), [
            'material_id' => $material->id,
            'type' => 'in',
            'quantity' => 5.00,
            'notes' => 'Uji tombol mutasi penerimaan',
        ]);
        $txResponse->assertRedirect(route('materials.index'));
        $this->assertEquals(20.00, $material->fresh()->current_stock);
    }

    public function test_production_spk_buttons_and_kanban_progression_work()
    {
        $product = Product::first();

        // 1. Terbitkan SPK Baru Button
        $spkResponse = $this->actingAs($this->user)->post(route('production.work-order.store'), [
            'product_id' => $product->id,
            'target_quantity' => 10,
            'priority' => 'urgent',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
            'notes' => 'Uji SPK Tombol',
        ]);
        $spkResponse->assertRedirect(route('production.kanban'));

        $wo = WorkOrder::where('notes', 'Uji SPK Tombol')->first();
        $this->assertNotNull($wo);

        // 2. Button Mulai Slep (in_progress)
        $progResponse = $this->actingAs($this->user)->patch(route('production.work-order.update-status', $wo->id), [
            'status' => 'in_progress',
        ]);
        $progResponse->assertRedirect(route('production.kanban'));
        $this->assertEquals('in_progress', $wo->fresh()->status);
    }

    public function test_qc_inspection_button_works()
    {
        $wo = WorkOrder::first();

        $qcResponse = $this->actingAs($this->user)->post(route('qc.inspect'), [
            'work_order_id' => $wo->id,
            'stage' => 'qc1_raw_shape',
            'inspected_quantity' => 14,
            'pass_quantity' => 12,
            'rework_quantity' => 2,
            'scrap_quantity' => 0,
            'defect_type' => 'Retak rambut halus',
            'rework_action' => 'Tambal resin',
        ]);
        $qcResponse->assertRedirect(route('qc.index'));
    }

    public function test_waste_log_button_works()
    {
        $wo = WorkOrder::first();

        $wasteResponse = $this->actingAs($this->user)->post(route('waste.store'), [
            'work_order_id' => $wo->id,
            'waste_type' => 'sisa_layak_cladding',
            'weight_kg' => 18.5,
            'reuse_status' => 'disimpan_daur_ulang',
            'notes' => 'Uji tombol residu',
        ]);
        $wasteResponse->assertRedirect(route('waste.index'));
    }

    public function test_distribution_shipment_and_status_buttons_work()
    {
        $customer = Customer::first();

        // 1. Terbitkan Surat Jalan Button
        $shipResponse = $this->actingAs($this->user)->post(route('distribution.shipment.store'), [
            'customer_id' => $customer->id,
            'shipment_date' => now()->toDateString(),
            'expedition_name' => 'Kargo Cepat Tulungagung',
            'vehicle_number' => 'AG 1234 XY',
            'driver_name' => 'Pak Yanto',
            'wooden_packing_checked' => 1,
            'notes' => 'Uji tombol surat jalan',
        ]);
        $shipResponse->assertRedirect(route('distribution.index'));

        $shipment = Shipment::where('notes', 'Uji tombol surat jalan')->first();
        $this->assertNotNull($shipment);

        // 2. Button Kirim Kargo (in_transit)
        $statusResponse = $this->actingAs($this->user)->patch(route('distribution.shipment.update-status', $shipment->id), [
            'status' => 'in_transit',
        ]);
        $statusResponse->assertRedirect(route('distribution.index'));
        $this->assertEquals('in_transit', $shipment->fresh()->status);
    }

    public function test_forecasting_recalculate_button_works()
    {
        $calcResponse = $this->actingAs($this->user)->post(route('forecasting.calculate'), [
            'target_type' => 'material',
            'target_id' => 1,
            'model_type' => 'holt_winters',
            'horizon_months' => 3,
        ]);
        $calcResponse->assertRedirect(route('forecasting.index'));
    }
}
