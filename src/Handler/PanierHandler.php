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

    public function remove($id): void
    {
        $panier = $this->session->get('panier', []);
        unset($panier[$id]);

        $this->session->set('panier', $panier);
    }

    public function decrement($id): void
    {
        $panier = $this->session->get('panier', []);
        if (!array_key_exists($id, $panier)) {
            return;
        }

        if ($panier[$id] === 1) {
            $this->remove($id);
        } else {
            $panier[$id]--;
            $this->session->set('panier', $panier);
        }

        return;
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

    public function getItems(): array
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

    public function getCountItems(): int
    {
        $sumQuantity = 0;

        foreach ($this->session->get('panier', []) as $id => $quantity) {

            $sumQuantity += $quantity;
        }

        return $sumQuantity;
    }
}
