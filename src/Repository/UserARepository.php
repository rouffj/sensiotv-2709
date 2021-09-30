<?php

namespace App\Repository;

use App\Entity\UserA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserA|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserA|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserA[]    findAll()
 * @method UserA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserA::class);
    }

    // /**
    //  * @return UserA[] Returns an array of UserA objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserA
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
