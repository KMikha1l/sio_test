<?php

namespace App\Tests\Service;

use App\Service\ApplyTaxesService;
use PHPUnit\Framework\TestCase;

class ApplyTaxesServiceTest extends TestCase
{
    /**
     * @dataProvider applyTaxesDataProvider
     */
    public function testExecute(string $taxNumber, float $totalPrice, float $calculatedPrice): void
    {
        $service = new ApplyTaxesService();
        $this->assertEquals($service->execute($taxNumber, $totalPrice), $calculatedPrice);
    }

    public function applyTaxesDataProvider()
    {
        return [
            ['DE123456789', 1000, 1190],
            ['IT12345678911', 1000, 1220],
            ['FRab123456789', 1000, 1200],
            ['GR123456789', 1000, 1240],
        ];
    }
}
