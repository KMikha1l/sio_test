<?php

namespace App\Entity\Purchase;

use App\Entity\DiscountCoupon;
use App\Entity\Money;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity()]
class Purchase
{
    use TimestampableEntity;

    public const STATUS_SUCCESS = 'success';

    public const STATUS_FAILED = 'failed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $taxNumber = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'coupon_name', referencedColumnName: 'name')]
    private ?DiscountCoupon $coupon = null;

    #[ORM\Embedded(class: Money::class)]
    private Money $totalPrice;

    #[ORM\Column(type: Types::STRING, columnDefinition: "ENUM('success', 'failed')")]
    private ?string $status = null;

    #[ORM\OneToMany(targetEntity: PurchaseItem::class, mappedBy: 'purchase', cascade: ['persist', 'remove'])]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): static
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    public function getCoupon(): ?DiscountCoupon
    {
        return $this->coupon;
    }

    public function setCoupon(?DiscountCoupon $coupon): static
    {
        $this->coupon = $coupon;

        return $this;
    }

    public function setTotalPrice(Money $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getTotalPrice(): Money
    {
        return $this->totalPrice;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(PurchaseItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setPurchase($this);
        }

        return $this;
    }

    public function removeItem(PurchaseItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getPurchase() === $this) {
                $item->setPurchase(null);
            }
        }

        return $this;
    }
}
