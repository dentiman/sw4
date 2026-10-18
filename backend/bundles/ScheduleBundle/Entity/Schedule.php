<?php

namespace Dentiman\ScheduleBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="schedule_cron_schedule", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="UQ_COMMAND", columns={"command", "args_hash", "definition"})
 * })
 * @ORM\Entity(repositoryClass="Dentiman\ScheduleBundle\Entity\Repository\ScheduleRepository")

 */
class Schedule
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="command", type="string", length=255)
     */
    protected $command;

    /**
     * @var array
     *
     * @ORM\Column(name="args", type = "array")
     */
    protected $arguments;

    /**
     * @var string
     *
     * @ORM\Column(name="args_hash", type="string", length=32)
     */
    protected $argumentsHash;

    /**
     * @var string
     *
     * @ORM\Column(name="definition", type="string", length=100, nullable=true)
     */
    protected $definition;

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     */
    public function setIsEnabled(bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_enabled", type="boolean", nullable=true)
     */
    protected $isEnabled = false;

    /**
     * @var ScheduleLog[] $logs
     * @ORM\OneToMany(targetEntity="Dentiman\ScheduleBundle\Entity\ScheduleLog", mappedBy="schedule")
     */
    protected $logs;

    public function __construct()
    {
        $this->setArguments([]);
        $this->logs = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get command name
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set command name
     *
     * @param  string  $command
     * @return Schedule
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments)
    {
        sort($arguments);

        $this->arguments = $arguments;
        $this->argumentsHash = md5(json_encode($arguments));

        return $this;
    }

    /**
     * Returns cron definition string
     *
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Set cron definition string
     *
     * General format:
     * *    *    *    *    *
     * ┬    ┬    ┬    ┬    ┬
     * │    │    │    │    │
     * │    │    │    │    │
     * │    │    │    │    └───── day of week (0 - 6) (0 to 6 are Sunday to Saturday, or use names)
     * │    │    │    └────────── month (1 - 12)
     * │    │    └─────────────── day of month (1 - 31)
     * │    └──────────────────── hour (0 - 23)
     * └───────────────────────── min (0 - 59)
     *
     *
     * @param  string  $definition New cron definition
     * @return Schedule
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getCommand();
    }

    /**
     * @return string
     */
    public function getArgumentsHash()
    {
        return $this->argumentsHash;
    }

    /**
     * @return ScheduleLog[]
     */
    public function getLogs() : Collection
    {
        return $this->logs;
    }

    /**
     * @param Collection $logs
     */
    public function setLogs(Collection $logs): void
    {
        $this->logs = $logs;
    }

}
