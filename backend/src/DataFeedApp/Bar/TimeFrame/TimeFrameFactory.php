<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-12-25
 * Time: 10:14
 */

namespace App\DataFeedApp\Bar\TimeFrame;


use Webmozart\Assert\Assert;

class TimeFrameFactory
{
    const CLASSES = [
        '1' => Bar1MinTimeFrame::class,
        '2' => Bar2MinTimeFrame::class,
        '3' => Bar3MinTimeFrame::class,
        '5' => Bar5MinTimeFrame::class,
        '15' => Bar15MinTimeFrame::class,
        '30' => Bar30MinTimeFrame::class,
        '60' => Bar60MinTimeFrame::class,
        'd' => DailyBarTimeFrame::class,
        'w' => WeeklyBarTimeFrame::class
        ];

    /**
     * @var string
     */
    protected $timeFrameId;

    /**
     * @param string $timeFrameId
     * @return BarTimeFrameInterface
     */
    public static function getTimeFrame(string $timeFrameId): BarTimeFrameInterface
    {
        Assert::oneOf($timeFrameId,['1','2','3' ,'5','15','30','60','d','w']);
        $class =  self::CLASSES[$timeFrameId];
        return new $class();
    }


}
