<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\WorkOrder;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DistributionAndTrackingFlowTest extends TestCase
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

    public function test_distribution_index_page_renders_successfully(): void
    {
        $response = $this->actingAs($this->owner)->get('/distribution');

        $response->assertStatus(200);
        $response->assertSee('Distribusi & Checklist Packing Kayu');
        $response->assertSee('Antrean Menunggu ACC');
        $response->assertSee('Packing');
        $response->assertSee('Dalam Perjalanan Kargo');
        $response->assertSee('Telah Diterima Buyer');
    }

    public function test_store_shipment_requires_packing_verification(): void
    {
        $wo = WorkOrder::create([
            'spk_number' => 'SPK-DST-001',
            'product_id' => $this->product->id,
            'customer_id' => $this->customer->id,
            'target_quantity' => 5,
            'completed_quantity' => 5,
            'status' => 'completed',
            'priority' => 'normal',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
            'created_by' => $this->owner->id,
        ]);

        // Attempt without packing_verified
        $payload = [
            'customer_id' => $this->customer->id,
            'work_order_id' => $wo->id,
            'shipment_date' => now()->toDateString(),
            'expedition_name' => 'Ekspedisi Bali Mandiri Express',
            'packing_verified' => 0,
        ];

        $response = $this->actingAs($this->owner)->post(route('distribution.shipment.store'), $payload);

        $response->assertSessionHasErrors(['packing_verified']);
        $this->assertFalse(Shipment::where('work_order_id', $wo->id)->exists());
    }

    public function test_store_shipment_blocks_incomplete_spk_and_duplicates(): void
    {
        // 1. Incomplete SPK (in_progress)
        $incompleteWo = WorkOrder::create([
            'spk_number' => 'SPK-DST-INCOMPLETE',
            'product_id' => $this->product->id,
            'customer_id' => $this->customer->id,
            'target_quantity' => 5,
            'completed_quantity' => 0,
            'status' => 'in_progress',
            'priority' => 'normal',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
            'created_by' => $this->owner->id,
        ]);

        $response = $this->actingAs($this->owner)->post(route('distribution.shipment.store'), [
            'customer_id' => $this->customer->id,
            'work_order_id' => $incompleteWo->id,
            'shipment_date' => now()->toDateString(),
            'expedition_name' => 'Kargo Sentra',
            'packing_verified' => 1,
        ]);

        $response->assertSessionHasErrors(['work_order_id']);

        // 2. Completed SPK
        $completedWo = WorkOrder::create([
            'spk_number' => 'SPK-DST-COMPLETED',
            'product_id' => $this->product->id,
            'customer_id' => $this->customer->id,
            'target_quantity' => 5,
            'completed_quantity' => 5,
            'status' => 'completed',
            'priority' => 'normal',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
            'created_by' => $this->owner->id,
        ]);

        $validPayload = [
            'customer_id' => $this->customer->id,
            'work_order_id' => $completedWo->id,
            'shipment_date' => now()->toDateString(),
            'expedition_name' => 'Baraka Sarana Tama',
            'vehicle_plate' => 'AG 1234 XY',
            'driver_name' => 'Pak Slamet',
            'packing_verified' => 1,
        ];

        $firstResponse = $this->actingAs($this->owner)->post(route('distribution.shipment.store'), $validPayload);
        $firstResponse->assertRedirect(route('distribution.index'));
        $this->assertTrue(Shipment::where('work_order_id', $completedWo->id)->exists());

        // 3. Attempt duplicate shipment on same SPK
        $duplicateResponse = $this->actingAs($this->owner)->post(route('distribution.shipment.store'), $validPayload);
        $duplicateResponse->assertSessionHasErrors(['work_order_id']);
    }

    public function test_update_shipment_status_syncs_to_order_shipped_and_delivered(): void
    {
        $wo = WorkOrder::create([
            'spk_number' => 'SPK-ORD-SYNC-01',
            'product_id' => $this->product->id,
            'customer_id' => $this->customer->id,
            'target_quantity' => 3,
            'completed_quantity' => 3,
            'status' => 'completed',
            'priority' => 'high',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(3)->toDateString(),
            'created_by' => $this->owner->id,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-SYNC-TEST-001',
            'customer_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'work_order_id' => $wo->id,
            'quantity' => 3,
            'payment_scheme' => 'full_100',
            'payment_method' => 'bank_bca',
            'unit_price' => 500000,
            'total_amount' => 1500000,
            'paid_amount' => 1500000,
            'payment_status' => 'paid_full',
            'order_status' => 'in_production',
            'shipping_address' => 'Jl. Sunset Road No. 88, Kuta',
            'shipping_city' => 'Denpasar, Bali',
            'receiver_name' => 'Bapak Ketut Sukerta',
            'receiver_phone' => '08123456789',
        ]);

        // Create Shipment
        $this->actingAs($this->owner)->post(route('distribution.shipment.store'), [
            'customer_id' => $this->customer->id,
            'work_order_id' => $wo->id,
            'shipment_date' => now()->toDateString(),
            'expedition_name' => 'Ekspedisi Bali Mandiri Express',
            'vehicle_plate' => 'DK 9988 AB',
            'driver_name' => 'Pak Wayan',
            'tracking_number' => 'RESI-BALI-9988',
            'packing_verified' => 1,
        ]);

        $shipment = Shipment::where('work_order_id', $wo->id)->first();
        $this->assertNotNull($shipment);

        // Order status synced to packing
        $order->refresh();
        $this->assertEquals('packing', $order->order_status);

        // 1. Update status to in_transit
        $this->actingAs($this->owner)->patch(route('distribution.shipment.update-status', $shipment->id), [
            'delivery_status' => 'in_transit',
        ]);

        $shipment->refresh();
        $this->assertEquals('in_transit', $shipment->delivery_status);

        $order->refresh();
        $this->assertEquals('shipped', $order->order_status);

        // 2. Update status to delivered
        $this->actingAs($this->owner)->patch(route('distribution.shipment.update-status', $shipment->id), [
            'delivery_status' => 'delivered',
        ]);

        $shipment->refresh();
        $this->assertEquals('delivered', $shipment->delivery_status);

        $order->refresh();
        $this->assertEquals('delivered', $order->order_status);
    }

    public function test_public_tracking_page_renders_live_shipment_info(): void
    {
        $wo = WorkOrder::create([
            'spk_number' => 'SPK-TRACK-LIVE-01',
            'product_id' => $this->product->id,
            'customer_id' => $this->customer->id,
            'target_quantity' => 2,
            'completed_quantity' => 2,
            'status' => 'completed',
            'priority' => 'normal',
            'start_date' => now()->toDateString(),
            'due_date' => now()->addDays(2)->toDateString(),
            'created_by' => $this->owner->id,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-TRACK-LIVE-01',
            'customer_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'work_order_id' => $wo->id,
            'quantity' => 2,
            'payment_scheme' => 'full_100',
            'payment_method' => 'bank_bca',
            'unit_price' => 500000,
            'total_amount' => 1000000,
            'paid_amount' => 1000000,
            'payment_status' => 'paid_full',
            'order_status' => 'shipped',
            'shipping_address' => 'Jl. Kemang Raya No. 12',
            'shipping_city' => 'Jakarta Selatan',
            'receiver_name' => 'Ibu Hendra Wijaya',
            'receiver_phone' => '08129876543',
        ]);

        Shipment::create([
            'shipment_code' => 'SJ-202608-999',
            'work_order_id' => $wo->id,
            'customer_id' => $this->customer->id,
            'shipment_date' => now()->toDateString(),
            'expedition_name' => 'Baraka Sarana Tama (BST Cargo)',
            'vehicle_plate' => 'B 9900 BST',
            'driver_name' => 'Pak Joko',
            'tracking_number' => 'BST-JKT-887766',
            'packing_verified' => true,
            'delivery_status' => 'in_transit',
            'created_by' => $this->owner->id,
        ]);

        $response = $this->get(route('order.tracking', ['order_number' => 'ORD-TRACK-LIVE-01']));

        $response->assertStatus(200);
        $response->assertSee('ORD-TRACK-LIVE-01');
        $response->assertSee('Informasi Logistik');
        $response->assertSee('Baraka Sarana Tama (BST Cargo)');
        $response->assertSee('BST-JKT-887766');
        $response->assertSee('Terverifikasi Solid Kayu');
    }
}
