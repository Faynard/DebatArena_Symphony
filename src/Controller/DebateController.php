<?php

namespace App\Controller;

use App\Entity\Debate;
use App\Form\DebateType;
use App\Repository\DebateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/debate')]
final class DebateController extends AbstractController
{
    #[Route(name: 'app_debate_index', methods: ['GET'])]
    public function index(DebateRepository $debateRepository): Response
    {
        return $this->render('debate/index.html.twig', [
            'debates' => $debateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_debate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $debate = new Debate();
        $form = $this->createForm(DebateType::class, $debate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($debate);
            $entityManager->flush();

            return $this->redirectToRoute('app_debate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('debate/new.html.twig', [
            'debate' => $debate,
            'form' => $form,
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

            return $this->redirectToRoute('app_debate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('debate/edit.html.twig', [
            'debate' => $debate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_debate_delete', methods: ['POST'])]
    public function delete(Request $request, Debate $debate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$debate->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($debate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_debate_index', [], Response::HTTP_SEE_OTHER);
    }
}
