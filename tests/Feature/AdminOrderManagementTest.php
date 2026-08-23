<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Order;
use App\Models\WorkOrder;
use App\Models\Customer;
use App\Models\User;

class AdminOrderManagementTest extends TestCase
{
    protected $user;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        if (Product::count() === 0) {
            $this->seed(\Database\Seeders\DatabaseSeeder::class);
        }

        $this->user = User::first() ?? User::create([
            'name' => 'Admin SCM',
            'email' => 'admin@scm-marmer.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'ikm_name' => 'UD Cahaya Onix',
            'is_active' => true,
        ]);

        $this->product = Product::first();
    }

    public function test_admin_can_view_orders_index()
    {
        $response = $this->actingAs($this->user)->get(route('orders.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Pesanan Masuk');
    }

    public function test_admin_can_verify_payment_and_generate_spk()
    {
        // 1. Create a pending order
        $customer = Customer::first() ?? Customer::create([
            'customer_code' => 'CUST-TEST',
            'name' => 'Pak Budi Santoso',
            'phone' => '081234567899',
            'address' => 'Jl. Basuki Rahmat No. 10',
            'city' => 'Tulungagung',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-TEST-' . time(),
            'customer_id' => $customer->id,
            'product_id' => $this->product->id,
            'work_order_id' => null,
            'quantity' => 3,
            'payment_scheme' => 'dp_50',
            'payment_method' => 'qris',
            'unit_price' => $this->product->selling_price,
            'total_amount' => $this->product->selling_price * 3,
            'paid_amount' => 0,
            'unique_code' => 123,
            'payment_status' => 'unpaid',
            'order_status' => 'pending_payment',
            'shipping_address' => 'Jl. Basuki Rahmat No. 10',
            'shipping_city' => 'Tulungagung',
            'receiver_name' => 'Pak Budi Santoso',
            'receiver_phone' => '081234567899',
            'expires_at' => now()->addHours(24),
        ]);

        $initialWorkOrdersCount = WorkOrder::count();

        // 2. Admin verifies payment and triggers SPK generation
        $response = $this->actingAs($this->user)->post(route('orders.verify-spk', $order->id));
        $response->assertRedirect(route('orders.index'));

        // 3. Verify Order updated & WorkOrder created
        $order->refresh();
        $this->assertNotNull($order->work_order_id);
        $this->assertEquals('paid_dp', $order->payment_status);
        $this->assertEquals('in_production', $order->order_status);
        $this->assertEquals($initialWorkOrdersCount + 1, WorkOrder::count());

        $workOrder = WorkOrder::find($order->work_order_id);
        $this->assertNotNull($workOrder);
        $this->assertEquals('scheduled', $workOrder->status);
        $this->assertEquals(3, $workOrder->target_quantity);
    }

    public function test_admin_can_cancel_order()
    {
        $customer = Customer::first();
        $order = Order::create([
            'order_number' => 'ORD-CANCEL-' . time(),
            'customer_id' => $customer->id,
            'product_id' => $this->product->id,
            'work_order_id' => null,
            'quantity' => 1,
            'payment_scheme' => 'full_100',
            'payment_method' => 'bank_bca',
            'unit_price' => 500000,
            'total_amount' => 500000,
            'paid_amount' => 0,
            'unique_code' => 456,
            'payment_status' => 'unpaid',
            'order_status' => 'pending_payment',
            'shipping_address' => 'Jl. Merdeka No. 1',
            'shipping_city' => 'Kediri',
            'receiver_name' => 'Spam User',
            'receiver_phone' => '081299887766',
            'expires_at' => now()->addHours(24),
        ]);

        $response = $this->actingAs($this->user)->post(route('orders.cancel', $order->id), [
            'cancellation_reason' => 'Data Fiktif / Spam Checkout',
        ]);

        $response->assertRedirect(route('orders.index'));

        $order->refresh();
        $this->assertEquals('cancelled', $order->order_status);
        $this->assertEquals('Data Fiktif / Spam Checkout', $order->cancellation_reason);
        $this->assertNotNull($order->cancelled_at);
    }

    public function test_admin_can_delete_spam_order()
    {
        $customer = Customer::first();
        $order = Order::create([
            'order_number' => 'ORD-DEL-' . time(),
            'customer_id' => $customer->id,
            'product_id' => $this->product->id,
            'work_order_id' => null,
            'quantity' => 1,
            'payment_scheme' => 'full_100',
            'payment_method' => 'bank_bca',
            'unit_price' => 500000,
            'total_amount' => 500000,
            'paid_amount' => 0,
            'unique_code' => 456,
            'payment_status' => 'unpaid',
            'order_status' => 'cancelled',
            'shipping_address' => 'Jl. Merdeka No. 1',
            'shipping_city' => 'Kediri',
            'receiver_name' => 'Spam User',
            'receiver_phone' => '081299887766',
        ]);

        $orderId = $order->id;

        $response = $this->actingAs($this->user)->delete(route('orders.destroy', $orderId));
        $response->assertRedirect(route('orders.index'));

        $this->assertNull(Order::find($orderId));
    }
}
