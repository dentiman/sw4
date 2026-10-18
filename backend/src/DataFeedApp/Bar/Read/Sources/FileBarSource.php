<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-12-14
 * Time: 21:00
 */

namespace App\DataFeedApp\Bar\Read\Sources;


use App\DataFeedApp\Bar\Read\Exception\NoDataException;
use App\DataFeedApp\Bar\Storage\DailyBarStorage;
use App\DataFeedApp\Bar\Model\BarData;
use App\DataFeedApp\Bar\TimeFrame\BarTimeFrameInterface;

class FileBarSource implements  BarSourcesInterface
{
    public function getBars(
        string $ticker,
        BarTimeFrameInterface $timeFrame,
        ?\DateTime $startTime = null,
        ?\DateTime $endTime = null,
        bool $disablePremarket = true
    ) : BarData
    {
        $barData = DailyBarStorage::getBars($ticker);
        if(count($barData->time)<5)  throw new NoDataException();
        $barData->source = 0;
        return $barData;
    }

    public function support(BarTimeFrameInterface $timeFrame): bool
    {
        if(!in_array($timeFrame->getId(),['d'])) return  false;
        return true;
    }
}
