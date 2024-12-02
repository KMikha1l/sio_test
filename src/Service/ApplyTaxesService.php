<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ApplyTaxesService
{
    public function execute(string $taxNumber, float $totalPrice): float
    {
        $taxPercent = match (true) {
            (bool) preg_match('/DE\d{9}/i', $taxNumber) => 19, // Germany
            (bool) preg_match('/IT\d{11}/i', $taxNumber) => 22, // Italy
            (bool) preg_match('/FR[A-Za-z]{2}\d{9}/i', $taxNumber) => 20, // France
            (bool) preg_match('/GR\d{9}/i', $taxNumber) => 24, // Greece
            default => throw new UnprocessableEntityHttpException('incorrect tax number')
        };

        return round($totalPrice + $totalPrice / 100 * $taxPercent, 2);
    }
}
