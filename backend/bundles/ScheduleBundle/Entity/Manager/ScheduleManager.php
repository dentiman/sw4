<?php

namespace Dentiman\ScheduleBundle\Entity\Manager;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Dentiman\ScheduleBundle\Entity\Schedule;

class ScheduleManager
{
    /** @var ManagerRegistry */
    protected $registry;

    /** @var string */
    protected $scheduleClass;


    /**
     * @param ManagerRegistry $registry
     * @param string $scheduleClass
     */
    public function __construct(
        ManagerRegistry $registry,
        $scheduleClass
    ) {
        $this->registry = $registry;
        $this->scheduleClass = $scheduleClass;
    }

    /**
     * @param string $command
     * @param array $arguments
     * @param string $definition
     * @return bool
     */
    public function hasSchedule($command, array $arguments, $definition)
    {
        $schedules = $this->getRepository()->findBy(['command' => $command, 'definition' => $definition]);


        return count($schedules) > 0;
    }

    /**
     * @param string $command
     * @param array $arguments
     * @param string $definition
     * @return Schedule
     */
    public function createSchedule($command, array $arguments, $definition)
    {
        if (!$command || !$definition) {
            throw new \InvalidArgumentException('Parameters "command" and "definition" must be specified.');
        }

        if ($this->hasSchedule($command, $arguments, $definition)) {
            throw new \LogicException('Schedule with same parameters already exists.');
        }

        $schedule = new Schedule();
        $schedule
            ->setCommand($command)
            ->setArguments($arguments)
            ->setDefinition($definition);

        return $schedule;
    }

    /**
     * @param string $command
     * @param string[] $arguments
     *
     * @return Schedule[]
     */
    public function getSchedulesByCommandAndArguments($command, array $arguments)
    {
        return  $this->getRepository()->findBy(['command' => $command]);

    }

    /**
     * @return ObjectManager
     */
    protected function getEntityManager()
    {
        return $this->registry->getManagerForClass($this->scheduleClass);
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->scheduleClass);
    }
}
