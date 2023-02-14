<?php

namespace App\Controller\Back;

use App\Enums\UserLessorRequestStatus;
use App\Repository\RentalRepository;
use App\Repository\UserLessorRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/lessor', name: 'lessor_')]
class RentalController extends AbstractController
{
    #[Route(path: '/pending', name: 'pending', methods: ['GET'])]
    public function index(UserLessorRequestRepository $userLessorRequestRepository): Response
    {
        $pendingRequests = $userLessorRequestRepository->findBy(['status' => UserLessorRequestStatus::Pending]);

        return $this->render('back/lessor/requests/pending.html.twig', [
            'pendingRequests' => $pendingRequests,
        ]);
    }
}
