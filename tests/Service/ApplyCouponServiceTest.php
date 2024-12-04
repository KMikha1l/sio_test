<?php

namespace App\Tests\Service;

use App\Entity\DiscountCoupon;
use App\Entity\Money;
use App\Service\ApplyCouponService;
use PHPUnit\Framework\TestCase;

class ApplyCouponServiceTest extends TestCase
{
    public function testExecute(): void
    {
        $coupon = new DiscountCoupon();
        $coupon->setType(DiscountCoupon::TYPE_PERCENT)
            ->setCurrency(Money::CURRENCY_EURO)
            ->setAmount('50.00');

        $service = new ApplyCouponService();
        $this->assertEquals($service->execute($coupon, 1000), 500);

        $coupon->setAmount('5')->setType(DiscountCoupon::TYPE_SUBTRACTION);
        $this->assertEquals($service->execute($coupon, 1000), 995);
    }
}
