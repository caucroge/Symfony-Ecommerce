<?php

namespace App\Controller;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    #[Route("/hello/{prenom?le monde !}", name: "hello")]
    public function hello($prenom, Environment $twig)
    {
        $html = $twig->render('hello.html.twig', [
            'prenom' => $prenom,
            'formateur' => [
                'prenom' => 'Roger',
                'nom' => 'Cauchon',
                'age' => 53
            ]
        ]);

        return new Response($html);
    }
}