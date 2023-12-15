<?php

namespace App\Controller;

use App\Entity\Party;
use App\Entity\User;
use App\Enum\Choices;
use App\Form\PartyType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class GameController extends AbstractController
{

    #[IsGranted('ROLE_USER')]
    #[Route('/game', name: 'app_game')]
    #[Route(path: '/{_locale}/game', requirements: ['_locale' => 'en|fr'], defaults: ['_locale' => 'en'])]
    public function index(Request $request, EntityManagerInterface $em, Security $security, LoggerInterface $logger, MailerInterface $mailer): Response
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

            $this->sendEmail($mailer, $winner, $user);


            $em->persist($partySubmit);
            $em->flush();

            $this->addFlash('success',
                'Vous avez : ' . ($winner === $partySubmit->getPlayer1() ? 'Gagné' : ($winner === $partySubmit->getPlayer2() ? 'Perdu' : 'Fait match nul'))
            );

            $logger->info('Nouvelle partie', ['Winner' => $winner ? $winner->getUsername() : 'Égalité']);
        }


        return $this->render('game/index.html.twig', [
            'choices' => $choices,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/api/games', name: 'api_games', methods: ['GET'])]
    public function getGames(SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $games = $em->getRepository(Party::class)->findAll();
        $data = $serializer->normalize($games, null, ['groups' => 'group']);

        return new JsonResponse($data);
    }

    #[Route('/api/games/{id}', name: 'api_game', methods: ['GET'])]
    public function getGame(SerializerInterface $serializer, Party $party): JsonResponse
    {
        $data = $serializer->normalize($party, null, ['groups' => 'group']);

        return new JsonResponse($data);
    }

    private function sendEmail(MailerInterface $mailer, User | null $winner, User $user): void
    {
        if ($winner === $user) {
            $email = (new Email())
                ->from('shifoumi@game.com')
                ->to(new Address($user->getEmail(), $user->getUsername()))
                ->subject('Victoire !')
                ->text('Vous avez gagné !');
        } else if ($winner) {
            $email = (new Email())
                ->from('shifoumi@game.com')
                ->to(new Address($user->getEmail(), $user->getUsername()))
                ->subject('Défaite...')
                ->text('Vous avez perdu...');
        } else {
            $email = (new Email())
                ->from('shifoumi@game.com')
                ->to(new Address($user->getEmail(), $user->getUsername()))
                ->subject('Match nul')
                ->text('Une prochaine fois peut-être :)');
        }

        $mailer->send($email);
    }
}
