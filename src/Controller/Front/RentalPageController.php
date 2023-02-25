<?php

namespace App\Controller\Front;

use App\Entity\Rental;
use App\Entity\Report;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\Front\BookReservationFormType;
use App\Form\Front\ReportFormType;
use App\Form\Front\ReviewReservationFormType;
use App\Repository\ReportRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/rental/{id}', name: 'rental_', requirements: ['id' => '\d+'])]
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
    public function reviews(
        Rental $rental,
        Request $request,
        ReservationRepository $reservationRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $reservations = $reservationRepository->findReservationsWithReviews($rental);

        $firstUserReservationWithoutReview = $form = null;
        /** @var User $user */
        $user = $this->getUser();
        if ($user) {
            if ($firstUserReservationWithoutReview = $reservationRepository->findUserReservationToReviewForRental(
                $user,
                $rental
            )) {
                $form = $this->createForm(ReviewReservationFormType::class, $firstUserReservationWithoutReview);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($firstUserReservationWithoutReview);
                    $entityManager->flush();

                    return $this->redirectToRoute('front_rental_reviews', [
                        'id' => $rental->getId(),
                    ]);
                }
            }
        }

        return $this->render('front/rental/reviews.html.twig', [
            'rental' => $rental,
            'selectedTab' => 'reviews',
            'reservations' => $reservations,
            'firstUserReservationWithoutReview' => $firstUserReservationWithoutReview,
            'form' => $form,
        ]);
    }

    #[Route('/book', name: 'book')]
    public function book(Rental $rental, Request $request, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository, TranslatorInterface $translator): Response
    {
        $user = $this->getUser();
        if (is_null($user)) {
            // should be triggered by security.yaml
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

    #[Route('/report', name: 'report')]
    public function report(Rental $rental, Request $request, EntityManagerInterface $entityManager, ReportRepository $reportRepository): Response
    {
        $user = $this->getUser();
        if (is_null($user)) {
            // should be triggered by security.yaml
            return $this->redirectToRoute('app_login');
        }

        // check if user already reported this rental
        $existingReport = $reportRepository->findOneBy([
            'author' => $user,
            'rental' => $rental,
        ]);
        if (!is_null($existingReport)) {
            return $this->render('front/rental/report_success.html.twig', [
                'rental' => $rental,
                'selectedTab' => 'report',
            ]);
        }

        $report = new Report();

        $form = $this->createForm(ReportFormType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $report->setRental($rental);
            $report->setAuthor($this->getUser());

            $entityManager->persist($report);
            $entityManager->flush();

            return $this->render('front/rental/report_success.html.twig', [
                'rental' => $rental,
                'selectedTab' => 'report',
            ]);
        }

        return $this->render('front/rental/report.html.twig', [
            'form' => $form->createView(),
            'rental' => $rental,
            'selectedTab' => 'report',
        ]);
    }
}
