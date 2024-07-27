<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-12-24
 * Time: 22:38
 */

namespace App\DataFeedApp\Bar\TimeFrame;


use App\Entity\Chart\ChartLayout;

class Bar1MinTimeFrame extends BaseMinuteTimeFrame implements MinuteBarTimeFrameInterface, BarTimeFrameInterface
{
    public function getId(): string
    {
        return '1';
    }

    public function minuteDuration(): int
    {
        return 1;
    }

    public function daysRange(?ChartLayout $chartLayout = null): int
    {
        return 3;
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
        return 'h';
    }

    public function label(): string
    {
        return '1m';
    }

    public function yahooTimeFrameId(): string
    {
        return '5m';
    }

}
