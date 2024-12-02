<?php

namespace App\Service;

use App\Entity\DiscountCoupon;

class ApplyCouponService
{
    function execute(DiscountCoupon $coupon, float $totalPrice): float
    {
        return match ($coupon->getType()) {
            'percent' => round(($totalPrice - $totalPrice / 100 * $coupon->getAmount()), 2),
            'subtraction' => $totalPrice - $coupon->getAmount()
        };
    }
}
