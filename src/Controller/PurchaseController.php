<?php

namespace App\Controller;

use App\Entity\Money;
use App\Repository\DiscountCouponRepository;
use App\Repository\ProductRepository;
use App\Request\CalculatePriceRequest;
use App\Request\PurchaseRequest;
use App\Service\ApplyCouponService;
use App\Service\PurchaseService;
use App\Service\ApplyTaxesService;
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
                $product = $productRepository->findOrFail($requestProduct['id']);
                $totalPrice += $product->getPrice()->getAmount();
            }

            if ($request->getCouponCode()) {
                $coupon = $couponRepository->findOrFail($request->getCouponCode());
                $totalPrice = $applyCouponService->execute($coupon, $totalPrice);
            }

            $totalPrice = $applyTaxesService->execute($request->getTaxNumber(), $totalPrice);
        } catch (Exception $e) {
            return $this->json([
                'type' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }

        return $this->json([
            'totalPrice' => $totalPrice,
            'currency' => Money::DEFAULT_CURRENCY // для упрощения опустим логику конверсии валют при работе с товарными ценами разных валют
        ]);
    }

    #[Route('/purchase', name: 'app_purchase', methods: ['POST'])]
    public function purchase(PurchaseRequest $request, PurchaseService $service): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PurchaseController.php',
        ]);
    }
}
