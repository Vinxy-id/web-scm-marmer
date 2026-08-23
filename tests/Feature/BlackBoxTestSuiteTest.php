<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Material;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WorkOrder;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlackBoxTestSuiteTest extends TestCase
{
    use DatabaseTransactions;

    protected $ownerUser;
    protected $gudangUser;
    protected $produksiUser;
    protected $distribusiUser;

    protected function setUp(): void
    {
        parent::setUp();

        if (User::where('email', 'owner@cahayaonix.com')->count() === 0 || Customer::count() === 0) {
            $this->seed(\Database\Seeders\DatabaseSeeder::class);
        }

        $this->ownerUser = User::updateOrCreate(
            ['email' => 'owner@cahayaonix.com'],
            [
                'name' => 'M. Ilham Nur Amali (Owner)',
                'password' => \Illuminate\Support\Facades\Hash::make('role123'),
                'role' => 'owner',
                'ikm_name' => 'UD Cahaya Onix',
                'is_active' => true,
            ]
        );

        $this->gudangUser = User::where('email', 'gudang@cahayaonix.com')->first() 
            ?? User::where('role', 'gudang')->first() 
            ?? $this->ownerUser;

        $this->produksiUser = User::where('email', 'produksi@cahayaonix.com')->first() 
            ?? User::where('role', 'produksi')->first() 
            ?? $this->ownerUser;

        $this->distribusiUser = User::where('email', 'distribusi@cahayaonix.com')->first() 
            ?? User::where('role', 'distribusi')->first() 
            ?? $this->ownerUser;
    }

    /** TC-AUTH-01: Login sukses dengan kredensial valid */
    public function test_tc_auth_01_login_success_valid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'owner@cahayaonix.com',
            'password' => 'role123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->ownerUser);
    }

    /** TC-AUTH-02: Login gagal dengan password salah */
    public function test_tc_auth_02_login_fail_invalid_password()
    {
        $response = $this->post('/login', [
            'email' => 'owner@cahayaonix.com',
            'password' => 'wrong123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** TC-STK-01: Input bahan baku baru data valid */
    public function test_tc_stk_01_store_material_valid()
    {
        $code = 'MAT-TEST-' . rand(1000, 9999);
        $response = $this->actingAs($this->gudangUser)->post(route('materials.store'), [
            'material_code' => $code,
            'name' => 'Bongkahan Marmer Hitam Campurdarat Test',
            'type' => 'marmer',
            'grade' => 'grade_b_standard',
            'dimension_info' => '60x60x80 cm',
            'unit' => 'blok',
            'current_stock' => 20.00,
            'minimum_stock' => 5.00,
            'unit_cost' => 250000.00,
        ]);

        $response->assertRedirect(route('materials.index'));
        $this->assertDatabaseHas('materials', ['material_code' => $code]);
    }

    /** TC-STK-02: Input mutasi stok keluar melebihi stok riil */
    public function test_tc_stk_02_record_transaction_exceed_stock()
    {
        $material = Material::first();
        $excessQuantity = $material->current_stock + 100.00;

        $response = $this->actingAs($this->gudangUser)->post(route('materials.transaction'), [
            'material_id' => $material->id,
            'type' => 'out',
            'quantity' => $excessQuantity,
            'notes' => 'Uji coba mutasi keluar melebihi stok',
        ]);

        $response->assertSessionHasErrors(['quantity']);
    }

    /** TC-STK-03: Input kuantitas mutasi bernilai negatif/nol */
    public function test_tc_stk_03_record_transaction_negative_qty()
    {
        $material = Material::first();

        $response = $this->actingAs($this->gudangUser)->post(route('materials.transaction'), [
            'material_id' => $material->id,
            'type' => 'in',
            'quantity' => -5.00,
            'notes' => 'Uji kuantitas negatif',
        ]);

        $response->assertSessionHasErrors(['quantity']);
    }

    /** TC-PRD-01: Penerbitan SPK baru data valid */
    public function test_tc_prd_01_store_work_order_valid()
    {
        $product = Product::first();
        $customer = Customer::first();

        $response = $this->actingAs($this->produksiUser)->post(route('production.work-order.store'), [
            'product_id' => $product->id,
            'customer_id' => $customer ? $customer->id : null,
            'target_quantity' => 10,
            'priority' => 'high',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(5)->toDateString(),
            'notes' => 'Uji coba SPK baru',
        ]);

        $response->assertRedirect(route('production.kanban'));
        $this->assertDatabaseHas('work_orders', [
            'product_id' => $product->id,
            'target_quantity' => 10,
            'priority' => 'high',
        ]);
    }

    /** TC-PRD-02: Tanggal tenggat (due date) sebelum tanggal mulai */
    public function test_tc_prd_02_store_work_order_invalid_dates()
    {
        $product = Product::first();

        $response = $this->actingAs($this->produksiUser)->post(route('production.work-order.store'), [
            'product_id' => $product->id,
            'target_quantity' => 10,
            'priority' => 'normal',
            'start_date' => now()->addDays(5)->toDateString(),
            'due_date' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors(['due_date']);
    }

    /** TC-QC-01: Input inspeksi QC2 Final Polish valid (setelah QC1) */
    public function test_tc_qc_01_store_inspection_qc2_valid()
    {
        $workOrder = WorkOrder::first();

        // QC 2-Tahap: Lakukan QC1 terlebih dahulu
        \App\Models\QcLog::create([
            'work_order_id' => $workOrder->id,
            'stage' => 'qc1_raw_shape',
            'inspector_id' => $this->distribusiUser->id,
            'inspected_quantity' => 10,
            'pass_quantity' => 10,
            'rework_quantity' => 0,
            'scrap_quantity' => 0,
            'inspection_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->distribusiUser)->post(route('qc.inspect'), [
            'work_order_id' => $workOrder->id,
            'stage' => 'qc2_final_polish',
            'inspected_quantity' => 10,
            'pass_quantity' => 10,
            'rework_quantity' => 0,
            'scrap_quantity' => 0,
            'notes' => 'Inspeksi QC2 Lolos Sempurna',
        ]);

        $response->assertRedirect(route('qc.index'));
        $this->assertDatabaseHas('qc_logs', [
            'work_order_id' => $workOrder->id,
            'stage' => 'qc2_final_polish',
            'pass_quantity' => 10,
        ]);
    }

    /** TC-QC-02: Total unit pass+rework+scrap tidak cocok dengan inspected_quantity */
    public function test_tc_qc_02_store_inspection_mismatched_quantities()
    {
        $workOrder = WorkOrder::first();

        $response = $this->actingAs($this->distribusiUser)->post(route('qc.inspect'), [
            'work_order_id' => $workOrder->id,
            'stage' => 'qc1_raw_shape',
            'inspected_quantity' => 10,
            'pass_quantity' => 8,
            'rework_quantity' => 5,
            'scrap_quantity' => 2, // Total: 15 vs 10 inspected
            'notes' => 'Uji diskrepansi kuantitas QC',
        ]);

        // Expect validation error if custom validation is present
        $response->assertSessionHasErrors(['inspected_quantity']);
    }

    /** TC-DST-01: Penerbitan Surat Jalan valid */
    public function test_tc_dst_01_store_shipment_valid()
    {
        $customer = Customer::first();

        $response = $this->actingAs($this->distribusiUser)->post(route('distribution.shipment.store'), [
            'customer_id' => $customer->id,
            'shipment_date' => now()->toDateString(),
            'expedition_name' => 'Kargo Express Tulungagung',
            'vehicle_number' => 'AG 8899 AB',
            'driver_name' => 'Pak Yatno',
            'wooden_packing_checked' => true,
            'notes' => 'Pengiriman Wastafel Marmer',
        ]);

        $response->assertRedirect(route('distribution.index'));
        $this->assertDatabaseHas('shipments', [
            'customer_id' => $customer->id,
            'expedition_name' => 'Kargo Express Tulungagung',
        ]);
    }

    /** TC-DST-02: Update status pengiriman menjadi delivered */
    public function test_tc_dst_02_update_shipment_status()
    {
        $shipment = Shipment::first();

        $response = $this->actingAs($this->distribusiUser)->patch(route('distribution.shipment.update-status', $shipment->id), [
            'delivery_status' => 'delivered',
            'tracking_number' => 'RESI-EXPRESS-9988',
        ]);

        $response->assertRedirect(route('distribution.index'));
        $this->assertEquals('delivered', $shipment->fresh()->delivery_status);
    }

    /** TC-FOR-01: Kalkulasi peramalan Holt-Winters */
    public function test_tc_for_01_calculate_forecasting()
    {
        $product = Product::first();

        $response = $this->actingAs($this->ownerUser)->post(route('forecasting.calculate'), [
            'target_type' => 'product',
            'target_id' => $product->id,
            'model_type' => 'holt_winters',
            'horizon_months' => 3,
        ]);

        $response->assertRedirect(route('forecasting.index'));
        $this->assertDatabaseHas('forecasting_logs', [
            'item_type' => 'product',
            'item_id' => $product->id,
            'forecast_horizon_months' => 3,
        ]);
    }
}
