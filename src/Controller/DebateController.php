<?php

namespace App\Controller;

use App\Entity\Argument;
use App\Entity\Debate;
use App\Entity\Votes;
use App\Form\DebateType;
use App\Repository\DebateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/new', name: 'app_debate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $debate = new Debate();

        // Champs automatiques
        $debate->setIsValid(false);
        $debate->setUserCreated($this->getUser());
        $debate->setCreationDate(new \DateTimeImmutable());

        $form = $this->createForm(DebateType::class, $debate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données non mappées
            $camp1Name = $form->get('camp1')->getData();
            $camp2Name = $form->get('camp2')->getData();

            // Créer les deux camps
            $camp1 = new \App\Entity\Camp();
            $camp1->setNameCamp($camp1Name);
            $camp1->setDebate($debate);

            $camp2 = new \App\Entity\Camp();
            $camp2->setNameCamp($camp2Name);
            $camp2->setDebate($debate);

            // Ajouter au débat
            $debate->addCamp($camp1);
            $debate->addCamp($camp2);

            // Persister
            $entityManager->persist($camp1);
            $entityManager->persist($camp2);
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
    public function show(Debate $debate, EntityManagerInterface $entityManager): Response
    {
        $arguments = [];
        $argumentRepository = $entityManager->getRepository(Argument::class);
        foreach ($debate->getCamps() as $camp) {
            $arguments[$camp->getId()] = $argumentRepository->findMainValidatedArgumentByCamp($camp);
        }

        $argumentIdVoted = [];
        $votesRepository = $entityManager->getRepository(Votes::class);
        foreach ($votesRepository->findByUserAndDebate($this->getUser(), $debate) as $vote) {
            $argumentIdVoted[] = $vote->getArgument()->getId();
        }

        return $this->render('debate/show.html.twig', [
            'debate' => $debate,
            'arguments' => $arguments,
            'argumentIdVoted' => $argumentIdVoted,
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
