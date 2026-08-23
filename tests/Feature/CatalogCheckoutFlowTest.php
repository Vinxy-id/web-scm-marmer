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

    public function test_checkout_page_renders_dynamic_banks(): void
    {
        $product = Product::first();

        $response = $this->get('/checkout/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('Ringkasan Pesanan');
        $response->assertSee('Transfer Bank BCA');
        $response->assertSee('048-1928-384');
        $response->assertSee('Transfer Bank BRI');
        $response->assertSee('Transfer Bank Mandiri');
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
}
