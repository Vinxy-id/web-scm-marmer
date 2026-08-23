<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WorkOrder;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

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
    }

    public function test_dashboard_renders_successfully_with_all_kpi_and_chart_data(): void
    {
        $response = $this->actingAs($this->owner)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Monitoring Rantai Pasok');
        $response->assertSee('Bahan Mentah');
        $response->assertSee('SPK Produksi');
        $response->assertSee('Barang Jadi');
        $response->assertSee('E-Commerce');
        $response->assertSee('Nilai Inventori');
        $response->assertSee('SPK Produksi Terkini');
        $response->assertSee('Status 7 Stasiun Mesin');
        $response->assertSee('Lihat Web Publik');
        $response->assertSee('Laporan PCE');
    }

    public function test_dashboard_displays_pending_order_alert_when_orders_exist(): void
    {
        $customer = Customer::first();
        $product = Product::first();

        Order::create([
            'order_number' => 'ORD-TEST-001',
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'payment_scheme' => 'full_100',
            'payment_method' => 'bank_bca',
            'unit_price' => $product->selling_price,
            'total_amount' => $product->selling_price * 2,
            'paid_amount' => $product->selling_price * 2,
            'unique_code' => 123,
            'payment_status' => 'paid_full',
            'order_status' => 'verified',
            'shipping_address' => 'Jl. Test No. 1',
            'shipping_city' => 'Surabaya',
            'receiver_name' => 'Pembeli Uji',
            'receiver_phone' => '081234567890',
        ]);

        $response = $this->actingAs($this->owner)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Pesanan E-Commerce Masuk!');
        $response->assertSee('Perlu Verifikasi');
    }

    public function test_dashboard_flow_stage_6_links_to_wip(): void
    {
        $response = $this->actingAs($this->owner)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee(route('production.wip'));
    }
}
