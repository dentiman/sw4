<?php

namespace Dentiman\ScheduleBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ScheduleRepository extends EntityRepository
{

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getEnabledSchedulesQb()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->select('s.command, s.arguments, s.definition')
            ->from('ScheduleBundle:Schedule', 's')
        ;


        return $queryBuilder;
    }

}
