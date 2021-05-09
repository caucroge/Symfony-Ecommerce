<?php

namespace App\Controller;

class TestController
{
    public function index()
    {
        var_dump("Premier controleur !");
        die();
    }

    public function test()
    {
        dd("Deuxieme route : test ! ");
    }
}