<?php

namespace App\Entity\Chart;

use Doctrine\ORM\Mapping as ORM;
use Intervention\Image\AbstractColor;
use Intervention\Image\Gd\Color;


/**
 * @ORM\Table(name="chart_layout")
 * @ORM\Entity
 */
class ChartLayout implements \JsonSerializable
{

    const SITE_THEME_DARK = 'dark';
    const SITE_THEME_LIGHT = 'light';

    const BACKGROUND_DARK = '#000000';
    const BACKGROUND_LIGHT = '#ffffff';

    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @var string */
    protected $siteTheme = self::SITE_THEME_LIGHT;

    protected $ticker = 'AAPL';

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    protected $timeFrame = '5';

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $width  = 1600;

    /**
     * @var integer
     *
     * @ORM\Column( type="integer")
     */
    private $height = 500;

    /**
     * @var integer
     *
     * @ORM\Column( type="integer")
     */
    private $volumeAreaHeight = 40;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    private $bgColor = '#ffffff';

    /**
     * @var string|null
     * @ORM\Column( type="string")
     */
    private $gridColor = '#d6d6d6';

    /**
     * @var bool|null
     * @ORM\Column(type="boolean")
     */
    private $preMarket = false;


    private $preMarketColor = '#B4B4B4';

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $separateVolumeArea = false;

    /**
     * @var bool|null
     * @ORM\Column( type="boolean")
     */
    private $spyOn = false;


    /**
     * @var string
     * @ORM\Column( type="string")
     */
    private $spyColor = '#cccccc';

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    private $barType = 'candle';

    /**
     * @var integer
     *
     * @ORM\Column( type="integer")
     */
    private $barWidth = 6;

    /**
     * @var integer|null
     *
     * @ORM\Column( type="integer")
     */
    private $barThick = 1;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $outline = 2;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $volumeBarWidth = 4;

    /**
     * @var string|null
     *
     * @ORM\Column( type="string")
     */
    private $colorUp = '#249E92';

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $colorDown = '#E34F4C';

    /**
     * @var string|null
     *
     * @ORM\Column( type="string")
     */
    private $outlineColorUp = '#000000';

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $outlineColorDown = '#000000';

    /**
     * @var string
     *
     * @ORM\Column( type="string")
     */
    private $volumeColorUp = '#00FF14';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $volumeColorDown = '#FF0000';

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sma1 ;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sma2;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sma3;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ema1;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ema2;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ema3;


    /**
     * @var string
     * @ORM\Column( type="string")
     */
    private $sma1Color = '#cccccc';
    /**
     * @var string
     * @ORM\Column( type="string")
     */
    private $sma2Color = '#cccccc';
    /**
     * @var string
     * @ORM\Column( type="string")
     */
    private $sma3Color = '#cccccc';
    /**
     * @var string
     * @ORM\Column( type="string")
     */
    private $ema1Color = '#cccccc';
    /**
     * @var string
     * @ORM\Column( type="string")
     */
    private $ema2Color = '#cccccc';
    /**
     * @var string
     * @ORM\Column( type="string")
     */
    private $ema3Color = '#cccccc';

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $maLabel = true;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $maLine = true;

    /**
     * @var bool|null
     * @ORM\Column(type="boolean")
     */
    private $linesOpen = false;

    /**
     * @var bool|null
     * @ORM\Column(type="boolean")
     */
    private $linesHigh= false;

    /**
     * @var bool|null
     * @ORM\Column(type="boolean")
     */
    private $linesLow= false;

    /**
     * @var bool|null
     * @ORM\Column(type="boolean")
     */
    private $linesClose= false;

    /**
     * @var bool|null
     * @ORM\Column(type="boolean")
     */
    private $lineLastPrice = false;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $linesDays = 1;


    protected $price_area_height= null;

    /**
     * @var string null
     */
    protected $lastBarData = null;

    /**
     * @var string null
     */
    protected $compareBarData = null;

    public $currentCandleColor;

    public $currentOutlineColor;


    /**
     * @var string|null
     *
     */
    private $gridTextColor = '#cccccc';


    protected $isCompare = false;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getVolumeAreaHeight(): int
    {
        return $this->volumeAreaHeight;
    }

    /**
     * @param int $volumeAreaHeight
     */
    public function setVolumeAreaHeight(int $volumeAreaHeight): void
    {
        $this->volumeAreaHeight = $volumeAreaHeight;
    }

    /**
     * @return string|null
     */
    public function getBgColor(): ?string
    {
        return $this->bgColor;
    }

    /**
     * @param string|null $bgColor
     */
    public function setBgColor(?string $bgColor): void
    {
        $this->bgColor = $bgColor;
    }

    /**
     * @return string|null
     */
    public function getGridColor(): ?string
    {
        return $this->gridColor;
    }

    /**
     * @param string|null $gridColor
     */
    public function setGridColor(?string $gridColor): void
    {
        $this->gridColor = $gridColor;
    }

    /**
     * @return bool|null
     */
    public function getPreMarket(): ?bool
    {
        return $this->preMarket;
    }


    /**
     * @param bool|null $preMarket
     */
    public function setPreMarket(?bool $preMarket): void
    {
        $this->preMarket = $preMarket;
    }

    /**
     * @return string
     */
    public function getPreMarketColor(): string
    {
        if($this->isLight($this->getBgColor()))  return 'rgba(0,0,0,.1)';
        return 'rgba(255,255,255,.07)';
    }

    protected function isLight(string $color): bool
    {
        $color = new Color($color);
        $gray = 0.299 * $color->r +  0.587 *$color->g +  0.114 *  $color->b;
        if($gray > 127.5)  return true;
        return  false;
    }

    /**
     * @param string $preMarketColor
     */
    public function setPreMarketColor(string $preMarketColor): void
    {
        $this->preMarketColor = $preMarketColor;
    }



    /**
     * @return bool
     */
    public function isSeparateVolumeArea(): bool
    {
        return $this->separateVolumeArea;
    }

    /**
     * @param bool $separateVolumeArea
     */
    public function setSeparateVolumeArea(bool $separateVolumeArea): void
    {
        $this->separateVolumeArea = $separateVolumeArea;
    }

    /**
     * @return bool|null
     */
    public function getSpyOn(): ?bool
    {
        return $this->spyOn;
    }

    /**
     * @param bool|null $spyOn
     */
    public function setSpyOn(?bool $spyOn): void
    {
        $this->spyOn = $spyOn;
    }

    /**
     * @return string
     */
    public function getSpyColor(): string
    {
        return $this->spyColor;
    }

    /**
     * @param string $spyColor
     */
    public function setSpyColor(string $spyColor): void
    {
        $this->spyColor = $spyColor;
    }



    /**
     * @return string|null
     */
    public function getBarType(): ?string
    {
        return $this->barType;
    }

    /**
     * @param string|null $barType
     */
    public function setBarType(?string $barType): void
    {
        $this->barType = $barType;
    }

    /**
     * @return int
     */
    public function getBarWidth(): int
    {
        return $this->barWidth;
    }

    /**
     * @param int $barWidth
     */
    public function setBarWidth(int $barWidth): void
    {
        $this->barWidth = $barWidth;
    }

    /**
     * @return int|null
     */
    public function getBarThick(): ?int
    {
        return $this->barThick;
    }

    /**
     * @param int|null $barThick
     */
    public function setBarThick(?int $barThick): void
    {
        $this->barThick = $barThick;
    }

    /**
     * @return int
     */
    public function getOutline(): int
    {
        return $this->outline;
    }

    /**
     * @param int $outline
     */
    public function setOutline(int $outline): void
    {
        $this->outline = $outline;
    }

    /**
     * @return int
     */
    public function getVolumeBarWidth(): int
    {
        return $this->volumeBarWidth;
    }

    /**
     * @param int $volumeBarWidth
     */
    public function setVolumeBarWidth(int $volumeBarWidth): void
    {
        $this->volumeBarWidth = $volumeBarWidth;
    }

    /**
     * @return string|null
     */
    public function getColorUp(): ?string
    {
        return $this->colorUp;
    }

    /**
     * @param string|null $colorUp
     */
    public function setColorUp(?string $colorUp): void
    {
        $this->colorUp = $colorUp;
    }

    /**
     * @return string|null
     */
    public function getColorDown(): ?string
    {
        return $this->colorDown;
    }

    /**
     * @param string|null $colorDown
     */
    public function setColorDown(?string $colorDown): void
    {
        $this->colorDown = $colorDown;
    }

    /**
     * @return string|null
     */
    public function getOutlineColorUp(): ?string
    {
        return $this->outlineColorUp;
    }

    /**
     * @param string|null $outlineColorUp
     */
    public function setOutlineColorUp(?string $outlineColorUp): void
    {
        $this->outlineColorUp = $outlineColorUp;
    }

    /**
     * @return string|null
     */
    public function getOutlineColorDown(): ?string
    {
        return $this->outlineColorDown;
    }

    /**
     * @param string|null $outlineColorDown
     */
    public function setOutlineColorDown(?string $outlineColorDown): void
    {
        $this->outlineColorDown = $outlineColorDown;
    }

    /**
     * @return string
     */
    public function getVolumeColorUp(): string
    {
        return $this->volumeColorUp;
    }

    /**
     * @param string $volumeColorUp
     */
    public function setVolumeColorUp(string $volumeColorUp): void
    {
        $this->volumeColorUp = $volumeColorUp;
    }

    /**
     * @return string
     */
    public function getVolumeColorDown(): string
    {
        return $this->volumeColorDown;
    }

    /**
     * @param string $volumeColorDown
     */
    public function setVolumeColorDown(string $volumeColorDown): void
    {
        $this->volumeColorDown = $volumeColorDown;
    }

    /**
     * @return int
     */
    public function getSma1(): ?int
    {
        return $this->sma1;
    }

    /**
     * @param int $sma1
     */
    public function setSma1(?int $sma1): void
    {
        $this->sma1 = $sma1;
    }

    /**
     * @return int
     */
    public function getSma2(): ?int
    {
        return $this->sma2;
    }

    /**
     * @param int $sma2
     */
    public function setSma2(?int $sma2): void
    {
        $this->sma2 = $sma2;
    }

    /**
     * @return int
     */
    public function getSma3():?int
    {
        return $this->sma3;
    }

    /**
     * @param int $sma3
     */
    public function setSma3(?int $sma3): void
    {
        $this->sma3 = $sma3;
    }

    /**
     * @return int
     */
    public function getEma1(): ?int
    {
        return $this->ema1;
    }

    /**
     * @param int $ema1
     */
    public function setEma1(?int $ema1): void
    {
        $this->ema1 = $ema1;
    }

    /**
     * @return int
     */
    public function getEma2(): ?int
    {
        return $this->ema2;
    }

    /**
     * @param int $ema2
     */
    public function setEma2(?int $ema2): void
    {
        $this->ema2 = $ema2;
    }

    /**
     * @return int
     */
    public function getEma3(): ?int
    {
        return $this->ema3;
    }

    /**
     * @param int $ema3
     */
    public function setEma3(?int $ema3): void
    {
        $this->ema3 = $ema3;
    }

    /**
     * @return string
     */
    public function getSma1Color(): string
    {
        return $this->sma1Color;
    }

    /**
     * @param string $sma1Color
     */
    public function setSma1Color(string $sma1Color): void
    {
        $this->sma1Color = $sma1Color;
    }

    /**
     * @return string
     */
    public function getSma2Color(): string
    {
        return $this->sma2Color;
    }

    /**
     * @param string $sma2Color
     */
    public function setSma2Color(string $sma2Color): void
    {
        $this->sma2Color = $sma2Color;
    }

    /**
     * @return string
     */
    public function getSma3Color(): string
    {
        return $this->sma3Color;
    }

    /**
     * @param string $sma3Color
     */
    public function setSma3Color(string $sma3Color): void
    {
        $this->sma3Color = $sma3Color;
    }

    /**
     * @return string
     */
    public function getEma1Color(): string
    {
        return $this->ema1Color;
    }

    /**
     * @param string $ema1Color
     */
    public function setEma1Color(string $ema1Color): void
    {
        $this->ema1Color = $ema1Color;
    }

    /**
     * @return string
     */
    public function getEma2Color(): string
    {
        return $this->ema2Color;
    }

    /**
     * @param string $ema2Color
     */
    public function setEma2Color(string $ema2Color): void
    {
        $this->ema2Color = $ema2Color;
    }

    /**
     * @return string
     */
    public function getEma3Color(): string
    {
        return $this->ema3Color;
    }

    /**
     * @param string $ema3Color
     */
    public function setEma3Color(string $ema3Color): void
    {
        $this->ema3Color = $ema3Color;
    }




    /**
     * @return bool
     */
    public function isMaLabel(): bool
    {
        return $this->maLabel;
    }

    /**
     * @param bool $maLabel
     */
    public function setMaLabel(bool $maLabel): void
    {
        $this->maLabel = $maLabel;
    }

    /**
     * @return bool
     */
    public function isMaLine(): bool
    {
        return $this->maLine;
    }

    /**
     * @param bool $maLine
     */
    public function setMaLine(bool $maLine): void
    {
        $this->maLine = $maLine;
    }

    /**
     * @return bool|null
     */
    public function getLinesOpen(): ?bool
    {
        return $this->linesOpen;
    }

    /**
     * @param bool|null $linesOpen
     */
    public function setLinesOpen(?bool $linesOpen): void
    {
        $this->linesOpen = $linesOpen;
    }

    /**
     * @return bool|null
     */
    public function getLinesHigh(): ?bool
    {
        return $this->linesHigh;
    }

    /**
     * @param bool|null $linesHigh
     */
    public function setLinesHigh(?bool $linesHigh): void
    {
        $this->linesHigh = $linesHigh;
    }

    /**
     * @return bool|null
     */
    public function getLinesLow(): ?bool
    {
        return $this->linesLow;
    }

    /**
     * @param bool|null $linesLow
     */
    public function setLinesLow(?bool $linesLow): void
    {
        $this->linesLow = $linesLow;
    }

    /**
     * @return bool|null
     */
    public function getLinesClose(): ?bool
    {
        return $this->linesClose;
    }

    /**
     * @param bool|null $linesClose
     */
    public function setLinesClose(?bool $linesClose): void
    {
        $this->linesClose = $linesClose;
    }

    /**
     * @return bool|null
     */
    public function getLineLastPrice(): ?bool
    {
        return $this->lineLastPrice;
    }

    /**
     * @param bool|null $lineLastPrice
     */
    public function setLineLastPrice(?bool $lineLastPrice): void
    {
        $this->lineLastPrice = $lineLastPrice;
    }

    /**
     * @return int
     */
    public function getLinesDays(): int
    {
        return $this->linesDays;
    }

    /**
     * @param int $linesDays
     */
    public function setLinesDays(int $linesDays): void
    {
        $this->linesDays = $linesDays;
    }

    /**
     * @return string
     */
    public function getTicker(): string
    {
        return $this->ticker;
    }

    /**
     * @param string $ticket
     */
    public function setTicker(string $ticker): void
    {
        $this->ticker = $ticker;
    }

    /**
     * @return string|null
     */
    public function getTimeFrame(): ?string
    {
        return $this->timeFrame;
    }

    /**
     * @param string|null $timeFrame
     */
    public function setTimeFrame(?string $timeFrame): void
    {
        $this->timeFrame = $timeFrame;
    }

    public function getAreaWidth()
    {
        return $this->width - $this->right_padding;
    }

    public function getAreaHeight()
    {
        return $this->getHeight()  - $this->bottom_padding;
    }


    /**
     * @return mixed
     */
    public function getBottomPadding()
    {
        return $this->bottom_padding;
    }

    protected $right_padding = 55;

    protected $volume_area_height = 0;

    protected $bottom_padding = 20;

    public function getX0()
    {
        return  $this->width - ($this->right_padding + 5);
    }

    public function getBarsCount()
    {
        return  ceil(($this->getAreaWidth()) / $this->getBarWidth());
    }


    public function  getPriceAreaHeight()
    {
        if($this->price_area_height) return $this->price_area_height;

        if ($this->getHeight() <= 200) {

            $this->price_area_height =  $this->getAreaHeight()- $this->getVolumeAreaTrueHeight() -30;
        } elseif ($this->getHeight() > 200 && $this->getHeight() < 300) {

            $this->price_area_height =  $this->getAreaHeight()- $this->getVolumeAreaTrueHeight() -35;
        } elseif ($this->getHeight() >= 300 && $this->getHeight() < 400) {

            $this->price_area_height =  $this->getAreaHeight()- $this->getVolumeAreaTrueHeight() -40;
        } else {
            $this->price_area_height =  $this->getAreaHeight()- $this->getVolumeAreaTrueHeight() -50;
        }

        return $this->price_area_height;
    }

    public function getVolumeAreaTrueHeight()
    {
        if ($this->isSeparateVolumeArea() == true) {
            return  $this->volumeAreaHeight;
        }
        return 0;
    }

    public function getBackBgColor()
    {
        if($this->siteTheme == self::SITE_THEME_DARK) return self::BACKGROUND_DARK;
        return self::BACKGROUND_LIGHT;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @param array $values
     */
    public function applyData(array $values)
    {
        foreach ($values as $property => $value) {
            $this->{$property} = $value;
        }
    }

    /**
     * @return string|null
     */
    public function getGridTextColor(): ?string
    {
        return $this->gridTextColor;
    }

    /**
     * @return string
     */
    public function getLastBarData(): ?string
    {
        return $this->lastBarData;
    }

    /**
     * @param string $lastBarData
     */
    public function setLastBarData(?string $lastBarData): void
    {
        $this->lastBarData = $lastBarData;
    }

    public function setupAsCompareLayout(string $ticker)
    {
        $this->setTicker($ticker);
        $this->setBarType('line');
        $this->setColorDown($this->getSpyColor());
        $this->setColorUp($this->getSpyColor());
        $this->setOutlineColorDown($this->getSpyColor());
        $this->setOutlineColorUp($this->getSpyColor());
        $this->setLastBarData($this->compareBarData);
        $this->isCompare = true;

    }

    /**
     * @return bool
     */
    public function isCompare(): bool
    {
        return $this->isCompare;
    }

    /**
     * @return string
     */
    public function getCompareBarData(): string
    {
        return $this->compareBarData;
    }

    /**
     * @param string $compareBarData
     */
    public function setCompareBarData(string $compareBarData): void
    {
        $this->compareBarData = $compareBarData;
    }

    protected function serializeBoolean($value): bool
    {
        if($value === '1')  return  true;
        if($value === 'true')  return  true;
        return  false;
    }

    protected function serializeInt($value): int
    {
        if($value === '0')  return  0;
        if($value === 'null')  return  0;
        return  0;
    }


}
