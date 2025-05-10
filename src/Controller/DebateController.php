<?php

namespace App\Controller;

use App\Entity\Debate;
use App\Form\DebateType;
use App\Repository\DebateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('PUBLIC_ACCESS')]
#[Route('/debate')]
final class DebateController extends AbstractController
{
    #[Route(name: 'app_debate_index', methods: ['GET'])]
    public function index(
        DebateRepository $debateRepository,
        Request $request
    ): Response {
        $page = max(1, $request->query->getInt('page', 1));
        $perPage = 3;
        $offset = ($page - 1) * $perPage;

        // Récupération des débats tendance et récents
        $responseTendance = $debateRepository->getAllDebatsSortedByParticipants($perPage, $offset);
        $responseRecents = $debateRepository->getAllDebatsSortedByDate($perPage, $offset);

        $debatsTendance = $responseTendance;
        $debatsRecents = $responseRecents;

        // Statistiques
        $statsTendance = $this->getDebatStats($debateRepository, $debatsTendance);
        $statsRecents = $this->getDebatStats($debateRepository, $debatsRecents);

        // Vérification page suivante
        $nextTendance = $debateRepository->getAllDebatsSortedByParticipants($perPage, $offset + $perPage);
        $nextRecents = $debateRepository->getAllDebatsSortedByDate($perPage, $offset + $perPage);
        $noMoreDebatsNextPage = empty($nextTendance) && empty($nextRecents);

        // Classement utilisateur via DebateRepository
        $userRanking = $debateRepository->getUserRankingByVotes();

        return $this->render('debate/index.html.twig', [
            'debatsRecents' => $debatsRecents,
            'debatsTendance' => $debatsTendance,
            'statsRecents' => $statsRecents,
            'statsTendance' => $statsTendance,
            'page' => $page,
            'noMoreDebatsNextPage' => $noMoreDebatsNextPage,
            'userRanking' => $userRanking
        ]);
    }

    private function getDebatStats(DebateRepository $debateRepository, array $debats): array
    {
        $stats = [];
        foreach ($debats as $debat) {
            $stats[$debat->getId()] = $debateRepository->calculateStatsForDebat($debat->getId());
        }
        return $stats;
    }

    #[IsGranted('ROLE_USER', message: 'Tu n\'es pas autorisé à accéder à cette page.')]
    #[Route('/new', name: 'app_debate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $debate = new Debate();

        // Attribuer automatiquement les champs cachés
        $debate->setIsValid(false); // ou true selon la logique métier
        $debate->setUserCreated($this->getUser());
        $debate->setCreationDate(new \DateTimeImmutable());

        $form = $this->createForm(DebateType::class, $debate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($debate);
            $entityManager->flush();

            $this->addFlash('success', 'Le débat a été créé avec succès.');

            return $this->redirectToRoute('app_debate_index');
        }

        return $this->render('debate/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_debate_show', methods: ['GET'])]
    public function show(Debate $debate): Response
    {
        return $this->render('debate/show.html.twig', [
            'debate' => $debate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_debate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Debate $debate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DebateType::class, $debate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_debate_index');
        }

        return $this->render('debate/edit.html.twig', [
            'debate' => $debate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_debate_delete', methods: ['POST'])]
    public function delete(Request $request, Debate $debate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$debate->getId(), $request->request->get('_token'))) {
            $entityManager->remove($debate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_debate_index');
    }
}
