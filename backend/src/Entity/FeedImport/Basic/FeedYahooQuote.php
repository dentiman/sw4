<?php

namespace App\Entity\FeedImport\Basic;

use Doctrine\ORM\Mapping as ORM;

/**
 * Yahoo
 *
 * @ORM\Table(name="feed_yahoo_quote")
 * @ORM\Entity()
 */
class FeedYahooQuote implements \JsonSerializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=10, nullable=true)
     * @ORM\Id
     *
     */
    private $symbol;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $ask;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $askSize;

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
    private $bid;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bidSize;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $bookValue;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $currency;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dividendDate;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $earningsTimestamp;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $earningsTimestampStart;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $earningsTimestampEnd;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $epsForward;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $epsTrailingTwelveMonths;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $exchange;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $exchangeDataDelayedBy;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $exchangeTimezoneName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $exchangeTimezoneShortName;

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
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $financialCurrency;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $forwardPE;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $fullExchangeName;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gmtOffSetMilliseconds;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $language;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $longName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $market;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $marketCap;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $marketState;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $messageBoardId;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $postMarketChange;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $postMarketChangePercent;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $postMarketPrice;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $postMarketTime;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $priceHint;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceToBook;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $quoteSourceName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $quoteType;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $regularMarketChange;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $regularMarketChangePercent;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $regularMarketDayHigh;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $regularMarketDayLow;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $regularMarketOpen;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $regularMarketPreviousClose;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $regularMarketPrice;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $regularMarketTime;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $regularMarketVolume;

    /**
     * @var float
     * @ORM\Column(type="float", nullable=true)
     */
    private $sharesOutstanding;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $shortName;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sourceInterval;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $tradeable;

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
     * @param array $values
     */
    public function __construct(array $values)
    {
        foreach ($values as $property => $value) {
            $this->{$property} = $value;
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return float
     */
    public function getAsk()
    {
        return $this->ask;
    }

    /**
     * @return int
     */
    public function getAskSize()
    {
        return $this->askSize;
    }

    /**
     * @return int
     */
    public function getAverageDailyVolume10Day()
    {
        return $this->averageDailyVolume10Day;
    }

    /**
     * @return int
     */
    public function getAverageDailyVolume3Month()
    {
        return $this->averageDailyVolume3Month;
    }

    /**
     * @return float
     */
    public function getBid()
    {
        return $this->bid;
    }

    /**
     * @return int
     */
    public function getBidSize()
    {
        return $this->bidSize;
    }

    /**
     * @return float
     */
    public function getBookValue()
    {
        return $this->bookValue;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return \DateTime
     */
    public function getDividendDate()
    {
        return $this->dividendDate;
    }

    /**
     * @return \DateTime
     */
    public function getEarningsTimestamp()
    {
        return $this->earningsTimestamp;
    }

    /**
     * @return \DateTime
     */
    public function getEarningsTimestampStart()
    {
        return $this->earningsTimestampStart;
    }

    /**
     * @return \DateTime
     */
    public function getEarningsTimestampEnd()
    {
        return $this->earningsTimestampEnd;
    }

    /**
     * @return float
     */
    public function getEpsForward()
    {
        return $this->epsForward;
    }

    /**
     * @return float
     */
    public function getEpsTrailingTwelveMonths()
    {
        return $this->epsTrailingTwelveMonths;
    }

    /**
     * @return string
     */
    public function getExchange()
    {
        return $this->exchange;
    }

    /**
     * @return int
     */
    public function getExchangeDataDelayedBy()
    {
        return $this->exchangeDataDelayedBy;
    }

    /**
     * @return string
     */
    public function getExchangeTimezoneName()
    {
        return $this->exchangeTimezoneName;
    }

    /**
     * @return string
     */
    public function getExchangeTimezoneShortName()
    {
        return $this->exchangeTimezoneShortName;
    }

    /**
     * @return float
     */
    public function getFiftyDayAverage()
    {
        return $this->fiftyDayAverage;
    }

    /**
     * @return float
     */
    public function getFiftyDayAverageChange()
    {
        return $this->fiftyDayAverageChange;
    }

    /**
     * @return float
     */
    public function getFiftyDayAverageChangePercent()
    {
        return $this->fiftyDayAverageChangePercent;
    }

    /**
     * @return float
     */
    public function getFiftyTwoWeekHigh()
    {
        return $this->fiftyTwoWeekHigh;
    }

    /**
     * @return float
     */
    public function getFiftyTwoWeekHighChange()
    {
        return $this->fiftyTwoWeekHighChange;
    }

    /**
     * @return float
     */
    public function getFiftyTwoWeekHighChangePercent()
    {
        return $this->fiftyTwoWeekHighChangePercent;
    }

    /**
     * @return float
     */
    public function getFiftyTwoWeekLow()
    {
        return $this->fiftyTwoWeekLow;
    }

    /**
     * @return float
     */
    public function getFiftyTwoWeekLowChange()
    {
        return $this->fiftyTwoWeekLowChange;
    }

    /**
     * @return float
     */
    public function getFiftyTwoWeekLowChangePercent()
    {
        return $this->fiftyTwoWeekLowChangePercent;
    }

    /**
     * @return string
     */
    public function getFinancialCurrency()
    {
        return $this->financialCurrency;
    }

    /**
     * @return float
     */
    public function getForwardPE()
    {
        return $this->forwardPE;
    }

    /**
     * @return string
     */
    public function getFullExchangeName()
    {
        return $this->fullExchangeName;
    }

    /**
     * @return int
     */
    public function getGmtOffSetMilliseconds()
    {
        return $this->gmtOffSetMilliseconds;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        return $this->longName;
    }

    /**
     * @return string
     */
    public function getMarket()
    {
        return $this->market;
    }

    /**
     * @return int
     */
    public function getMarketCap()
    {
        return $this->marketCap;
    }

    /**
     * @return string
     */
    public function getMarketState()
    {
        return $this->marketState;
    }

    /**
     * @return string
     */
    public function getMessageBoardId()
    {
        return $this->messageBoardId;
    }

    /**
     * @return float
     */
    public function getPostMarketChange()
    {
        return $this->postMarketChange;
    }

    /**
     * @return float
     */
    public function getPostMarketChangePercent()
    {
        return $this->postMarketChangePercent;
    }

    /**
     * @return float
     */
    public function getPostMarketPrice()
    {
        return $this->postMarketPrice;
    }

    /**
     * @return \DateTime
     */
    public function getPostMarketTime()
    {
        return $this->postMarketTime;
    }

    /**
     * @return int
     */
    public function getPriceHint()
    {
        return $this->priceHint;
    }

    /**
     * @return float
     */
    public function getPriceToBook()
    {
        return $this->priceToBook;
    }

    /**
     * @return string
     */
    public function getQuoteSourceName()
    {
        return $this->quoteSourceName;
    }

    /**
     * @return string
     */
    public function getQuoteType()
    {
        return $this->quoteType;
    }

    /**
     * @return float
     */
    public function getRegularMarketChange()
    {
        return $this->regularMarketChange;
    }

    /**
     * @return float
     */
    public function getRegularMarketChangePercent()
    {
        return $this->regularMarketChangePercent;
    }

    /**
     * @return float
     */
    public function getRegularMarketDayHigh()
    {
        return $this->regularMarketDayHigh;
    }

    /**
     * @return float
     */
    public function getRegularMarketDayLow()
    {
        return $this->regularMarketDayLow;
    }

    /**
     * @return float
     */
    public function getRegularMarketOpen()
    {
        return $this->regularMarketOpen;
    }

    /**
     * @return float
     */
    public function getRegularMarketPreviousClose()
    {
        return $this->regularMarketPreviousClose;
    }

    /**
     * @return float
     */
    public function getRegularMarketPrice()
    {
        return $this->regularMarketPrice;
    }

    /**
     * @return \DateTime
     */
    public function getRegularMarketTime()
    {
        return $this->regularMarketTime;
    }

    /**
     * @return int
     */
    public function getRegularMarketVolume()
    {
        return $this->regularMarketVolume;
    }

    /**
     * @return int
     */
    public function getSharesOutstanding()
    {
        return $this->sharesOutstanding;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @return int
     */
    public function getSourceInterval()
    {
        return $this->sourceInterval;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @return bool
     */
    public function getTradeable()
    {
        return $this->tradeable;
    }

    /**
     * @return float
     */
    public function getTrailingAnnualDividendRate()
    {
        return $this->trailingAnnualDividendRate;
    }

    /**
     * @return float
     */
    public function getTrailingAnnualDividendYield()
    {
        return $this->trailingAnnualDividendYield;
    }

    /**
     * @return float
     */
    public function getTrailingPE()
    {
        return $this->trailingPE;
    }

    /**
     * @return float
     */
    public function getTwoHundredDayAverage()
    {
        return $this->twoHundredDayAverage;
    }

    /**
     * @return float
     */
    public function getTwoHundredDayAverageChange()
    {
        return $this->twoHundredDayAverageChange;
    }

    /**
     * @return float
     */
    public function getTwoHundredDayAverageChangePercent()
    {
        return $this->twoHundredDayAverageChangePercent;
    }
}
