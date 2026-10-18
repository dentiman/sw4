<?php

namespace App\Entity\FeedImport\Tickers;

use Doctrine\ORM\Mapping as ORM;

/**
 * NasdaqListed
 *
 * @ORM\Table(name="feed_tickers_nasdaq_listed")
 * @ORM\Entity()
 */
class NasdaqListed
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="marketCategory", type="string", length=255, nullable=true)
     */
    private $marketCategory;

    /**
     * @var string
     *
     * @ORM\Column(name="test", type="string", length=255, nullable=true)
     */
    private $test;

    /**
     * @var string
     *
     * @ORM\Column(name="financialStatus", type="string", length=255, nullable=true)
     */
    private $financialStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="lotSize", type="string", length=255, nullable=true)
     */
    private $lotSize;

    /**
     * @var string
     *
     * @ORM\Column(name="etf", type="string", length=255, nullable=true)
     */
    private $etf;

    /**
     * @var string
     *
     * @ORM\Column(name="nextShares", type="string", length=255, nullable=true)
     */
    private $nextShares;


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
     * Set name
     *
     * @param string $name
     *
     * @return NasdaqListed
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set marketCategory
     *
     * @param string $marketCategory
     *
     * @return NasdaqListed
     */
    public function setMarketCategory($marketCategory)
    {
        $this->marketCategory = $marketCategory;

        return $this;
    }

    /**
     * Get marketCategory
     *
     * @return string
     */
    public function getMarketCategory()
    {
        return $this->marketCategory;
    }

    /**
     * Set test
     *
     * @param string $test
     *
     * @return NasdaqListed
     */
    public function setTest($test)
    {
        $this->test = $test;

        return $this;
    }

    /**
     * Get test
     *
     * @return string
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Set financialStatus
     *
     * @param string $financialStatus
     *
     * @return NasdaqListed
     */
    public function setFinancialStatus($financialStatus)
    {
        $this->financialStatus = $financialStatus;

        return $this;
    }

    /**
     * Get financialStatus
     *
     * @return string
     */
    public function getFinancialStatus()
    {
        return $this->financialStatus;
    }

    /**
     * Set lotSize
     *
     * @param string $lotSize
     *
     * @return NasdaqListed
     */
    public function setLotSize($lotSize)
    {
        $this->lotSize = $lotSize;

        return $this;
    }

    /**
     * Get lotSize
     *
     * @return string
     */
    public function getLotSize()
    {
        return $this->lotSize;
    }

    /**
     * Set etf
     *
     * @param string $etf
     *
     * @return NasdaqListed
     */
    public function setEtf($etf)
    {
        $this->etf = $etf;

        return $this;
    }

    /**
     * Get eTF
     *
     * @return string
     */
    public function getEtf()
    {
        return $this->etf;
    }

    /**
     * Set nextShares
     *
     * @param string $nextShares
     *
     * @return NasdaqListed
     */
    public function setNextShares($nextShares)
    {
        $this->nextShares = $nextShares;

        return $this;
    }

    /**
     * Get nextShares
     *
     * @return string
     */
    public function getNextShares()
    {
        return $this->nextShares;
    }
}

