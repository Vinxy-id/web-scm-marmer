<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WorkOrder;
use App\Models\ProductionStep;
use App\Models\QcLog;
use App\Models\WasteLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QcAndWasteModuleFlowTest extends TestCase
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

    public function test_qc_index_page_renders_properly(): void
    {
        $response = $this->actingAs($this->owner)->get('/qc');

        $response->assertStatus(200);
        $response->assertSee('Pengendalian Kualitas QC Dua Tahap');
        $response->assertSee('Tahap 1: Bentuk Mentah');
        $response->assertSee('Tahap 2: Akhir');
        $response->assertSee('Jenis Cacat Batuan (Defect)');
        $response->assertSee('Histori Pemeriksaan QC Lengkap');
    }

    public function test_qc1_raw_shape_records_successfully_and_links_step(): void
    {
        $wo = WorkOrder::create([
            'spk_number' => 'SPK-QC-001',
            'product_id' => $this->product->id,
            'customer_id' => $this->customer->id,
            'target_quantity' => 10,
            'completed_quantity' => 0,
            'scrap_quantity' => 0,
            'status' => 'in_progress',
            'priority' => 'normal',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
            'created_by' => $this->owner->id,
        ]);

        $step = ProductionStep::create([
            'work_order_id' => $wo->id,
            'step_name' => 'pembubutan_bentuk',
            'sequence_order' => 3,
            'machine_number' => 'Mesin Bubut 1',
            'input_qty' => 10,
            'status' => 'running',
        ]);

        $payload = [
            'work_order_id' => $wo->id,
            'stage' => 'qc1_raw_shape',
            'inspected_quantity' => 10,
            'pass_quantity' => 8,
            'rework_quantity' => 1,
            'scrap_quantity' => 1,
            'defect_type' => 'retak_serat',
            'rework_action' => 'Tambal resin pada serat retak halus',
            'notes' => '1 unit pecah serat tembus',
        ];

        $response = $this->actingAs($this->owner)->post('/qc/inspect', $payload);

        $response->assertRedirect(route('qc.index'));

        $log = QcLog::where('work_order_id', $wo->id)->where('stage', 'qc1_raw_shape')->first();
        $this->assertNotNull($log);
        $this->assertEquals(8, $log->pass_quantity);
        $this->assertEquals(1, $log->rework_quantity);
        $this->assertEquals(1, $log->scrap_quantity);
        $this->assertEquals('retak_serat', $log->defect_type);
        $this->assertEquals($step->id, $log->step_id);

        $wo->refresh();
        $this->assertEquals('in_progress', $wo->status);
        $this->assertEquals(1, $wo->scrap_quantity);
    }

    public function test_qc2_blocked_if_qc1_not_yet_performed(): void
    {
        $wo = WorkOrder::create([
            'spk_number' => 'SPK-QC-002',
            'product_id' => $this->product->id,
            'customer_id' => $this->customer->id,
            'target_quantity' => 6,
            'completed_quantity' => 0,
            'scrap_quantity' => 0,
            'status' => 'qc_phase',
            'priority' => 'high',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
            'created_by' => $this->owner->id,
        ]);

        // Attempt QC2 directly without QC1
        $payload = [
            'work_order_id' => $wo->id,
            'stage' => 'qc2_final_polish',
            'inspected_quantity' => 6,
            'pass_quantity' => 6,
            'rework_quantity' => 0,
            'scrap_quantity' => 0,
        ];

        $response = $this->actingAs($this->owner)->post('/qc/inspect', $payload);

        $response->assertSessionHasErrors(['stage']);
        $this->assertFalse(QcLog::where('work_order_id', $wo->id)->exists());
    }

    public function test_qc2_completes_work_order_and_increments_stock_only_once(): void
    {
        $initialStock = $this->product->ready_stock;

        $wo = WorkOrder::create([
            'spk_number' => 'SPK-QC-003',
            'product_id' => $this->product->id,
            'customer_id' => $this->customer->id,
            'target_quantity' => 8,
            'completed_quantity' => 0,
            'scrap_quantity' => 0,
            'status' => 'qc_phase',
            'priority' => 'normal',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
            'created_by' => $this->owner->id,
        ]);

        ProductionStep::create([
            'work_order_id' => $wo->id,
            'step_name' => 'inspeksi_qc',
            'sequence_order' => 5,
            'machine_number' => 'Meja QC',
            'input_qty' => 8,
            'status' => 'running',
        ]);

        // 1. Perform QC1
        $this->actingAs($this->owner)->post('/qc/inspect', [
            'work_order_id' => $wo->id,
            'stage' => 'qc1_raw_shape',
            'inspected_quantity' => 8,
            'pass_quantity' => 8,
            'rework_quantity' => 0,
            'scrap_quantity' => 0,
        ]);

        // 2. Perform QC2
        $response = $this->actingAs($this->owner)->post('/qc/inspect', [
            'work_order_id' => $wo->id,
            'stage' => 'qc2_final_polish',
            'inspected_quantity' => 8,
            'pass_quantity' => 7,
            'rework_quantity' => 0,
            'scrap_quantity' => 1,
            'defect_type' => 'pecah_bibir',
        ]);

        $response->assertRedirect(route('qc.index'));

        $wo->refresh();
        $this->assertEquals('completed', $wo->status);
        $this->assertEquals(7, $wo->completed_quantity);
        $this->assertEquals(1, $wo->scrap_quantity);

        $this->product->refresh();
        $this->assertEquals($initialStock + 7, $this->product->ready_stock);

        // 3. Attempt duplicate QC2
        $duplicateResponse = $this->actingAs($this->owner)->post('/qc/inspect', [
            'work_order_id' => $wo->id,
            'stage' => 'qc2_final_polish',
            'inspected_quantity' => 8,
            'pass_quantity' => 7,
            'rework_quantity' => 0,
            'scrap_quantity' => 1,
        ]);

        $duplicateResponse->assertSessionHasErrors(['stage']);

        // Ready stock must NOT increment a second time
        $this->product->refresh();
        $this->assertEquals($initialStock + 7, $this->product->ready_stock);
    }

    public function test_waste_index_and_store_logging_with_step_link(): void
    {
        $wo = WorkOrder::create([
            'spk_number' => 'SPK-WASTE-001',
            'product_id' => $this->product->id,
            'customer_id' => $this->customer->id,
            'target_quantity' => 10,
            'completed_quantity' => 0,
            'scrap_quantity' => 0,
            'status' => 'in_progress',
            'priority' => 'normal',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
            'created_by' => $this->owner->id,
        ]);

        $stepSlep = ProductionStep::create([
            'work_order_id' => $wo->id,
            'step_name' => 'pemotongan_slep',
            'sequence_order' => 2,
            'machine_number' => 'Mesin Slep',
            'input_qty' => 10,
            'status' => 'running',
        ]);

        $response = $this->actingAs($this->owner)->get(route('waste.index'));
        $response->assertStatus(200);
        $response->assertSee('Hilirisasi Residu');
        $response->assertSee('Residu Layak Cladding');

        $storeResponse = $this->actingAs($this->owner)->post(route('waste.store'), [
            'work_order_id' => $wo->id,
            'waste_type' => 'sisa_layak_cladding',
            'weight_kg' => 18.5,
            'reuse_status' => 'disimpan_daur_ulang',
            'notes' => 'Potongan tepi tebal 3cm',
        ]);

        $storeResponse->assertRedirect(route('waste.index'));

        $wasteLog = WasteLog::where('work_order_id', $wo->id)->first();
        $this->assertNotNull($wasteLog);
        $this->assertEquals(18.5, (float) $wasteLog->weight_kg);
        $this->assertEquals('sisa_layak_cladding', $wasteLog->waste_type);
        $this->assertEquals($stepSlep->id, $wasteLog->step_id);
    }
}
