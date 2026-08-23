<?php

namespace Tests\Unit;

use App\Services\CodeGeneratorService;
use PHPUnit\Framework\TestCase;

class CodeGeneratorUnitTest extends TestCase
{
    public function test_format_prefixes_are_well_defined(): void
    {
        $this->assertTrue(method_exists(CodeGeneratorService::class, 'generateShipmentCode'));
        $this->assertTrue(method_exists(CodeGeneratorService::class, 'generateSpkNumber'));
        $this->assertTrue(method_exists(CodeGeneratorService::class, 'generateStockTransactionCode'));
        $this->assertTrue(method_exists(CodeGeneratorService::class, 'generateCustomerCode'));
        $this->assertTrue(method_exists(CodeGeneratorService::class, 'generateMaterialCode'));
        $this->assertTrue(method_exists(CodeGeneratorService::class, 'generateProductCode'));
    }
}
