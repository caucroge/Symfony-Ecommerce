<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    #[Route('/', name: 'index')]
    public function index(Calculator $calculator)
    {
        $tva =$calculator->calcul(50);

        return new Response("Tva : $tva");
    }

    #[Route('/test/{age<\d+>?0}', name:'test', methods:['GET'], host: '127.0.0.1', schemes: ['https'])]
    public function test(Request $request, $age)
    {
        return new Response("Vous avez $age ans");
    }
}