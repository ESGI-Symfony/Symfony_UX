<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\Front\UserProfileFormType;
use App\Repository\RentalRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserLessorRequestRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// this route is protected by the firewall
#[Route(path: '/profile', name: 'app_profile_')]
class UserController extends AbstractController
{

    #[Route(path: '/', name: 'index')]
    public function account(RentalRepository $rentalRepository, ReservationRepository $reservationRepository, UserLessorRequestRepository $userLessorRequestRepository): Response
    {

        $user = $this->getUser();

        return $this->render('front/profile/account.html.twig',  [
            'user' => $user,
            'rental' => $rentalRepository->getRentalWithMaxReservations(['owner' => $user]),
            'reservation' => $reservationRepository->getLastReservation(['buyer' => $user]),
            'notification' => $userLessorRequestRepository->getLastRequest(['lessor' => $user]),
        ]);
    }

    #[Route(path: '/edit', name: 'edit')]
    public function edit(Request $request, UserRepository $userRepository, UserLessorRequestRepository $userLessorRequestRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $shouldShowLessorFields = in_array('ROLE_LESSOR', $user->getRoles()) || $userLessorRequestRepository->findOneBy(['lessor' => $user, 'status' => 'pending']);
        $form = $this->createForm(UserProfileFormType::class, $user, [
            'validation_groups' => $shouldShowLessorFields ? ['Default', 'lessor'] : ['Default'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('front_app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/profile/edit_profile.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);

    }

}
