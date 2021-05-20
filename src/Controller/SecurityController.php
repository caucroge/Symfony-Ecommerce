<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(LoginFormType::class, ['email' => $authenticationUtils->getLastUsername()]);

        return $this->render('security/login.html.twig', [
            'formView' => $form->createView(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }
}
