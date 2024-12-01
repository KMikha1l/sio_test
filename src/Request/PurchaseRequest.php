<?php

namespace App\Request;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

class PurchaseRequest extends BaseRequest
{
    #[Assert\NotBlank]
    #[Assert\All([
        new Assert\Collection(
            fields: [
                'id' => [
                    new Assert\Type(Types::INTEGER)
                ],
                'quantity' => [
                    new Assert\Type(Types::INTEGER),
                    new Assert\Length(max: 2)
                ]
            ],
            allowMissingFields: false,
        )
    ])]
    protected array $products;

    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    #[Assert\Length(min: 12, max: 14)]
    protected string $taxNumber;

    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    protected string $couponCode;

    #[Assert\NotBlank]
    #[Assert\Type(Types::STRING)]
    protected string $paymentProcessor;
}
