<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    #[Route('/adresse/update/{id}', name: 'addresse_update_id')]
    public function update($id)
    {
        $this->address = $this->adressRepository->findDefault($this->getUser(), "default");

        return $this->render(
            "commande/addressLivraison.html.twig",
            [
                'addressLivraison' => $this->address->toString(),
                'lignePaniers' => $this->panierService->getLignePaniers(),
                'totalPanier' => $this->panierService->getTotalPanier()
            ]
        );
    }

    #[Route('/adresse/create', name: 'addresse_create')]
    public function create()
    {
        $this->address = $this->adressRepository->findDefault($this->getUser(), "default");

        return $this->render(
            "commande/addressLivraison.html.twig",
            [
                'addressLivraison' => $this->address->toString(),
                'lignePaniers' => $this->panierService->getLignePaniers(),
                'totalPanier' => $this->panierService->getTotalPanier()
            ]
        );
    }
}
