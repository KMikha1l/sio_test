<?php

namespace App\Entity\Purchase;

use App\Entity\Money;
use App\Entity\Product;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity()]
class PurchaseItem
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Embedded(class: Money::class)]
    private ?Money $sellingPrice = null;

    #[ORM\ManyToOne(targetEntity: Purchase::class, inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'purchase_id', referencedColumnName: 'id')]
    private Purchase|null $purchase;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getPurchase(): ?Purchase
    {
        return $this->purchase;
    }

    public function setPurchase(Purchase $purchase): PurchaseItem
    {
        $this->purchase = $purchase;

        return $this;
    }

    public function getSellingPrice(): ?Money
    {
        return $this->sellingPrice;
    }

    public function setSellingPrice(?Money $sellingPrice): PurchaseItem
    {
        $this->sellingPrice = $sellingPrice;
        return $this;
    }
}
