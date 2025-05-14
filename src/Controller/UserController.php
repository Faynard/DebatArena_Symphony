<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\VotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/user')]
final class UserController extends AbstractController
{
    #[IsGranted('ROLE_USER', message: 'Tu n\'es pas autorisé à accéder à cette page.')]
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_USER', message: 'Tu n\'es pas autorisé à accéder à cette page.')]
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, UserRepository $userRepository ,VotesRepository $voteRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $debates = $user->getDebates();

        $ranking = $userRepository->getUserRankingByVotes($user);
        $nbVotes = $voteRepository->countVotesByUser($user);

        $stats = [
            'total_votes' => $nbVotes,
            'debates_won' => 0, // À compléter si tu veux
            'rank_month' => $ranking['rank_month'],
            'rank_global' => $ranking['rank_global'],
        ];

        return $this->render('user/_show.html.twig', [
            'user' => $user,
            'debates' => $debates,
            'stats' => $stats,
        ]);
    }


    #[IsGranted('ROLE_USER', message: 'Tu n\'es pas autorisé à accéder à cette page.')]
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_USER', message: 'Tu n\'es pas autorisé à accéder à cette page.')]
    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
