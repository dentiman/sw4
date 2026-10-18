<?php

namespace App\Entity\Feed\Traits;

use Doctrine\ORM\Mapping as ORM;


trait MainCalculatedDataTrait
{

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $relativeVolume;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $gep;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceRange;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chpo;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cho;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $atrp;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $newHigh;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $newLow;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $level;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $spread;


    public function getRelativeVolume(): ?int
    {
        return $this->relativeVolume;
    }

    public function setRelativeVolume(?float $relativeVolume): self
    {
        $this->relativeVolume = $relativeVolume;

        return $this;
    }

    public function getGep(): ?float
    {
        return $this->gep;
    }

    public function setGep(?float $gep): self
    {
        $this->gep = $gep;

        return $this;
    }

    public function getPriceRange(): ?float
    {
        return $this->priceRange;
    }

    public function setPriceRange(?float $priceRange): self
    {
        $this->priceRange = $priceRange;

        return $this;
    }

    public function getChpo(): ?float
    {
        return $this->chpo;
    }

    public function setChpo(?float $chpo): self
    {
        $this->chpo = $chpo;

        return $this;
    }

    public function getCho(): ?float
    {
        return $this->cho;
    }

    public function setCho(?float $cho): self
    {
        $this->cho = $cho;

        return $this;
    }

    public function getAtrp(): ?float
    {
        return $this->atrp;
    }

    public function setAtrp(?float $atrp): self
    {
        $this->atrp = $atrp;

        return $this;
    }

    public function getNewHigh(): ?float
    {
        return $this->newHigh;
    }

    public function setNewHigh(?float $newHigh): self
    {
        $this->newHigh = $newHigh;

        return $this;
    }

    public function getNewLow(): ?float
    {
        return $this->newLow;
    }

    public function setNewLow(?float $newLow): self
    {
        $this->newLow = $newLow;

        return $this;
    }

    public function getLevel(): ?float
    {
        return $this->level;
    }

    public function setLevel(?float $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpread(): ?float
    {
        return $this->spread;
    }

    /**
     * @param mixed $spread
     */
    public function setSpread($spread): void
    {
        $this->spread = $spread;
    }


}
