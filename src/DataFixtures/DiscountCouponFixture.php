<?php

namespace App\DataFixtures;

use App\Entity\DiscountCoupon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DiscountCouponFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $coupon = new DiscountCoupon();
        $coupon->setType(DiscountCoupon::TYPE_PERCENT)
            ->setAmount('50')
            ->setName('P50');
        $manager->persist($coupon);

        $coupon = new DiscountCoupon();
        $coupon->setName('S3')
            ->setType(DiscountCoupon::TYPE_SUBTRACTION)
            ->setAmount('3')
            ->setCurrency(DiscountCoupon::DEFAULT_CURRENCY);
        $manager->persist($coupon);

        $coupon = new DiscountCoupon();
        $coupon->setName('S5')
            ->setType(DiscountCoupon::TYPE_SUBTRACTION)
            ->setAmount('5')
            ->setCurrency(DiscountCoupon::DEFAULT_CURRENCY);
        $manager->persist($coupon);

        $manager->flush();
    }
}
