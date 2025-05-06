<?php

namespace App\Controller;

use App\Entity\Argument;
use App\Entity\Camp;
use App\Entity\User;
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

    #[Route('/new', name: 'app_argument_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ID_DEBATE = 1; /* TODO Ne pas mettre l'id en dur */
        $USER = $entityManager->getRepository(User::class)->find(1); /* TODO Ne pas mettre l'id en dur */

        $argument = new Argument();
        $argument->setUser($USER); /* TODO Mettre le current user */

        $campRepository = $entityManager->getRepository(Camp::class);
        $form = $this->createForm(ArgumentType::class, $argument, ['camps' => $campRepository->findByDebate($ID_DEBATE)]);
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
