<?php
/**
 * Created by PhpStorm.
 * User: denis
 * Date: 19.12.16
 * Time: 10:19
 */

namespace App\DataFeedApp\Bar\Read\Sources;


use App\DataFeedApp\Bar\Model\BarData;
use App\DataFeedApp\Bar\TimeFrame\BarTimeFrameInterface;

interface BarSourcesInterface
{
    public function getBars(
        string $ticker,
        BarTimeFrameInterface $timeFrame,
        ?\DateTime $startTime = null,
        ?\DateTime $endTime = null,
        bool $disablePremarket = true
    ) : BarData;

    public function support(BarTimeFrameInterface $timeFrame): bool;
}
