<?php

namespace App\Repository;

use App\Entity\User;
use App\Model\Feed\TickerTaskInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


abstract class TickerTaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @param int $limit
     * @return TickerTaskInterface[]
     */
    public function findTickersForCurrentTask( int $limit)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.done = :val')
            ->setParameter('val', false)
            ->orderBy('t.updatedAt', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?User
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
