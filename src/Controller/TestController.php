<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController
{
    public function index()
    {
        var_dump("Premier controleur !");
        die();
    }

    public function test(Request $request)
    {
        // $age = 0;
        // if( !empty( $_GET['age'] ) )
        // {
        //     $age= $_GET['age'];
        // }

        dump($request);

        $age = $request->query->get('age', 0);
        return new Response("Vous avez $age ans");
    }
}