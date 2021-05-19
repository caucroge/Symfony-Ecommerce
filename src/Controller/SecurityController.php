<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        $form = $this->createForm(LoginFormType::class);
        dump($form);
        return $this->render('security/login.html.twig', [
            'formView' => $form->createView(),
        ]);
    }
}
