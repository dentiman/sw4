<?php

namespace App\Entity\Feed\Traits;

use Doctrine\ORM\Mapping as ORM;




trait MainFinvizDataTrait
{

    /**
     * @var integer
     * @ORM\Column(name="sector", type="string", length=1, nullable=true)
     **/
    private $sector;

    /**
     * @var string
     *
     * @ORM\Column(name="ind", type="string", length=255, nullable=true)
     */
    private $ind;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;


    /**
     * @var float
     *
     * @ORM\Column(name="mc", type="float", nullable=true)
     */
    private $mc;

    /**
     * @var float
     *
     * @ORM\Column(name="pe", type="float", nullable=true)
     */
    private $pe;

    /**
     * @var float
     *
     * @ORM\Column(name="fpe", type="float", nullable=true)
     */
    private $fpe;

    /**
     * @var float
     *
     * @ORM\Column(name="epsf", type="float", nullable=true)
     */
    private $epsf;

    /**
     * @var float
     *
     * @ORM\Column(name="aut", type="float", nullable=true)
     */
    private $aut;

    /**
     * @var float
     *
     * @ORM\Column(name="sfloat", type="float", nullable=true)
     */
    private $sfloat;

    /**
     * @var float
     *
     * @ORM\Column(name="insider", type="float", nullable=true)
     */
    private $insider;

    /**
     * @var float
     *
     * @ORM\Column(name="fshort", type="float", nullable=true)
     */
    private $fshort;

    /**
     * @var float
     *
     * @ORM\Column(name="shratio", type="float", nullable=true)
     */
    private $shratio;

    /**
     * @var float
     *
     * @ORM\Column(name="pw", type="float", nullable=true)
     */
    private $pw;

    /**
     * @var float
     *
     * @ORM\Column(name="pm", type="float", nullable=true)
     */
    private $pm;

    /**
     * @var float
     *
     * @ORM\Column(name="pq", type="float", nullable=true)
     */
    private $pq;

    /**
     * @var float
     *
     * @ORM\Column(name="ph", type="float", nullable=true)
     */
    private $ph;

    /**
     * @var float
     *
     * @ORM\Column(name="py", type="float", nullable=true)
     */
    private $py;

    /**
     * @var float
     *
     * @ORM\Column(name="atr", type="float", nullable=true)
     */
    private $atr;

    /**
     * @var float
     *
     * @ORM\Column(name="sma20pc", type="float", nullable=true)
     */
    private $sma20pc;

    /**
     * @var float
     *
     * @ORM\Column(name="sma50pc", type="float", nullable=true)
     */
    private $sma50pc;

    /**
     * @var float
     *
     * @ORM\Column(name="sma200pc", type="float", nullable=true)
     */
    private $sma200pc;

    /**
     * @var float
     *
     * @ORM\Column(name="hi50pc", type="float", nullable=true)
     */
    private $hi50pc;

    /**
     * @var float
     *
     * @ORM\Column(name="lo50pc", type="float", nullable=true)
     */
    private $lo50pc;

    /**
     * @var float
     *
     * @ORM\Column(name="hi52pc", type="float", nullable=true)
     */
    private $hi52pc;

    /**
     * @var float
     *
     * @ORM\Column(name="lo52pc", type="float", nullable=true)
     */
    private $lo52pc;


    /**
     * @var float
     *
     * @ORM\Column(name="avvo", type="float", nullable=true)
     */
    private $avvo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ipo", type="date", nullable=true)
     */
    private $ipo;

    /**
     * @var int
     *
     * @ORM\Column(name="i", type="integer", nullable=true)
     */
    private $index;

    /**
     * @return \DateTime
     */
    public function getIpo()
    {
        return $this->ipo;
    }

    /**
     * @param \DateTime $ipo
     */
    public function setIpo($ipo)
    {
        $this->ipo = $ipo;
    }

    /**
     * @return string
     */
    public function getInd()
    {
        return $this->ind;
    }

    /**
     * @param string $ind
     */
    public function setInd($ind)
    {
        $this->ind = $ind;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return float
     */
    public function getMc()
    {
        return $this->mc;
    }

    /**
     * @param float $mc
     */
    public function setMc($mc)
    {
        $this->mc = $mc;
    }

    /**
     * @return float
     */
    public function getPe()
    {
        return $this->pe;
    }

    /**
     * @param float $pe
     */
    public function setPe($pe)
    {
        $this->pe = $pe;
    }

    /**
     * @return float
     */
    public function getFpe()
    {
        return $this->fpe;
    }

    /**
     * @param float $fpe
     */
    public function setFpe($fpe)
    {
        $this->fpe = $fpe;
    }

    /**
     * @return float
     */
    public function getEpsf()
    {
        return $this->epsf;
    }

    /**
     * @param float $epsf
     */
    public function setEpsf($epsf)
    {
        $this->epsf = $epsf;
    }

    /**
     * @return float
     */
    public function getAut()
    {
        return $this->aut;
    }

    /**
     * @param float $aut
     */
    public function setAut($aut)
    {
        $this->aut = $aut;
    }

    /**
     * @return float
     */
    public function getSfloat()
    {
        return $this->sfloat;
    }

    /**
     * @param float $sfloat
     */
    public function setSfloat($sfloat)
    {
        $this->sfloat = $sfloat;
    }

    /**
     * @return float
     */
    public function getInsider()
    {
        return $this->insider;
    }

    /**
     * @param float $insider
     */
    public function setInsider($insider)
    {
        $this->insider = $insider;
    }

    /**
     * @return float
     */
    public function getFshort()
    {
        return $this->fshort;
    }

    /**
     * @param float $fshort
     */
    public function setFshort($fshort)
    {
        $this->fshort = $fshort;
    }

    /**
     * @return float
     */
    public function getShratio()
    {
        return $this->shratio;
    }

    /**
     * @param float $shratio
     */
    public function setShratio($shratio)
    {
        $this->shratio = $shratio;
    }

    /**
     * @return float
     */
    public function getPw()
    {
        return $this->pw;
    }

    /**
     * @param float $pw
     */
    public function setPw($pw)
    {
        $this->pw = $pw;
    }

    /**
     * @return float
     */
    public function getPm()
    {
        return $this->pm;
    }

    /**
     * @param float $pm
     */
    public function setPm($pm)
    {
        $this->pm = $pm;
    }

    /**
     * @return float
     */
    public function getPq()
    {
        return $this->pq;
    }

    /**
     * @param float $pq
     */
    public function setPq($pq)
    {
        $this->pq = $pq;
    }

    /**
     * @return float
     */
    public function getPh()
    {
        return $this->ph;
    }

    /**
     * @param float $ph
     */
    public function setPh($ph)
    {
        $this->ph = $ph;
    }

    /**
     * @return float
     */
    public function getPy()
    {
        return $this->py;
    }

    /**
     * @param float $py
     */
    public function setPy($py)
    {
        $this->py = $py;
    }

    /**
     * @return float
     */
    public function getAtr()
    {
        return $this->atr;
    }

    /**
     * @param float $atr
     */
    public function setAtr($atr)
    {
        $this->atr = $atr;
    }

    /**
     * @return float
     */
    public function getSma20pc()
    {
        return $this->sma20pc;
    }

    /**
     * @param float $sma20pc
     */
    public function setSma20pc($sma20pc)
    {
        $this->sma20pc = $sma20pc;
    }

    /**
     * @return float
     */
    public function getSma50pc()
    {
        return $this->sma50pc;
    }

    /**
     * @param float $sma50pc
     */
    public function setSma50pc($sma50pc)
    {
        $this->sma50pc = $sma50pc;
    }

    /**
     * @return float
     */
    public function getSma200pc()
    {
        return $this->sma200pc;
    }

    /**
     * @param float $sma200pc
     */
    public function setSma200pc($sma200pc)
    {
        $this->sma200pc = $sma200pc;
    }

    /**
     * @return float
     */
    public function getHi50pc()
    {
        return $this->hi50pc;
    }

    /**
     * @param float $hi50pc
     */
    public function setHi50pc($hi50pc)
    {
        $this->hi50pc = $hi50pc;
    }

    /**
     * @return float
     */
    public function getLo50pc()
    {
        return $this->lo50pc;
    }

    /**
     * @param float $lo50pc
     */
    public function setLo50pc($lo50pc)
    {
        $this->lo50pc = $lo50pc;
    }

    /**
     * @return float
     */
    public function getHi52pc()
    {
        return $this->hi52pc;
    }

    /**
     * @param float $hi52pc
     */
    public function setHi52pc($hi52pc)
    {
        $this->hi52pc = $hi52pc;
    }

    /**
     * @return float
     */
    public function getLo52pc()
    {
        return $this->lo52pc;
    }

    /**
     * @param float $lo52pc
     */
    public function setLo52pc($lo52pc)
    {
        $this->lo52pc = $lo52pc;
    }

    /**
     * @return float
     */
    public function getAvvo()
    {
        return $this->avvo;
    }

    /**
     * @param float $avvo
     */
    public function setAvvo($avvo)
    {
        $this->avvo = $avvo;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param int $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }



    /**
     * @return int
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * @param int $sector
     */
    public function setSector($sector)
    {
        $this->sector = $sector;
    }



}

