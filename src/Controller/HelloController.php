<?php

namespace App\Controller;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    protected $twig;
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    #[Route("/hello/{prenom?le monde !}", name: "hello")]
    public function hello($prenom)
    {
        return $this->render("hello.html.twig",['prenom' => $prenom]);
    }

    #[Route("/example", name: "example")]
    public function example()
    {
        return $this->render("example.html.twig",['age' => 33]);
    }

    protected function render(string $path, array $vars = [])
    {
        $html = $this->twig->render($path, $vars);

        return new Response($html);
    }
}