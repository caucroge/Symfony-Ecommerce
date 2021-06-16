<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserRegisterFormType;
use App\Form\Type\LoginFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/logout', name: 'logout')]
    public function logout()
    {
    }

    #[Route('/register', name: 'register')]
    public function register()
    {
        $user = new User();
        $form = $this->createForm(UserRegisterFormType::class, $user);

        return $this->render('security/register.html.twig', [
            'formView' => $form->createView(),
        ]);
    }
}
