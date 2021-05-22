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

    protected function getPanier(): array
    {
        return $this->session->get('panier', []);
    }

    protected function savePanier(array $panier): void
    {
        $this->session->set('panier', $panier);
    }

    public function add(int $id,)
    {
        $panier = $this->getPanier();

        if (!array_key_exists($id, $panier)) {
            $panier[$id] = 0;
        }

        $panier[$id]++;

        $this->savePanier($panier);
    }

    public function remove($id): void
    {
        $panier = $this->getPanier();
        unset($panier[$id]);

        $this->savePanier($panier);
    }

    public function decrement($id): void
    {
        $panier = $this->getPanier();
        if (!array_key_exists($id, $panier)) {
            return;
        }

        if ($panier[$id] === 1) {
            $this->remove($id);
        } else {
            $panier[$id]--;
            $this->savePanier($panier);
        }

        return;
    }

    public function getAllSum(): int
    {
        $allSum = 0;

        foreach ($this->getPanier() as $id => $quantity) {

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

        foreach ($this->getPanier()  as $id => $quantity) {

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

        foreach ($this->getPanier()  as $id => $quantity) {

            $sumQuantity += $quantity;
        }

        return $sumQuantity;
    }
}
