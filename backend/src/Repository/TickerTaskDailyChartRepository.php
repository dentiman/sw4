<?php

namespace App\Repository;

use App\Entity\FeedImport\Charts\TickerTaskDailyCharts;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class TickerTaskDailyChartRepository extends TickerTaskRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TickerTaskDailyCharts::class);
    }

}
