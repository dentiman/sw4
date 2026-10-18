<?php
namespace App\Command\FeedImport\Charts;

use App\Command\FeedImport\BaseFeedImportCommand;
use App\Command\FeedImport\BaseTickerTaskCommand;
use App\DataFeedApp\Bar\Read\BarReader;
use App\DataFeedApp\Bar\Read\Sources\IqFeedBarSource;
use App\DataFeedApp\Bar\Storage\DailyBarStorage;
use App\DataFeedApp\Bar\Storage\MinuteBarStorage;
use App\DataFeedApp\Bar\TimeFrame\Bar1MinTimeFrame;
use App\DataFeedApp\Bar\TimeFrame\DailyBarTimeFrame;
use App\DataFeedApp\Helper;
use App\Entity\FeedImport\Charts\MinuteHistory;
use App\Entity\FeedImport\Charts\TickerTaskMinuteCharts;
use App\Model\Feed\TickerTaskInterface;

class TickerTaskMinuteChartCommand extends BaseTickerTaskCommand
{
    protected $ticketTaskClass = TickerTaskMinuteCharts::class;

    /**
     * @var int $limit
     */
    protected $limit = 30;

    public function getDefaultDefinition()
    {
        return '* 1-6 * * 2-6';
    }

    protected function configure()
    {
        $this
            ->setName('cron:feed:ticker-task:minute')
            ->setDescription("task per ticker");
    }

    protected function executeTickerTask(TickerTaskInterface $tickerTask)
    {
        $barReader = new BarReader(...[new IqFeedBarSource()]);

        $startDate = Helper::lastSessionDay();
        $startTime = new \DateTime(implode(' ',[$startDate->format('Y-m-d'),'04:00:00']));


        $barData =  $barReader->getBars(
            $tickerTask->getId(),
            new Bar1MinTimeFrame(),
            $startTime,
            new \DateTime()
        );

        MinuteBarStorage::saveBars($tickerTask->getId(),$barData);
//        $totalIterationsCount = count($barData->time) >= 14 ? 14 : count($barData->time);
//        for ($i=0;$i<$totalIterationsCount;$i++) {
//            $MinuteHistory = new MinuteHistory();
//            $MinuteHistory->setTicker($tickerTask->getId());
//            $MinuteHistory->setOp($barData->open[$i]);
//            $MinuteHistory->setHi($barData->high[$i]);
//            $MinuteHistory->setLo($barData->low[$i]);
//            $MinuteHistory->setCl($barData->close[$i]);
//            $MinuteHistory->setVol($barData->volume[$i]);
//            $MinuteHistory->setTickerTask($tickerTask);
//            $MinuteHistory->setTime($barData->time[$i]);
//            $this->getEntityManager()->persist($MinuteHistory);
//        }

        $tickerTask->markDone();
        $tickerTask->markSuccess();

    }

}
