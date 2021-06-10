<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\LignePanier;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{
    // Constantes
    const ENTITY_NOT_FOUND = "Le produit demandé n'est plus disponible !";

    // Attributs
    protected SessionInterface $session;
    protected ProductRepository $productRepository;
    protected UserRepository $userRepository;
    protected ?LignePanier $lignePanier;
    protected ?Product $product;
    protected ?User $user;

    // Constructeur
    public function __construct(
        SessionInterface $session,
        ProductRepository $productRepository,
        UserRepository $userRepository,
        Security $security,
    ) {
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->lignePaniers = new ArrayCollection();
        $this->user = $security->getUser();
    }

    public function getLignePaniers(): array
    {
        return $this->user->getLignePaniers()->getValues();
    }

    public function searchProduit($product_id): Product
    {
        /**
         * @var Product $product
         */
        $product = $this->productRepository->find($product_id);
        if (!$product) {
            throw new EntityNotFoundException(self::ENTITY_NOT_FOUND);
        }
        return $product;
    }

    public function update(Product $product, int $quantity)
    {
        /**
         * @var LignePanier $lignePanier
         */
        $lignePaniers = $this->getLignePaniers();

        foreach ($lignePaniers as $lignePanier) {

            if ($lignePanier->getProduct()->getId() == $product->getId()) {

                $quantityProduct = $lignePanier->getQuantity() + $quantity;

                if ($quantityProduct == 0) {
                    $this->user->removeLignePanier($lignePanier);
                } else {
                    $lignePanier->setQuantity($quantityProduct);
                }

                return;
            }
        }

        // Le produit n'exite pas encore dans le panier alors on crée une nouvelle lignePanier
        $lignePanier = new LignePanier();
        $lignePanier
            ->setName($product->getName())
            ->setPrice($product->getPrice())
            ->setQuantity($quantity)
            ->setTotal($product->getPrice() * $lignePanier->getQuantity())
            ->setProduct($product);

        $this->user->addLignePanier($lignePanier);
    }

    public function incrementProduit(int $product_id,): ?Product
    {
        $product = $this->searchProduit($product_id);
        $this->update($product, 1);
        $this->userRepository->updateUser($this->user);

        return $product;
    }

    public function decrementProduit(int $product_id): Product
    {
        $product = $this->searchProduit($product_id);
        $this->update($product, -1);
        $this->userRepository->updateUser($this->user);

        return $product;
    }

    public function remove(int $lignePanierId): ?Product
    {
        $lignePaniers = $this->getLignePaniers();
        $product = null;

        foreach ($lignePaniers as $lignePanier) {

            if ($lignePanier->getId() == $lignePanierId) {

                $product = $lignePanier->getProduct();
                $this->user->removeLignePanier($lignePanier);
                $this->userRepository->updateUser($this->user);
            }
        }

        if ($product === null) {
            throw new EntityNotFoundException(self::ENTITY_NOT_FOUND);
        }

        return $product;
    }

    public function removeAll()
    {
        foreach ($this->getLignePaniers() as $lignePanier) {
            $this->user->removeLignePanier($lignePanier);
        }

        $this->userRepository->updateUser($this->user);
    }

    public function getTotalPanier(): int
    {
        $total = 0;
        $lignePaniers = $this->getLignePaniers();

        foreach ($lignePaniers as $lignePanier) {

            $total += $lignePanier->getPrice() * $lignePanier->getQuantity();;
        }

        return $total;
    }

    public function getCountItems(): int
    {
        $totalItems = 0;
        $lignePaniers = $this->user->getLignePaniers();

        foreach ($lignePaniers as $lignePanier) {

            $totalItems += $lignePanier->getQuantity();
        }

        return $totalItems;
    }
}
