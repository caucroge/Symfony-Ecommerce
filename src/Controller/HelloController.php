<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    #[Route("/hello/{prenom?le monde !}", name: "hello")]
    public function hello(LoggerInterface $logger, $prenom)
    {
        $logger->error("Mon message de log");
        return new Response("hello $prenom");
    }
}