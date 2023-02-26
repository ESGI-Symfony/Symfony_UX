<?php

namespace App\Controller\Back;

use App\Entity\UserLessorRequest;
use App\Enums\UserLessorRequestStatus;
use App\Form\Back\RefuseLessorRequestFormType;
use App\Repository\UserLessorRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/lessor', name: 'lessor_')]
class LessorController extends AbstractController
{
    #[Route(path: '/pending', name: 'pending', methods: ['GET'])]
    public function index(UserLessorRequestRepository $userLessorRequestRepository): Response
    {
        // thanks to front rental controller, there's only one pending request possible by user
        $pendingRequests = $userLessorRequestRepository->findBy(['status' => UserLessorRequestStatus::Pending]);

        return $this->render('back/lessor/requests/pending.html.twig', [
            'pendingRequests' => $pendingRequests,
        ]);
    }

    #[Route(path: '/{id}/validate', name: 'validate', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function validate(UserLessorRequest $userLessorRequest, EntityManagerInterface $entityManager): Response
    {
        $userLessorRequest->setStatus(UserLessorRequestStatus::Validated);
        $userLessorRequest->getLessor()->addRole('ROLE_LESSOR');

        $entityManager->persist($userLessorRequest);
        $entityManager->flush();
        return $this->redirectToRoute('back_lessor_pending');
    }

    #[Route(path: '/{id}/refuse', name: 'refuse', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function refuse(UserLessorRequest $userLessorRequest, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RefuseLessorRequestFormType::class, $userLessorRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userLessorRequest->setStatus(UserLessorRequestStatus::Rejected);
            $entityManager->persist($userLessorRequest);
            $entityManager->flush();
            return $this->redirectToRoute('back_lessor_pending');
        }

        return $this->render('back/lessor/requests/refuse.html.twig', [
            'form' => $form->createView(),
            'userLessorRequest' => $userLessorRequest,
        ]);
    }

}
