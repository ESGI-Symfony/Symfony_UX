<?php

namespace App\Repository;

use App\Entity\UserLessorRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserLessorRequest>
 *
 * @method UserLessorRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLessorRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLessorRequest[]    findAll()
 * @method UserLessorRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLessorRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserLessorRequest::class);
    }

    public function save(UserLessorRequest $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserLessorRequest $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getLastRequest($filters): ArrayCollection {

        $query = $this->createQueryBuilder('ur')
            ->select('ur.status, ur.refusing_reason')
            ->orderBy('ur.id', 'DESC')
            ->setMaxResults(1);

        foreach ($filters as $key => $value) {
            $query->andWhere("ur.$key = :$key")
                ->setParameter($key, $value);
        }

        $results = $query->getQuery()->getResult();

        $collection = new ArrayCollection();
        foreach ($results as $result) {
            $collection->add($result);
        }

        return $collection;
    }

//    /**
//     * @return UserLessorRequest[] Returns an array of UserLessorRequest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserLessorRequest
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
