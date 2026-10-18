<?php

namespace App\Entity\Feed\Traits;

use Doctrine\ORM\Mapping as ORM;


trait MainEarningsTrait
{

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="earn", type="date", nullable=true)
     */
    private $earn;

    /**
     * @var string
     *
     * @ORM\Column(name="earntime", type="string", length=255, nullable=true)
     */
    private $earntime;

    /**
     * @var float
     *
     * @ORM\Column(name="eps", type="float", nullable=true)
     */
    private $eps;

    /**
     * @var float
     *
     * @ORM\Column(name="eps_est", type="float", nullable=true)
     */
    private $epsEst;

    /**
     * @var float
     *
     * @ORM\Column(name="eps_surprise", type="float", nullable=true)
     */
    private $epsSurprise;

    /**
     * @var float
     *
     * @ORM\Column(name="eps_surprise_percent", type="float", nullable=true)
     */
    private $epsSurprisePercent;



    public function setEarn($earn)
    {
        $this->earn = $earn;

        return $this;
    }


    public function getEarn()
    {
        return $this->earn;
    }


    public function setEarntime($earntime)
    {
        $this->earntime = $earntime;

        return $this;
    }


    public function getEarntime()
    {
        return $this->earntime;
    }


    public function setEps($eps)
    {
        $this->eps = $eps;

        return $this;
    }

    /**
     * Get eps
     *
     * @return float
     */
    public function getEps()
    {
        return $this->eps;
    }


    public function setEpsEst($epsEst)
    {
        $this->epsEst = $epsEst;

        return $this;
    }

    /**
     * Get epsEst
     *
     * @return float
     */
    public function getEpsEst()
    {
        return $this->epsEst;
    }


    public function setEpsSurprise($epsSurprise)
    {
        $this->epsSurprise = $epsSurprise;

        return $this;
    }

    /**
     * Get epsSurprise
     *
     * @return float
     */
    public function getEpsSurprise()
    {
        return $this->epsSurprise;
    }


    public function setEpsSurprisePercent($epsSurprisePercent)
    {
        $this->epsSurprisePercent = $epsSurprisePercent;

        return $this;
    }

    /**
     * Get epsSurprisePercent
     *
     * @return float
     */
    public function getEpsSurprisePercent()
    {
        return $this->epsSurprisePercent;
    }
}

