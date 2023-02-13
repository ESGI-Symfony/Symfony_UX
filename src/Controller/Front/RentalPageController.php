<?php

namespace App\Controller\Front;

use App\Entity\Rental;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function reviews(Rental $rental): Response
    {
        return $this->render('front/rental/reviews.html.twig', [
            'rental' => $rental,
            'selectedTab' => 'reviews',
        ]);
    }

    #[Route('/book', name: 'book')]
    public function book(Rental $rental): Response
    {
        return $this->render('front/rental/book.html.twig', [
            'rental' => $rental,
            'selectedTab' => 'reviews',
        ]);
    }
}
