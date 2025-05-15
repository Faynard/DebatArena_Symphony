<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\DebateRepository;
use App\Repository\UserRepository;
use App\Repository\VotesRepository;
use App\Form\UserEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[IsGranted('ROLE_USER')]
#[Route('/user')]
final class UserController extends AbstractController
{

    #[IsGranted('ROLE_USER', message: 'Tu n\'es pas autorisé à accéder à cette page.')]
    #[Route(name: 'app_user_show', methods: ['GET','POST'])]
    public function show( DebateRepository $debateRepository, UserRepository $userRepository ,VotesRepository $voteRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $recentDebates = $debateRepository->findRecentDebatesByUser($user);

        foreach ($recentDebates as $debat) {
            $statDebate[$debat->getId()] = $debateRepository->calculateStatsForDebat($debat->getId());
        }

        $form = $this->createForm(UserEditType::class, $user);

        $ranking = $userRepository->getUserRankingByVotes($user);
        $nbVotes = $voteRepository->countVotesByUser($user);

        $stats = [
            'total_votes' => $nbVotes,
            'debates_won' => 0, // À compléter si tu veux
            'rank_month' => $ranking['rank_month'],
            'rank_global' => $ranking['rank_global'],
        ];

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'recentDebates' => $recentDebates,
            'statDebates' => $statDebate,
            'stats' => $stats,
        ]);
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET','POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        echo $user->getId();
        // Vérifie que l'utilisateur connecté modifie bien son propre profil
        if ($this->getUser()->getId() !== $user->getId()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $hashedPassword = $hasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour.');
            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
        }

        // Retourne une 400 car seule une requête POST est attendue
        return new Response('Formulaire invalide.', Response::HTTP_BAD_REQUEST);
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
