<?php

namespace App\Controller\Front;

use App\Repository\RentalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private function filters(RentalRepository $rental_repository, string $search, string $system) {
        $queryBuilder = $rental_repository->createQueryBuilder('r')
            ->where('(r.celestial_object LIKE :search')
            ->orWhere('r.description LIKE :search)')
            ->setParameter('search', '%' . strtolower($search) . '%');

        if (!empty($system)) {
            $queryBuilder->andWhere('r.system = :system')
                ->setParameter('system', $system);
        }

        return $queryBuilder;
    }

    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function index(Request $request, RentalRepository $rental_repository): Response
    {
        $search = $request->query->get('search', '');
        $page = $request->query->get('page', 0);
        $size = $request->query->get('size', 5);
        $system = $request->query->get('system', '');
        $offset = $page * $size;

        $query = $this->filters($rental_repository, $search, $system);

        $systems = $this->filters($rental_repository, $search, '')
            ->select('r.system')
            ->groupBy('r.system')
            ->getQuery()
            ->getSingleColumnResult();

        $count = $this->filters($rental_repository, $search, $system)
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
            'systems' => $systems,
            'selectedSystem' => $system,
            'count' => $count,
            'hasMorePages' => $hasMorePages,
        ]);
    }
}
