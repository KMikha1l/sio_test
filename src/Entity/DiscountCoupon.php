<?php

namespace App\Entity;

use App\Repository\DiscountCouponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiscountCouponRepository::class)]
class DiscountCoupon
{
    public const TYPE_PERCENT = 'percent';
    public const TYPE_SUBTRACTION = 'subtraction';
    public const DEFAULT_CURRENCY = 'EUR';

    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, columnDefinition: "ENUM('percent', 'subtraction')")]
    private ?string $type = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $currency = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }
}
