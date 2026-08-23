<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Order;
use App\Models\WorkOrder;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class BotSpamAndSecurityStressTest extends TestCase
{
    protected $product;
    protected $admin;

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
        $this->admin = User::first();

        // Clear rate limiter cache between tests
        Cache::flush();
    }

    /**
     * TEST 1: BOT FLOODING & RATE LIMITER (HTTP 429)
     * Simulating a bot flooding the checkout endpoint rapidly.
     */
    public function test_bot_flooding_is_blocked_by_rate_limiter_after_5_attempts()
    {
        $payload = [
            'product_id' => $this->product->id,
            'quantity' => 1,
            'receiver_name' => 'Bot Spammer 1',
            'receiver_phone' => '081234567891',
            'shipping_city' => 'Surabaya',
            'shipping_address' => 'Jl. Bot Testing No. 1',
            'payment_scheme' => 'dp_50',
            'payment_method' => 'qris',
        ];

        $server = ['REMOTE_ADDR' => '10.0.0.1'];

        // 5 valid requests within rate limit
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->call('POST', route('checkout.store'), $payload, [], [], $server);
            $response->assertStatus(302); // Redirect to invoice
        }

        // The 6th request from the same client IP MUST BE BLOCKED with HTTP 429
        $floodResponse = $this->call('POST', route('checkout.store'), $payload, [], [], $server);
        $floodResponse->assertStatus(429); // 429 Too Many Requests
    }

    /**
     * TEST 2: PRICE & AMOUNT TAMPERING ATTACK
     * Bot/Attacker tries to tamper with price, discount, or free amount via client payload.
     */
    public function test_client_cannot_tamper_unit_price_or_total_amount()
    {
        $payload = [
            'product_id' => $this->product->id,
            'quantity' => 2,
            'receiver_name' => 'Attacker Hacker',
            'receiver_phone' => '081299887766',
            'shipping_city' => 'Jakarta',
            'shipping_address' => 'Jl. Tampering 123',
            'payment_scheme' => 'full_100',
            'payment_method' => 'qris',
            // Malicious injection attempts:
            'unit_price' => 1.00, // Attacker tries to set price to Rp 1
            'total_amount' => 2.00,
            'paid_amount' => 1000000.00,
            'payment_status' => 'paid_full',
            'order_status' => 'delivered',
            'work_order_id' => 999,
        ];

        $response = $this->call('POST', route('checkout.store'), $payload, [], [], ['REMOTE_ADDR' => '10.0.0.2']);
        $response->assertStatus(302);

        $order = Order::where('receiver_phone', '081299887766')->latest()->first();
        $this->assertNotNull($order);

        // Assert: System must calculate price strictly from database, ignoring attacker payload
        $this->assertEquals((float) $this->product->selling_price, (float) $order->unit_price);
        $this->assertEquals((float) ($this->product->selling_price * 2), (float) $order->total_amount);
        $this->assertEquals(0, $order->paid_amount);
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertEquals('pending_payment', $order->order_status);
        $this->assertNull($order->work_order_id);
    }

    /**
     * TEST 3: QUANTITY BOUNDARY & EXPLOIT ATTACK (Negative, Zero, String, Overflow)
     */
    public function test_invalid_quantity_exploits_are_strictly_rejected()
    {
        $invalidQuantities = [-5, 0, 999999, 'abc', 1.5];

        foreach ($invalidQuantities as $idx => $invalidQty) {
            Cache::flush();
            $payload = [
                'product_id' => $this->product->id,
                'quantity' => $invalidQty,
                'receiver_name' => 'Fuzzing Bot',
                'receiver_phone' => '081234567890',
                'shipping_city' => 'Surabaya',
                'shipping_address' => 'Jl. Test',
                'payment_scheme' => 'dp_50',
                'payment_method' => 'qris',
            ];

            $response = $this->call('POST', route('checkout.store'), $payload, [], [], ['REMOTE_ADDR' => '10.0.0.' . ($idx + 10)]);
            $response->assertSessionHasErrors(['quantity']);
        }
    }

    /**
     * TEST 4: SQL INJECTION PROBING IN ALL INPUT FIELDS
     */
    public function test_sql_injection_payloads_in_inputs_do_not_break_system()
    {
        $sqliPayloads = [
            "'; DROP TABLE orders; --",
            "1' OR '1'='1",
            "admin' --",
            "UNION SELECT null, username, password FROM users --",
        ];

        foreach ($sqliPayloads as $idx => $sqli) {
            Cache::flush();
            $payload = [
                'product_id' => $this->product->id,
                'quantity' => 1,
                'receiver_name' => 'SQLi Test ' . $sqli,
                'receiver_phone' => '081234567890',
                'shipping_city' => 'Surabaya ' . $sqli,
                'shipping_address' => 'Alamat ' . $sqli,
                'payment_scheme' => 'dp_50',
                'payment_method' => 'qris',
                'custom_notes' => 'Catatan ' . $sqli,
            ];

            $response = $this->call('POST', route('checkout.store'), $payload, [], [], ['REMOTE_ADDR' => '10.0.0.' . ($idx + 20)]);
            $response->assertStatus(302); // Successfully handled as text without SQL crash

            // Verify order exists with literal SQL string safely escaped in DB
            $order = Order::where('receiver_phone', '081234567890')->latest()->first();
            $this->assertNotNull($order);
            $this->assertStringContainsString('SQLi Test', $order->receiver_name);
        }

        // Verify database tables still intact
        $this->assertGreaterThan(0, Product::count());
    }

    /**
     * TEST 5: XSS (CROSS-SITE SCRIPTING) PAYLOAD SANITIZATION
     */
    public function test_xss_script_injection_is_safely_rendered()
    {
        $xssString = '<script>alert("XSS_PWNED")</script><img src=x onerror=alert(1)>';

        $payload = [
            'product_id' => $this->product->id,
            'quantity' => 1,
            'receiver_name' => $xssString,
            'receiver_phone' => '081233445566',
            'shipping_city' => 'Malang',
            'shipping_address' => $xssString,
            'payment_scheme' => 'dp_50',
            'payment_method' => 'qris',
            'custom_notes' => $xssString,
        ];

        $response = $this->call('POST', route('checkout.store'), $payload, [], [], ['REMOTE_ADDR' => '10.0.0.30']);
        $order = Order::where('receiver_phone', '081233445566')->latest()->first();
        $this->assertNotNull($order);

        // Check invoice view rendering
        $invoiceResponse = $this->get(route('checkout.invoice', $order->order_number));
        $invoiceResponse->assertStatus(200);
        // HTML entities should be escaped by Blade (e.g. &lt;script&gt; instead of raw <script>)
        $invoiceResponse->assertDontSee('<script>alert("XSS_PWNED")</script>', false);

        // Check Admin order index view rendering
        $adminResponse = $this->actingAs($this->admin)->get(route('orders.index'));
        $adminResponse->assertStatus(200);
        $adminResponse->assertDontSee('<script>alert("XSS_PWNED")</script>', false);
    }

    /**
     * TEST 6: BOT PHONE NUMBER VALIDATION (Rejects fake / foreign / alphanumeric phone numbers)
     */
    public function test_invalid_bot_phone_numbers_are_rejected()
    {
        $invalidPhones = [
            '12345',              // Too short
            '0123456789',         // Invalid prefix (01...)
            '+1-555-0199',        // US Number
            'phone_bot_spam',     // Alphanumeric string
            '0812',               // 4 digits
            '081234567890123456', // Too long (>13 digits)
            '<script>',           // Tag
        ];

        foreach ($invalidPhones as $idx => $badPhone) {
            Cache::flush();
            $payload = [
                'product_id' => $this->product->id,
                'quantity' => 1,
                'receiver_name' => 'Bad Phone Bot',
                'receiver_phone' => $badPhone,
                'shipping_city' => 'Surabaya',
                'shipping_address' => 'Jl. Test',
                'payment_scheme' => 'dp_50',
                'payment_method' => 'qris',
            ];

            $response = $this->call('POST', route('checkout.store'), $payload, [], [], ['REMOTE_ADDR' => '10.0.0.' . ($idx + 40)]);
            $response->assertSessionHasErrors(['receiver_phone']);
        }
    }

    /**
     * TEST 7: ZERO WORKSHOP POLLUTION GUARANTEE (Gate 1 Isolation)
     * Proving that even with multiple valid/pending checkouts, NOT A SINGLE WorkOrder is created.
     */
    public function test_zero_workshop_pollution_under_multiple_checkouts()
    {
        $initialWorkOrdersCount = WorkOrder::count();

        for ($i = 1; $i <= 3; $i++) {
            Cache::flush();
            $this->call('POST', route('checkout.store'), [
                'product_id' => $this->product->id,
                'quantity' => 1,
                'receiver_name' => "Pembeli {$i}",
                'receiver_phone' => "08129876543{$i}",
                'shipping_city' => 'Blitar',
                'shipping_address' => 'Jl. Merdeka No. ' . $i,
                'payment_scheme' => 'dp_50',
                'payment_method' => 'bank_bca',
            ], [], [], ['REMOTE_ADDR' => '10.0.0.' . ($i + 50)]);
        }

        // Workshop Kanban board remains completely unaffected
        $this->assertEquals($initialWorkOrdersCount, WorkOrder::count());
    }
}
