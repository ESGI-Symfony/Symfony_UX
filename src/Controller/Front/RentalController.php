<?php

namespace App\Controller\Front;

use App\Entity\Rental;
use App\Entity\Reservation;
use App\Repository\ReviewRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rental/{id}', name: 'app_rental_', requirements: ['id' => '\d+'])]
class RentalController extends AbstractController
{
    #[Route('/overview', name: '_overview')]
    public function index(Rental $rental, Request $request): Response
    {
        $search = $request->query->get('search', '');

        return $this->render('front/rental/index.html.twig', [
            'rental' => $rental,
            'search' => $search,
            'selectedTab' => 'overview',
        ]);
    }

    #[Route('/access', name: '_access')]
    public function access(Request $request, Rental $rental): Response
    {
        $search = $request->query->get('search', '');

        return $this->render('front/rental/access.html.twig', [
            'rental' => $rental,
            'transports' => $rental->getTransports(),
            'search' => $search,
            'selectedTab' => 'access',
        ]);
    }

    #[Route('/reviews', name: '_reviews')]
    public function reviews(Request $request, Rental $rental): Response
    {
        $search = $request->query->get('search', '');

        return $this->render('front/rental/reviews.html.twig', [
            'rental' => $rental,
            'search' => $search,
            'selectedTab' => 'reviews',
        ]);
    }

    #[Route('/book', name: '_book')]
    public function book(Request $request, Rental $rental): Response
    {
        $search = $request->query->get('search', '');

        return $this->render('front/rental/book.html.twig', [
            'rental' => $rental,
            'search' => $search,
            'selectedTab' => 'reviews',
        ]);
    }
}
