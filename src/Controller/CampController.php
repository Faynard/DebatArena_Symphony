<?php

namespace App\Controller;

use App\Entity\Camp;
use App\Form\CampType;
use App\Repository\CampRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/camp')]
final class CampController extends AbstractController
{
    #[Route(name: 'app_camp_index', methods: ['GET'])]
    public function index(CampRepository $campRepository): Response
    {
        return $this->render('camp/index.html.twig', [
            'camps' => $campRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_camp_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $camp = new Camp();
        $form = $this->createForm(CampType::class, $camp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($camp);
            $entityManager->flush();

            return $this->redirectToRoute('app_camp_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('camp/new.html.twig', [
            'camp' => $camp,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_camp_show', methods: ['GET'])]
    public function show(Camp $camp): Response
    {
        return $this->render('camp/show.html.twig', [
            'camp' => $camp,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_camp_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Camp $camp, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CampType::class, $camp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_camp_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('camp/edit.html.twig', [
            'camp' => $camp,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_camp_delete', methods: ['POST'])]
    public function delete(Request $request, Camp $camp, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$camp->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($camp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_camp_index', [], Response::HTTP_SEE_OTHER);
    }
}
