<?php

namespace App\Entity\FeedImport\Level1;

use Doctrine\ORM\Mapping as ORM;

/**
 * Finviz
 *
 * @ORM\Table(name="feed_level1_finviz")
 * @ORM\Entity()
 */
class FeedLevel1Finviz
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=10, nullable=false)
     * @ORM\Id
     *
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="chp", type="float", nullable=true)
     */
    private $chp;


    /**
     * @var int
     *
     * @ORM\Column(name="vol", type="integer", nullable=true)
     */
    private $vol;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param string $id
     *
     */
    public function setId($id)
    {
        $this->id = $id;

    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getChp()
    {
        return $this->chp;
    }

    /**
     * @param float $chp
     */
    public function setChp($chp)
    {
        $this->chp = $chp;
    }

    /**
     * @return int
     */
    public function getVol()
    {
        return $this->vol;
    }

    /**
     * @param int $vol
     */
    public function setVol($vol)
    {
        $this->vol = $vol;
    }
}

