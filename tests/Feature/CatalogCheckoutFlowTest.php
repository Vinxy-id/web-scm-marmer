<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogCheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_landing_page_renders_hero_and_material_counts(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Koleksi Kerajinan Marmer');
        $response->assertSee('Semua Produk');
        $response->assertSee('Wastafel Marmer');
        $response->assertSee('Wastafel Onyx Tembus Cahaya');
        $response->assertSee('Batu Kali');
        $response->assertSee('Lihat Spesifikasi');
    }

    public function test_catalog_search_and_stock_filtering(): void
    {
        // 1. Full catalog
        $response = $this->get('/katalog');
        $response->assertStatus(200);
        $response->assertSee('Ketersediaan Stok');

        // 2. Filter ready stock
        $responseReady = $this->get('/katalog?stock=ready');
        $responseReady->assertStatus(200);

        // 3. Filter preorder stock
        $responsePreorder = $this->get('/katalog?stock=preorder');
        $responsePreorder->assertStatus(200);

        // 4. Search query
        $responseSearch = $this->get('/katalog?q=Wastafel');
        $responseSearch->assertStatus(200);
    }

    public function test_product_detail_page_and_related_products(): void
    {
        $product = Product::first();

        $response = $this->get('/katalog/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('Beli / Checkout Online');
        $response->assertSee('Bagikan Produk');
        $response->assertSee('Estimasi Bobot Fisik');
    }

    public function test_public_catalog_json_endpoint_does_not_leak_bank_details(): void
    {
        $product = Product::first();

        $response = $this->get('/katalog/' . $product->id . '?json=1');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.name', $product->name);
        
        // Assert sensitive financial data is strictly NOT exposed in public JSON
        $data = $response->json('data.artisan');
        $this->assertArrayNotHasKey('account_number', $data);
        $this->assertArrayNotHasKey('bank_name', $data);
        $this->assertArrayNotHasKey('account_holder', $data);
    }

    public function test_dynamic_xml_sitemap_renders_all_products(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));
        $response->assertSee('<urlset', false);
        $response->assertSee('/katalog', false);
        $response->assertSee('/lacak-pesanan', false);
        
        $product = Product::first();
        if ($product) {
            $response->assertSee('/katalog/' . $product->id, false);
        }
    }

    public function test_checkout_page_renders_automated_midtrans_payment_channels(): void
    {
        $product = Product::first();

        $response = $this->get('/checkout/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('Ringkasan Pesanan');
        $response->assertSee('Saluran Pembayaran Digital (Otomatis)');
        $response->assertSee('Pembayaran Terintegrasi Midtrans');
        $response->assertSee('QRIS Dinamis');
        $response->assertSee('Virtual Account');
        $response->assertSee('Bebas Kode Unik');
    }

    public function test_checkout_submission_and_invoice_generation(): void
    {
        $product = Product::first();

        $postData = [
            'product_id' => $product->id,
            'quantity' => 2,
            'receiver_name' => 'Budi Santoso',
            'receiver_phone' => '081234567890',
            'shipping_city' => 'Surabaya',
            'shipping_address' => 'Jl. Pemuda No. 45, Genteng',
            'payment_scheme' => 'dp_50',
            'payment_method' => 'bank_bca',
            'custom_notes' => 'Tolong pilih yang seratnya lurus',
        ];

        $response = $this->post('/checkout', $postData);

        $order = Order::where('receiver_name', 'Budi Santoso')->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending_payment', $order->order_status);
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertEquals(2, $order->quantity);

        $response->assertRedirect(route('checkout.invoice', $order->order_number));

        // Test invoice view
        $invoiceResponse = $this->get(route('checkout.invoice', $order->order_number));
        $invoiceResponse->assertStatus(200);
        $invoiceResponse->assertSee($order->order_number);
        $invoiceResponse->assertSee('TAGIHAN UANG MUKA (DP 50%)');

        // Test tracking view
        $trackResponse = $this->get('/lacak-pesanan?order_number=' . $order->order_number);
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee($order->order_number);
        $trackResponse->assertSee('Budi Santoso');
    }

    public function test_custom_404_page_renders_clean_branded_error(): void
    {
        $response = $this->get('/non-existent-page-test-audit-xyz');
        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('Halaman atau Produk Tidak Ditemukan');
        $response->assertSee('Jelajahi Katalog Produk');
    }
}

