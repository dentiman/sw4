<?php

namespace App\Entity\Feed\Traits;

use Doctrine\ORM\Mapping as ORM;


trait MainTechTrait
{

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $averageDailyVolume10Day;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $averageDailyVolume3Month;


    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $fiftyDayAverage;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $fiftyDayAverageChange;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $fiftyDayAverageChangePercent;


    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $twoHundredDayAverage;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $twoHundredDayAverageChange;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $twoHundredDayAverageChangePercent;


    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $fiftyTwoWeekHigh;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $fiftyTwoWeekHighChange;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $fiftyTwoWeekHighChangePercent;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $fiftyTwoWeekLow;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $fiftyTwoWeekLowChange;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $fiftyTwoWeekLowChangePercent;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $forwardPE;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $marketCap;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $sharesOutstanding;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $trailingAnnualDividendRate;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $trailingAnnualDividendYield;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $trailingPE;

    /**
     * @return int
     */
    public function getAverageDailyVolume10Day()
    {
        return $this->averageDailyVolume10Day;
    }

    /**
     * @param int $averageDailyVolume10Day
     */
    public function setAverageDailyVolume10Day( $averageDailyVolume10Day): void
    {
        $this->averageDailyVolume10Day = $averageDailyVolume10Day;
    }

    /**
     * @return int
     */
    public function getAverageDailyVolume3Month()
    {
        return $this->averageDailyVolume3Month;
    }

    /**
     * @param int $averageDailyVolume3Month
     */
    public function setAverageDailyVolume3Month( $averageDailyVolume3Month): void
    {
        $this->averageDailyVolume3Month = $averageDailyVolume3Month;
    }

    /**
     * @return float
     */
    public function getFiftyDayAverage()
    {
        return $this->fiftyDayAverage;
    }

    /**
     * @param float $fiftyDayAverage
     */
    public function setFiftyDayAverage( $fiftyDayAverage): void
    {
        $this->fiftyDayAverage = $fiftyDayAverage;
    }

    /**
     * @return float
     */
    public function getFiftyDayAverageChange()
    {
        return $this->fiftyDayAverageChange;
    }

    /**
     * @param float $fiftyDayAverageChange
     */
    public function setFiftyDayAverageChange( $fiftyDayAverageChange): void
    {
        $this->fiftyDayAverageChange = $fiftyDayAverageChange;
    }

    /**
     * @return float
     */
    public function getFiftyDayAverageChangePercent()
    {
        return $this->fiftyDayAverageChangePercent;
    }

    /**
     * @param float $fiftyDayAverageChangePercent
     */
    public function setFiftyDayAverageChangePercent($fiftyDayAverageChangePercent): void
    {
        $this->fiftyDayAverageChangePercent = $fiftyDayAverageChangePercent;
    }

    /**
     * @return float
     */
    public function getTwoHundredDayAverage()
    {
        return $this->twoHundredDayAverage;
    }

    /**
     * @param float $twoHundredDayAverage
     */
    public function setTwoHundredDayAverage( $twoHundredDayAverage): void
    {
        $this->twoHundredDayAverage = $twoHundredDayAverage;
    }

    /**
     * @return float
     */
    public function getTwoHundredDayAverageChange()
    {
        return $this->twoHundredDayAverageChange;
    }

    /**
     * @param float $twoHundredDayAverageChange
     */
    public function setTwoHundredDayAverageChange( $twoHundredDayAverageChange): void
    {
        $this->twoHundredDayAverageChange = $twoHundredDayAverageChange;
    }

    /**
     * @return float
     */
    public function getTwoHundredDayAverageChangePercent()
    {
        return $this->twoHundredDayAverageChangePercent;
    }

    /**
     * @param float $twoHundredDayAverageChangePercent
     */
    public function setTwoHundredDayAverageChangePercent( $twoHundredDayAverageChangePercent): void
    {
        $this->twoHundredDayAverageChangePercent = $twoHundredDayAverageChangePercent;
    }

    /**
     * @return float
     */
    public function getFiftyTwoWeekHigh()
    {
        return $this->fiftyTwoWeekHigh;
    }

    /**
     * @param float $fiftyTwoWeekHigh
     */
    public function setFiftyTwoWeekHigh( $fiftyTwoWeekHigh): void
    {
        $this->fiftyTwoWeekHigh = $fiftyTwoWeekHigh;
    }

    /**
     * @return float
     */
    public function getFiftyTwoWeekHighChange()
    {
        return $this->fiftyTwoWeekHighChange;
    }

    /**
     * @param float $fiftyTwoWeekHighChange
     */
    public function setFiftyTwoWeekHighChange( $fiftyTwoWeekHighChange): void
    {
        $this->fiftyTwoWeekHighChange = $fiftyTwoWeekHighChange;
    }

    /**
     * @return float
     */
    public function getFiftyTwoWeekHighChangePercent()
    {
        return $this->fiftyTwoWeekHighChangePercent;
    }

    /**
     * @param float $fiftyTwoWeekHighChangePercent
     */
    public function setFiftyTwoWeekHighChangePercent( $fiftyTwoWeekHighChangePercent): void
    {
        $this->fiftyTwoWeekHighChangePercent = $fiftyTwoWeekHighChangePercent;
    }

    /**
     * @return float
     */
    public function getFiftyTwoWeekLow()
    {
        return $this->fiftyTwoWeekLow;
    }

    /**
     * @param float $fiftyTwoWeekLow
     */
    public function setFiftyTwoWeekLow( $fiftyTwoWeekLow): void
    {
        $this->fiftyTwoWeekLow = $fiftyTwoWeekLow;
    }

    /**
     * @return float
     */
    public function getFiftyTwoWeekLowChange()
    {
        return $this->fiftyTwoWeekLowChange;
    }

    /**
     * @param float $fiftyTwoWeekLowChange
     */
    public function setFiftyTwoWeekLowChange( $fiftyTwoWeekLowChange): void
    {
        $this->fiftyTwoWeekLowChange = $fiftyTwoWeekLowChange;
    }

    /**
     * @return float
     */
    public function getFiftyTwoWeekLowChangePercent()
    {
        return $this->fiftyTwoWeekLowChangePercent;
    }

    /**
     * @param float $fiftyTwoWeekLowChangePercent
     */
    public function setFiftyTwoWeekLowChangePercent( $fiftyTwoWeekLowChangePercent): void
    {
        $this->fiftyTwoWeekLowChangePercent = $fiftyTwoWeekLowChangePercent;
    }

    /**
     * @return float
     */
    public function getForwardPE()
    {
        return $this->forwardPE;
    }

    /**
     * @param float $forwardPE
     */
    public function setForwardPE( $forwardPE): void
    {
        $this->forwardPE = $forwardPE;
    }

    /**
     * @return int
     */
    public function getMarketCap()
    {
        return $this->marketCap;
    }

    /**
     * @param int $marketCap
     */
    public function setMarketCap( $marketCap): void
    {
        $this->marketCap = $marketCap;
    }

    /**
     * @return float
     */
    public function getSharesOutstanding()
    {
        return $this->sharesOutstanding;
    }

    /**
     * @param float $sharesOutstanding
     */
    public function setSharesOutstanding( $sharesOutstanding): void
    {
        $this->sharesOutstanding = $sharesOutstanding;
    }

    /**
     * @return float
     */
    public function getTrailingAnnualDividendRate()
    {
        return $this->trailingAnnualDividendRate;
    }

    /**
     * @param float $trailingAnnualDividendRate
     */
    public function setTrailingAnnualDividendRate( $trailingAnnualDividendRate): void
    {
        $this->trailingAnnualDividendRate = $trailingAnnualDividendRate;
    }

    /**
     * @return float
     */
    public function getTrailingAnnualDividendYield()
    {
        return $this->trailingAnnualDividendYield;
    }

    /**
     * @param float $trailingAnnualDividendYield
     */
    public function setTrailingAnnualDividendYield( $trailingAnnualDividendYield): void
    {
        $this->trailingAnnualDividendYield = $trailingAnnualDividendYield;
    }

    /**
     * @return float
     */
    public function getTrailingPE()
    {
        return $this->trailingPE;
    }

    /**
     * @param float $trailingPE
     */
    public function setTrailingPE( $trailingPE): void
    {
        $this->trailingPE = $trailingPE;
    }



}
