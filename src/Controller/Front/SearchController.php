<?php

namespace App\Controller\Front;

use App\Repository\RentalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function index(Request $request, RentalRepository $rental_repository): Response
    {
        $search = $request->query->get('search', '');
        $page = $request->query->get('page', 0);
        $size = $request->query->get('size', 5);
        $object = $request->query->get('object', '');
        $offset = $page * $size;

        $query = $rental_repository->search($search, $object);

        $objects = $rental_repository->search($search, '')
            ->select('r.celestial_object')
            ->groupBy('r.celestial_object')
            ->getQuery()
            ->getSingleColumnResult();

        $count = $rental_repository->search($search, $object)
            ->select('count(r.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $results = $query
            ->setMaxResults($size)
            ->setFirstResult($offset)
            ->getQuery()
            ->execute();

        $hasMorePages = $offset + $size < $count;

        return $this->render('front/search/index.html.twig', [
            'rentals' => $results,
            'search' => $search,
            'next_page' => $page + 1,
            'previous_page' => $page - 1,
            'objects' => $objects,
            'selectedObject' => $object,
            'count' => $count,
            'hasMorePages' => $hasMorePages,
        ]);
    }
}
