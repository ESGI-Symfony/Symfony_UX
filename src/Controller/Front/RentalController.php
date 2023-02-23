<?php

namespace App\Controller\Front;

use App\Entity\Rental;
use App\Entity\User;
use App\Entity\UserLessorRequest;
use App\Enums\UserLessorRequestStatus;
use App\Form\Front\RentalFormType;
use App\Form\Front\LessorRequestFormType;
use App\Repository\RentalRepository;
use App\Repository\UserLessorRequestRepository;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

// this route is protected by the firewall
#[Route(path: '/profile/rentals', name: 'app_profile_rentals_')]
class RentalController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function rentals(RentalRepository $rentalRepository): Response
    {
        if(!$this->isGranted(UserVoter::RENTALS, $this->getUser())) {
            if ($this->isGranted(UserVoter::BECOME_LESSOR, $this->getUser())) {
                return $this->redirectToRoute('front_app_profile_rentals_become_lessor');
            }
            return $this->redirectToRoute('app_home');
        }

        /** @var User $user */
        $user = $this->getUser();

        return $this->render('front/profile/lessor/rentals.html.twig', [
            'rentals' => $rentalRepository->getRentalsWithSumRating(['owner' => $user]),
        ]);
    }

    #[Route(path: '/create', name: 'create', methods: ['GET', 'POST'])]
    public function createRental(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::CREATE_RENTALS, $this->getUser());

        /** @var User $user */
        $user = $this->getUser();

        $rental = new Rental;
        $rental->setOwner($user)
            ->setUuid(Uuid::v6());
        $form = $this->createForm(RentalFormType::class, $rental, [
            'validation_groups' => ['create']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rental);
            $entityManager->flush();

             return $this->redirectToRoute('front_app_profile_rentals_index');
        }

        return $this->render('front/profile/lessor/create_rental.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/become-lessor', name: 'become_lessor', methods: ['GET', 'POST'])]
    public function becomeLessor(Request $request, EntityManagerInterface $entityManager, UserLessorRequestRepository $userLessorRequestRepository): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::BECOME_LESSOR, $this->getUser());

        /** @var User $user */
        $user = $this->getUser();

        $hasPendingRequest = !!$userLessorRequestRepository->findOneBy(['lessor' => $user, 'status' => UserLessorRequestStatus::Pending]);
        if($hasPendingRequest) {
            return $this->render('front/profile/lessor/become_lessor_success.html.twig');
        }

        $lessorRequest = new UserLessorRequest;
        $lessorRequest->setStatus(UserLessorRequestStatus::Pending)
            ->setLessor($user);
        $form = $this->createForm(LessorRequestFormType::class, $lessorRequest, [
            'validation_groups' => ['lessor']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lessorRequest);
            $entityManager->flush();

            return $this->render('front/profile/lessor/become_lessor_success.html.twig');
        }

        return $this->render('front/profile/lessor/become_lessor.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
