<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Order;
use App\Models\WorkOrder;
use App\Models\Customer;
use App\Models\User;

class EcommerceCheckoutTest extends TestCase
{
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        if (User::count() === 0) {
            User::create([
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

    public function test_guest_can_submit_checkout_without_polluting_work_orders()
    {
        $initialWorkOrdersCount = WorkOrder::count();

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
        $this->assertEquals('pending_payment', $order->order_status);
        $this->assertNull($order->work_order_id); // Gate 1: NOT in workshop yet!
        $this->assertNotNull($order->expires_at);

        // Verify that NO new WorkOrder was added to the workshop floor!
        $this->assertEquals($initialWorkOrdersCount, WorkOrder::count());

        $response->assertRedirect(route('checkout.invoice', $order->order_number));
    }

    public function test_guest_can_view_digital_invoice()
    {
        $order = Order::first();
        if (!$order) {
            $this->test_guest_can_submit_checkout_without_polluting_work_orders();
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
            $this->test_guest_can_submit_checkout_without_polluting_work_orders();
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

    public function test_guest_can_check_payment_status_route()
    {
        $order = Order::first();
        if (!$order) {
            $this->test_guest_can_submit_checkout_without_polluting_work_orders();
            $order = Order::latest()->first();
        }

        $response = $this->get(route('checkout.check-status', $order->order_number));
        $response->assertRedirect(route('checkout.invoice', $order->order_number));
    }

    public function test_guest_can_track_order_using_phone_number()
    {
        $order = Order::first();
        if (!$order) {
            $this->test_guest_can_submit_checkout_without_polluting_work_orders();
            $order = Order::latest()->first();
        }

        $response = $this->get(route('order.tracking', ['order_number' => $order->receiver_phone]));
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
    }

    public function test_paid_midtrans_order_disables_pay_button_and_shows_verification()
    {
        $order = Order::create([
            'order_number' => 'ORD-TEST-PAID-001',
            'customer_id' => 1,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'unit_price' => $this->product->price ?? 1000000,
            'total_amount' => $this->product->price ?? 1000000,
            'paid_amount' => $this->product->price ?? 1000000,
            'payment_scheme' => 'full_100',
            'payment_method' => 'midtrans',
            'snap_token' => 'mock-snap-token-123',
            'midtrans_transaction_id' => 'TRX-MIDTRANS-999',
            'midtrans_payment_type' => 'bca_va',
            'midtrans_status' => 'settlement',
            'payment_status' => 'paid_full',
            'order_status' => 'in_production',
            'receiver_name' => 'Bpk. Hendra',
            'receiver_phone' => '081233445566',
            'shipping_city' => 'Jakarta Selatan',
            'shipping_address' => 'Jl. Sudirman No. 1',
            'expires_at' => now()->addDay(),
        ]);

        $response = $this->get(route('checkout.invoice', $order->order_number));
        $response->assertStatus(200);
        $response->assertSee('Pembayaran Berhasil Terverifikasi!');
        $response->assertSee('TRX-MIDTRANS-999');
        $response->assertDontSee('id="pay-button"', false);
        $response->assertDontSee('Ganti Metode');

        // Test check-status on already paid order
        $checkStatusResponse = $this->get(route('checkout.check-status', $order->order_number));
        $checkStatusResponse->assertRedirect(route('checkout.invoice', $order->order_number));
        $checkStatusResponse->assertSessionHas('success');
        $checkStatusResponse->assertSessionMissing('info');

        // Test regenerate snap token on already paid order
        $regenResponse = $this->get(route('checkout.regenerate-snap', $order->order_number));
        $regenResponse->assertRedirect(route('checkout.invoice', $order->order_number));
        $regenResponse->assertSessionHas('info');
    }

    public function test_invoice_view_renders_clean_labels_and_print_layout_without_raw_snake_case()
    {
        $product = Product::first();

        // Create an order with Midtrans bank_transfer channel
        $order = Order::create([
            'order_number' => 'ORD-TEST-PRINT-001',
            'customer_id' => 1,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->selling_price ?? 1250000,
            'total_amount' => $product->selling_price ?? 1250000,
            'payment_scheme' => 'dp_50',
            'payment_method' => 'midtrans',
            'midtrans_payment_type' => 'bank_transfer',
            'midtrans_status' => 'settlement',
            'payment_status' => 'paid_dp',
            'order_status' => 'in_production',
            'receiver_name' => 'Dr. H. Bambang',
            'receiver_phone' => '081234567890',
            'shipping_city' => 'Surabaya',
            'shipping_address' => 'Jl. Pemuda No. 45',
            'expires_at' => now()->addDay(),
        ]);

        $response = $this->get(route('checkout.invoice', $order->order_number));
        $response->assertStatus(200);

        // Verify clean human-friendly Indonesian payment label is shown
        $response->assertSee('Transfer Virtual Account / Bank');
        $response->assertSee('Uang Muka (DP 50%)');
        $response->assertSee('DP 50% TERVERIFIKASI');

        // Verify Kop Surat & Official A4 Print Elements are present
        $response->assertSee('KLASTER IKM KERAJINAN MARMER & ONYX TULUNGAGUNG', false);
        $response->assertSee('FAKTUR PENJUALAN & BUKTI PEMBAYARAN', false);
        $response->assertSee('PEMESAN / DITUJUKAN KEPADA:', false);
        $response->assertSee('INFORMASI TRANSAKSI & SCM:', false);
        $response->assertSee('Catatan & Jaminan Garansi Mutu Pengrajin:', false);
        $response->assertSee('Verifikasi Digital:', false);
        $response->assertSee('TERVALIDASI SISTEM E-SCM', false);

        // Verify raw snake_case "bank_transfer" is NOT rendered as raw text in payment channel
        $this->assertEquals('Transfer Virtual Account / Bank', $order->formatted_payment_type);
        $this->assertEquals('Uang Muka (DP 50%)', $order->payment_scheme_label);
    }
}
