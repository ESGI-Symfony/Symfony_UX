<?php

namespace App\Controller\Front;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// this route is protected by the firewall
#[Route(path: '/profile/bookings', name: 'app_profile_bookings_')]
class BookingsController extends AbstractController
{
    
    #[Route(path: '/passed', name: 'passed')]
    public function bookingsPassed(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();

        return $this->render('front/profile/tenant/bookings.html.twig', [
            'user' => $user,
            'passedReservations' => true,
            'reservations' => $reservationRepository->getPassedReservation($user),
        ]);
    }

    #[Route(path: '/ongoing', name: 'on_going')]
    public function bookingsOnGoing(ReservationRepository $reservationRepository): Response
    {
        $user = $this->getUser();

        return $this->render('front/profile/tenant/bookings.html.twig', [
            'user' => $user,
            'passedReservations' => false,
            'reservations' => $reservationRepository->getOnGoigReservation($user),
        ]);
    }

}
