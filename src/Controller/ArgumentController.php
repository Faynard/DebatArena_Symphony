<?php

namespace App\Controller;

use App\Entity\Argument;
use App\Entity\Camp;
use App\Entity\Debate;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\Translation\t;

#[Route('/argument')]
final class ArgumentController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/vote', name: 'app_argument_vote', methods: ['POST'])]
    public function vote(EntityManagerInterface $entityManager): Response
    {
        $argumentId = $_POST['argumentId'];

        $argumentRepository = $entityManager->getRepository(Argument::class);
        $argument = $argumentRepository->findOneBy(['id' => $argumentId]);

        $voteRepository = $entityManager->getRepository(Votes::class);
        $numberVotes = count($voteRepository->findByUserAndDebate($this->getUser(), $argument->getCamp()->getDebate()));

        if($this->getUser() === $argument->getUser()){
            $this->addFlash('danger', t('argument.vote.unsuccess'));
        } else {
            if ($numberVotes < 5) {
                $vote = new Votes();
                $vote->setUser($this->getUser());
                $vote->setArgument($argument);

                $entityManager->persist($vote);
                $entityManager->flush();
                $this->addFlash('success', t('argument.vote.success'));
            } else {
                $this->addFlash('danger', t('argument.vote.unsuccess'));
            }
        }


        return $this->redirectToRoute('app_debate_show', [
            'id' => $argument->getCamp()->getDebate()->getId(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
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

        $this->addFlash('success', t('argument.unvote.success'));

        return $this->redirectToRoute('app_debate_show', [
            'id' => $argument->getCamp()->getDebate()->getId(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/report', name: 'app_argument_report', methods: ['POST'])]
    public function report(EntityManagerInterface $entityManager): Response
    {
        $argumentId = $_POST['argumentId'];

        $argumentRepository = $entityManager->getRepository(Argument::class);
        $argument = $argumentRepository->findOneBy(['id' => $argumentId]);

        $reportRepository = $entityManager->getRepository(Report::class);
        $report = $reportRepository->findOneBy(['user' => $this->getUser(), 'argument' => $argument]);

        if(!$report) {
            $report = new Report();
            $report->setArgument($argument);
            $report->setUser($this->getUser());

            $entityManager->persist($report);
            $entityManager->flush();
            $this->addFlash('success', t('argument.report.success'));
        } else {
            $this->addFlash('danger', t('argument.vote.unsuccess'));
        }


        return $this->redirectToRoute('app_debate_show', [
            'id' => $argument->getCamp()->getDebate()->getId(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'app_argument_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $debateId = $request->query->get('debate');
        $mainArgumentId = $request->query->get('mainArgumentId');
        $mainArgument = false;

        $debateRepository = $entityManager->getRepository(Debate::class);
        $argumentRepository = $entityManager->getRepository(Argument::class);

        if (!$debateId) {
            $this->addFlash('success', t('argument.post.unsuccess'));
            return $this->redirectToRoute('app_debate_list');
        }
        $debate = $debateRepository->find($debateId);
        if (!$debate) {
            $this->addFlash('success', t('argument.post.unsuccess'));
            return $this->redirectToRoute('app_debate_list');
        }

        $argument = new Argument();
        $argument->setUser($this->getUser());

        if ($mainArgumentId) {
            $mainArgument = $argumentRepository->find($mainArgumentId);
        }
        if ($mainArgument) {
            if ($mainArgument->getCamp()->getDebate() !== $debate) {
                $this->addFlash('success', t('argument.post.unsuccess'));
                return $this->redirectToRoute('app_debate_list');
            }
            $argument->setMainArgument($mainArgument);
        }

        $form = $this->createForm(ArgumentType::class, $argument, [
            'debate' => $debate,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($argument);
            $entityManager->flush();

            $this->addFlash('success', t('argument.post.success'));

            return $this->redirectToRoute('app_debate_show', [
                'id' => $argument->getCamp()->getDebate()->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('argument/form.html.twig', [
            'argument' => $argument,
            'debate' => $debate,
            'mainArgument' => $mainArgument,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edit', name: 'app_argument_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Argument $argument, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArgumentType::class, $argument, [
            'debate' => $argument->getCamp()->getDebate(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_debate_show', [
                'id' => $argument->getCamp()->getDebate()->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('argument/form.html.twig', [
            'argument' => $argument,
            'debate' => $argument->getCamp()->getDebate(),
            'mainArgument' => $argument->getMainArgument(),
            'form' => $form,
        ]);
    }
}
