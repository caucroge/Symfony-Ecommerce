<?php

namespace App\Handler;

use App\Entity\PanierItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierHandler
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
            if (!$product) {
                continue;
            }

            $allSum += $product->getPrice() * $quantity;
        }

        return $allSum;
    }

    public function getPanierItems(): array
    {
        $panierItems = [];

        foreach ($this->session->get('panier', []) as $id => $quantity) {

            $product = $this->productRepository->find($id);
            if (!$product) {
                continue;
            }

            $panierItems[] = new PanierItem($product, $quantity);
        }

        return $panierItems;
    }
}
