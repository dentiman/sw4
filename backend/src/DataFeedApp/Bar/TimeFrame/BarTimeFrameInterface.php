<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-12-24
 * Time: 21:31
 */

namespace App\DataFeedApp\Bar\TimeFrame;


use App\Entity\Chart\ChartLayout;

interface BarTimeFrameInterface
{
    public function getId(): string;

    public function minuteDuration(): int;

    //TODO:: implemet daysRange or barsRange depends on last sessions number
    /**
     * Calculate number of days to set time range BarData
     * @param ChartLayout|null $chartLayout
     * @return int
     */
    public function daysRange(?ChartLayout $chartLayout = null): int;

    public function dayLabelFormat(): ?string;

    public function timeLabelFormat(): ?string;

    public function divideConditionFormat(): ?string;

    public function label(): string;

    public function yahooTimeFrameId();

}
