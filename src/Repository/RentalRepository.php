<?php

namespace App\Repository;

use App\Entity\Rental;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rental>
 *
 * @method Rental|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rental|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rental[]    findAll()
 * @method Rental[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rental::class);
    }

    public function save(Rental $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Rental $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getRentalsWithSumRating($filters): ArrayCollection
    {
        $query = $this->createQueryBuilder('r')
            ->leftJoin('r.reservations', 'rev')
            ->orderBy('r.id', 'ASC')
            ->addSelect('AVG(rev.review_mark) as sum_rating');

        foreach ($filters as $key => $value) {
            $query->andWhere("r.$key = :$key")
                ->setParameter($key, $value);
        }

        $results = $query->groupBy('r.id')
            ->getQuery()
            ->getResult();

        $collection = new ArrayCollection();
        foreach ($results as $result) {
            $rental = $result[0];
            $rental->setSumRating($result['sum_rating']);
            $collection->add($rental);
        }
        return $collection;
    }

//    /**
//     * @return Rental[] Returns an array of Rental objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Rental
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
