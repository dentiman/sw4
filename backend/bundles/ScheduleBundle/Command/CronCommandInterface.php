<?php

namespace Dentiman\ScheduleBundle\Command;

interface CronCommandInterface
{
    /**
     * Define default cron schedule definition for a command.
     * Example: "5 * * * *"
     *
     * @see    \Dentiman\ScheduleBundle\Entity\Schedule::setDefinition()
     * @return string
     */
    public function getDefaultDefinition();

}
