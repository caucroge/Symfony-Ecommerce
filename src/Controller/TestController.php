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

    public function test(Request $request, $age)
    {
        // $age = 0;
        // if( !empty( $_GET['age'] ) )
        // {
        //     $age= $_GET['age'];
        // }
        
        // dump($request);
        // $age = $request->attributes->get('age');

        return new Response("Vous avez $age ans");
    }
}