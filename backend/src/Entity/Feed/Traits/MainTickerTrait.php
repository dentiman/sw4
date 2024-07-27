<?php

namespace App\Entity\Feed\Traits;

use Doctrine\ORM\Mapping as ORM;




trait MainTickerTrait
{


    /**
     * @var integer
     * @ORM\Column(name="exchange", type="integer", length=1, nullable=true)
     **/
    private $exchange;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="etf", type="string", length=255, nullable=true)
     */
    private $etf;




    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEtf()
    {
        return $this->etf;
    }

    /**
     * @param string $etf
     */
    public function setEtf($etf)
    {
        $this->etf = $etf;
    }


    public function getExchange(): ?int
    {
        return $this->exchange;
    }


    public function setExchange(?int $exchange)
    {
        $this->exchange = $exchange;
    }






}

