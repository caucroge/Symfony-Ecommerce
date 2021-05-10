<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    protected $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    #[Route("/hello/{prenom?le monde !}", name: "hello")]
    public function hello(LoggerInterface $logger, $prenom)
    {
        $tva = $this->calculator->calcul(100);
        dump($tva);

        $logger->error("Mon message de log");
        return new Response("hello $prenom");
    }
}