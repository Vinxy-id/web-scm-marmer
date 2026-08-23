<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Order;
use App\Models\WorkOrder;
use App\Models\Customer;

class EcommerceCheckoutTest extends TestCase
{
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        if (\App\Models\User::count() === 0) {
            \App\Models\User::create([
                'name' => 'Admin SCM',
                'email' => 'admin@scm-marmer.com',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'ikm_name' => 'UD Cahaya Onix',
                'is_active' => true,
            ]);
        }

        if (Product::count() === 0) {
            $this->seed(\Database\Seeders\DatabaseSeeder::class);
        }

        $this->product = Product::first();
    }

    public function test_guest_can_view_checkout_page()
    {
        $response = $this->get(route('checkout.show', $this->product->id));
        $response->assertStatus(200);
        $response->assertSee($this->product->name);
        $response->assertSee('DP 50%');
        $response->assertSee('Lunas 100%');
    }

    public function test_guest_can_submit_checkout_and_generate_spk()
    {
        $payload = [
            'product_id' => $this->product->id,
            'quantity' => 2,
            'receiver_name' => 'Bpk. Ahmad Santoso',
            'receiver_phone' => '081298765432',
            'shipping_city' => 'Surabaya',
            'shipping_address' => 'Jl. Pemuda No. 45, Genteng, Surabaya',
            'payment_scheme' => 'dp_50',
            'payment_method' => 'qris',
            'custom_notes' => 'Tolong pilihkan motif serat yang halus',
        ];

        $response = $this->post(route('checkout.store'), $payload);

        $order = Order::where('receiver_phone', '081298765432')->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals(2, $order->quantity);
        $this->assertEquals('dp_50', $order->payment_scheme);
        $this->assertEquals('qris', $order->payment_method);
        $this->assertNotNull($order->work_order_id);

        $response->assertRedirect(route('checkout.invoice', $order->order_number));
    }

    public function test_guest_can_view_digital_invoice()
    {
        $order = Order::first();
        if (!$order) {
            $this->test_guest_can_submit_checkout_and_generate_spk();
            $order = Order::latest()->first();
        }

        $response = $this->get(route('checkout.invoice', $order->order_number));
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee('Instruksi Pembayaran');
    }

    public function test_guest_can_track_order_live()
    {
        $order = Order::first();
        if (!$order) {
            $this->test_guest_can_submit_checkout_and_generate_spk();
            $order = Order::latest()->first();
        }

        // 1. Search with order number
        $response = $this->get(route('order.tracking', ['order_number' => $order->order_number]));
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee($order->receiver_name);

        // 2. Search with non-existent number
        $responseNotFound = $this->get(route('order.tracking', ['order_number' => 'ORD-INVALID-9999']));
        $responseNotFound->assertStatus(200);
        $responseNotFound->assertSee('Pesanan Tidak Ditemukan');
    }
}
