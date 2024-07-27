<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-12-24
 * Time: 22:38
 */

namespace App\DataFeedApp\Bar\TimeFrame;


use App\Entity\Chart\ChartLayout;

class WeeklyBarTimeFrame implements BarTimeFrameInterface
{
    public function getId(): string
    {
        return 'w';
    }
    public function minuteDuration(): int
    {
        return 1440*7;
    }

    public function daysRange(?ChartLayout $chartLayout = null): int
    {
        return 365*3;
    }

    public function dayLabelFormat(): ?string
    {
        return 'D d m';
    }

    public function timeLabelFormat(): ?string
    {
        return 'Y';
    }

    public function divideConditionFormat(): ?string
    {
        return 'Y';
    }

    public function label(): string
    {
        return 'W';
    }

    public function yahooTimeFrameId()
    {
        return '1w';
    }


}
