<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-12-24
 * Time: 22:38
 */

namespace App\DataFeedApp\Bar\TimeFrame;


use App\Entity\Chart\ChartLayout;

class Bar30MinTimeFrame extends BaseMinuteTimeFrame implements MinuteBarTimeFrameInterface, BarTimeFrameInterface
{
    public function getId(): string
    {
        return '30';
    }

    public function minuteDuration(): int
    {
        return 30;
    }

    public function daysRange(?ChartLayout $chartLayout = null): int
    {
        return 30;
    }

    public function dayLabelFormat(): ?string
    {
        return 'D d M';
    }

    public function timeLabelFormat(): ?string
    {
        return 'd M';
    }

    public function divideConditionFormat(): ?string
    {
        return 'd';
    }

    public function label(): string
    {
        return '30m';
    }

    public function yahooTimeFrameId(): string
    {
        return '30m';
    }

}
