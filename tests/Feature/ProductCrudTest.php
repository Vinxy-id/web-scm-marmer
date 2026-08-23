<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\WorkOrder;
use App\Services\CodeGeneratorService;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    protected User $user;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first() ?? User::create([
            'name' => 'Admin SCM',
            'email' => 'admin@scm-marmer.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'ikm_name' => 'UD Cahaya Onix',
            'is_active' => true,
        ]);

        $this->category = Category::firstOrCreate(
            ['name' => 'Wastafel Test'],
            ['type' => 'product', 'slug' => 'wastafel-test']
        );
    }

    public function test_authenticated_user_can_view_product_index(): void
    {
        $product = Product::create([
            'product_code' => CodeGeneratorService::generateProductCode('marmer'),
            'name' => 'Wastafel Marmer Batu Oval Test',
            'category_id' => $this->category->id,
            'material_type' => 'marmer',
            'ready_stock' => 10,
            'safety_stock' => 2,
            'standard_cogs' => 250000,
            'selling_price' => 450000,
        ]);

        $response = $this->actingAs($this->user)->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertSee('Wastafel Marmer Batu Oval Test');

        $product->delete();
    }

    public function test_user_can_store_new_product(): void
    {
        $data = [
            'name' => 'Wastafel Onyx Tembus Cahaya Test',
            'category_id' => $this->category->id,
            'material_type' => 'onix',
            'dimension_spec' => 'Diameter 40cm',
            'finishing_type' => 'Polish Clear',
            'ready_stock' => 5,
            'safety_stock' => 2,
            'standard_cogs' => 600000,
            'selling_price' => 1200000,
        ];

        $response = $this->actingAs($this->user)->post(route('products.store'), $data);
        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Wastafel Onyx Tembus Cahaya Test',
            'material_type' => 'onix',
            'ready_stock' => 5,
        ]);

        Product::where('name', 'Wastafel Onyx Tembus Cahaya Test')->delete();
    }

    public function test_user_can_update_product(): void
    {
        $product = Product::create([
            'product_code' => CodeGeneratorService::generateProductCode('batu_kali'),
            'name' => 'Wastafel Batu Kali Natural Test',
            'category_id' => $this->category->id,
            'material_type' => 'batu_kali',
            'ready_stock' => 8,
            'safety_stock' => 3,
            'standard_cogs' => 200000,
            'selling_price' => 350000,
        ]);

        $updateData = [
            'name' => 'Wastafel Batu Kali Super Polished Test',
            'category_id' => $this->category->id,
            'material_type' => 'batu_kali',
            'ready_stock' => 12,
            'safety_stock' => 4,
            'standard_cogs' => 220000,
            'selling_price' => 400000,
        ];

        $response = $this->actingAs($this->user)->put(route('products.update', $product), $updateData);
        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Wastafel Batu Kali Super Polished Test',
            'ready_stock' => 12,
        ]);

        $product->delete();
    }

    public function test_user_can_delete_unused_product(): void
    {
        $product = Product::create([
            'product_code' => CodeGeneratorService::generateProductCode('marmer'),
            'name' => 'Produk Sample Hapus Test',
            'category_id' => $this->category->id,
            'material_type' => 'marmer',
            'ready_stock' => 0,
            'safety_stock' => 0,
            'standard_cogs' => 100000,
            'selling_price' => 150000,
        ]);

        $response = $this->actingAs($this->user)->delete(route('products.destroy', $product));
        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_cannot_delete_product_with_work_orders(): void
    {
        $product = Product::create([
            'product_code' => CodeGeneratorService::generateProductCode('marmer'),
            'name' => 'Wastafel Terhubung SPK Test',
            'category_id' => $this->category->id,
            'material_type' => 'marmer',
            'ready_stock' => 5,
            'safety_stock' => 2,
            'standard_cogs' => 200000,
            'selling_price' => 350000,
        ]);

        $wo = WorkOrder::create([
            'spk_number' => CodeGeneratorService::generateSpkNumber(),
            'product_id' => $product->id,
            'target_quantity' => 10,
            'completed_quantity' => 0,
            'status' => 'draft',
            'priority' => 'normal',
            'start_date' => now(),
            'due_date' => now()->addDays(5),
            'created_by' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('products.destroy', $product));
        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);

        $wo->delete();
        $product->delete();
    }

    public function test_code_generator_service_formats(): void
    {
        $spk = CodeGeneratorService::generateSpkNumber();
        $this->assertMatchesRegularExpression('/^SPK-\d{6}-\d{3}$/', $spk);

        $sj = CodeGeneratorService::generateShipmentCode();
        $this->assertMatchesRegularExpression('/^SJ-\d{6}-\d{3}$/', $sj);

        $cust = CodeGeneratorService::generateCustomerCode();
        $this->assertMatchesRegularExpression('/^CUST-\d{6}-\d{3}$/', $cust);

        $prdMarmer = CodeGeneratorService::generateProductCode('marmer');
        $this->assertStringStartsWith('PRD-MRM-', $prdMarmer);

        $prdOnyx = CodeGeneratorService::generateProductCode('onix');
        $this->assertStringStartsWith('PRD-ONX-', $prdOnyx);

        $matMarmer = CodeGeneratorService::generateMaterialCode('marmer');
        $this->assertStringStartsWith('MAT-MRM-', $matMarmer);
    }
}
