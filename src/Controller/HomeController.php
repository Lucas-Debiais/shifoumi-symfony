<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[Route(path: '/{_locale}', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'en'])]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
        ]);
    }
}
