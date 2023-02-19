<?php

namespace App\Controller\Front;

use App\Entity\Rental;
use App\Entity\Reservation;
use App\Form\BookReservationFormType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function book(Rental $rental, Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = (new Reservation)
            ->setRental($rental)
            ->setBuyer($this->getUser());

        $form = $this->createForm(BookReservationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->render('front/rental/payment.html.twig', [
                'rental' => $rental,
                'selectedTab' => 'payment',
            ]);
        }

        return $this->render('front/rental/book.html.twig', [
            'form' => $form->createView(),
            'rental' => $rental,
            'selectedTab' => 'book',
        ]);
    }
}
