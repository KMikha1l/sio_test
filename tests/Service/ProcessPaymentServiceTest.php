<?php

namespace App\Tests\Service;

use App\Entity\Money;
use App\Entity\Purchase\Purchase;
use App\Service\ProcessPaymentService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ProcessPaymentServiceTest extends TestCase
{
    public function testExecuteWithPayPal(): void
    {
        $processor = 'paypal';
        $service = new ProcessPaymentService();
        $purchase = new Purchase();
        $purchase->setTotalPrice(new Money(500));

        $this->assertTrue($service->execute($processor, $purchase));
    }

    public function testExecuteWithStripe(): void
    {
        $processor = 'stripe';
        $service = new ProcessPaymentService();
        $purchase = new Purchase();
        $purchase->setTotalPrice(new Money(500));

        $this->assertTrue($service->execute($processor, $purchase));
    }

    public function testExecuteWithStripeReturnFalse()
    {
        $processor = 'stripe';
        $service = new ProcessPaymentService();
        $purchase = new Purchase();
        $purchase->setTotalPrice(new Money(50));

        $this->assertFalse($service->execute($processor, $purchase));
    }

    public function testInvalidProcessorName()
    {
        $processor = 'helloErrorProcessor';
        $purchase = new Purchase();

        $this->expectException(UnprocessableEntityHttpException::class);
        $this->expectExceptionMessage('Incorrect payment method');

        $service = new ProcessPaymentService();
        $service->execute($processor, $purchase);
    }
}
