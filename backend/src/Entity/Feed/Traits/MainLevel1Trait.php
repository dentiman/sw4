<?php

namespace App\Entity\Feed\Traits;

use Doctrine\ORM\Mapping as ORM;


trait MainLevel1Trait
{

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="op", type="float", nullable=true)
     */
    private $op;

    /**
     * @var float
     *
     * @ORM\Column(name="hi", type="float", nullable=true)
     */
    private $hi;

    /**
     * @var float
     *
     * @ORM\Column(name="lo", type="float", nullable=true)
     */
    private $lo;

    /**
     * @var float
     *
     * @ORM\Column(name="chp", type="float", nullable=true)
     */
    private $chp;

    /**
     * @var float
     *
     * @ORM\Column(name="ch", type="float", nullable=true)
     */
    private $ch;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ttime", type="datetime", nullable=true)
     */
    private $ttime;

    /**
     * @var float
     *
     * @ORM\Column(name="bid", type="float", nullable=true)
     */
    private $bid;

    /**
     * @var float
     *
     * @ORM\Column(name="ask", type="float", nullable=true)
     */
    private $ask;

    /**
     * @var float
     *
     * @ORM\Column(name="bidsize", type="float", nullable=true)
     */
    private $bidsize;

    /**
     * @var float
     *
     * @ORM\Column(name="asksize", type="float", nullable=true)
     */
    private $asksize;

    /**
     * @var int
     *
     * @ORM\Column(name="tcount", type="integer", nullable=true)
     */
    private $tcount;

    /**
     * @var int
     *
     * @ORM\Column(name="vol", type="integer", nullable=true)
     */
    private $vol;


    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    private $source;



    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }


    public function setOp($op)
    {
        $this->op = $op;

        return $this;
    }

    /**
     * Get op
     *
     * @return float
     */
    public function getOp()
    {
        return $this->op;
    }


    public function setHi($hi)
    {
        $this->hi = $hi;

        return $this;
    }

    /**
     * Get hi
     *
     * @return float
     */
    public function getHi()
    {
        return $this->hi;
    }


    public function setLo($lo)
    {
        $this->lo = $lo;

        return $this;
    }

    /**
     * Get lo
     *
     * @return float
     */
    public function getLo()
    {
        return $this->lo;
    }


    public function setChp($chp)
    {
        $this->chp = $chp;

        return $this;
    }

    /**
     * Get chp
     *
     * @return float
     */
    public function getChp()
    {
        return $this->chp;
    }


    public function setCh($ch)
    {
        $this->ch = $ch;

        return $this;
    }

    /**
     * Get ch
     *
     * @return float
     */
    public function getCh()
    {
        return $this->ch;
    }


    public function setTtime($ttime)
    {
        $this->ttime = $ttime;

        return $this;
    }

    /**
     * Get ttime
     *
     * @return \DateTime
     */
    public function getTtime()
    {
        return $this->ttime;
    }


    public function setBid($bid)
    {
        $this->bid = $bid;

        return $this;
    }

    /**
     * Get bid
     *
     * @return float
     */
    public function getBid()
    {
        return $this->bid;
    }


    public function setAsk($ask)
    {
        $this->ask = $ask;

        return $this;
    }

    /**
     * Get ask
     *
     * @return float
     */
    public function getAsk()
    {
        return $this->ask;
    }

    public function setBidsize($bidsize)
    {
        $this->bidsize = $bidsize;

        return $this;
    }

    /**
     * Get bidsize
     *
     * @return float
     */
    public function getBidsize()
    {
        return $this->bidsize;
    }


    public function setAsksize($asksize)
    {
        $this->asksize = $asksize;

        return $this;
    }

    /**
     * Get asksize
     *
     * @return float
     */
    public function getAsksize()
    {
        return $this->asksize;
    }


    public function setTcount($tcount)
    {
        $this->tcount = $tcount;

        return $this;
    }

    /**
     * Get tcount
     *
     * @return int
     */
    public function getTcount()
    {
        return $this->tcount;
    }


    public function setVol($vol)
    {
        $this->vol = $vol;

        return $this;
    }

    /**
     * Get vol
     *
     * @return int
     */
    public function getVol()
    {
        return $this->vol;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }


}

