<?php

namespace App\DataFixtures;

use App\Entity\Money;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName('iphone')
            ->setPrice(new Money(100));
        $manager->persist($product);

        $product = new Product();
        $product->setName('headphones')
            ->setPrice(new Money(20));
        $manager->persist($product);

        $product = new Product();
        $product->setName('case for iphone')
            ->setPrice(new Money(10));
        $manager->persist($product);

        $manager->flush();
    }
}
