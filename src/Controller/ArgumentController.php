<?php

namespace App\Controller;

use App\Entity\Argument;
use App\Entity\Camp;
use App\Entity\Report;
use App\Entity\User;
use App\Entity\Votes;
use App\Form\ArgumentType;
use App\Repository\ArgumentRepository;
use App\Repository\CampRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/argument')]
final class ArgumentController extends AbstractController
{
    #[Route(name: 'app_argument_index', methods: ['GET'])]
    public function index(ArgumentRepository $argumentRepository): Response
    {
        return $this->render('argument/index.html.twig', [
            'arguments' => $argumentRepository->findAll(),
        ]);
    }

    #[Route('/vote', name: 'app_argument_vote', methods: ['POST'])]
    public function vote(EntityManagerInterface $entityManager): Response
    {
        $argumentId = $_POST['argumentId'];

        $argumentRepository = $entityManager->getRepository(Argument::class);
        $argument = $argumentRepository->findOneBy(['id' => $argumentId]);

        $voteRepository = $entityManager->getRepository(Votes::class);
        $numberVotes = $voteRepository->countByUserAndDebate($this->getUser(), $argument->getCamp()->getDebate()->getId());

        if ($numberVotes < 1) {
            $vote = new Votes();
            $vote->setUser($this->getUser());
            $vote->setArgument($argument);

            $entityManager->persist($vote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_debate_show', [
            'id' => $argument->getCamp()->getDebate()->getId(),
        ]);
    }

    #[Route('/unvote', name: 'app_argument_unvote', methods: ['POST'])]
    public function unvote(EntityManagerInterface $entityManager): Response
    {
        $argumentId = $_POST['argumentId'];

        $argumentRepository = $entityManager->getRepository(Argument::class);
        $argument = $argumentRepository->findOneBy(['id' => $argumentId]);

        $voteRepository = $entityManager->getRepository(Votes::class);
        $vote = $voteRepository->findOneBy(['argument' => $argument, "user" => $this->getUser()]);

        $entityManager->remove($vote);
        $entityManager->flush();

        return $this->redirectToRoute('app_debate_show', [
            'id' => $argument->getCamp()->getDebate()->getId(),
        ]);
    }

    #[Route('/report', name: 'app_argument_report', methods: ['POST'])]
    public function report(EntityManagerInterface $entityManager): Response
    {
        $argumentId = $_POST['argumentId'];

        $argumentRepository = $entityManager->getRepository(Argument::class);
        $argument = $argumentRepository->findOneBy(['id' => $argumentId]);

        $report = new Report();
        $report->setArgument($argument);
        $report->setUser($this->getUser());

        $entityManager->persist($report);
        $entityManager->flush();

        return $this->redirectToRoute('app_debate_index');
    }

    #[Route('/new', name: 'app_argument_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ID_DEBATE = 1; /* TODO Ne pas mettre l'id en dur */
        $USER = $entityManager->getRepository(User::class)->find(1); /* TODO Ne pas mettre l'id en dur */

        $argument = new Argument();
        $argument->setUser($USER); /* TODO Mettre le current user */

        $campRepository = $entityManager->getRepository(Camp::class);
        $form = $this->createForm(ArgumentType::class, $argument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($argument);
            $entityManager->flush();

            return $this->redirectToRoute('app_argument_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('argument/new.html.twig', [
            'argument' => $argument,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_argument_show', methods: ['GET'])]
    public function show(Argument $argument): Response
    {
        return $this->render('argument/show.html.twig', [
            'argument' => $argument,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_argument_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Argument $argument, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArgumentType::class, $argument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_argument_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('argument/edit.html.twig', [
            'argument' => $argument,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_argument_delete', methods: ['POST'])]
    public function delete(Request $request, Argument $argument, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$argument->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($argument);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_argument_index', [], Response::HTTP_SEE_OTHER);
    }
}
