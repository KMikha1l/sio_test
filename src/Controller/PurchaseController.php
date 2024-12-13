<?php

namespace App\Controller;

use App\Entity\Money;
use App\Entity\Purchase\Purchase;
use App\Entity\Purchase\PurchaseItem;
use App\Repository\DiscountCouponRepository;
use App\Repository\ProductRepository;
use App\Request\CalculatePriceRequest;
use App\Request\PurchaseRequest;
use App\Service\ApplyCouponService;
use App\Service\ProcessPaymentService;
use App\Service\ApplyTaxesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Exception;

class PurchaseController extends AbstractController
{
    /**
     * @param CalculatePriceRequest $request
     * @param ProductRepository $productRepository
     * @param DiscountCouponRepository $couponRepository
     * @param ApplyTaxesService $applyTaxesService
     * @param ApplyCouponService $applyCouponService
     * @return JsonResponse
     */
    #[Route('/calculate-price', name: 'app_calculate_price', methods: ['POST'])]
    public function calculatePrice(
        CalculatePriceRequest    $request,
        ProductRepository        $productRepository,
        DiscountCouponRepository $couponRepository,
        ApplyTaxesService        $applyTaxesService,
        ApplyCouponService       $applyCouponService
    ): JsonResponse
    {
        $totalPrice = 0.00;

        try {
            foreach ($request->getProducts() as $requestProduct) {
                for ($i = $requestProduct['quantity']; $i >= 1; $i--) {
                    $product = $productRepository->findOrFail($requestProduct['id']);
                    $totalPrice += $product->getPrice()->getAmount();
                }
            }

            if ($request->getCouponCode()) {
                $coupon = $couponRepository->findOrFail($request->getCouponCode());
                $totalPrice = $applyCouponService->execute($coupon, $totalPrice);
            }

            $totalPrice = $applyTaxesService->execute($request->getTaxNumber(), $totalPrice);
        } catch (Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }

        return $this->json([
            'status' => 'success',
            'totalPrice' => $totalPrice,
            'currency' => Money::CURRENCY_EURO // для упрощения опустим логику конверсии валют при работе с товарными ценами разных валют
        ]);
    }

    /**
     * @param PurchaseRequest $request
     * @param ProductRepository $productRepository
     * @param DiscountCouponRepository $couponRepository
     * @param ApplyCouponService $applyCouponService
     * @param ApplyTaxesService $applyTaxesService
     * @param EntityManagerInterface $entityManager
     * @param ProcessPaymentService $paymentService
     * @return JsonResponse
     */
    #[Route('/purchase', name: 'app_purchase', methods: ['POST'])]
    public function purchase(
        PurchaseRequest          $request,
        ProductRepository        $productRepository,
        DiscountCouponRepository $couponRepository,
        ApplyCouponService       $applyCouponService,
        ApplyTaxesService        $applyTaxesService,
        EntityManagerInterface   $entityManager,
        ProcessPaymentService    $paymentService
    ): JsonResponse
    {
        $totalPrice = 0.00;
        $purchase = new Purchase();

        try {
            foreach ($request->getProducts() as $requestProduct) {
                for ($i = $requestProduct['quantity']; $i >= 1; $i--) {
                    $product = $productRepository->findOrFail($requestProduct['id']);
                    $totalPrice += $product->getPrice()->getAmount();
                    $item = new PurchaseItem();
                    $item->setProduct($product)
                        ->setSellingPrice($product->getPrice());
                    $purchase->addItem($item);
                }
            }

            if ($request->getCouponCode()) {
                $coupon = $couponRepository->findOrFail($request->getCouponCode());
                $totalPrice = $applyCouponService->execute($coupon, $totalPrice);
            }

            $totalPrice = $applyTaxesService->execute($request->getTaxNumber(), $totalPrice);
            $purchase->setTotalPrice(new Money($totalPrice, Money::CURRENCY_EURO));
            $purchase->setTaxNumber($request->getTaxNumber());

            $payment = $paymentService->execute($request->getPaymentProcessor(), $purchase);
            $purchase->setStatus($payment ? Purchase::STATUS_SUCCESS : Purchase::STATUS_FAILED);

            $entityManager->persist($purchase);
            $entityManager->flush();
        } catch (Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }

        return $this->json([
            'purchaseStatus' => $purchase->getStatus(),
            'totalPrice' => $totalPrice,
            'currency' => Money::CURRENCY_EURO
        ]);
    }
}
