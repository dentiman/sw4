<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-12-24
 * Time: 22:38
 */

namespace App\DataFeedApp\Bar\TimeFrame;


use App\Entity\Chart\ChartLayout;

class DailyBarTimeFrame implements BarTimeFrameInterface
{
    public function getId(): string
    {
        return 'd';
    }

    public function minuteDuration(): int
    {
        return 1440;
    }

    public function daysRange(?ChartLayout $chartLayout = null): int
    {
        return 365;
    }

    public function dayLabelFormat(): ?string
    {
        return null;
    }

    public function timeLabelFormat(): ?string
    {
        return 'M Y';
    }

    public function divideConditionFormat(): ?string
    {
        return 'm';
    }

    public function label(): string
    {
        return 'D';
    }

    public function yahooTimeFrameId(): string
    {
        return '1d';
    }


}
