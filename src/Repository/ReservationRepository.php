<?php

namespace App\Repository;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function save(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getClashingReservationCount(Reservation $reservation): int
    {
        return $this->createQueryBuilder("r")
            ->where("r.date_begin <= :begin")
            ->andWhere("r.date_end >= :end")
            ->setParameter("begin", $reservation->getDateBegin())
            ->setParameter("end", $reservation->getDateEnd())
            ->select("count(r.id)")
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getReservationGroupByMonthYear(User $user, string $type): ArrayCollection
    {
        $query = $this->createQueryBuilder('r')
            ->select('r', 'to_char(r.date_begin, \'YYYY-MM\') AS year_month')
            ->where('r.buyer = :buyer')
            ->setParameter('buyer', $user);

        if ($type == 'passed') {
            $query->andWhere('r.date_begin < CURRENT_TIMESTAMP()');
        } elseif ($type == 'future') {
            $query->andWhere('r.date_begin >= CURRENT_TIMESTAMP()');
        }

        $reservations = $query
            ->orderBy('r.date_begin', 'ASC')
            ->getQuery()
            ->getResult();

        $reservationsByYearMonth = new ArrayCollection();
        foreach ($reservations as $reservation) {
            $yearMonth = $reservation['year_month'];
            if (!$reservationsByYearMonth->containsKey($yearMonth)) {
                $reservationsByYearMonth->set($yearMonth, new ArrayCollection());
            }
            $reservationsByYearMonth->get($yearMonth)->add($reservation[0]);
        }

        return $reservationsByYearMonth;
    }

    public function getLastReservation($filters): Reservation
    {
        $currentDate = new \DateTime();
        $query = $this->createQueryBuilder('r')
            ->addSelect('(DATE_DIFF(r.date_begin, :currentDate)) AS HIDDEN dateDiff')
            ->setParameter('currentDate', $currentDate->format('Y-m-d'))
            ->orderBy('dateDiff', 'ASC')
            ->setMaxResults(1);

        foreach ($filters as $key => $value) {
            $query->andWhere("r.$key = :$key")
                ->setParameter($key, $value);
        }

        $results = $query->getQuery()->getResult();

        return $results[0];
    }

    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
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

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
