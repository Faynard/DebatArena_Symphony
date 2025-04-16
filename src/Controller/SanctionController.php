<?php

namespace App\Controller;

use App\Entity\Sanction;
use App\Form\SanctionType;
use App\Repository\SanctionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sanction')]
final class SanctionController extends AbstractController
{
    #[Route(name: 'app_sanction_index', methods: ['GET'])]
    public function index(SanctionRepository $sanctionRepository): Response
    {
        return $this->render('sanction/index.html.twig', [
            'sanctions' => $sanctionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sanction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sanction = new Sanction();
        $form = $this->createForm(SanctionType::class, $sanction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sanction);
            $entityManager->flush();

            return $this->redirectToRoute('app_sanction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sanction/new.html.twig', [
            'sanction' => $sanction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sanction_show', methods: ['GET'])]
    public function show(Sanction $sanction): Response
    {
        return $this->render('sanction/show.html.twig', [
            'sanction' => $sanction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sanction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sanction $sanction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SanctionType::class, $sanction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sanction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sanction/edit.html.twig', [
            'sanction' => $sanction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sanction_delete', methods: ['POST'])]
    public function delete(Request $request, Sanction $sanction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sanction->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($sanction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sanction_index', [], Response::HTTP_SEE_OTHER);
    }
}
