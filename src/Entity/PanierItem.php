<?php

namespace App\Entity;

use App\Entity\Product;

class PanierItem
{
    protected $product;
    protected $quantity;

    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getSum(): int
    {
        return $this->product->getPrice() * $this->quantity;
    }
}
