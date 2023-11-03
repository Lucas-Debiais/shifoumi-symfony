<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScorePanelController extends AbstractController
{
    #[Route('/tableau-des-scores', name: 'app_score_panel')]
    public function index(UserRepository $userRepo): Response
    {
        $users = $userRepo->findAll();

        return $this->render('score_panel/index.html.twig', [
            'controller_name' => 'ScorePanelController',
            'users' => $users
        ]);
    }
}
