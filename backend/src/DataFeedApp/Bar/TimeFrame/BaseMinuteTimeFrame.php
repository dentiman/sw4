<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-12-24
 * Time: 22:38
 */

namespace App\DataFeedApp\Bar\TimeFrame;


use App\Entity\Chart\ChartLayout;

class BaseMinuteTimeFrame implements MinuteBarTimeFrameInterface, BarTimeFrameInterface
{
    const MINUTES_IN_EXTENDED_SESSION = 960;
    const MINUTES_IN_REGULAR_SESSION = 390;

    public function getId(): string
    {
        return '5';
    }

    public function minuteDuration(): int
    {
        return 5;
    }

    public function daysRange(?ChartLayout $chartLayout = null): int
    {
       return ceil(
           ($this::MINUTES_IN_REGULAR_SESSION / $this->minuteDuration()) /
           $chartLayout->getBarsCount()
           );
    }

    public function dayLabelFormat(): ?string
    {
        return 'D d M';
    }

    public function timeLabelFormat(): ?string
    {
        return 'H:i';
    }

    public function divideConditionFormat(): ?string
    {
        return 'i';
    }

    public function label(): string
    {
        return '5 min';
    }

    public function oneDayBarsCount()
    {

    }

    public function yahooTimeFrameId(): string
    {
        return '5m';
    }


}
