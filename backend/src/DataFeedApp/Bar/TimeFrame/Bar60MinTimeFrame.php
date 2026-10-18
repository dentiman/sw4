<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-12-24
 * Time: 22:38
 */

namespace App\DataFeedApp\Bar\TimeFrame;


use App\Entity\Chart\ChartLayout;

class Bar60MinTimeFrame extends BaseMinuteTimeFrame implements MinuteBarTimeFrameInterface, BarTimeFrameInterface
{
    public function getId(): string
    {
        return '60';
    }

    public function minuteDuration(): int
    {
        return 60;
    }

    public function daysRange(?ChartLayout $chartLayout = null): int
    {
        return 60;
    }

    public function dayLabelFormat(): ?string
    {
        return 'D d M';
    }

    public function timeLabelFormat(): ?string
    {
        return 'M';
    }

    public function divideConditionFormat(): ?string
    {
        return 'm';
    }

    public function label(): string
    {
        return '60m';
    }

    public function yahooTimeFrameId(): string
    {
        return '60m';
    }

}
