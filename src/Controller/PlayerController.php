<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class PlayerController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/profile', name: 'app_player')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('player/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/api/players', name: 'api_players', methods: ['GET'])]
    public function getPlayers(SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $parties = $em->getRepository(User::class)->findAll();
        $data = $serializer->normalize($parties, null, ['groups' => 'group']);

        return new JsonResponse($data);
    }

    #[Route('/api/players/{id}', name: 'api_player', methods: ['GET'])]
    public function getPlayer(SerializerInterface $serializer, User $party): JsonResponse
    {
        $data = $serializer->normalize($party, null, ['groups' => 'group']);

        return new JsonResponse($data);
    }
}
