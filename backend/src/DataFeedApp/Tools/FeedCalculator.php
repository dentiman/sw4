<?php


namespace App\DataFeedApp\Tools;


use App\Entity\FeedImport\Charts\DailyHistory;

class FeedCalculator
{
    /**
     * @param DailyHistory ...$dailyHistories
     * @return float|int
     */
    public static function ATR(DailyHistory ...$dailyHistories)
    {
        if(count($dailyHistories) == 0) return 0;
        $total = 0;
        foreach ($dailyHistories as $dailyHistory) {
            $total +=  $dailyHistory->getHi() - $dailyHistory->getLo();
        }

        return round($total/count($dailyHistories),2);
    }

}
