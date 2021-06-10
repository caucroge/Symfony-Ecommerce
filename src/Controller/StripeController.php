<?php

namespace App\Controller;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Service\PanierService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    public function __construct(protected PanierService $panierService)
    {
    }

    #[Route('/stripe/session/create', name: 'stripe_session_create')]
    public function sessionCreate()
    {
        Stripe::setApiKey('sk_test_51IzMMpAYIAZzu3DTyYZrRutbFpZVdk3Bl3imQl7cz4UmgYzrEQ9IWV7DAjOkTQj8P9sgiKcYYVX5lHIoUj3SAPVx00Q6EYL7sU');
        $YOUR_DOMAIN = 'https://localhost:8000';


        foreach ($this->panierService->getLignePaniers() as $lignePanier) {
            $productsForStripe[] =
                [
                    'price_data' =>
                    [
                        'currency' => 'eur',
                        'unit_amount' => $lignePanier->getPrice() * 100,
                        'product_data' =>
                        [
                            'name' => $lignePanier->getName(),
                            'images' => [$lignePanier->getProduct()->getMainPicture()],
                        ],
                    ],
                    'quantity' => $lignePanier->getQuantity(),
                ];
        }

        $checkout_session = Session::create(
            [
                'payment_method_types' => ['card'],
                'line_items' => [$productsForStripe],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/success.html',
                'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
            ]
        );

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
