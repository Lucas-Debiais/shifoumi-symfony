<?php

namespace App\Controller;

use App\Entity\Party;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'app_admin')]
    public function index(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();
        $parties = $em->getRepository(Party::class)->findAll();

        $countNull = 0;

        foreach ($parties as $partie) {
            if ($partie->getWhoWin() === null) {
                $countNull += 1;
            }
        }

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'parties' => $parties,
            'countNull' => $countNull,
        ]);
    }

    #[Route('/admin/user', name: 'app_admin_user')]
    public function user(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/user.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/user/{id}/ban', name: 'app_admin_ban')]
    public function banUser(User $user, EntityManagerInterface $em): Response
    {
        $user->setIsBanned(true);

        $em->flush();

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/user/{id}/unban', name: 'app_admin_unban')]
    public function unbanUser(User $user, EntityManagerInterface $em): Response
    {
        $user->setIsBanned(false);

        $em->flush();

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/user/{id}/delete', name: 'app_admin_delete')]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_admin');
    }
}
