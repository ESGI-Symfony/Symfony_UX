<?php

namespace App\Controller\Front;

use App\Repository\RentalRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    private function filters(RentalRepository $rental_repository, string $search, string $object): QueryBuilder
    {
        $queryBuilder = $rental_repository->createQueryBuilder('r')
            ->where('(r.celestial_object LIKE :search')
            ->orWhere('r.description LIKE :search')
            ->orWhere('r.rent_type LIKE :search)')
            ->setParameter('search', '%' . strtolower($search) . '%');

        if (!empty($object)) {
            $queryBuilder->andWhere('r.celestial_object = :celestial_object')
                ->setParameter('celestial_object', $object);
        }

        return $queryBuilder;
    }

    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function index(Request $request, RentalRepository $rental_repository): Response
    {
        $search = $request->query->get('search', '');
        $page = $request->query->get('page', 0);
        $size = $request->query->get('size', 5);
        $object = $request->query->get('object', '');
        $offset = $page * $size;

        $query = $this->filters($rental_repository, $search, $object);

        $objects = $this->filters($rental_repository, $search, '')
            ->select('r.celestial_object')
            ->groupBy('r.celestial_object')
            ->getQuery()
            ->getSingleColumnResult();

        $count = $this->filters($rental_repository, $search, $object)
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
