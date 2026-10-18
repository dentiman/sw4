<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-11-23
 * Time: 22:24
 */

namespace App\DataFeedApp\Bar\Read;


use App\DataFeedApp\Bar\Read\Exception\NoDataException;
use App\DataFeedApp\Bar\Read\Sources\BarSourcesInterface;
use App\DataFeedApp\Bar\Model\BarData;
use App\DataFeedApp\Bar\TimeFrame\BarTimeFrameInterface;
use App\DataFeedApp\Bar\TimeFrame\MinuteBarTimeFrameInterface;
use App\DataFeedApp\Bar\TimeFrame\TimeFrameFactory;
use App\DataFeedApp\ChartBuilder\ChartBuilder;
use App\DataFeedApp\Helper;
use App\Entity\Chart\ChartLayout;

class BarReader
{
    //количество дней для выборки в зависимости от  таймфрейма
    const DAYS_RANGE = [
        '1' => 4,
        '2' => 4,
        '3' => 5,
        '5' => 10,
        '15' => 30,
        '30' => 30,
        '60' => 60,
        'd' => 365,
        'w' => 365 * 3,
    ];

    const TIME_FRAME_MINUTES = [
        1 => 1,
        2 => 2,
        3 => 3,
        5 => 5,
        15 => 15,
        30 => 30,
        60 => 60,
        'd' => 1440,
        'w' => 1440 * 7,
    ];


    /**
     * @var BarSourcesInterface[]
     */
    protected $sources;

    public function __construct(BarSourcesInterface ...$sources)
    {
        $this->sources = $sources;
    }

    /**
     * @param string $ticker
     * @param BarTimeFrameInterface $timeFrame
     * @param \DateTime|null $startTime
     * @param \DateTime|null $endTime
     * @param bool $disablePremarket
     * @return BarData
     * @throws \Exception
     */
    public function getBars(
        string $ticker,
        BarTimeFrameInterface $timeFrame,
        ?\DateTime $startTime = null,
        ?\DateTime $endTime = null,
        bool $disablePremarket = true
    ): BarData
    {
        $sourcesCount = count($this->sources);
        $i = 0;
        foreach ($this->sources as $key => $source) {

            try {
                if($source->support($timeFrame) === false) throw new \LogicException('source is not supported');
                return $source->getBars($ticker, $timeFrame, $startTime, $endTime, $disablePremarket);
            } catch (NoDataException $e) {
                if (++$i === $sourcesCount) {
                    throw new NoDataException($e->getMessage());
                }
            } catch (\Exception $e) {
                if (++$i === $sourcesCount) {
                    throw new \Exception($e->getMessage());
                }
            }
        }
        throw new \Exception('bar sources data foreach not cached');

    }

    /**
     * @param ChartLayout $chartLayout
     * @return BarData
     * @throws \Exception
     */
    public function getBarsForChart(ChartLayout $chartLayout): BarData
    {
        $timeFrame = TimeFrameFactory::getTimeFrame($chartLayout->getTimeFrame());

        $begin = $this->roundUpToMinuteInterval(
             new \DateTime('now', new \DateTimeZone('America/New_York')),
            $timeFrame->minuteDuration()
        );

        //TODO:: day range can calculate from chart layout bars count
        $begin->modify("-{$timeFrame->daysRange($chartLayout)} days");

        $finish = $this->roundUpToMinuteInterval(
            new \DateTime('now', new \DateTimeZone('America/New_York')),
            $timeFrame->minuteDuration()
        );

        if($timeFrame instanceof MinuteBarTimeFrameInterface) {
            $currentTime = new \DateTime('now', new \DateTimeZone('America/New_York'));
            if($finish->format('i') == $currentTime->format('i')) {
                $finish->modify("+{$timeFrame->minuteDuration()} min");
            };
        }


        $barData = $this->getBars(
            $chartLayout->getTicker(),
            $timeFrame,
            $begin,
            $finish,
            !$chartLayout->getPreMarket()
        );

        if(count( $barData->time) <2) {
            throw new NoDataException();
        }
        //todo:: move to  barSource
        $this->pushLastBar($chartLayout,$barData);
        $barData->calculateForChart($chartLayout);

        return $barData;
    }


    public function calculateTimeAndGetBars(
        string $ticker,
        BarTimeFrameInterface $timeFrame,
        bool $disablePremarket = true ): BarData
    {
        $begin = $this->roundUpToMinuteInterval(
            new \DateTime('now', new \DateTimeZone('America/New_York')),
            $timeFrame->minuteDuration()
        );

        //TODO:: day range can calculate from chart layout bars count
        $begin->modify("-{$timeFrame->daysRange()} days");

        $finish = $this->roundUpToMinuteInterval(
            new \DateTime('now', new \DateTimeZone('America/New_York')),
            $timeFrame->minuteDuration()
        );

        if($timeFrame instanceof MinuteBarTimeFrameInterface) {
            $currentTime = new \DateTime('now', new \DateTimeZone('America/New_York'));
            if($finish->format('i') == $currentTime->format('i')) {
                $finish->modify("+{$timeFrame->minuteDuration()} min");
            };
        }

        $barData = $this->getBars(
            $ticker,
            $timeFrame,
            $begin,
            $finish,
            $disablePremarket
        );

        if(count( $barData->time) <2) {
            throw new NoDataException();
        }

        return $barData;
    }


    protected function pushLastBar(ChartLayout $chartLayout,  BarData $barData)
    {
        if ($chartLayout->getLastBarData()
            && $chartLayout->getTimeFrame() == 'd'
            && in_array(Helper::marketTime(),[1,2])) {

            $parts = explode(",", $chartLayout->getLastBarData());

            if (
                count($parts) == 5 &&
                isset($barData->time[0]) &&
                $barData->time[0] instanceof \DateTime &&
                $barData->time[0]->format('Ymd') !== Helper::lastSessionDay()->format('Ymd')

            ) {
                array_unshift($barData->open, $parts[0] * 1);
                array_unshift($barData->high, $parts[1] * 1);
                array_unshift($barData->low, $parts[2] * 1);
                array_unshift($barData->close, $parts[3] * 1);
                array_unshift($barData->volume, $parts[4] * 1);
                array_unshift($barData->time, new \DateTime('now'));

            }
        }
    }

    /**
     * Round up minutes to the nearest upper interval of a DateTime object.
     *
     * @param \DateTime $dateTime
     * @param int $minuteInterval
     * @return \DateTime
     */
    public function roundUpToMinuteInterval(\DateTime $dateTime, $minuteInterval = 10)
    {
        return $dateTime->setTime(
            $dateTime->format('H'),
            ceil($dateTime->format('i') / $minuteInterval) * $minuteInterval,
            0
        );
    }
}
