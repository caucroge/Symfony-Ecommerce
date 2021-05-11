<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomePageController extends AbstractController
{
    #[Route("/", name: "homepageIndex")]
    public function index()
    {
        return $this->render('homepage/index.html.twig');
    }
}