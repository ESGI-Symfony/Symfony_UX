<?php

namespace App\Controller\Front;

use App\Entity\Rental;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rental/{id}', name: 'app_rental_', requirements: ['id' => '\d+'])]
class BookController extends AbstractController
{
    #[Route('/overview', name: 'overview')]
    public function index(Rental $rental, Request $request): Response
    {
        $search = $request->query->get('search', '');

        return $this->render('front/rental/index.html.twig', [
            'rental' => $rental,
            'search' => $search,
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
        ]);
    }

    #[Route('/reviews', name: '_reviews')]
    public function reviews(Request $request, Rental $rental): Response
    {
        $search = $request->query->get('search', '');

        return $this->render('front/rental/reviews.html.twig', [
            'rental' => $rental,
            'search' => $search,
        ]);
    }
}
