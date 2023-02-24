<?php

namespace App\Controller\Front;

use App\Entity\Rental;
use App\Entity\Reservation;
use App\Form\BookReservationFormType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/rental/{id}', name: 'front_rental_', requirements: ['id' => '\d+'])]
class RentalPageController extends AbstractController
{
    #[Route('/overview', name: 'overview')]
    public function index(Rental $rental): Response
    {
        return $this->render('front/rental/index.html.twig', [
            'rental' => $rental,
            'selectedTab' => 'overview',
        ]);
    }

    #[Route('/access', name: 'access')]
    public function access(Rental $rental): Response
    {
        return $this->render('front/rental/access.html.twig', [
            'rental' => $rental,
            'transports' => $rental->getTransports(),
            'selectedTab' => 'access',
        ]);
    }

    #[Route('/reviews', name: 'reviews')]
    public function reviews(Rental $rental, ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findBy(['rental' => $rental]);

        return $this->render('front/rental/reviews.html.twig', [
            'rental' => $rental,
            'selectedTab' => 'reviews',
            'reservations' => $reservations,
        ]);
    }

    #[Route('/book', name: 'book')]
    public function book(Rental $rental, Request $request, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository, TranslatorInterface $translator): Response
    {
        $user = $this->getUser();
        if (is_null($user)) {
            return $this->redirectToRoute('app_login');
        }

        $reservation = (new Reservation)
            ->setRental($rental)
            ->setBuyer($user);

        $form = $this->createForm(BookReservationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $count = $reservationRepository->getClashingReservationCount($reservation);

            if ($count > 0) {
                $form->addError(new FormError($translator->trans('already_booked')));
            } else {
                $entityManager->persist($reservation);
                $entityManager->flush();

                return $this->redirectToRoute('app_stripe_index', [
                    'id' => $reservation->getId(),
                ]);
            }
        }

        return $this->render('front/rental/book.html.twig', [
            'form' => $form->createView(),
            'rental' => $rental,
            'selectedTab' => 'book',
        ]);
    }
}
