<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/cookie", name="cookie")
     */
    public function showCookie(): Response
    {
        return $this->render('home/cookie.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
     /**
     * @Route("/confidentialite", name="confidentialite")
     */
    public function showConfidentialite(): Response
    {
        return $this->render('home/confidentialite.html.twig', [
            'controller_name' => 'HomeController',
        ]);
}
}