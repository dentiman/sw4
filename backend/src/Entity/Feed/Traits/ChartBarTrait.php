<?php

namespace App\Entity\Feed\Traits;

use Doctrine\ORM\Mapping as ORM;


trait ChartBarTrait
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="ticker", type="string", length=10, nullable=false)
     */
    private $ticker;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var float
     *
     * @ORM\Column(name="op", type="float")
     */
    private $op;

    /**
     * @var float
     *
     * @ORM\Column(name="hi", type="float")
     */
    private $hi;

    /**
     * @var float
     *
     * @ORM\Column(name="lo", type="float")
     */
    private $lo;

    /**
     * @var float
     *
     * @ORM\Column(name="cl", type="float")
     */
    private $cl;

    /**
     * @var int
     *
     * @ORM\Column(name="vol", type="integer")
     */
    private $vol;

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime(\DateTime $time): void
    {
        $this->time = $time;
    }

    /**
     * @return float
     */
    public function getOp(): float
    {
        return $this->op;
    }

    /**
     * @param float $op
     */
    public function setOp(float $op): void
    {
        $this->op = $op;
    }

    /**
     * @return float
     */
    public function getHi(): float
    {
        return $this->hi;
    }

    /**
     * @param float $hi
     */
    public function setHi(float $hi): void
    {
        $this->hi = $hi;
    }

    /**
     * @return float
     */
    public function getLo(): float
    {
        return $this->lo;
    }

    /**
     * @param float $lo
     */
    public function setLo(float $lo): void
    {
        $this->lo = $lo;
    }

    /**
     * @return float
     */
    public function getCl(): float
    {
        return $this->cl;
    }

    /**
     * @param float $cl
     */
    public function setCl(float $cl): void
    {
        $this->cl = $cl;
    }

    /**
     * @return int
     */
    public function getVol(): int
    {
        return $this->vol;
    }

    /**
     * @param int $vol
     */
    public function setVol(int $vol): void
    {
        $this->vol = $vol;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTicker(): string
    {
        return $this->ticker;
    }

    /**
     * @param string $ticker
     */
    public function setTicker(string $ticker): void
    {
        $this->ticker = $ticker;
    }

}

