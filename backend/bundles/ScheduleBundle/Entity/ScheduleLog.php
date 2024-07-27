<?php

namespace Dentiman\ScheduleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;



/**
 * ScheduleLog
 *
 * @ORM\Table(name="schedule_log")
 * @ORM\Entity
 */
class ScheduleLog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isSuccess", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    private $isSuccess;

    /**
     * @var string|null
     *
     * @ORM\Column(name="message", type="text", precision=0, scale=0, nullable=true, unique=false)
     */
    private $message;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="startedAt", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $startedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="finishedAt", type="datetime", precision=0, scale=0, nullable=true, unique=false)
     */
    private $finishedAt;

    /**
     * @var \Dentiman\ScheduleBundle\Entity\Schedule
     *
     * @ORM\ManyToOne(targetEntity="Dentiman\ScheduleBundle\Entity\Schedule", inversedBy="logs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="schedule_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $schedule;





    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isSuccess.
     *
     * @param bool|null $isSuccess
     *
     * @return ScheduleLog
     */
    public function setIsSuccess($isSuccess = null)
    {
        $this->isSuccess = $isSuccess;

        return $this;
    }

    /**
     * Get isSuccess.
     *
     * @return bool|null
     */
    public function getIsSuccess()
    {
        return $this->isSuccess;
    }

    /**
     * Set message.
     *
     * @param string|null $message
     *
     * @return ScheduleLog
     */
    public function setMessage($message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set startedAt.
     *
     * @param \DateTime|null $startedAt
     *
     * @return ScheduleLog
     */
    public function setStartedAt($startedAt = null)
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * Get startedAt.
     *
     * @return \DateTime|null
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * Set finishedAt.
     *
     * @param \DateTime|null $finishedAt
     *
     * @return ScheduleLog
     */
    public function setFinishedAt($finishedAt = null)
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * Get finishedAt.
     *
     * @return \DateTime|null
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * Set schedule.
     *
     * @param \Dentiman\ScheduleBundle\Entity\Schedule|null $schedule
     *
     * @return ScheduleLog
     */
    public function setSchedule(\Dentiman\ScheduleBundle\Entity\Schedule $schedule = null)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule.
     *
     * @return \Dentiman\ScheduleBundle\Entity\Schedule|null
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    public function getDuration() {
       return $this->getStartedAt()->diff($this->finishedAt)->s;
    }

    public function __toString()
    {
        return (string) $this->message;
    }
}
