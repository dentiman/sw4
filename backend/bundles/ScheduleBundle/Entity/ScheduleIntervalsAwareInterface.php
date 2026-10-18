<?php

namespace Dentiman\ScheduleBundle\Entity;

use Doctrine\Common\Collections\Collection;

interface ScheduleIntervalsAwareInterface
{
    /**
     * @return Collection|ScheduleIntervalInterface[]
     */
    public function getSchedules();
}
