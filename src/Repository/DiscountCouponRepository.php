<?php

namespace App\Repository;

use App\Entity\DiscountCoupon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends ServiceEntityRepository<DiscountCoupon>
 */
class DiscountCouponRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscountCoupon::class);
    }

    public function findOrFail(string $name): DiscountCoupon
    {
        $coupon = $this->find($name);

        $this->getClassName();

        if (!$coupon instanceof DiscountCoupon) {
            throw new NotFoundHttpException('Coupon not found');
        }

        return $coupon;
    }
}
