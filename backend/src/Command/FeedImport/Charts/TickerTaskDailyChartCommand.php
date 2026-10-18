<?php
namespace App\Command\FeedImport\Charts;

use App\Command\FeedImport\BaseFeedImportCommand;
use App\Command\FeedImport\BaseTickerTaskCommand;
use App\DataFeedApp\Bar\Read\BarReader;
use App\DataFeedApp\Bar\Read\Sources\CacheBarSource;
use App\DataFeedApp\Bar\Read\Sources\IqFeedBarSource;
use App\DataFeedApp\Bar\Storage\DailyBarStorage;
use App\DataFeedApp\Bar\TimeFrame\DailyBarTimeFrame;
use App\Entity\FeedImport\Charts\DailyHistory;
use App\Entity\FeedImport\Charts\TickerTaskDailyCharts;
use App\Model\Feed\TickerTaskInterface;

class TickerTaskDailyChartCommand extends BaseTickerTaskCommand
{
    protected $ticketTaskClass = TickerTaskDailyCharts::class;

    /**
     * @var int $limit
     */
    protected $limit = 40;

    public function getDefaultDefinition()
    {
        return '* 1-6 * * 2-6';
    }

    protected function configure()
    {
        $this
            ->setName('cron:feed:ticker-task:daily')
            ->setDescription("task per ticker");
    }



    protected function executeTickerTask(TickerTaskInterface $tickerTask)
    {
        $barReader = new BarReader(...[new IqFeedBarSource()]);
        $startTime = new \DateTime();

        $barData =  $barReader->getBars(
            $tickerTask->getId(),
            new DailyBarTimeFrame(),
            $startTime->modify('-2 year'),
            new \DateTime()
        );

        $cache = CacheBarSource::getCacheClient();
        $cachedContent = $cache->getItem($tickerTask->getId().'d');;
        $cachedContent->expiresAfter(60*60*24);
        $cachedContent->set(json_encode($barData->toArray()));

        $cache->save($cachedContent);

       // DailyBarStorage::saveBars($tickerTask->getId(),$barData);
        $DailyHistoriesForAtr = [];
        $totalIterationsCount = count($barData->time) >= 14 ? 14 : count($barData->time);

        for ($i=0;$i<$totalIterationsCount;$i++) {
            $DailyHistory = new DailyHistory();
            $DailyHistory->setTicker($tickerTask->getId());
            $DailyHistory->setOp($barData->open[$i]);
            $DailyHistory->setHi($barData->high[$i]);
            $DailyHistory->setLo($barData->low[$i]);
            $DailyHistory->setCl($barData->close[$i]);
            $DailyHistory->setVol($barData->volume[$i]);
            $DailyHistory->setTickerTask($tickerTask);
            $DailyHistory->setTime($barData->time[$i]);
            $this->getEntityManager()->persist($DailyHistory);
            $DailyHistoriesForAtr[] = $DailyHistory;
        }
        $tickerTask->markDone();
        $tickerTask->markSuccess();

    }

}
