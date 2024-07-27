<?php

namespace App\Entity\Feed\Traits;

use Doctrine\ORM\Mapping as ORM;


trait MainTmpTrait
{

    /**
     * @var string
     * @ORM\Column(name="sector", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="atr", type="float", nullable=true)
     */
    private $atr;


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
    private $index = 0;

    /**
     * @return string
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * @param string $sector
     */
    public function setSector( $sector): void
    {
        $this->sector = $sector;
    }

    /**
     * @return string
     */
    public function getInd(): ?string
    {
        return $this->ind;
    }

    /**
     * @param string $ind
     */
    public function setInd( $ind): void
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
    public function setCountry( $country): void
    {
        $this->country = $country;
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
    public function setAtr( $atr): void
    {
        $this->atr = $atr;
    }

    /**
     * @return \DateTime
     */
    public function getIpo(): ?\DateTime
    {
        return $this->ipo;
    }

    /**
     * @param \DateTime $ipo
     */
    public function setIpo(\DateTime $ipo): void
    {
        $this->ipo = $ipo;
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
    public function setIndex( $index): void
    {
        $this->index = $index;
    }


}
