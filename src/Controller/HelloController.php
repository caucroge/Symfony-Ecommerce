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
            'formateur1' => ['prenom' => 'Roger', 'nom' => 'Cauchon'],
            'formateur2' => ['prenom' => 'Marina', 'nom'=> 'Lequette']
        ]);

        return new Response($html);
    }
}