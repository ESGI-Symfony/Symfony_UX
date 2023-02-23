<?php

namespace App\Repository;

use App\Entity\Reservation;
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

    private function getReservationGroupByMonthYear($sql, $userId): ArrayCollection {

        $conn = $this->getEntityManager()->getConnection();

        $stmt = $conn->prepare($sql);
        $reservations = $stmt->executeQuery(['userId' => $userId])->fetchAllAssociative();

        $reservationRepository = $this->getEntityManager()->getRepository(Reservation::class);
        $entities = [];
        foreach ($reservations as $reservation) {
            $id = $reservation['id'];
            $entities[] = $reservationRepository->find($id);
        }

        $reservationsByYearMonth = new ArrayCollection();
        foreach ($entities as $reservation) {
            $yearMonth = $reservation->getDateBegin()->format('M Y');
            if (!isset($reservationsByYearMonth[$yearMonth])) {
                $reservationsByYearMonth[$yearMonth] = new ArrayCollection();
            }
            $reservationsByYearMonth[$yearMonth][] = $reservation;
        }

        foreach ($reservationsByYearMonth as &$reservations) {
            $reservations = $reservations->toArray();
            usort($reservations, function($a, $b) {
                return $a->getDateBegin() <=> $b->getDateBegin();
            });
        }
        unset($reservations);

        $criteria = Criteria::create()->orderBy(['year_month' => 'DESC']);
        $reservationsByYearMonth = $reservationsByYearMonth->matching($criteria);

        return $reservationsByYearMonth;

    }

    public function getPassedReservation($user) : ArrayCollection {

        $userId = $user->getId();

        $sql = "SELECT *, to_char(date_begin, 'YYYY-MM') AS year_month FROM reservation r WHERE r.buyer_id = :userId AND r.date_begin < NOW()";

        return $this->getReservationGroupByMonthYear($sql, $userId);

    }

    public function getOnGoigReservation($user) : ArrayCollection {

        $userId = $user->getId();

        $sql = "SELECT *, to_char(date_begin, 'YYYY-MM') AS year_month FROM reservation r WHERE r.buyer_id = :userId AND r.date_begin >= NOW()";

        return $this->getReservationGroupByMonthYear($sql, $userId);

    }

    public function getLastReservation($filters): Reservation {

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
