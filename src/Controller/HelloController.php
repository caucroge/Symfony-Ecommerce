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
            'age' => 17,
            'prenoms' => [
                'Roger',
                'Mathieu',
                'Marina'
            ]
        ]);
        
        return new Response($html);
    }
}