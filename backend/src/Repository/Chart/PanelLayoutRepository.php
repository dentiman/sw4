<?php

namespace App\Repository\Chart;

use App\Entity\Presets\PanelLayout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PanelLayout|null find($id, $lockMode = null, $lockVersion = null)
 * @method PanelLayout|null findOneBy(array $criteria, array $orderBy = null)
 * @method PanelLayout[]    findAll()
 * @method PanelLayout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanelLayoutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PanelLayout::class);
    }

    // /**
    //  * @return PanelLayout[] Returns an array of PanelLayout objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PanelLayout
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
