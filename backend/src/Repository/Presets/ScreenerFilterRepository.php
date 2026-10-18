<?php

namespace App\Repository\Presets;

use App\Entity\Presets\ScreenerFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ScreenerFilter|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScreenerFilter|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScreenerFilter[]    findAll()
 * @method ScreenerFilter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScreenerFilterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScreenerFilter::class);
    }

    // /**
    //  * @return ScreenerFilter[] Returns an array of ScreenerFilter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ScreenerFilter
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
