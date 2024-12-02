<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findOrFail(int $id): Product
    {
        $product = $this->find($id);

        $this->getClassName();

        if (!$product instanceof Product) {
            throw new NotFoundHttpException(sprintf('Product with id=%s not found', $id));
        }

        return $product;
    }
}
