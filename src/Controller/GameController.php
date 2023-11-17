<?php

namespace App\Controller;

use App\Entity\Party;
use App\Entity\User;
use App\Enum\Choices;
use App\Form\PartyType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class GameController extends AbstractController
{

    #[IsGranted('ROLE_USER')]
    #[Route('/game', name: 'app_game')]
    public function index(Request $request, EntityManagerInterface $em, Security $security, LoggerInterface $logger): Response
    {

        $party = new Party();
        $form = $this->createForm(PartyType::class, $party);
        $form->handleRequest($request);

        $choices = Choices::cases();

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            $randomChoice = Choices::cases()[array_rand(Choices::cases())];

            $advRepo = $em->getRepository(User::class);
            $adv = $advRepo->find(9);

            $partySubmit = $form->getData();
            $partySubmit->setPlayer1($user);
            $partySubmit->setPlayer2($adv);
            $partySubmit->setChoice2($randomChoice);

            $result = $partySubmit->getChoice1()->isWin($randomChoice);
            $winner = null;
            if ($result === true) {
                $winner = $partySubmit->getPlayer1();
            }

            if ($result === false) {
                $winner = $partySubmit->getPlayer2();
            }
            $partySubmit->setWhoWin($winner);


            $em->persist($partySubmit);
            $em->flush();
            $this->addFlash('success', 'Party bien ajoutée');
        }

        $logger->info('Nouvelle partie', ['Winner' => $winner ? $winner->getUsername() : 'Égalité']);

        return $this->render('game/index.html.twig', [
            'choices' => $choices,
            'form' => $form->createView(),
        ]);
    }


}
