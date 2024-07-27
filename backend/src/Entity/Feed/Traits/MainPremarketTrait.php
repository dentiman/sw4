<?php

namespace App\Entity\Feed\Traits;

use Doctrine\ORM\Mapping as ORM;


trait MainPremarketTrait
{

    /**
     * @var int
     *
     * @ORM\Column(name="pvol", type="integer", nullable=true)
     */
    private $pvol;

    /**
     * @var int
     *
     * @ORM\Column(name="ptcount", type="integer", nullable=true)
     */
    private $ptcount;

    /**
     * @var float
     *
     * @ORM\Column(name="pprice", type="float", nullable=true)
     */
    private $pprice;

    /**
     * @var float
     *
     * @ORM\Column(name="pchp", type="float", nullable=true)
     */
    private $pchp;

    /**
     * @var float
     *
     * @ORM\Column(name="pch", type="float", nullable=true)
     */
    private $pch;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pttime", type="datetime", nullable=true)
     */
    private $pttime;



    public function setPvol($pvol)
    {
        $this->pvol = $pvol;

        return $this;
    }

    /**
     * Get pvol
     *
     * @return int
     */
    public function getPvol()
    {
        return $this->pvol;
    }


    public function setPtcount($ptcount)
    {
        $this->ptcount = $ptcount;

        return $this;
    }

    /**
     * Get ptcount
     *
     * @return int
     */
    public function getPtcount()
    {
        return $this->ptcount;
    }


    public function setPprice($pprice)
    {
        $this->pprice = $pprice;

        return $this;
    }

    /**
     * Get pprice
     *
     * @return float
     */
    public function getPprice()
    {
        return $this->pprice;
    }


    public function setPchp($pchp)
    {
        $this->pchp = $pchp;

        return $this;
    }

    /**
     * Get pchp
     *
     * @return float
     */
    public function getPchp()
    {
        return $this->pchp;
    }


    public function setPch($pch)
    {
        $this->pch = $pch;

        return $this;
    }

    /**
     * Get pch
     *
     * @return float
     */
    public function getPch()
    {
        return $this->pch;
    }


    /**
     * @return \DateTime
     */
    public function getPttime(): ?\DateTime
    {
        return $this->pttime;
    }

    /**
     * @param \DateTime $pttime
     */
    public function setPttime(?\DateTime $pttime): void
    {
        $this->pttime = $pttime;
    }


}

