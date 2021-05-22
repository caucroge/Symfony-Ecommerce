<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add(int $id,)
    {
        $panier = $this->session->get('panier', []);

        if (array_key_exists($id, $panier)) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $this->session->set('panier', $panier);
    }

    public function getAllSum(): int
    {
        $allSum = 0;

        foreach ($this->session->get('panier', []) as $id => $quantity) {

            $product = $this->productRepository->find($id);
            $allSum += $product->getPrice() * $quantity;
        }

        return $allSum;
    }

    public function getItems(): array
    {
        $items = [];

        foreach ($this->session->get('panier', []) as $id => $quantity) {

            $product = $this->productRepository->find($id);
            $sum = $product->getPrice() * $quantity;
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'sum' => $sum,
            ];
        }

        return $items;
    }
}
