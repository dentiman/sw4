<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-12-15
 * Time: 12:16
 */

namespace App\DataFeedApp\ChartBuilder;


use App\DataFeedApp\Bar\Model\BarData;
use App\DataFeedApp\Bar\TimeFrame\BarTimeFrameInterface;
use App\DataFeedApp\Bar\TimeFrame\DailyBarTimeFrame;
use App\DataFeedApp\Bar\TimeFrame\TimeFrameFactory;
use App\DataFeedApp\ChartBuilder\Traits\CalculateTrait;
use App\DataFeedApp\ChartBuilder\Traits\DrawTrait;
use App\Entity\Chart\ChartLayout;
use Dentiman\PaymentBundle\Gateway\Webmoney\Action\RefundAction;
use Intervention\Image\ImageManagerStatic as Image;
use Psr\Log\LoggerInterface;

class ChartBuilder
{
    use DrawTrait;
    use CalculateTrait;
    /**
     * @var \Intervention\Image\Image
     */
    protected $img;

    /**
     * @var BarData
     */
    protected $feed;

    /**
     * @var BarData
     */
    protected $compareFeed;

    /**
     * @var ChartLayout
     */
    protected $chartLayout;

    /**
     * @var ChartLayout
     */
    protected $compareChartLayout;

    /**
     * @var BarTimeFrameInterface
     */
    protected $timeFrame;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(ChartLayout $chartLayout, LoggerInterface $appLogger)
    {
        $this->chartLayout = $chartLayout;
        $this->timeFrame = TimeFrameFactory::getTimeFrame($chartLayout->getTimeFrame());
        $this->prepareEmptyImage($chartLayout);
        $this->logger = $appLogger;
    }


    /** //TODO:: separate Volume
     * /** //TODO:: first 5m bar hiden in premarket
     * /** //TODO:: cut bars time range
     * @param BarData $barData
     * @param BarData|null $compareBarData
     */
    public function applyData(BarData $barData)
    {
        //$barData->calculateForChart($this->chartLayout);
        $this->feed = $barData;

        $this->img->rectangle(1, 1, $this->chartLayout->getAreaWidth(), $this->chartLayout->getAreaHeight(), function ($draw) {
            $draw->background($this->chartLayout->getBgColor());
        });

        $this->drawGrid($this->chartLayout);

        if($this->timeFrame instanceof DailyBarTimeFrame) {
            $this->addMA();
        }
        $this->addAxis($this->chartLayout);
        $this->addLines();


    }


    protected function prepareEmptyImage(ChartLayout $chartLayout)
    {
        Image::configure(array('driver' => 'imagick'));
        $this->img = Image::canvas($chartLayout->getWidth(), $chartLayout->getHeight());
    }

    public function setServerError()
    {
        $this->setError("We're sorry, a server error occurred.");
    }


    public function setError( string $error)
    {
        $this->prepareEmptyImage($this->chartLayout);
        $this->img->rectangle(1, 1, $this->chartLayout->getAreaWidth(), $this->chartLayout->getAreaHeight(), function ($draw) {
            $draw->background($this->chartLayout->getBgColor());
        });
        $this->img->text(
            $error,
            $this->chartLayout->getAreaWidth() / 2,
            $this->chartLayout->getAreaHeight() / 2,
            function ($font) {
                $font->file($this->regularFont);
                $font->size(18);
                $font->color($this->chartLayout->getGridColor());
                $font->align('center');
                $font->valign('center');

            }
        );

    }

    public function render()
    {
        return $this->img->encode('png');
    }

    public function getBarData(): BarData
    {
        return $this->feed;
    }

    /**
     * @return ChartLayout
     */
    public function getChartLayout(): ChartLayout
    {
        return $this->chartLayout;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }



}
