<?php

namespace App\Service;

use App\Entity\Purchase\Purchase;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class ProcessPaymentService
{
    /**
     * @param string $processorName
     * @param Purchase $purchase
     * @return bool
     * @throws \Exception
     */
    public function execute(string $processorName, Purchase $purchase): bool
    {
        return match ($processorName) {
            'paypal' => $this->payByPayPal($purchase),
            'stripe' => $this->payByStripe($purchase),
            default => throw new UnprocessableEntityHttpException('Incorrect payment method')
        };
    }

    /**
     * @param Purchase $purchase
     * @return bool
     */
    private function payByStripe(Purchase $purchase): bool
    {
        $processor = new StripePaymentProcessor();

        return $processor->processPayment($purchase->getTotalPrice()->getAmount());
    }

    /**
     * @param Purchase $purchase
     * @return bool
     * @throws \Exception
     */
    private function payByPayPal(Purchase $purchase): bool
    {
        $processor = new PaypalPaymentProcessor();

        $processor->pay($purchase->getTotalPrice()->getAmount());

        return true;
    }
}
